<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/03/2017
 * Time: 09:30
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Controller\SecurityController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Security;

class ActivityListener
{
    protected $tokenStorage;
    protected $entityManager;

    public function __construct(TokenStorage $tokenStorage, EntityManager $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * Update the user "lastActivity" on each request
     * @param FilterControllerEvent $event
     */
    public function onCoreController(FilterControllerEvent $event)
    {
        // Check that the current request is a "MASTER_REQUEST"
        // Ignore any sub-request
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }

        // Check token authentication availability

        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();

            if ( ($user instanceof User) && ($user->isEnabled()) ) {
                $user->setLastActivityAt(new \DateTime());
                $this->entityManager->flush($user);
            }
        }
    }
}