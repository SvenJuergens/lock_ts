<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$tempColumns = array (
	'tx_lockts_lock' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lock_ts/Resources/Private/Language/locallang_db.xlf:sys_template.tx_lockts_lock',		
		'config' => array (
			'type' => 'check',
		)
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_template', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_template','tx_lockts_lock;;;;1-1-1');

if ( TYPO3_MODE == 'BE') {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/tstemplate_info/class.tx_tstemplateinfo.php']['postOutputProcessingHook'][] =
	'SvenJuergens\\LockTs\\Hooks\\Tceforms->checkLock';
}
