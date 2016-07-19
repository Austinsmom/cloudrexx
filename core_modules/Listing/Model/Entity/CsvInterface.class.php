<?php
<<<<<<< HEAD
/**
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
=======
>>>>>>> f7ee35166c3ea0314d3113cfac8fc8894c4d0211

/**
 * Csv interface
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      COMVATION Development Team <info@comvation.com>
 * @package     contrexx
 * @subpackage  core_module_listing
 */

namespace Cx\Core_Modules\Listing\Model;

/**
 * Csv interface
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      COMVATION Development Team <info@comvation.com>
 * @package     contrexx
 * @subpackage  core_module_listing
 */

class CsvInterface implements Exportable, Importable {
    protected $lineEnding;
    protected $separator;
    
    public function __construct($separator = ',', $lineEnding = "\n") {
        $this->lineEnding = $lineEnding;
        $this->separator = $separator;
    }

    /**
     * @todo Add "" around values containing separator
     * @todo Add support for different charsets, since CSV does not declare which one to use
     * Please note that Excel uses tab as delimiter if charset is unicode and semikolon for ANSI charsets
     * @param type $twoDimensionalArray
     * @return type 
     */
    public function export($twoDimensionalArray) {
        $content = '';
        foreach ($twoDimensionalArray as $array) {
            $content .= implode($this->separator, $array);
            $content .= $this->lineEnding;
        }
        return $content;
    }

    /**
     * @todo Add "" around values containing separator
     * @todo Add support for different charsets, since CSV does not declare which one to use
     * Please note that Excel uses tab as delimiter if charset is unicode and semikolon for ANSI charsets
     * @param type $dataAsString
     * @return type 
     */
    public function import($dataAsString) {
        $array = array();
        $array = explode($this->lineEnding, $dataAsString);
        foreach ($array as &$line) {
            $line = explode($this->separator, $line);
        }
        return $array;
    }
}
