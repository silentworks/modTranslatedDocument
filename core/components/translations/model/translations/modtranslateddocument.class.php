<?php
/**
 * Translations
 *
 * Copyright 2012 by Alan Pich <alan@alanpich.com>
 *
 * Translations is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Translations is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Translations; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
**/
require_once MODX_CORE_PATH.'model/modx/modprocessor.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/create.class.php';

require_once MODX_CORE_PATH.'model/modx/processors/resource/update.class.php';
require_once dirname(dirname(dirname(__FILE__))).'/processors/mgr/resource/update.class.php';

class modTranslatedDocument extends modResource {

	public $showInContextMenu = true;

	public function __construct(xPDO & $xpdo) {
        parent :: __construct($xpdo);
        $this->set('class_key','modTranslatedDocument');
		
		$language = $xpdo->getOption('cultureKey');
		$this->language = $xpdo->getOption('cultureKey');
		$this->_cacheKey = '[contextKey]/resources/'.$language.'/[id]';
		
		$this->_pagetitle = "arse";
    }
	
	// Return the path to controllers --------------------------------------------------------------------------------------------------------------------
	public static function getControllerPath(xPDO &$modx) {
		return $modx->getOption('translations.core_path', null, $modx->getOption('core_path').'components/translations/').'controllers/mgr/';
	}
	

	// Return text to use in manager context create menus ------------------------------------------------------------------------------------------------
	public function getContextMenuText() {
		$this->xpdo->lexicon->load('translations:default');
		return array(
			'text_create' => $this->xpdo->lexicon('translations.document'),
			'text_create_here' => $this->xpdo->lexicon('translations.createDocumentHere'),
		);
	}


	// Return the name of this resource type --------------------------------------------------------------------------------------------------------------
	public function getResourceTypeName() {
		$this->xpdo->lexicon->load('translations:default');
		return $this->xpdo->lexicon('translations.document');
	}
	
	
	// Return the content for this article -----------------------------------------------------------------------------------------------------------------
	public function getContent(array $options = array()) {
		$content = parent::getContent($options);
		$language = $this->getLanguage();
		$year = date('Y');
		
		if(!$content = $this->lookForTranslations('content')){
			$content = parent::getContent($options);
		};
		
		return $content;
	}
	
	
	// Process a resource, transforming source content to output. -------------------------------------------------------------------------------------------
 	public function process() { 
		switch($this->getLanguage()){
		//	case 'fr'	: $this->pagetitle = 'La title francais!';
		};
	
		return parent::process();
	}


	// Override get for translations -----------------------------------
	public function get( $key ) {
		$orig = parent::get($key);
		$translated = array('pagetitle','longtitle','description','introtext','menutitle','content');

		if(in_array($key,$translated)){
		 $value = $this->lookForTranslations($key);  
		 if(!$value){$value = parent::get($key);};
		} else {
			$value = parent::get($key);
		};

		return $value;
	}

    /**
     * Use this in your extended Resource class to modify the tree node contents
     * @param array $node
     * @return array
     */
    public function prepareTreeNode(array $node = array()) {
        return $node;
    }

	private function lookForTranslations($field){
		$language = $this->xpdo->getOption('cultureKey');
		
		$query = $this->xpdo->newQuery('Translation');
		$query->where(array(
			'articleID' => $this->get('id'),
			'language' => $language
		));
		$translations = $this->xpdo->getCollection('Translation',$query);
		$value = '';
		
		foreach($translations as $T){
			$row = $T->toArray();
			return $row[$field];
		};
		return false;

	}
	
	public function getTranslationsJSON(){
		$JSON = array();
		$query = $this->xpdo->newQuery('Translation');
		$query->where(array(
			'articleID' => $this->get('id'),
		));
		$translations = $this->xpdo->getCollection('Translation',$query);
		foreach($translations as $T){
			$row = $T->toArray();
			$JSON[$row['language']] = $row;
		};
		
		return json_encode($JSON);
	}


	private function getLanguage(){
		return $this->xpdo->getOption('cultureKey');
	}
}