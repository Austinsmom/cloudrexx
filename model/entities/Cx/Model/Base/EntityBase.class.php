<?php

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

/**
 * EntityBase
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      COMVATION Development Team <info@comvation.com>
 * @package     contrexx
 * @subpackage  model_base
 */

namespace Cx\Model\Base;

/**
 * Thrown by @link EntityBase::validate() if validation errors occur.
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      COMVATION Development Team <info@comvation.com>
 * @package     contrexx
 * @subpackage  model_base
 */
class ValidationException extends \Exception {
    protected $errors;

    public function __construct(array $errors) {
        parent::__construct();
        $this->errors = $errors;
        $this->assignMessage();
    }

    private function assignMessage() {
        $str = '';
        foreach($this->errors as $field => $details) {
            $str .= $field.":\n";
            foreach($details as $id => $message) {
                $str .= "    $id: $message\n";
            }
        }
        $this->message = $str;
    }

    public function getErrors() {
        return $this->errors;
    }
}

/**
 * This class provides the magic of being validatable.
 * See EntityBase::$validators if you want to subclass it.
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      COMVATION Development Team <info@comvation.com>
 * @package     contrexx
 * @subpackage  model_base
 */
class EntityBase {
    /**
     * Initialize this array as follows:
     * array(
     *     'columName' => Zend_Validate
     * )
     * @var array
     */
    protected $validators = null;

    /**
     * This is an ugly solution to allow $this->cx to be available in all entity classes
     * Since the entity's constructor is not called when an entity is loaded from DB this
     * cannot be assigned there.
     */
    public function __get($name) {
        if ($name == 'cx') {
            return \Cx\Core\Core\Controller\Cx::instanciate();
        }
    }

    /**
     * Returns the component controller for this component
     * @return \Cx\Core\Core\Model\Entity\SystemComponent
     */
    public function getComponentController() {
        $matches = array();
        preg_match('/Cx\\\\(?:Core|Core_Modules|Modules)\\\\([^\\\\]*)\\\\/', get_class($this), $matches);
        if (empty($matches[1])) {
            throw new \Exception('Could not find component name');
        }
        $em = $this->cx->getDb()->getEntityManager();
        $componentRepo = $em->getRepository('Cx\Core\Core\Model\Entity\SystemComponent');
        $myComponent = $componentRepo->findOneBy(array(
            'name' => $matches[1],
        ));
        if (!$myComponent) {
            throw new \Cx\Core\Core\Model\Entity\SystemComponentException('Component not found: "' . $matches[1] . '"');
        }
        return $myComponent;
    }

    /**
     * @throws ValidationException
     * @prePersist
     */
    public function validate() {
        if(!$this->validators)
            return;

        $errors = array();
        foreach($this->validators as $field => $validator) {
            $methodName = 'get'.ucfirst($field);
            $val = $this->$methodName();
            if($val) {
                if(!$validator->isValid($val)) {
                     $errors[$field] = $validator->getMessages();
                }
            }
        }
        if(count($errors) > 0)
            throw new ValidationException($errors);
    }

    /**
     * Route methods like getName(), getType(), getDirectory(), etc.
     * @param string $methodName Name of method to call
     * @param array $arguments List of arguments for the method to call
     * @return mixed Return value of the method to call
     */
    public function __call($methodName, $arguments) {
        return call_user_func_array(array($this->getComponentController(), $methodName), $arguments);
    }

    public function __toString() {
        $em = $this->cx->getDb()->getEntityManager();
        $cmf = $em->getMetadataFactory();
        $meta = $cmf->getMetadataFor(get_class($this));
        return (string) implode('/', $meta->getIdentifierValues($this));
    }
}
