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
 * Sync
 *
 * @copyright   Cloudrexx AG
 * @author      Project Team SS4U <info@cloudrexx.com>
 * @package     cloudrexx
 * @subpackage  coremodule_sync
 */

namespace Cx\Core_Modules\Sync\Model\Entity;

/**
 * Sync
 *
 * @copyright   Cloudrexx AG
 * @author      Project Team SS4U <info@cloudrexx.com>
 * @package     cloudrexx
 * @subpackage  coremodule_sync
 */
class Sync extends \Cx\Model\Base\EntityBase {
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $toUri
     */
    protected $toUri;

    /**
     * @var string $apiKey
     */
    protected $apiKey;

    /**
     * @var boolean $active
     */
    protected $active;

    /**
     * @var Cx\Core_Modules\DataAccess\Model\Entity\DataAccess $dataAccess
     */
    protected $dataAccess;

    /**
     * @var Cx\Core_Modules\Sync\Model\Entity\Relation
     */
    protected $relations;

    /**
     * @var Cx\Core_Modules\Sync\Model\Entity\HostEntity
     */
    protected $hostEntities;

    public function __construct()
    {
        $this->relations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hostEntities = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set toUri
     *
     * @param string $toUri
     */
    public function setToUri($toUri) {
        $this->toUri = $toUri;
    }

    /**
     * Get toUri
     *
     * @return string
     */
    public function getToUri($entityIndexData = array()) {
        if (!count($entityIndexData)) {
            return $this->toUri();
        }
        
        $indexData = implode('/', $entityIndexData);
        
        //<domain>(/<offset>)/api/sync/<apiVersion>/<outputModule>/<dataSource>/<parameters>[(?apikey=<apikey>(&<options>))|?<options>]
        //<domain>(/<offset>)/api/sync/<apiVersion>/<outputModule>/[[DATA_SOURCE]]/[[INDEX_DATA]][(?apikey=[[API_KEY]](&<options>))|?<options>]
        $uri = $this->toUri;
        $uri = str_replace('[[DATA_SOURCE]]', $this->getDataAccess()->getName(), $uri);
        $uri = str_replace('[[API_KEY]]', $this->getApiKey(), $uri);
        $uri = str_replace('[[INDEX_DATA]]', $indexData, $uri);
        return $uri;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * Set active
     *
     * @param boolean $active
     */
    public function setActive($active) {
        $this->active = $active;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * Set dataAccess
     *
     * @param \Cx\Core_Modules\DataAccess\Model\Entity\DataAccess $dataAccess
     */
    public function setDataAccess(\Cx\Core_Modules\DataAccess\Model\Entity\DataAccess $dataAccess)
    {
        $this->dataAccess = $dataAccess;
    }

    /**
     * Get dataAccess
     *
     * @return \Cx\Core_Modules\DataAccess\Model\Entity\DataAccess $dataAccess
     */
    public function getDataAccess()
    {
        return $this->dataAccess;
    }

    /**
     * Add relation
     *
     * @param Cx\Core_Modules\Sync\Model\Entity\Relation $relation
     */
    public function addRelation(\Cx\Core_Modules\Sync\Model\Entity\Relation $relation)
    {
        $this->relations[] = $relation;
    }

    /**
     * Get relations
     *
     * @return Doctrine\Common\Collections\Collection $relations
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Set Relations
     *
     * @param array $relations
     */
    public function setRelations($relations)
    {
        $this->relations[] = $relations;
    }

    /**
     * Add hostEntity
     *
     * @param Cx\Core_Modules\Sync\Model\Entity\HostEntity $hostEntity
     */
    public function addHostEntity(\Cx\Core_Modules\Sync\Model\Entity\HostEntity $hostEntity)
    {
        $this->hostEntities[] = $hostEntity;
    }

    /**
     * Get hostEntities
     *
     * @return Doctrine\Common\Collections\Collection $hostEntities
     */
    public function getHostEntities()
    {
        // Customizing for old Calendar sync config:
        //if we're syncing Events:
        if (
            $this->getDataAccess()->getDataSource()->getIdentifier() !=
            'Cx\\Modules\\Calendar\\Model\\Entity\\Event'
        ) {
            return $this->hostEntities;
        }
        
        //load all contrexx_module_calendar_rel_event_host joined to contrexx_module_calendar_host
        global $objDatabase;
        $query = '
            SELECT
                `host`.`url`,
                `host_entity`.`event_id`
            FROM
                `' . DBPREFIX . 'module_calendar_rel_event_host` AS `host_entity`
            JOIN
                `' . DBPREFIX . 'module_calendar_host` AS `host`
            ON
                `host_entity`.`host_id` = `host`.`id`
            WHERE
                `host`.`status` = 0
        ';
        $results = $objDatabase->Execute($query);
        if (!$results || $results->EOF) {
            return $this->hostEntities;
        }
        while (!$results->EOF) {
            $host = $hostRepo->findOneBy(array(
                'host' => $results->fields['url'],
            ));
            $hostEntity = new \Cx\Core_Modules\Sync\Model\Entity\HostEntity();
            $hostEntity->setVirtual(true);
            $hostEntity->setSync($this);
            $hostEntity->setHost($host);
            $hostEntity->setEntityId($results->fields['event_id']);
            $this->hostEntities[] = $hostEntity;
            
            $results->moveNext();
        }
        // end customizing
        
        return $this->hostEntities;
    }

    /**
     * Set hostEntities
     *
     * @param array $hostEntities
     */
    public function setHostEntities($hostEntities)
    {
        $this->hostEntities[] = $hostEntities;
    }
    
    /**
     * Pushes entity changes to remote
     * @param string $eventType One of "post", "put", "delete"
     * @param array $entityIndexData Field=>value-type array with primary key data
     * @param \Cx\Model\Base\EntityBase $entity (optional) Entity for "post" and "put"
     * @throws   HTTP_Request2_Exception
     */
    public function push($eventType, $entityIndexData, $entity) {
        // is this synchronization active?
        if (!$this->getActive()) {
            return;
        }
        
        // customizing for current calendar:
        if (
            $this->getDataAccess()->getDataSource()->getIdentifier() ==
            'Cx\\Modules\\Calendar\\Model\\Entity\\Event'
        ) {
            // set registration to "extern" and registration uri to our host:
            $entity->setRegistration(
                \Cx\Modules\Calendar\Controller\CalendarEvent::EVENT_REGISTRATION_EXTERNAL
            );
            
            $event = new \Cx\Modules\Calendar\Controller\CalendarEvent($entity->getId());
            $url = \Cx\Core\Routing\Url::fromModuleAndCmd('Calendar', 'detail', '', array(
                'id' => $entity->getId(),
                'date' => $event->startDate->getTimestamp(),
            ));
            $entity->setRegistrationExternalLink($url->toString());
            
            // from CalendarEventManager:
            $fullyBooked = true;
            if (
                (
                    $event->registration == \Cx\Modules\Calendar\Controller\CalendarEvent::EVENT_REGISTRATION_EXTERNAL &&
                    !$event->registrationExternalFullyBooked
                ) ||
                (
                    $event->registration == \Cx\Modules\Calendar\Controller\CalendarEvent::EVENT_REGISTRATION_INTERNAL &&
                    (
                        empty($event->numSubscriber) ||
                        !\FWValidator::isEmpty($event->getFreePlaces())
                    )
                )
            ) {
                $fullyBooked = false;
            }
            $entity->setRegistrationExternalFullyBooked($fullyBooked);
        }
        // end customizing
        
        foreach ($this->getHostEntities() as $hostEntity) {
            // is there a host we should sync this entity to?
            if (
                $hostEntity->getHost()->getActive() &&
                $hostEntity->getEntityId() != '*' &&
                $hostEntity->getEntityId() != $entityIndexData
            ) {
                continue;
            }
            
            ob_start();
            
            // now we really push
            $url = $hostEntity->getHost()->getToUri(
                $this->getDataAccess()->getName(),
                $entityIndexData
            );
            $method = strtoupper($eventType);
            $content = array();
            $metaData = $this->cx->getDb()->getEntityManager()->getClassMetadata(get_class($entity));
            $primaryKeyNames = $metaData->getIdentifierFieldNames(); // get the name of primary key in database table
            foreach ($metaData->getColumnNames() as $column) {
                $field = $metaData->getFieldName($column);
                if (in_array($field, $primaryKeyNames)) {
                    //continue;
                }
                $content[$field] = $metaData->getFieldValue($entity, $field);
                if (is_object($content[$field]) && get_class($content[$field]) == 'DateTime') {
                    $content[$field] = $content[$field]->format(DATE_ATOM);
                }
            }
            $associationMappings = $metaData->getAssociationMappings();
            $classMethods = get_class_methods($entity);
            foreach ($associationMappings as $field => $associationMapping) {
                if (   $metaData->isSingleValuedAssociation($field)
                    && in_array('set'.ucfirst($field), $classMethods)
                ) {
                    if ($metaData->getFieldValue($entity, $field)) {
                        $foreignEntity = $metaData->getFieldValue($entity, $field);
                        $indexData = $this->getComponentController()->getEntityIndexData($foreignEntity);
                        $content[$field] = implode('/', $indexData);
                        continue;
                    }
                    $content[$field]= new $associationMapping['targetEntity']();
                } elseif ($metaData->isCollectionValuedAssociation($field)) {
                    $content[$field]= new $associationMapping['targetEntity']();
                }
            }
            
            $config = array(
            );
            $request = new \HTTP_Request2($url, $method, $config);
            $refUrl = \Cx\Core\Routing\Url::fromDocumentRoot();
            $refUrl->setMode('backend');
            $request->setHeader('Referrer', $refUrl->toString());
            $request->setBody(http_build_query($content, null, '&'));
            
            $response = $request->send();
            var_dump($response->getStatus());
            echo '<pre>' . $response->getBody() . '</pre>';
            echo 'Pushed to ' . $url . ' with method ' . $method . ', body was: ' . http_build_query($content);
            $logContents = ob_get_contents();
            ob_end_clean();
            
            $severity = 'INFO';
            if ($response->getStatus() != 200) {
                $severity = 'FATAL';
            }
            $this->cx->getEvents()->triggerEvent('SysLog/Add', array(
               'severity' => $severity,
               'message' => 'Sent ' . strtoupper($method) . ' to ' . $url,
               'data' => $logContents,
            ));
        }
    }
}
