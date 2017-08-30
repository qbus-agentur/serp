<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'SERP preview in TCA forms',
    'description' => '',
    'category' => '',
    'author' => 'Benjamin Franzke',
    'author_email' => 'bfr@qbus.de',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '',
    'constraints' => array(
        'depends' => array(
            'typo3' => '7.6.0-8.7.99',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
            'seo_basics' => '0.9.2',
        ),
    ),
    'autoload' => array(
        'psr-4' => array(
            'Qbus\\Serp\\' => 'Classes',
        ),
    ),
);
