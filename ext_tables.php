<?php

defined('TYPO3_MODE') or die();

if (TYPO3_MODE == 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/tstemplate_info/class.tx_tstemplateinfo.php']['postOutputProcessingHook'][] =
    'SvenJuergens\\LockTs\\Hooks\\Tceforms->checkLock';
}

// register own Element
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1449433601] = [
    'nodeName' => 'lockts',
    'priority' => 10,
    'class' => SvenJuergens\Form\Element\LockTsElement::class,
];
