<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05/03/2017
 * Time: 19:31
 */

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;

class OrderStatusManager
{
    /**
     * OrderStatusManager constructor.
     * @param EntityManager $entityManager
     * @param $class
     */
    public function __construct(EntityManager $entityManager, $class) {

        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('AppBundle:OrderStatus');

    }

    /**
     * @return \AppBundle\Entity\OrderStatus[]|array
     */
    public function getListStatus(){
        return $this->repository->findAll();
    }

    /**
     * @param $id
     * @return \AppBundle\Entity\OrderStatus|null|object
     */
    public function findStatusDefault(){
        return $this->repository->findOneBy(array('defaults'=>true));
    }

    /**
     * @param $id
     * @return \AppBundle\Entity\OrderStatus|null|object
     */
    public function findStatusById($id){
        return $this->repository->find($id);
    }

    /**
     * @return \AppBundle\Entity\OrderStatus[]|array
     */
    public function findAllStatusDefault(){
        return $this->repository->findBy(array('defaults'=>true));
    }

    /**
     * @return \AppBundle\Entity\OrderStatus[]|array
     */
    public function findAllStatusDefaultCancel(){
        return $this->repository->findBy(array('default_cancel'=>true));
    }

}