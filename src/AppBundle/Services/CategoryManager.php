<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17/02/2017
 * Time: 11:22
 */

namespace AppBundle\Services;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
class CategoryManager
{
    private $em;
    private $repository;

    /**
     * CategoryManager constructor.
     * @param EntityManager $entityManager
     * @param $class
     */
    public function __construct(EntityManager $entityManager, $class)
    {
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('AppBundle:Category');
    }

    /**
     * @param $id_parent
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findSubcategoryByParent($id_parent){
        $category=$this->em->find(Category::class,$id_parent);
        return $category->getSubCategories();
    }

    /**
     * @return mixed
     */
    public function getTotalCategory()
    {
        $qb = $this->em->createQueryBuilder()
            ->select('count(category.id)')
            ->from('AppBundle:Category','category');
        return $qb->getQuery()->getSingleScalarResult();

    }

    /**
     * @return Category[]|array
     */
    public function getListCategories($id_families=array()){

        $qb = $this->em->createQueryBuilder();
        $qb->select('category')
            ->from('AppBundle:Category', 'category');
      return $qb->getQuery()->getResult();
        //return $this->repository->findAll();
    }

    /**
     * @param $id_category
     * @return Category|null|object
     */
    public function findCategoryById($id_category){
      return $this->repository->find($id_category);
    }



}