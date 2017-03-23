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
 * Class EsiWidgetController
 *
 * @copyright   CLOUDREXX CMS - Cloudrexx AG Thun
 * @author      Project Team SS4U <info@comvation.com>
 * @package     cloudrexx
 * @subpackage  coremodule_stats
 * @version     1.0.0
 */

namespace Cx\Core_Modules\Stats\Controller;

/**
 * JsonAdapter Controller to handle EsiWidgets
 * Usage:
 * - Create a subclass that implements parseWidget()
 * - Register it as a Controller in your ComponentController
 *
 * @copyright   CLOUDREXX CMS - Cloudrexx AG Thun
 * @author      Project Team SS4U <info@comvation.com>
 * @package     cloudrexx
 * @subpackage  coremodule_stats
 * @version     1.0.0
 */

class EsiWidgetController extends \Cx\Core_Modules\Widget\Controller\EsiWidgetController {

    /**
     * Parses a widget
     *
     * @param string              $name     Widget name
     * @param \Cx\Core\Html\Sigma $template Widget template
     * @param string              $locale   RFC 3066 locale identifier
     */
    public function parseWidget($name, $template, $locale)
    {
        $stats      = $this->cx->getComponent('Stats');
        $objCounter = $stats->getCounterInstance();
        if ($name === 'GOOGLE_ANALYTICS') {
            $template->setVariable($name, $objCounter->getGoogleAnalyticsScript());
            return;
        }

        if ($name === 'ONLINE_USERS') {
            $template->setVariable($name, $objCounter->getOnlineUsers());
            return;
        }

        if ($name === 'VISITOR_NUMBER') {
            $template->setVariable($name, $objCounter->getVisitorNumber());
            return;
        }

        if ($name === 'COUNTER') {
            $template->setVariable($name, $objCounter->getCounterTag($this->currentPageId));
        }
    }

    /**
    * Returns the content of a widget
    *
    * @param array $params JsonAdapter parameters
    *
    * @return array Content in an associative array
    */
    public function getWidget($params)
    {
        if (isset($params['get']) && isset($params['get']['page'])) {
            $this->currentPageId = $params['get']['page'];
        }
        return parent::getWidget($params);
    }

}
