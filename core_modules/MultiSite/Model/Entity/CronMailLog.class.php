<?php

/**
 * Class CronMailLog
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      Project Team SS4U <info@comvation.com>
 * @package     contrexx
 * @subpackage  coremodule_multisite
 */

namespace Cx\Core_Modules\MultiSite\Model\Entity;

/**
 * Class CronMailLog
 * 
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      Project Team SS4U <info@comvation.com>
 * @package     contrexx
 * @subpackage  coremodule_multisite
 */
class CronMailLog extends \Cx\Model\Base\EntityBase {

    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var integer $contactId 
     */
    protected $contactId;

    /**
     * @var integer $websiteId 
     */
    protected $websiteId;

    /**
     * @var integer $success; 
     */
    protected $success;

    /**
     * @var datetime $sentDate; 
     */
    protected $sentDate;

    /**
     *
     * @var Cx\Core_Modules\MultiSite\Model\Entity\CronMail $cronMail
     */
    protected $cronMail;
    
    /**
     * @var string $token
     */
    protected $token;

    /**
     * Constructor
     */
    public function __construct() {}
        
    /**
     * Get the id
     * 
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the id
     * 
     * @param integer $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get the contact id
     * 
     * @return integer $contactId
     */
    public function getContactId() {
        return $this->contactId;
    }

    /**
     * Set the contact id
     * 
     * @param integer $contactId
     */
    public function setContactId($contactId) {
        $this->contactId = $contactId;
    }

    /**
     * Get the websiteId
     * 
     * @return integer $websiteId
     */
    public function getWebsiteId() {
        return $this->websiteId;
    }

    /**
     * Set the websiteId
     * 
     * @param integer $websiteId
     */
    public function setWebsiteId($websiteId) {
        $this->websiteId = $websiteId;
    }

    /**
     * Get the success
     * 
     * @return integer $success
     */
    public function getSuccess() {
        return $this->success;
    }

    /**
     * Set the success
     * 
     * @param integer $success
     */
    public function setSuccess($success) {
        $this->success = $success;
    }

    /**
     * Get the sentDate
     * 
     * @return datetime $sentDate
     */
    public function getSentDate() {
        return $this->sentDate;
    }

    /**
     * Set the sentDate
     * 
     * @param datetime $sentDate
     */
    public function setSentDate($sentDate) {
        $this->sentDate = $sentDate;
    }

    /**
     * Set the cron mail
     * 
     * @param \Cx\Core_Modules\MultiSite\Model\Entity\CronMail $cronMail
     */
    public function setCronMail(CronMail $cronMail) {
        $this->cronMail = $cronMail;
    }

    /**
     * Get the cron mail
     * 
     * @return \Cx\Core_Modules\MultiSite\Model\Entity\CronMail $cronMail
     */
    public function getCronMail() {
        return $this->cronMail;
    }

    /**
     * Set the token
     * 
     * @param string $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * Get the token
     * 
     * @return string $token
     */
    public function getToken() {
        return $this->token;
    }

}
