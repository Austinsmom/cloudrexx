<?php

/**
<<<<<<< HEAD
 * Contrexx
 *
 * @link      http://www.contrexx.com
 * @copyright Comvation AG 2007-2014
 * @version   Contrexx 4.0
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
 * "Contrexx" is a registered trademark of Comvation AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

/**
=======
>>>>>>> f7ee35166c3ea0314d3113cfac8fc8894c4d0211
 * JsonWrapper
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      COMVATION Development Team <info@comvation.com>
 * @package     contrexx
 * @subpackage  lib_framework
 */

if(!function_exists("json_encode")) {

    /**
     * @ignore
     */
    require_once(ASCMS_LIBRARY_PATH.'/PEAR/Services/JSON.php');

    /**
     * JsonWrapper
     *
     * @copyright   CONTREXX CMS - COMVATION AG
     * @author      COMVATION Development Team <info@comvation.com>
     * @package     contrexx
     * @subpackage  lib_framework
     */
    class JsonWrapper {
        protected $pearJSON;
        protected static $instance;
        protected function __construct() {
            $this->pearJSON = new Services_JSON();
        }

        public static function getInstance() {
            if(!self::$instance)
                self::$instance = new JsonWrapper();

            return self::$instance;
        }

        public function encode($obj) {
            return $this->pearJSON->encode($obj);
        }
        public function decode($str) {
            return $this->pearJSON->decode($str);
        }
    }

    function json_encode($obj) {
        return JsonWrapper::getInstance()->encode($obj);
    }

    function json_decode($str) {
        return JsonWrapper::getInstance()->encode($str);
    }
}
