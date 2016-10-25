<?php

/**
 * Cloudrexx
 *
 * @link      http://www.cloudrexx.com
 * @copyright Cloudrexx AG 2007-2015
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Cloudrexx" is a registered trademark of Cloudrexx AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

/**
 * Main controller for Directory
 *
 * @copyright   Cloudrexx AG
 * @author      Project Team SS4U <info@cloudrexx.com>
 * @package     cloudrexx
 * @subpackage  module_directory
 */

namespace Cx\Modules\Directory\Controller;

/**
 * Main controller for Directory
 *
 * @copyright   Cloudrexx AG
 * @author      Project Team SS4U <info@cloudrexx.com>
 * @package     cloudrexx
 * @subpackage  module_directory
 */
class ComponentController extends \Cx\Core\Core\Model\Entity\SystemComponentController {

    public function getControllerClasses() {
// Return an empty array here to let the component handler know that there
// does not exist a backend, nor a frontend controller of this component.
        return array();
    }

    /**
     * Returns a list of JsonAdapter class names
     *
     * @return array List of ComponentController classes
     */
    public function getControllersAccessableByJson() {
        return array('JsonDirectory');
    }

    /**
     * Load your component.
     *
     * @param \Cx\Core\ContentManager\Model\Entity\Page $page       The resolved page
     */
    public function load(\Cx\Core\ContentManager\Model\Entity\Page $page) {
        global $_CORELANG, $subMenuTitle, $objTemplate;
        switch ($this->cx->getMode()) {
            case \Cx\Core\Core\Controller\Cx::MODE_FRONTEND:
                $objDirectory = new Directory(\Env::get('cx')->getPage()->getContent());
                \Env::get('cx')->getPage()->setContent($objDirectory->getPage());
                $directory_pagetitle = $objDirectory->getPageTitle();
                if (!empty($directory_pagetitle)) {
                    \Env::get('cx')->getPage()->setTitle($directory_pagetitle);
                    \Env::get('cx')->getPage()->setContentTitle($directory_pagetitle);
                    \Env::get('cx')->getPage()->setMetaTitle($directory_pagetitle);
                }
                if ($_GET['cmd'] == 'detail' && isset($_GET['id'])) {
                    $objTemplate->setVariable(array(
                        'DIRECTORY_ENTRY_ID' => intval($_GET['id']),
                    ));
                }
                break;

            case \Cx\Core\Core\Controller\Cx::MODE_BACKEND:

                $this->cx->getTemplate()->addBlockfile('CONTENT_OUTPUT', 'content_master', 'LegacyContentMaster.html');
                $objTemplate = $this->cx->getTemplate();

                $subMenuTitle = $_CORELANG['TXT_LINKS_MODULE_DESCRIPTION'];
                $objDirectoryManager = new DirectoryManager();
                $objDirectoryManager->getPage();
                break;

            default:
                break;
        }
    }

    /*
     * Do something before content is loaded from DB
     *
     * @param \Cx\Core\ContentManager\Model\Entity\Page $page The resolved page
     */

    public function preContentLoad(
        \Cx\Core\ContentManager\Model\Entity\Page $page
    ) {
        global $_CONFIG, $cl, $themesPages, $page_template, $themesPages;

        // get Directory Homecontent
        if (
            $_CONFIG['directoryHomeContent'] == '1' &&
            $cl->loadFile(
                ASCMS_MODULE_PATH . '/Directory/Controller/DirHomeContent.class.php'
            )
        ) {
            $cache = \Cx\Core\Core\Controller\Cx::instanciate()
                ->getComponent('Cache');
            $directoryContent = $cache->getEsiContent(
                'Directory',
                'getContent',
                array(
                    'template' => \Env::get('init')->getCurrentThemeId()
                )
            );

            if (
                preg_match(
                    '/{DIRECTORY_FILE}/',
                    \Env::get('cx')->getPage()->getContent()
                )
            ) {
                \Env::get('cx')->getPage()->setContent(
                    str_replace(
                        '{DIRECTORY_FILE}',
                        $directoryContent,
                        \Env::get('cx')->getPage()->getContent()
                    )
                );
            }
            if (preg_match('/{DIRECTORY_FILE}/', $page_template)) {
                $page_template = str_replace(
                    '{DIRECTORY_FILE}',
                    $directoryContent,
                    $page_template
                );
            }
            if (preg_match('/{DIRECTORY_FILE}/', $themesPages['index'])) {
                $themesPages['index'] = str_replace(
                    '{DIRECTORY_FILE}',
                    $directoryContent,
                    $themesPages['index']
                );
            }
        }
    }

    /**
     * Do something after content is loaded from DB
     *
     * @param \Cx\Core\ContentManager\Model\Entity\Page $page The resolved page
     */
    public function postContentLoad(
        \Cx\Core\ContentManager\Model\Entity\Page $page
    ) {
        global $objTemplate, $_CORELANG;

        //Show Latest Directories
        $directoryBlockName = 'directoryLatest_row_';
        $cache = \Cx\Core\Core\Controller\Cx::instanciate()
            ->getComponent('Cache');
        $arrBlocks = $this->getLatestTplBlockDetails(
            $cache,
            $objTemplate
        );
        if (empty($arrBlocks)) {
            return;
        }

        $objDirectory = new Directory('');
        $objTemplate->setVariable(
            'TXT_DIRECTORY_LATEST',
            $_CORELANG['TXT_DIRECTORY_LATEST']
        );
        $entryIds = $objDirectory->getBlockLatestIds();
        if (!$entryIds) {
            return;
        }
        $i = 0;
        foreach ($entryIds as $entryId) {
            $params = $arrBlocks[$i];
            $params['blockId'] = $entryId;
            $content = $cache->getEsiContent(
                'Directory',
                'getBlockById',
                $params
            );

            ${$directoryBlockName . $params['block']}[] = $content;
            if ($i < (count($arrBlocks) - 1)) {
                ++$i;
            } else {
                $i = 0;
            }
        }
        foreach($arrBlocks as $arrBlock) {
            $objTemplate->replaceBlock(
                $directoryBlockName . $arrBlock['block'],
                implode(' ', ${$directoryBlockName . $arrBlock['block']})
            );
            $objTemplate->touchBlock(
                $directoryBlockName . $arrBlock['block']
            );
        }
    }

    /**
     * Get the latest template block name and its details
     *
     * @param \Cx\Core_Modules\Cache\Controller\ComponentController $cache
     * @param \Cx\Core\Html\Sigma                                   $template
     *
     * @return array
     */
    public function getLatestTplBlockDetails(
        \Cx\Core_Modules\Cache\Controller\ComponentController $cache,
        $template = null
    ) {
        $arrBlocks = array();
        $blockName = 'directoryLatest_row_';
        for ($i = 1; $i <= 10; $i++) {
            $params = $cache->getParamsByFindBlockExistsInTpl($blockName . $i);
            if (
                !empty($params) &&
                (
                    $template == null ||
                    $template->blockExists($blockName . $i)
                )
            ) {
                $params['block'] = $i;
                $arrBlocks[]     = $params;
            }
        }

        return $arrBlocks;
    }

    /**
     * Do something for search the content
     *
     * @param \Cx\Core\ContentManager\Model\Entity\Page $page       The resolved page
     */
    public function preContentParse(\Cx\Core\ContentManager\Model\Entity\Page $page) {
        $this->cx->getEvents()->addEventListener('SearchFindContent', new \Cx\Modules\Directory\Model\Event\DirectoryEventListener());
    }

    /**
     * Register the Event listeners
     */
    public function registerEventListeners()
    {
        $directoryEventListener = new \Cx\Modules\Directory\Model\Event\DirectoryEventListener();
        $this->cx->getEvents()->addEventListener('clearEsiCache', $directoryEventListener);
    }
}
