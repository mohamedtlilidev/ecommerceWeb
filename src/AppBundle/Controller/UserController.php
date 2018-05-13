<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();
        $service_user = $this->get('user_manager');
        $users = $service_user->getUsersByContext(false);
        return $this->render('user/index.html.twig', array(
            'users' => $users,
            'employee' => false
        ));
    }

    /**
     * Lists all user employees.
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Route("/employees", name="user_employee")
     * @Method("GET")
     */
    public function employeeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $service_user = $this->get('user_manager');
        $users = $service_user->getUsersByContext(true);

        return $this->render('user/index.html.twig', array(
            'users' => $users,
            'employee' => true
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new/{id}", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id = null)
    {
        $service_user = $this->get('user_manager');
        if (is_null($id))
            $user = new User();
        else
            $user = $service_user->findUserById($id);

        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush($user);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing user entity.
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Route("/disable/{id}", name="user_enable")
     * @Method({"GET", "POST"})
     */
    public function disableAction($id)
    {
        $service_user = $this->get('user_manager');
        $user = $service_user->findUserById($id);
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $message="L'utilisateur est débloqué";
        if($user->isEnabled()){
            $message="L'utilisateur est bloqué";
            $user->setEnabled(false);
        }else
            $user->setEnabled(true);
        try {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('update_success',$message);

        } catch (\Exception $ex) {

        }
        if(!$user->hasRole('ROLE_USER')||count($user->getRoles())>1){
            return $this->redirectToRoute('user_employee');

        }else
            return $this->redirectToRoute('user_index');



    }

    /**
     * Deletes a user entity.
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush($user);
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Deletes admin user.
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Route("/{id}/deleteadmin", name="user_admin_delete")
     * @Method({"GET","POST","DELETE"})
     */
    public function adminDeleteAction(Request $request, User $user)
    {
        $service_user = $this->get('fos_user.user_manager');
        try {
            if ($user->getId() != $this->getUser()->getId()) {
                $service_user->deleteUser($user);
                $this->addFlash('delete_success', 'Suppression effectué avec succés');
            } else
                $this->addFlash('danger', 'vous ne pouvez pas supprimer l\'admin actif !!');

        } catch (\Exception $ex) {
            $this->addFlash('danger', 'Une erreur s\'est produite !!');
        }
        return $this->redirectToRoute('user_employee');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
