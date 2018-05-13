<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/02/2017
 * Time: 11:34
 */

namespace Admin\UserBundle\Controller;
use AppBundle\Entity\Family;
use FOS\UserBundle\Controller\GroupController as BaseController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\FilterGroupResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseGroupEvent;
use FOS\UserBundle\Event\GroupEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\GroupInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
class GroupController extends  BaseController
{

    /**
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_ADMIN')")
     * Show all groups.
     */
    public function listAction()
    {
        $groups = $this->get('fos_user.group_manager')->findGroups();

        return $this->render('@FOSUser/Group/list.html.twig', array(
            'groups' => $groups,
        ));
    }

    /**
     * Show the new form.
     *
     *@param Request $request
     *
     *@return Response
     */
    public function newAction(Request $request)
    {

        /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
        $groupManager = $this->get('fos_user.group_manager');
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.group.form.factory');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $group = $groupManager->createGroup('');

        $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_INITIALIZE, new GroupEvent($group, $request));

        $form = $formFactory->createForm();
        $form->add('description');
        $roles = array(
            'ROLE_SUPER_ADMIN'  => 'ROLE_SUPER_ADMIN'
        );
        $form->add('roles', ChoiceType::class, array(
            'choices'   => $roles,
            'required'  => true,
            'multiple' => true
        ));
        $form->setData($group);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_SUCCESS, $event);

            $groupManager->updateGroup($group);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_group_list');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_COMPLETED, new FilterGroupResponseEvent($group, $request, $response));

            return $response;
        }

        return $this->render('@FOSUser/Group/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Edit one group, show the edit form.
     *
     * @param Request $request
     * @param string  $groupName
     *
     * @return Response
     */
    public function editAction(Request $request, $groupName)
    {
       // $group = $this->findGroupBy('id', $groupName);
        $em = $this->getDoctrine()->getManager();
        $group=$em->getRepository('AppBundle:Group')->find($groupName);
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseGroupEvent($group, $request);
        $dispatcher->dispatch(FOSUserEvents::GROUP_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.group.form.factory');

        $form = $formFactory->createForm();

        $form->add('description');
        $roles = array(
            'ROLE_SUPER_ADMIN'  => 'ROLE_SUPER_ADMIN'
        );

        $form->add('roles', ChoiceType::class, array(
            'choices'   => $roles,
            'required'  => true,
            'multiple' => true
        ));
        $form->setData($group);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
            $groupManager = $this->get('fos_user.group_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::GROUP_EDIT_SUCCESS, $event);

            $groupManager->updateGroup($group);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_group_list');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::GROUP_EDIT_COMPLETED, new FilterGroupResponseEvent($group, $request, $response));

            return $response;
        }

        return $this->render('@FOSUser/Group/edit.html.twig', array(
            'form' => $form->createView(),
            'group_name' => $group->getName(),
        ));
    }
    /**
     * Delete one group.
     *
     * @param Request $request
     * @param string  $groupName
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $groupName)
    {
        //$group = $this->findGroupBy('id', $groupName);
        $em = $this->getDoctrine()->getManager();
        $group=$em->getRepository('AppBundle:Group')->find($groupName);
        $this->get('fos_user.group_manager')->deleteGroup($group);

        $response = new RedirectResponse($this->generateUrl('fos_user_group_list'));

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(FOSUserEvents::GROUP_DELETE_COMPLETED, new FilterGroupResponseEvent($group, $request, $response));

        return $response;
    }

}