<?php
/**
 * Created by PhpStorm.
 * User: Hamdi
 * Date: 26/11/2016
 * Time: 21:52
 */

namespace AppBundle\Services;

use AppBundle\Entity\Brand;
use AppBundle\Entity\Category;
use AppBundle\Entity\Family;
use AppBundle\Entity\SubCategory;
use AppBundle\Entity\Unity;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Article;
use Doctrine\ORM\Tools\Pagination\Paginator;


class ArticleManager
{
    private $em;
    private $session;
    private $repository;
    private $tokenStorage;

    public function __construct(EntityManager $entityManager, $class)
    {

        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('AppBundle:Article');

    }


    /**
     * Get the paginated list of published articles
     *
     * @param int $page
     * @param int $maxperpage
     * @param string $sortby
     * @return Paginator
     */
    public function getList($page = 1, $maxperpage = 10, $filter = null,$id_families=array())
    {

        $q = $this->em->createQueryBuilder()
            ->select('article', 'brand', 'category1')
            ->from('AppBundle:Article', 'article')
            ->leftJoin('article.brand', 'brand')
            ->leftJoin('article.category', 'category1');


        if (!is_null($filter) && count($filter)) {
            foreach ($filter as $key => $fil) {

                    if ($key == 'f') {
                        $q->andWhere('family.nameFr LIKE :'.$key)->setParameter($key, '%' . $fil . '%');
                    } elseif ($key == 'ca') {
                        $id_cat=explode('-',$fil);
                        if(end($id_cat)=='parent'){
                            $q->andWhere('category1.id = :'.$key)->setParameter($key,$id_cat[0]);
                        }
                    } elseif ($key == 'b') {
                        $q->andWhere('brand.brand LIKE :' .$key)->setParameter($key, '%' . $fil . '%');
                    } elseif ($key == 'code') {
                        $q->andWhere('article.code LIKE :'.$key.' OR article.name like :'.$key)->setParameter($key, '%' . trim($fil) . '%');
                    } elseif ($key == 'isNew') {
                        $q->andWhere('article.isNew = :'.$key)->setParameter($key,true);
                    } elseif ($key == 'isFragrance') {
                        $q->andWhere('article.isTopFragrances = :'.$key)->setParameter($key,true);
                    } elseif ($key == 'isOffer') {
                        $q->andWhere('article.isOffer = :'.$key)->setParameter($key,true);
                    } elseif ($key == 'isSpecial') {
                        $q->andWhere('article.isSpecial = :'.$key)->setParameter($key,true);
                    }



            }
        }

        $q->setFirstResult(($page - 1) * $maxperpage)
            ->setMaxResults($maxperpage);

        return new Paginator($q);
    }

    /**
     * @return mixed
     */
    public function getTotalProduct()
    {
        $qb = $this->em->createQueryBuilder()
            ->select('count(article.id)')
            ->from('AppBundle:Article', 'article');
        return $qb->getQuery()->getSingleScalarResult();

    }

    /**
     * @param Category $category
     * @return Article[]|array
     */
    public function findProductsByCategory(Category $category)
    {
        return $this->repository->findBy(array('category' => $category));
    }

    /**
     * @param SubCategory $subCategory
     * @return Article[]|array
     */
    public function findProductsBySubCategory(SubCategory $subCategory)
    {
        return $this->repository->findBy(array('subCategory' => $subCategory));
    }

    /**
     * @param Family $family
     * @return Article[]|array
     */
    public function findProductsByFamily(Family $family)
    {
        return $this->repository->findBy(array('family' => $family));
    }

    /**
     * @param Brand $brand
     * @return Article[]|array
     */
    public function findProductsByBrand(Brand $brand)
    {
        return $this->repository->findBy($brand);
    }

    /**
     * @param Unity $unity
     * @return Article[]|array
     */
    public function findProductsByCurrency(Unity $unity)
    {
        return $this->repository->findBy($unity);
    }

    /**
     * @param $article_id
     * @return Article|null|object
     */
    public function findProductsByID($article_id)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('partial art.{id,name,price,currency,code}')
            ->from('AppBundle:Article', 'art')
            ->leftJoin('art.currency','cur')
            ->where('art.id = :article_id AND art.isPublished=true')
            ->setParameter('article_id',$article_id)
        ;
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param $family_id
     * @return mixed
     */
    public function validateProductInFamily($article_id,$keyword){
        $qb = $this->em->createQueryBuilder();
        $qb->select('partial art.{id,name,price,currency,code}')
            ->from('AppBundle:Article', 'art')
            ->leftJoin('art.currency','cur')
            ->leftJoin('art.family','family')
            ->where('family.keyword = :keyword AND art.isPublished=true')
            ->andWhere('art.id = :article_id')
            ->setParameter('article_id',$article_id)
            ->setParameter('keyword',$keyword)
        ;
        return $qb->getQuery()->getOneOrNullResult();
    }
    /**
     * @param $keyword
     * @return array
     */
    public function findProductsByKeyword($keyword)
    {
        $q = $this->em->createQueryBuilder()
            ->select('article', 'currency','family','brand')
            ->from('AppBundle:Article', 'article')
            ->leftJoin('article.currency', 'currency')
            ->leftJoin('article.brand', 'brand')
            ->leftJoin('article.category', 'category1')
            ->leftJoin('article.subCategory', 'subCategory')
            ->leftJoin('subCategory.parent', 'category2')
            ->leftJoin('article.family', 'family')
            ->addOrderBy("article.updatedOn", 'DESC');
        $q->andWhere('article.code LIKE :keyword OR article.name like :keyword OR category1.nameFr like :keyword OR subCategory.nameFr like :keyword OR family.nameFr like :keyword')->setParameter('keyword', '%' . $keyword . '%');

        return $q->getQuery()->getResult(2);

    }

    /**
     * @param $id_article
     * @return null|object
     */
    public function getArticleById($id_article){
       return $this->repository->find($id_article) ;
    }

}