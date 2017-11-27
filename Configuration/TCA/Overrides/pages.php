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
            /* Use false to disable autodetection */
            'titleSuffix' => '',
            'titleSuffixSeparator' => ' – ',
            'defaultTitle' => 'No title',
            'defaultDescription' => 'No description',
            'defaultUrl' => 'https://domain.tld/path',
        ],
    ];

    /* Configure the wizard position for TYPO3 7 */
    $GLOBALS['TCA']['pages']['columns'][$field]['config']['wizards']['_POSITION'] = 'bottom';
}

$GLOBALS['TCA']['pages_language_overlay']['columns']['description']['config']['wizards']['serp'] =
    $GLOBALS['TCA']['pages']['columns']['description']['config']['wizards']['serp'];
$GLOBALS['TCA']['pages_language_overlay']['columns']['title']['config']['wizards']['serp'] =
    $GLOBALS['TCA']['pages']['columns']['title']['config']['wizards']['serp'];
//$GLOBALS['TCA']['pages_language_overlay']['columns']['tx_seo_titletag']['config']['wizards']['serp'] =
//    $GLOBALS['TCA']['pages']['columns']['tx_seo_titletag']['config']['wizards']['serp'];

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('metaseo')) {
    $GLOBALS['TCA']['pages']['columns']['title']['config']['wizards']['serp']['params']['titleOverride'] = 'tx_metaseo_pagetitle';
    $GLOBALS['TCA']['pages']['columns']['description']['config']['wizards']['serp']['params']['titleOverride'] = 'tx_metaseo_pagetitle';

    unset($GLOBALS['TCA']['pages']['columns']['tx_seo_titletag']);
    $GLOBALS['TCA']['pages']['columns']['tx_metaseo_pagetitle']['config']['wizards']['serp'] =
        $GLOBALS['TCA']['pages']['columns']['title']['config']['wizards']['serp'];

    /* Configure the wizard position for TYPO3 7 */
    $GLOBALS['TCA']['pages']['columns']['tx_metaseo_pagetitle']['config']['wizards']['_POSITION'] = 'bottom';
}
