<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Category controller.
 *
 * @Route("category")
 */
class CategoryController extends Controller
{


    /**
     * Lists all category entities.
     *
     * @Route("/", name="category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $service_category = $this->get('category_manager');
        $categories = $service_category->getListCategories($this->getUser()->getFamilies());

        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Finds and displays a category entity.
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_ADMIN')")
     * @Route("/show/{id}", name="category_show")
     * @Method("GET")
     */
    public function showAction(Category $category)
    {

        return $this->render('category/show.html.twig', array(
            'category' => $category,
        ));
    }

    /**
     * Finds and displays a category entity.
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_ADMIN')")
     * @Route("/new", name="category_create")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('AppBundle\Form\CategoryType', $category);
        $form->handleRequest($request);
        $service_category = $this->get('category_manager');
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setRank($service_category->getTotalCategory() + 1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush($category);
            $this->addFlash('add_success', 'Ajout effectué avec succés');
            return $this->redirectToRoute('category_index');

        }

        return $this->render('category/new.html.twig', array(
            'article' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     * @Security("has_role('ROLE_SUPER_ADMIN') or has_role('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {

        $editForm = $this->createForm('AppBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {


            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('update_success', 'Mise à jour effectué avec succés');
            return $this->redirectToRoute('category_edit', array('id' => $category->getId()));
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'form' => $editForm->createView(),
        ));
    }

    /**
     *Delete a category entity.
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Route("/{id}/delete", name="category_delete")
     * @Method({"GET","POST","DELETE"})
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        try {
            if(!$this->isAssociated($category)){
                $em->remove($category);
                $em->flush($category);
                $this->addFlash('delete_success', 'Suppression effectué avec succés');
            }else
                $this->addFlash('danger', 'Cette catégorie est associé à plusieurs produits');
        } catch (\Exception $ex) {
            $this->addFlash('danger', 'Une erreur s\'est produite !!');
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/find/{id}", name="subcategory_find")
     * @Method("GET")
     */
    public function findSubcategoryAction($id)
    {
        $category_manager = $this->get('category_manager');

    }

    /**
     * @Route("/reorder/category/{id}",name="reorder_category")
     * @Method({"GET","POST"})
     */
    public function ajaxReorderCategoryAction(Request $request,Category $category)
    {

        if ($request->isXmlHttpRequest()) {
            $position = $request->get('position');
            try{
                $em = $this->getDoctrine()->getManager();
                $repository=$em->getRepository('AppBundle:Category');
                $repository->updatePosition($category->getRank(),$position);
                $category->setRank($position);
                $em->flush();
                return new JsonResponse(array('success'=>1));
            }catch (Exception $ex){
                return new JsonResponse(array('success'=>0));
            }

        }
    }

    /**
     * @param Category $category
     * @return int
     */
    public function isAssociated(Category $category)
    {
        $service_article = $this->get('article_manager');
        return count($service_article->findProductsByCategory($category));

    }

}
