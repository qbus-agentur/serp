<?php
namespace Qbus\Serp\FormEngine\FieldControl;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Utility\RootlineUtility;

/**
 * Serp
 *
 * @author Benjamin Franzke <bfr@qbus.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Serp extends AbstractNode
{
    /**
     * @var string
     */
    protected static $siteTitle = null;

    public function render()
    {
        // Using traditional configuration to allow backwards compatible overwrites
        $config = $this->data['parameterArray']['fieldConf']['config']['wizards']['serp']['params'];
        if (!isset($config['titleSuffix']) || $config['titleSuffix'] === '') {
            $config['titleSuffix'] = $this->autodetectTitleSuffix($this->data);
        }

        $html = '<div class="t3js-serp-wizard" data-config="' . htmlentities(json_encode($config), ENT_QUOTES, 'UTF-8') . '"></div>';

        $result = [
            'requireJsModules' => ['TYPO3/CMS/Serp/SerpV2'],
            'stylesheetFiles' => ['EXT:serp/Resources/Public/CSS/Serp.css'],
            'html' => $html
        ];
        return $result;
    }

    protected function autodetectTitleSuffix($data)
    {
        if (self::$siteTitle !== null) {
            return self::$siteTitle;
        }

        $pageId = $data['effectivePid'] ?? 0;
        if (!$pageId) {
            return '';
        }

        $templateService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TemplateService::class);
        $templateService->tt_track = 0;
        $templateService->forceTemplateParsing = 1;

        $rootlineUtility = GeneralUtility::makeInstance(RootlineUtility::class, $pageId);
        try {
            $rootline = $rootlineUtility->get();
        } catch (\Exception $ex) {
             if ($ex->getCode() === 1343589451) {
                $rootline = [];
             } else {
                throw $ex;
            }
        }

        $templateService->runThroughTemplates($rootline, 0);
        $templateService->generateConfig();

        self::$siteTitle = $templateService->setup['sitetitle'] ?? '';

        return self::$siteTitle;
    }
}
