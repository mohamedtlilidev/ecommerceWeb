<?php
/**
 * Created by PhpStorm.
 * User: Hamdi
 * Date: 26/11/2016
 * Time: 23:48
 */

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\SessionUser;


class SessionUserManager {
    private $em;
    private $session;
    private $repository;
    private $tokenStorage;
    private $sessionUser;

    public function __construct(EntityManager $entityManager, TokenStorage $tokenStorage){
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('AppBundle:SessionUser');
        $this->tokenStorage = $tokenStorage;
        $this->session = new Session();
    }

    public function create(User $user){
        $token = $this->generateToken();
        $sessionUser = new SessionUser();
        $sessionUser->setToken($token);
        $sessionUser->setUser($user);
        $sessionUser->setCreatedOn(new \DateTime());
        $user->setLastActivityAt(new \DateTime());
        $sessions = $this->em->getRepository('AppBundle:SessionUser')->findBy(array('user' => $user));
        foreach($sessions as $session){
            // delete old;
            $this->em->remove($session);
        }
        $this->em->flush();

        $this->em->persist($sessionUser);
        $this->em->flush();

        return $sessionUser;
    }

    public function generateToken()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);
        $token = '';
        $counter = 0;
        while ($counter < 30) {
            $index = rand(0, $count - 1);
            $token .= mb_substr($chars, $index, 1);
            $counter++;
        }
        return $token;
    }

    public function findOneByUser(User $user){
        return $this->repository->findOneBy(array("user_id" => $user->getId()));
    }

    public function findUserByToken($token){
        return $this->repository->findOneBy(array("token" => $token));
    }

}