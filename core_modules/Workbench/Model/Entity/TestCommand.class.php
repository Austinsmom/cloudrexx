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
 * Command to access behat command line tools
 * @author Michael Ritter <michael.ritter@comvation.com>
 */

namespace Cx\Core_Modules\Workbench\Model\Entity;

/**
 * Command to access behat command line tools
 * @author Michael Ritter <michael.ritter@comvation.com>
 */
class TestCommand extends Command {
    
    /**
     * Command name
     * @var string
     */
    protected $name = 'test';
    
    /**
     * Command description
     * @var string
     */
    protected $description = 'Wrapper for behat command line tools';
    
    /**
     * Command synopsis
     * @var string
     */
    protected $synopsis = 'workbench(.bat) test ([{component_name}|{component_type}]) ({some_crazy_arguments_for_phpunit})';
    
    /**
     * Command help text
     * @var string
     */
    protected $help = 'To be defined';
    
    /**
     * Array of testing folders
     */
    protected $testingFolders = array();

    /**
     * Execute this command
     * @param array $arguments Array of commandline arguments
     */
    public function execute(array $arguments) {
        /*
         * When creating a new component
         *  - cd to component's testing folder
         *  - behat --init
         *  - sample feature file (behat story-syntax)
         *  - behat --snippets
         * 
         * To execute tests
         *  - cd to component's testing folder
         *  - behat
         * 
         * Create test code
         *  - behat --snippets
         */
        /*global $argv;

        // php phpunit.php --bootstrap ../cx_bootstrap.php --testdox ../test/core/
        //\DBG::activate(DBG_PHP);
        $argv = array(
            'phpunit.php',
            //'--bootstrap',
            //'../cx_bootstrap.php',
            '--testdox',
            ASCMS_DOCUMENT_ROOT.'/testing/tests/core/',
        );*/
        
        $systemConfig      = \Env::get('config');
        $useCustomizing    = isset($systemConfig['useCustomizings']) && $systemConfig['useCustomizings'] == 'on';
        
        $arrComponentTypes = array('core', 'core_module', 'module');                
        
        // check for the component type
        if (isset($arguments[2]) && in_array($arguments[2], $arrComponentTypes)) {
            $this->getTestingFoldersByType($arguments[2], $useCustomizing);
        } elseif (!empty ($arguments[2])) {
            // check whether it a valid component
            $componentName = $arguments[2];            
            
            foreach ($arrComponentTypes as $cType) {
                $componentFolder        = $this->getModuleFolder($componentName, $cType, $useCustomizing);
                if ($this->addTestingFolderToArray($componentName, $componentFolder)) {
                    break;
                }                                
            }
        }
        
        // get all testing folder when component type or name not specificed
        if (empty($this->testingFolders)) {
            foreach ($arrComponentTypes as $cType) {
                $this->getTestingFoldersByType($cType, $useCustomizing);
            }
        }
        
        $workbench = new \Cx\Core_Modules\Workbench\Controller\Workbench();
        $phpPath   = $workbench->getConfigEntry('php');
        
        $phpUnitTestPath = ASCMS_DOCUMENT_ROOT.'/testing/PHPUnit/phpunit.php';        
        if(!file_exists($phpUnitTestPath)) {
            $this->interface->show("PhpUnit test is not found in ". ASCMS_DOCUMENT_ROOT.'/testing/PHPUnit');
            return;
        }
        
        chdir(ASCMS_DOCUMENT_ROOT.'/testing/PHPUnit/');
        foreach ($this->testingFolders as $testingFolder) {
            echo shell_exec($phpPath . ' ' . $phpUnitTestPath .' --bootstrap ../cx_bootstrap.php --testdox ' . $testingFolder);
        }
        
        $this->interface->show('Done');
    }
    
    /**
     * Return the testing folders by component type
     * 
     * @param  string $componentType Component type
     * 
     * @return array Testing folders by given component type
     */
    private function getTestingFoldersByType($componentType, $useCustomizing) {
        
        $cx = \Env::get('cx');
        $em = $cx->getDb()->getEntityManager();        
        
        $systemComponentRepo = $em->getRepository('Cx\\Core\\Core\\Model\\Entity\\SystemComponent');
        $systemComponents = $systemComponentRepo->findBy(array('type'=>$componentType));
        
        if (!empty($systemComponents)) {
            foreach ($systemComponents as $systemComponent) {
                $this->addTestingFolderToArray($systemComponent->getName(), $systemComponent->getDirectory($useCustomizing));
            }
        }
        
        // load the old legacy components. assume core_module, module can only possible
        if (in_array($componentType, array('core_module', 'module'))) {
            static $objModuleChecker = NULL;

            if (!isset($objModuleChecker)) {
                $objModuleChecker = \Cx\Core\ModuleChecker::getInstance(\Env::get('em'), \Env::get('db'), \Env::get('ClassLoader'));
            }
            
            $arrModules = array();
            switch ($componentType) {
                case 'core_module':
                    $arrModules = $objModuleChecker->getCoreModules();
                    break;
                case 'module':
                    $arrModules = $objModuleChecker->getModules();
                    break;
                default:
                    break;
            }
            
            foreach ($arrModules as $component) {
                if (!array_key_exists($component, $this->testingFolders)) {
                    $componentFolder = $this->getModuleFolder($component, $componentType, $useCustomizing);
                    $this->addTestingFolderToArray($component, $componentFolder);
                }
            }
        }
    }
    
    /**
     * Returns module folder name
     * 
     * @param string  $componentName     Component Name
     * @param string  $componentType     Component Type
     * @param boolean $allowCustomizing  Check for the customizing folder
     * 
     * @return string module folder name
     */
    private function getModuleFolder($componentName, $componentType, $allowCustomizing = true)
    {
        $basepath      = ASCMS_DOCUMENT_ROOT . \Cx\Core\Core\Model\Entity\SystemComponent::getPathForType($componentType);
        $componentPath = $basepath . '/' . $componentName;
        
        if (!$allowCustomizing) {
            return $componentPath;
        }
        
        return \Env::get('cx')->getClassLoader()->getFilePath($componentPath);
    }
    
    /**
     * Added module testing folder to a array
     * 
     * @param string $componentName Component name
     * @param string $componentFolder Module Fodler path
     *      
     * @return boolean true if added successfully otherwise false
     */
    private function addTestingFolderToArray($componentName, $componentFolder)
    {
        $componentTestingFolder = $componentFolder . ASCMS_TESTING_FOLDER;
        if (!empty($componentFolder) && file_exists($componentFolder) && file_exists($componentTestingFolder)) {
            $this->testingFolders[$componentName] = $componentTestingFolder;
            return true;
        }
        
        return false;        
    }
}
