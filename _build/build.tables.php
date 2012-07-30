<?php /* Translations Transport Package Builder

	v1.0	May 2011
*/

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

require_once dirname(__FILE__) . '/build.config.php';
include_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modelPath = $modx->getOption('translations.core_path', null, $modx->getOption('core_path') . 'components/translations/') . 'model/';

$modx->addExtensionPackage('translations', $modelPath);
//$modx->addPackage('translations', $modelPath);
echo '<pre>'; /* used for nice formatting of log messages */
$manager= $modx->getManager();

/* Model Classes names */
$objects = array(
	'Translation',
);

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

foreach($objects as $object) {
	$$object = $manager->createObjectContainer($object);
	echo $$object ? "\n{$object} table created\n" : "\n{$object} table not created\n";
}

echo "\nExecution time: {$totalTime}\n";

exit ();
