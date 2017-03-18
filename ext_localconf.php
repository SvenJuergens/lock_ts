<?php
defined('TYPO3_MODE') or die();

if (TYPO3_MODE == 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][] =
        'SvenJuergens\\LockTs\\Hooks\\Tceforms';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][] =
        'SvenJuergens\\LockTs\\Hooks\\Tceforms';
}
