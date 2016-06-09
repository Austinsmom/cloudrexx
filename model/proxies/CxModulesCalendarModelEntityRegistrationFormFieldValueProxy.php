<?php

namespace Cx\Model\Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class CxModulesCalendarModelEntityRegistrationFormFieldValueProxy extends \Cx\Modules\Calendar\Model\Entity\RegistrationFormFieldValue implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    private function _load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    
    public function setRegId($regId)
    {
        $this->_load();
        return parent::setRegId($regId);
    }

    public function getRegId()
    {
        $this->_load();
        return parent::getRegId();
    }

    public function setFieldId($fieldId)
    {
        $this->_load();
        return parent::setFieldId($fieldId);
    }

    public function getFieldId()
    {
        $this->_load();
        return parent::getFieldId();
    }

    public function setValue($value)
    {
        $this->_load();
        return parent::setValue($value);
    }

    public function getValue()
    {
        $this->_load();
        return parent::getValue();
    }

    public function setRegistration(\Cx\Modules\Calendar\Model\Entity\Registration $registration)
    {
        $this->_load();
        return parent::setRegistration($registration);
    }

    public function getRegistration()
    {
        $this->_load();
        return parent::getRegistration();
    }

    public function setRegistrationFormField(\Cx\Modules\Calendar\Model\Entity\RegistrationFormField $registrationFormField)
    {
        $this->_load();
        return parent::setRegistrationFormField($registrationFormField);
    }

    public function getRegistrationFormField()
    {
        $this->_load();
        return parent::getRegistrationFormField();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'regId', 'fieldId', 'value', 'registration', 'registrationFormField');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}