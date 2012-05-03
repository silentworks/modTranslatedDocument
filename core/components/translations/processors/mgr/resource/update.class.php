<?php class modTranslatedDocumentUpdateProcessor extends modResourceUpdateProcessor {

/**
* Do any processing before the save of the Resource but after fields are set.
* @return boolean
*/
    public function beforeSave() {
        $beforeSave = parent::beforeSave();
        
        $this->modx->log(LOG_LEVEL_WARN,'modTranslatedDocument->beforeSave()');
        
        
        // Get all translated data as arrays
        $translations = $this->getTranslatedFields();
        
         	$this->modx->log(LOG_LEVEL_WARN,'  =>'.print_r($_REQUEST,true));
          
        foreach($translations as $lang => $fields){
        	$row = $this->modx->getObject('Translation',$_REQUEST['TranslationID'.$lang]);
        	
        	$this->modx->log(LOG_LEVEL_WARN,'Setting data for object '.$lang.' '.$_REQUEST['TranslationID'.$lang]. '||');
        	$this->modx->log(LOG_LEVEL_WARN,'  =>'.print_r($fields,true));
        	
	    	$row->set('pagetitle',$fields['pagetitle']);
	    	$row->set('longtitle',$fields['longtitle']);
	    	$row->set('menutitle',$fields['menutitle']);
	    	$row->set('introtext',$fields['introtext']);
	    	$row->set('description',$fields['description']);
	    	$row->set('content',$fields['content']);
        	$row->save();
        };
       
        $this->modx->log(LOG_LEVEL_WARN,'modTranslatedDocument->beforeSave() complete');
        return $beforeSave;
    }


/**
 * Gather translated fields as array
 * @return array
 */
private function getTranslatedFields() {
        $langs = explode(',',$_REQUEST['translations']);
		$translations = array();
		foreach($langs as $lang){		
			$translations[$lang] = array(
				'id' => (int) $_REQUEST['TranslationID'.$lang],
				'pagetitle' => $_REQUEST['pagetitle'.$lang],
				'longtitle' => $_REQUEST['longtitle'.$lang],
				'menutitle' => $_REQUEST['menutitle'.$lang],
				'introtext' => $_REQUEST['introtext'.$lang],
				'description' => $_REQUEST['description'.$lang],
				'content' => $_REQUEST['content'.$lang],
			);
			foreach($translations[$lang] as $key => $val){
				if($translations[$lang][$key] == null){
				 $translations[$lang][$key] = '';
				};
			};					
		};
		return $translations;		
	}//



};// end class TranslatedDocumentUpdateProcessor
