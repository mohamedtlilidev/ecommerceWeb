<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $way
     * @param $position
     * @return mixed
     */
    public function updatePosition($way, $position)
    {


        //$qb = $this->createQueryBuilder("c");

        $qb = $this->createQueryBuilder("c");
            $q = $qb->update();
            //+ 1
        if($way>$position){
            $q->set('c.rank','c.rank +1');
            $q->where('c.rank >= :position')
                ->setParameter('position', $position);
            // -1
            //$q->set('c.rank','c.rank-1');
            $q->andWhere('c.rank < :way')
                ->setParameter('way',$way)
                ->getQuery()->execute();
        }else{
            $q->set('c.rank','c.rank -1');
            $q->where('c.rank <= :position')
                ->setParameter('position', $position);
            // -1
            //$q->set('c.rank','c.rank-1');
            $q->andWhere('c.rank > :way')
                ->setParameter('way',$way)
                ->getQuery()->execute();
        }




    }
}
