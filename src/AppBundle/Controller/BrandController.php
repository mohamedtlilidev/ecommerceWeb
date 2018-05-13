<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Brand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Brand controller.
 *
 * @Route("brand")
 */
class BrandController extends Controller
{
    /**
     * Lists all brand entities.
     *
     * @Route("/", name="brand_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $brands = $em->getRepository('AppBundle:Brand')->findAll();

        return $this->render('brand/index.html.twig', array(
            'brands' => $brands,
        ));
    }

    /**
     * Creates a new brand entity.
     *
     * @Route("/new/{id}", name="brand_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request,$id=null)
    {
        $service_brand=$this->get('brand_manager');
        if(is_null($id))
            $brand=new Brand();
        else
            $brand=$service_brand->findBrandById($id);

        $form = $this->createForm('AppBundle\Form\BrandType', $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($brand);
            $em->flush($brand);
            if (is_null($id))
                $this->addFlash('add_success', 'Ajout effectué avec succés');
            else
                $this->addFlash('update_success', 'Mise à jour effectué avec succés');
            return $this->redirectToRoute('brand_index');
        }

        return $this->render('brand/new.html.twig', array(
            'brand' => $brand,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new unity entity.
     *
     * @Route("/ajax_brand_new", name="brand_ajax_new")
     * @Method({"GET", "POST"})
     */
    public function ajaxProcessAction(Request $request)
    {
        $brand = new Brand();
        $form = $this->createForm('AppBundle\Form\BrandType', $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($brand);
            $em->flush();
            return new JsonResponse(array('success'=>true,'id'=>$brand->getId(),'name'=>$brand->getBrand()));

        }

        return $this->render('brand/new_modal.html.twig', array(
            'brand' => $brand,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing brand entity.
     *
     * @Route("/{id}/edit", name="brand_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Brand $brand)
    {
        $deleteForm = $this->createDeleteForm($brand);
        $editForm = $this->createForm('AppBundle\Form\BrandType', $brand);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('brand_edit', array('id' => $brand->getId()));
        }

        return $this->render('brand/edit.html.twig', array(
            'brand' => $brand,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a brand entity.
     *
     * @Route("/{id}/delete", name="brand_delete")
     * @Method({"GET","POST","DELETE"})
     */
    public function deleteAction(Request $request, Brand $brand)
    {
        $form = $this->createDeleteForm($brand);
        $form->handleRequest($request);

        try {

            $em = $this->getDoctrine()->getManager();
            $em->remove($brand);
            $em->flush($brand);
            $this->addFlash('delete_success', 'Suppression effectué avec succés');
        } catch (\Exception $ex) {
            $this->addFlash('danger', 'Cette marque est associé à plusieurs produits !');
        }

        return $this->redirectToRoute('brand_index');
    }

    /**
     * Creates a form to delete a brand entity.
     *
     * @param Brand $brand The brand entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Brand $brand)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('brand_delete', array('id' => $brand->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
