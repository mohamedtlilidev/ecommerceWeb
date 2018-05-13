<?php
/**
 * Created by PhpStorm.
 * User: Hamdi
 * Date: 26/11/2016
 * Time: 21:52
 */

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use AppBundle\Entity\User;

/**
 * This class extends the default fos user bundle doctrine usermanager to fit my own user entity.
 */
class UserManager {
    private $em;
    private $session;
    private $repository;
    private $tokenStorage;

    public function __construct(EntityManager $entityManager, $class) {

        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('AppBundle:User');

    }

    /**
     * @return User
     */
    public function createUser(array $data = null) {
        return new User($data);
    }

    public function updateUser(User $user, $andFlush = true)
    {
        $this->em->persist($user);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    /**
     * @return User
     */
    public function findOneByUsername($username){
        return $this->repository->findOneByUsername($username);
    }

    /**
     * @return User
     */
    public function findOneByEmail($email){
        return $this->repository->findOneByEmail($email);
    }

    /**
     * @return User
     */
    public function findOneByConsularCard($consulardCard){
        return $this->repository->findOneByConsularCard($consulardCard);
    }

    /**
     * @param $array
     * @return User|null|object
     */
    public function findOneBy($array){
        return $this->repository->findOneBy($array);
    }

    /**
     * @param $id
     * @return User|null|object
     */
    public function findUserById($id){
        return $this->repository->find($id);
    }

    /**
     * @return array
     */
    public function getUsersByContext($context=false){
        $qb = $this->em->createQueryBuilder();

        $qb->select('user','gr')
            ->from('AppBundle:User', 'user')
            ->leftJoin('user.groups','gr');
        return $qb->getQuery()->getResult();
    }


}