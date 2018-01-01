<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "lock_ts".
 *
 * Auto generated 07-04-2015 08:59
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] =  [
    'title' => 'Lock TypoScript Templates',
    'description' => 'A simple write protection for TypoScript Templates.',
    'category' => 'be',
    'version' => '3.0.0',
    'state' => 'stable',
    'clearcacheonload' => 0,
    'author' => 'Sven Juergens',
    'author_email' => 't3@blue-side.de',
    'constraints' =>
     [
        'depends' =>
         [
            'typo3' => '8.7.0-8.7.99',
        ],
        'conflicts' =>
         [
        ],
        'suggests' =>
         [
        ],
    ],
];
