<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}	

if (TYPO3_MODE=='BE') {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][] = 'EXT:lock_ts/class.tx_lockts_hooks.php:tx_lockts_hooks';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][] = 'EXT:lock_ts/class.tx_lockts_hooks.php:tx_lockts_hooks';
}