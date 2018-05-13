<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderStatus;
use MongoDB\Driver\Exception\ExecutionTimeoutException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Orderstatus controller.
 *
 * @Route("orderstatus")
 */
class OrderStatusController extends Controller
{
    /**
     * Lists all orderStatus entities.
     *
     * @Route("/", name="orderstatus_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $orderStatuses = $em->getRepository('AppBundle:OrderStatus')->findAll();

        return $this->render('orderstatus/index.html.twig', array(
            'orderStatuses' => $orderStatuses,
        ));
    }

    /**
     * Creates a new orderStatus entity.
     *
     * @Route("/new/{id}", name="orderstatus_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request,$id=null)
    {
        $service_status=$this->get('order_status_manager');
        if(is_null($id))
            $orderStatus = new Orderstatus();
        else
            $orderStatus=$service_status->findStatusById($id);
        $form = $this->createForm('AppBundle\Form\OrderStatusType', $orderStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($orderStatus);
            $em->flush($orderStatus);

            $this->addFlash('add_success', 'Ajout effectué avec succés');
            return $this->redirectToRoute('orderstatus_index');
        }

        return $this->render('orderstatus/new.html.twig', array(
            'orderStatus' => $orderStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new orderStatus entity.
     *
     * @Route("/editDefault/{id}", name="orderstatus_default")
     * @Method({"GET", "POST"})
     */
    public function defaultAction(Request $request,OrderStatus $orderStatus)
    {
            $service_order_status=$this->get('order_status_manager');

            $em = $this->getDoctrine()->getManager();
            if($orderStatus->getDefaults()){
                $orderStatus->setDefaults(false);
            }else
                $orderStatus->setDefaults(true);
            try{

               //get order default and set to false
                $orderDefaults=$service_order_status->findAllStatusDefault();

                foreach ($orderDefaults as $orderDefault){
                    $orderDefault->setDefaults(false);
                    $em->flush();
                    $em->clear();
                }

                $em->flush();
                $this->addFlash('add_success', 'Status modifier');
            }catch (\Exception $exception){

            }

            return $this->redirectToRoute('orderstatus_index');

    }


    /**
     * Creates a new orderStatus entity.
     *
     * @Route("/editDefaultCancel/{id}", name="orderstatus_default_cancel")
     * @Method({"GET", "POST"})
     */
    public function defaultCancelAction(Request $request,OrderStatus $orderStatus)
    {
        $service_order_status=$this->get('order_status_manager');

        $em = $this->getDoctrine()->getManager();
        if($orderStatus->getDefaultCancel()){
            $orderStatus->setDefaultCancel(false);
        }else
            $orderStatus->setDefaultCancel(true);
        try{

            //get order default and set to false
            $orderDefaults=$service_order_status->findAllStatusDefaultCancel();

            foreach ($orderDefaults as $orderDefault){
                $orderDefault->setDefaultCancel(false);
                $em->flush();
                $em->clear();
            }

            $em->flush();
            $this->addFlash('add_success', 'Status modifier');
        }catch (\Exception $exception){

        }

        return $this->redirectToRoute('orderstatus_index');

    }

    /**
     * Deletes a orderStatus entity.
     *
     * @Route("/{id}", name="orderstatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, OrderStatus $orderStatus)
    {
        try{

            $em = $this->getDoctrine()->getManager();
            $em->remove($orderStatus);
            $em->flush();
            $this->addFlash('delete_success', 'Suppression effectué avec succés');
        }catch (Exception $ex){
            $this->addFlash('danger', 'Une erreur s\'est produite !!');
        }

        return $this->redirectToRoute('orderstatus_index');
    }

    /**
     * Creates a form to delete a orderStatus entity.
     *
     * @param OrderStatus $orderStatus The orderStatus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(OrderStatus $orderStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('orderstatus_delete', array('id' => $orderStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
