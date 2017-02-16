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
 * Main controller for Widget handler
 *
 * @author Michael Ritter <michael.ritter@cloudrexx.com>
 * @package cloudrexx
 * @subpackage coremodules_widget
 */

namespace Cx\Core_Modules\Widget\Controller;

/**
 * Main controller for Widget handler
 *
 * @author Michael Ritter <michael.ritter@cloudrexx.com>
 * @package cloudrexx
 * @subpackage coremodules_widget
 */
class ComponentController extends \Cx\Core\Core\Model\Entity\SystemComponentController {

    /**
     * List of widgets
     *
     * @var array
     */
    protected $widgets = array();

    /**
     * get controller classes
     *
     * @return array
     */
    public function getControllerClasses() {
        // Return an empty array here to let the component handler know that there
        // does not exist a backend, nor a frontend controller of this component.
        return array();
    }

    /**
     * Do something after content is loaded from DB
     *
     * USE CAREFULLY, DO NOT DO ANYTHING COSTLY HERE!
     * CALCULATE YOUR STUFF AS LATE AS POSSIBLE
     * @param \Cx\Core\ContentManager\Model\Entity\Page $page       The resolved page
     */
    public function postContentLoad(\Cx\Core\ContentManager\Model\Entity\Page $page) {
        $template = new \Cx\Core\Html\Sigma();
        $template->setTemplate($page->getContent());
        $this->parseWidgets($template, 'ContentManager', 'Page', $page->getId());
        $page->setContent($template->get());
    }

    /**
     * Do something before main template gets parsed
     *
     * USE CAREFULLY, DO NOT DO ANYTHING COSTLY HERE!
     * CALCULATE YOUR STUFF AS LATE AS POSSIBLE
     * @param \Cx\Core\Html\Sigma                       $template   The main template
     */
    public function preFinalize(\Cx\Core\Html\Sigma $template) {
        $this->parseWidgets(
            $template,
            'View',
            'Theme',
            \Env::get('init')->getCurrentThemeId()
        );
    }

    /**
     * Registers a Widget
     *
     * @param \Cx\Core_Modules\Widget\Model\Entity\Widget $widget Widget to add
     */
    public function registerWidget($widget) {
        $this->widgets[] = $widget;
    }

    /**
     * Parses the widgets on a template
     *
     * @param \HTML_Template_Sigma $template Template to parse widgets into
     * @param string $targetComponent Parse target component name
     * @param string $targetEntity Parse target entity name
     * @param string $targetId Parse target entity ID
     * @param array $excludedWidgets List of widget names that shall not be parsed
     */
    public function parseWidgets($template, $targetComponent, $targetEntity, $targetId, $excludedWidgets = array()) {
        foreach ($this->widgets as $widget) {
            if (in_array($widget->getName(), $excludedWidgets)) {
                continue;
            }
            $widget->parse(
                $template,
                null,
                $targetComponent,
                $targetEntity,
                $targetId,
                $excludedWidgets
            );
        }
    }

    /**
     * Returns the list of registered widgets
     * @return array List of \Cx\Core_Modules\Widget\Entity\Widget objects
     */
    public function getWidgets() {
        return $this->widgets;
    }

    /**
     * Looks up the template content of a widget
     * @param string $widgetName Name of the widget to get content for
     * @param int $themeId ID of the theme to get Widget content for
     * @param int $pageId ID of the page to get Widget content for
     * @param string $targetComponent Parse target component name
     * @param string $targetEntity Parse target entity name
     * @param string $targetId Parse target entity ID
     * @param string $channel Channel identifier
     * @return \Cx\Core\Html\Sigma Widget content as template
     */
    public function getWidgetContent($widgetName, $themeId, $pageId, $targetComponent, $targetEntity, $targetId, $channel) {
        $em = $this->cx->getDb()->getEntityManager();
        $themeRepo = new \Cx\Core\View\Model\Repository\ThemeRepository();
        $theme = $themeRepo->findById($themeId);
        // Since version number is not yet defined (XY), we do not check this yet
        if (false) {//version_compare($theme->getVersionNumber(), 'XY' '>=') {
            // load theme file contents:
            // /themes/<theme>/<widgetComponentType>/<widgetComponentName>/Widget/<widgetName>/<targetComponentName>/<targetEntityName>/<targetId>.html
            return;
        }
        $page = $em->find('Cx\Core\ContentManager\Model\Entity\Page', $pageId);
        $parseTarget = $this->getParseTarget(
            $targetComponent,
            $targetEntity,
            $targetId
        );
        return $parseTarget->getWidgetContent($widgetName, $theme, $page, $channel);
    }

    /**
     * Returns the parse target entity
     * @param string $componentName Parse target component name
     * @param string $entityName Parse target entity name
     * @param string $entityId Parse target entity id
     * @return \Cx\Model\Base\EntityBase Parse target entity
     */
    protected function getParseTarget($componentName, $entityName, $entityId) {
        // the following IF block can be dropped as soon as Block is a Doctrine entity
        if ($componentName == 'Block' && $entityName == 'Block') {
            return new \Cx\Modules\Block\Model\Entity\Block($entityId);
        } else if ($componentName == 'View' && $entityName == 'Theme') {
            $themeRepo = new \Cx\Core\View\Model\Repository\ThemeRepository();
            return $themeRepo->findById($entityId);
        }
        $em = $this->cx->getDb()->getEntityManager();
        $component = $this->getComponent($componentName);
        if (!$component) {
            throw new \Exception('Component not found: "' . $componentName . '"');
        }
        $target = $em->find(
            'Cx\\' . ucfirst($component->getType()) . '\\' . $component->getName() . '\\Model\\Entity\\' . $entityName,
            $entityId
        );
        if (!is_a($target, $this->getNamespace() . '\Model\Entity\WidgetParseTarget')) {
            throw new \Exception('Invalid parse target specified');
        }
        return $target;
    }

    /**
     * Register your event listeners here
     *
     * USE CAREFULLY, DO NOT DO ANYTHING COSTLY HERE!
     * CALCULATE YOUR STUFF AS LATE AS POSSIBLE.
     * Keep in mind, that you can also register your events later.
     * Do not do anything else here than initializing your event listeners and
     * list statements like
     * $this->cx->getEvents()->addEventListener($eventName, $listener);
     */
    public function registerEventListeners()
    {
        $eventListener = new \Cx\Core_Modules\Widget\Model\Event\WidgetEventListener($this->cx);
        $this->cx->getEvents()->addEventListener('clearEsiCache', $eventListener);
    }

    /**
     * Create a new widget
     *
     * @param \Cx\Core\Core\Model\Entity\SystemComponentController $component   Component registering this widget
     * @param string  $name              Name of the widget
     * @param boolean $hasContent        (optional) Wheter this widget has content or not
     * @param string  $adapterName       (optional) Name of the JsonAdapter to call. If not specified, $component->getName() is used
     * @param string  $adapterMethodName (optional) Name of the JsonAdapter method to call. If not specified, "getWidget" is used
     * @param array   $adapterParams     (optional) Params to pass on JsonAdapter call. If not specified, a default list is used, see getEsiParams()
     */
    public function createWidget(
        $component,
        $name,
        $hasContent = false,
        $adapterName = '',
        $adapterMethodName = '',
        $adapterParams = array()
    ) {
        $widget = new \Cx\Core_Modules\Widget\Model\Entity\EsiWidget(
            $component,
            $name,
            $hasContent,
            $adapterName,
            $adapterMethodName,
            $adapterParams
        );
        $this->registerWidget(
            $widget
        );
    }

    /**
     * Instanciates a new widget
     *
     * @param \Cx\Core\Core\Model\Entity\SystemComponentController $component Component registering this widget
     * @param string  $name              Name of this widget
     * @param array   $randomNames       Array of random widget names
     * @param boolean $hasContent        (optional) Wheter this widget has content or not
     * @param string  $adapterName       (optional) Name of the JsonAdapter to call. If not specified, $component->getName() is used
     * @param string  $adapterMethodName (optional) Name of the JsonAdapter method to call. If not specified, "getWidget" is used
     * @param array   $adapterParams     (optional) Params to pass on JsonAdapter call. If not specified, a default list is used, see getEsiParams()
     */
    public function createRandomWidget(
        $component,
        $name,
        $randomNames,
        $hasContent = false,
        $adapterName = '',
        $adapterMethodName = '',
        $adapterParams = array()
    ) {
        $widget = new \Cx\Core_Modules\Widget\Model\Entity\RandomEsiWidget(
            $component,
            $name,
            $randomNames,
            $hasContent,
            $adapterName,
            $adapterMethodName,
            $adapterParams
        );
        $this->registerWidget(
            $widget
        );
    }
}
