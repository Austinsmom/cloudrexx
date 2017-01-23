<?php

/**
 * Class AffiliateCreditRepository
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      ss4u <ss4ugroup@gmail.com>
 * @package     contrexx
 * @subpackage  coremodule_multisite
 */

namespace Cx\Core_Modules\MultiSite\Model\Repository;

/**
 * Class AffiliateCreditRepository
 * 
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      ss4u <ss4ugroup@gmail.com>
 * @package     contrexx
 * @subpackage  coremodule_multisite
 */

class AffiliateCreditRepository extends \Doctrine\ORM\EntityRepository {
    
    /**
     * Get the sum of the Affiliate credits amount based on the credit and payout
     * 
     * @param object $user User object
     * 
     * @return decimal
     */
    public function getTotalCreditsAmountByUser($user) {
        
        if (!$user || (!($user instanceof \User) && !($user instanceof \Cx\Core\User\Model\Entity\User))) {
            return 0;
        }

        $userId = $user->getId();        
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('sum(ac.amount)')
           ->from('\Cx\Core_Modules\MultiSite\Model\Entity\AffiliateCredit', 'ac')
           ->leftJoin('ac.referee', 'r')
           ->groupBy('r.id')->having('r.id = :userId')->setParameter('userId' , $userId)
           ->andWhere('ac.credited = 1')
           ->andWhere('ac.payout is NULL');
        $result = $qb->getQuery()->getResult();
        return !empty($result) ? current(current($result)) : 0;
    }
    
    /**
     * Get the subscriptions count by Criteria
     * 
     * @param array $criteria
     * 
     * @return decimal
     */
    public function getSubscriptionCountByCriteria($criteria) {
        if (empty($criteria)) {
            return;
        }
        
        if (empty($criteria['user'])) {
            return;
        }
        $user = $criteria['user'];
        unset($criteria['user']);
        if (!$user || (!($user instanceof \User) && !($user instanceof \Cx\Core\User\Model\Entity\User))) {
            return;
        }
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(ac.id)')
           ->from('\Cx\Core_Modules\MultiSite\Model\Entity\AffiliateCredit', 'ac')
           ->leftJoin('ac.referee', 'r')
           ->leftJoin('ac.subscription', 's')
           ->groupBy('r.id')->having('r.id = :userId')->setParameter('userId' , $user->getId());
        
        $i = 1;
        foreach ($criteria as $fieldType => $value) {
            if ($fieldType === 'payout') {
                $qb->andWhere('ac.payout ' . $value);
                continue;
            }
            if (method_exists($qb->expr(), $fieldType) && is_array($value)) {
                foreach ($value as $condition) {
                    $qb->andWhere(call_user_func(array($qb->expr(), $fieldType), $condition[0], $condition[1]));
                }
            } else {
                $qb->andWhere($fieldType . ' = ?' . $i)->setParameter($i, $value);
            }
            $i++;
        }
        
        $result = $qb->getQuery()->getResult();
        return !empty($result) ? current(current($result)) : 0;
    }
}