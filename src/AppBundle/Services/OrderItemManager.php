<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05/03/2017
 * Time: 17:23
 */

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;
use AppBundle\Entity\OrderItem;

class OrderItemManager
{
    /**
     * OrderItemManager constructor.
     * @param EntityManager $entityManager
     * @param $class
     */
    public function __construct(EntityManager $entityManager, $class)
    {

        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('AppBundle:OrderItem');

    }

    /**
     * @param $order
     */
    public function findItemByOrder($order)
    {
        $q = $this->em->createQueryBuilder()
            ->select('item')
            ->from('AppBundle:OrderItem', 'item')
            ->where('item.order = :id_order')
            ->setParameter('id_order',$order);
        return $q->getQuery()->getResult();
    }



}