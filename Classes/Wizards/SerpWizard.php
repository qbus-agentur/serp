<?php
namespace Qbus\Serp\Wizards;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * SerpWizard
 *
 * @author Benjamin Franzke <bfr@qbus.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SerpWizard
{
    /**
     * @var PageRenderer
     */
    protected $pageRenderer;

    /**
     * @var string
     */
    protected static $siteTitle = null;

    /**
     * @param  array  $params
     * @param  object $ref
     * @return string
     */
    public function renderWizard($params, $ref)
    {
        $this->loadCss();
        $this->loadJs();

        $config = $params['wConf']['params'];
        if (!isset($config['titleSuffix']) || $config['titleSuffix'] === '') {
            $config['titleSuffix'] = $this->autodetectTitleSuffix($params);
        }

        return '<div class="t3js-serp-wizard" data-config="' . htmlentities(json_encode($config), ENT_QUOTES, 'UTF-8') . '"></div>';
    }

    /**
     * @return void
     */
    protected function loadJs()
    {
        $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Serp/Serp');
    }

    /**
     * @return void
     */
    protected function loadCss()
    {
        $compress = false;
        $cssFiles = [
            'Serp.css',
        ];
        $baseUrl = 'EXT:serp/Resources/Public/CSS/';
        if (version_compare(TYPO3_branch, '8.4', '<')) {
            $baseUrl = ExtensionManagementUtility::extRelPath('serp') . 'Resources/Public/CSS/';
        }

        foreach ($cssFiles as $cssFile) {
            $this->getPageRenderer()->addCssFile($baseUrl . $cssFile, 'stylesheet', 'all', '', $compress, false);
        }
    }

    /**
     * @return PageRenderer
     */
    protected function getPageRenderer()
    {
        if (!isset($this->pageRenderer)) {
            $this->pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        }

        return $this->pageRenderer;
    }

    protected function autodetectTitleSuffix($params)
    {
        if (self::$siteTitle !== null) {
            return self::$siteTitle;
        }

        $pageId = 0;
        if ($params['table'] === 'pages') {
            $pageId = $params['uid'] || $params['pid'];
        } else {
            $pageId = $params['pid'];
        }

        if (!$pageId) {
            return '';
        }

        $templateService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TemplateService::class);
        $templateService->tt_track = 0;
        $templateService->forceTemplateParsing = 1;
        $templateService->init();

        $pageRepository = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\Page\PageRepository::class);
        $rootline = $pageRepository->getRootLine($pageId);

        $templateService->runThroughTemplates($rootline, 0);
        $templateService->generateConfig();

        self::$siteTitle = $templateService->setup['sitetitle'];

        return self::$siteTitle;
    }
}
