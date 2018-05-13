<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05/03/2017
 * Time: 17:23
 */

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;

class OrderManager
{
    /**
     * OrderManager constructor.
     * @param EntityManager $entityManager
     * @param $class
     */
    public function __construct(EntityManager $entityManager, $class) {

        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('AppBundle:Orders');

    }

    /**
     * @param $id
     * @return \AppBundle\Entity\Orders|null|object
     */
    public function findOrderByID($id){
       return $this->repository->find($id);
    }

    /**
     * @return \AppBundle\Entity\Orders[]|array
     */
    public function getListOrder($families_id=array()){
        return $this->repository->findAll();
    }

}