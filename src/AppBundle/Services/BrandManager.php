<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22/02/2017
 * Time: 18:02
 */

namespace AppBundle\Services;


use AppBundle\Entity\Brand;
use Doctrine\ORM\EntityManager;

class BrandManager
{
    private $em;
    private $repository;

    /**
     * UnityService constructor.
     * @param EntityManager $entityManager
     * @param $class
     */
    public function __construct(EntityManager $entityManager, $class)
    {

        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('AppBundle:Brand');

    }

    public function findBrandById($id)
    {
        return $this->repository->find($id);
    }
}