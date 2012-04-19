<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id: ext_autoload.php 41726 2011-01-03 12:16:11Z martoro $
 */

$caretakerExtPath = t3lib_extMgm::extPath('caretaker_redmine');

return array(
	'tx_caretakerredmine_rest'			   => $caretakerExtPath.'lib/class.tx_caretakerredmine_rest.php'
);
?>
