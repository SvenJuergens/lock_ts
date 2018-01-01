<?php
defined('TYPO3_MODE') || die();

$tempColumns =  [
    'tx_lockts_lock' =>  [
        'exclude' => 0,
        'label' => 'LLL:EXT:lock_ts/Resources/Private/Language/locallang_db.xlf:sys_template.tx_lockts_lock',
        'config' =>  [
            'type' => 'check',
            'renderType' => 'lockts'
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'sys_template',
    $tempColumns
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_template',
    'tx_lockts_lock'
);
