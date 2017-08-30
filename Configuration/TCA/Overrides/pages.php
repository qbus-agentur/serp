<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/* Create tx_seo_titletag TCA definition in case EXT:seo_basics is not installed */
if (!isset($GLOBALS['TCA']['pages']['columns']['tx_seo_titletag'])) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', [
        'tx_seo_titletag' => [
            'exclude' => 1,
            'label' => 'Title Tag',
            'config' => [
                'type' => 'input',
                'size' => 70,
                'eval' => 'trim'
            ]
        ],
    ]);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', 'tx_seo_titletag', 1, 'before:keywords');
}

foreach (['title', 'description', 'tx_seo_titletag'] as $field) {
    $GLOBALS['TCA']['pages']['columns'][$field]['config']['wizards']['serp'] = [
        'type' => 'userFunc',
        'userFunc' => \Qbus\Serp\Wizards\SerpWizard::class . '->renderWizard',
        'params' => [
            'title' => 'title',
            'description' => 'description',
            'titleOverride' => 'tx_seo_titletag',
            'titleSuffix' => ' – Site Name',
            'defaultTitle' => 'No title',
            'defaultDescription' => 'No description',
            'defaultUrl' => 'https://domain.tld/path',
        ],
    ];
}

$GLOBALS['TCA']['pages_language_overlay']['columns']['description']['config']['wizards']['serp'] =
    $GLOBALS['TCA']['pages']['columns']['description']['config']['wizards']['serp'];
$GLOBALS['TCA']['pages_language_overlay']['columns']['title']['config']['wizards']['serp'] =
    $GLOBALS['TCA']['pages']['columns']['title']['config']['wizards']['serp'];
//$GLOBALS['TCA']['pages_language_overlay']['columns']['tx_seo_titletag']['config']['wizards']['serp'] =
//    $GLOBALS['TCA']['pages']['columns']['tx_seo_titletag']['config']['wizards']['serp'];
