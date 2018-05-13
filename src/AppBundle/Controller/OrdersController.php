<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\ParentOrderController as BaseOrder;

/**
 * Order controller.
 *
 * @Route("orders")
 */
class OrdersController extends Controller
{
    private $service;
    public function __construct()
    {


    }

    /**
     * Lists all order entities.
     *
     * @Route("/", name="orders_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $orders = $this->getService()->getListOrder();
        return $this->render('orders/index.html.twig', array(
            'orders' => $orders,
        ));
    }

    /**
     * Creates a new order entity.
     *
     * @Route("/new", name="orders_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $order = new Orders();
        $form = $this->createForm('AppBundle\Form\OrdersType', $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush($order);

            return $this->redirectToRoute('orders_show', array('id' => $order->getId()));
        }

        return $this->render('orders/new.html.twig', array(
            'order' => $order,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a order entity.
     *
     * @Route("/details/{id}", name="orders_show")
     * @Method("GET")
     */
    public function showAction(Orders $order)
    {
        $deleteForm = $this->createDeleteForm($order);
        $service_status=$this->get('order_status_manager');
        $status=$service_status->getListStatus();
        $em = $this->getDoctrine()->getManager();
        if(!$order->isViewed()){
            $order->setViewed(true);
            $order->setViewedBy($this->getUser());
            $em->flush();
        }
        return $this->render('orders/show.html.twig', array(
            'order' => $order,
            'status' => $status,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing order entity.
     *
     * @Route("/{id}/editStatus", name="orders_status",options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function statusAction(Request $request, Orders $order)
    {

        if($request->isXmlHttpRequest()){
            $status_manager=$this->get('order_status_manager');
            $order_item_manager=$this->get('order_item_manager');
            $id_status=$request->get('id_status');
            $status=$status_manager->findStatusById($id_status);
            $order->setStatus($status);
            $order->setViewedBy($this->getUser());
            try{
                if(!$status->getDefaultCancel()){
                    $items=$order_item_manager->findItemByOrder($order);
                    $this->getDoctrine()->getManager()->flush();
                    $this->getServiceMail()->renderTemplate('orders/mail/order_conf.html.twig',array('order'=>$order,'items'=>$items));
                    $this->getServiceMail()->sendMail('Commande '.$order->getStatus(),$order->getUser()->getEmail(),'noreply@hmila.com');
                    return new JsonResponse(array('data'=>$status->getName(),'message'=>0));
                }else{
                    $message=$request->get('message_cancel');
                    if($message){
                        $this->getDoctrine()->getManager()->flush();
                        $this->getServiceMail()->renderTemplate('orders/mail/simple_mail.html.twig',array('message'=>$message,'order'=>$order));
                        $this->getServiceMail()->sendMail('Commande '.$order->getStatus(),$order->getUser()->getEmail(),'noreply@hmila.com');
                        return new JsonResponse(array('data'=>$status->getName(),'message'=>0));
                    }else{
                        return new JsonResponse(array('data'=>$status->getName(),'message'=>1));
                    }

                }

            }catch (\Exception $ex){
                return 0;
            }

        }
        return 0;
    }

    /**
     * Displays a form to edit an existing order entity.
     *
     * @Route("/sendMail", name="order_send_mail",options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function sendMailAction(Request $request)
    {

        if($request->isXmlHttpRequest()){
            $order_manager=$this->get('order_manager');
            $order_id=$request->get('order_id');
            $order=$order_manager->findOrderByID($order_id);
            $message=$request->get('message');
            $subject=$request->get('subject');

            try{
                if(!empty($message)){
                    $this->getServiceMail()->renderTemplate('orders/mail/simple_mail.html.twig',array('message'=>$message,'order'=>$order));
                    $this->getServiceMail()->sendMail($subject,$order->getUser()->getEmail(),'noreply@hmila.com');
                    return new JsonResponse(array('error'=>0));
                }else{
                    return new JsonResponse(array('error'=>"Message et Objet requis"));
                }

            }catch (\Exception $ex){
                return 0;
            }

        }
        return 0;
    }

    /**
     * Displays a form to edit an existing order entity.
     *
     * @Route("/{id}/edit", name="orders_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Orders $order)
    {
        $deleteForm = $this->createDeleteForm($order);
        $editForm = $this->createForm('AppBundle\Form\OrdersType', $order);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('orders_edit', array('id' => $order->getId()));
        }

        return $this->render('orders/edit.html.twig', array(
            'order' => $order,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a order entity.
     *
     * @Route("/{id}", name="orders_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Orders $order)
    {
        $form = $this->createDeleteForm($order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($order);
            $em->flush($order);
        }

        return $this->redirectToRoute('orders_index');
    }

    /**
     * Creates a form to delete a order entity.
     *
     * @param Orders $order The order entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Orders $order)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('orders_delete', array('id' => $order->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @return \AppBundle\Services\OrderManager|object
     */
    public function getService()
    {
        return $this->get('order_manager');
    }

    /**
     * @param \AppBundle\Services\OrderManager|object $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return \AppBundle\Services\MailerManager|object
     */
    public function getServiceMail(){
        return $this->get('mail_send_manager');
    }



}
