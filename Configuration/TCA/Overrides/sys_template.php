<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'serp',
    'Configuration/TypoScript/PageTitle',
    'Page Title Configuration'
);
