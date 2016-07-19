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
 * Base class for workbench commands
 * @author Michael Ritter <michael.ritter@comvation.com>
 */

namespace Cx\Core_Modules\Workbench\Model\Entity;

/**
 * Exceptions thrown by commands should be CommandExceptions
 * @author Michael Ritter <michael.ritter@comvation.com>
 */
class CommandException extends \Cx\Core_Modules\Workbench\Controller\WorkbenchException {};

/**
 * Base class for workbench commands
 * @author Michael Ritter <michael.ritter@comvation.com>
 */
abstract class Command {
    /**
     * Currently used user interface
     * @var UserInterface
     */
    protected $interface;
    
    /**
     * Command name
     * @var string
     */
    protected $name;
    
    /**
     * Command description
     * @var string
     */
    protected $description;
    
    /**
     * Command synopsis
     * @var string
     */
    protected $synopsis;
    
    /**
     * Command help text
     * @var string
     */
    protected $help;
    
    /**
     * Loads a command
     * @param UserInterface $owner 
     */
    public function __construct(UserInterface $owner) {
        $this->interface = $owner;
    }
    
    /**
     * Returns the name of this command
     * @return string Command name
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Returns the description for this command
     * @return string Command description
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * Returns the synopsis for this command
     * @return string Command synopsis
     */
    public function getSynopsis() {
        return $this->synopsis;
    }
    
    /**
     * Returns the help text for this command
     * @return string Command help text
     */
    public function getHelp() {
        return $this->help;
    }
    
    /**
     * Execute this command
     * @param array $arguments Array of commandline arguments
     */
    public abstract function execute(array $arguments);
}
