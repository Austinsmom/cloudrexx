<?php

/**
 * Class WebsiteCollection
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      Project Team SS4U <info@comvation.com>
 * @package     contrexx
 * @subpackage  coremodule_multisite
 */

namespace Cx\Core_Modules\MultiSite\Model\Entity;

/**
 * Class WebsiteCollection
 * 
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      Project Team SS4U <info@comvation.com>
 * @package     contrexx
 * @subpackage  coremodule_multisite
 */

class WebsiteCollection extends \Cx\Model\Base\EntityBase {
    
    /**
     *
     * @var integer $id 
     */
    protected $id;
    /**
     * @var integer $quota
     */
    protected $quota;
    
    /**
     *
     * @var Cx\Core_Modules\MultiSite\Model\Entity\Website
     */
    protected $websites;
    
    /**
     *
     * @var Cx\Core_Modules\MultiSite\Model\Entity\WebsiteTemplate
     */
    protected $websiteTemplate;
    
    /**
     * @var array
     */
    protected $tempData = array();

    /**
     * Constructor
     */
    public function __construct() {
        $this->websites = new \Doctrine\Common\Collections\ArrayCollection();        
    }
    
    public function getTempData() {
        return $this->tempData;
    }
    
    public function setTempData($tempData) {
        $this->tempData = $tempData;
    }
    
    /**
     * Get the id
     * 
     * @return integer id
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
     * Get the quota value
     * 
     * @return integer value of the quota
     */
    public function getQuota() {
        return $this->quota;
    }
    
    /**
     * Set the quota value
     * 
     * @param integer $quota
     */
    public function setQuota($quota) {
        $this->quota = $quota;
    }

    /**
     * Get the websites
     * 
     * @return $websites
     */
    public function getWebsites()
    {
        return $this->websites;
    }
    
    /**
     * Set the websites
     *  
     * @param \Doctrine\Common\Collections\ArrayCollection $websites
     */
    public function setWebsites($websites)
    {
        $this->websites = $websites;
        foreach ($websites as $website) {
            $website->setWebsiteCollection($this);
        }
    }
    
    /**
     * Add the website
     * 
     * @param \Cx\Core_Modules\MultiSite\Model\Entity\Website $website
     */
    public function addWebsite(Website $website) {
        $this->websites[] = $website;
        $website->setWebsiteCollection($this);
    }
    
    /**
     * Get the website Template
     * 
     * @return array $websiteTemplate
     */
    public function getWebsiteTemplate() {
        return $this->websiteTemplate;
    }
    
    /**
     * Set the website Template
     * 
     * @param \Cx\Core_Modules\MultiSite\Model\Entity\WebsiteTemplate $websiteTemplate
     */
    public function setWebsiteTemplate(WebsiteTemplate $websiteTemplate) {
        $this->websiteTemplate = $websiteTemplate;
        $websiteTemplate->addWebsiteCollection($this); 
    }

    /**
     * Return the backend edit link of websites
     */
    public function getEditLink()
    {
        global $_ARRAYLANG;
        
        $websites = array();
        foreach ($this->websites as $website) {
            $websiteDetailLink = '<a href="index.php?cmd=MultiSite&term=' . $website->getId() . '" title="' . $_ARRAYLANG['TXT_CORE_MODULE_MULTISITE_WEBSITE_DETAIL_LINK'] . '"> 
                                    <img 
                                        src = "' . \Env::get('cx')->getCodeBaseCoreModuleWebPath() . '/MultiSite/View/Media/details.gif"
                                        width="16px" height="16px"
                                        alt="' . $_ARRAYLANG['TXT_CORE_MODULE_MULTISITE_WEBSITE_DETAIL_LINK'] . '"
                                    />
                                </a>';
            $websites[] = '<a href="index.php?cmd=MultiSite&editid='. $website->getId() .'" title="' . $_ARRAYLANG['TXT_CORE_MODULE_MULTISITE_WEBSITE_EDIT_LINK'] . '">'
                            . $website->getName()  . 
                          '</a>'. $websiteDetailLink;
        }
        return implode(', ', $websites);
    }
}
