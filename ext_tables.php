<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_lockts_lock' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lock_ts/locallang_db.xml:sys_template.tx_lockts_lock',		
		'config' => array (
			'type' => 'check',
		)
	),
);


t3lib_div::loadTCA('sys_template');
t3lib_extMgm::addTCAcolumns('sys_template', $tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('sys_template','tx_lockts_lock;;;;1-1-1');

if (TYPO3_MODE=='BE') {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/tstemplate_info/class.tx_tstemplateinfo.php']['postOutputProcessingHook'][] = 'EXT:lock_ts/class.tx_lockts_hooks.php:tx_lockts_hooks->checklock';
}
