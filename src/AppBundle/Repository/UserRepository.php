<?php
/**
 * Created by PhpStorm.
 * User: Hamdi
 * Date: 23/11/2016
 * Time: 16:56
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u FROM AppBundle:User u ORDER BY u.firstname ASC'
            )
            ->getResult();
    }

    public function findAll()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u FROM AppBundle:User u'
            )
            ->getResult();
    }

    public function findOneByUsername($username){
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.username LIKE :username')
            ->setParameter('username', $username);
        return $qb->getQuery()->getResult();
    }

    public function findOneByEmail($email){
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.email LIKE :email')
            ->setParameter('email', $email);
        return $qb->getQuery()->getResult();
    }

    public function findOneByConsularCard($consularCard){
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.consularCard LIKE :consular_card')
            ->setParameter('consular_card', $consularCard);
        return $qb->getQuery()->getResult();
    }

    /**
     * @return int
     */
    public function getLastActiveUser(){
        $qb = $this->createQueryBuilder("u")
                 ->leftJoin('u.groups','gr')
                 ->having('COUNT(gr.id)=0')
                 ->groupBy('u.id')
             ;
        $results=$qb->getQuery()->getResult();
        $c=0;
        foreach ($results as $user){
           if($user->isActiveNow()) {
               $c++;
           }
        }
        return $c;
    }

}