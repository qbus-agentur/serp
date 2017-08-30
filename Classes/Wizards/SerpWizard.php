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
     * @param  array  $params
     * @param  object $ref
     * @return string
     */
    public function renderWizard($params, $ref)
    {
        $config = $params['wConf']['params'];
        $this->loadCss();
        $this->loadJs();

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
        $baseUrl = ExtensionManagementUtility::extRelPath('serp') . 'Resources/Public/CSS/';

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
}
