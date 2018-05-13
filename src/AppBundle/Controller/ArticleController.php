<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Brand;
use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Services\FileUploader;

/**
 * Article controller.
 *
 * @Route("article")
 */
class ArticleController extends Controller
{
    /**
     * ArticleController constructor.
     */
    public function __construct()
    {
    }

    /**
     * Lists all article entities.
     *
     * @Route("/list/{page}", name="article_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, $page = null)
    {

        if (is_null($page))
            $page = 1;
        $em = $this->getDoctrine()->getManager();
        $articleManager = $this->get('article_manager');
        $max_product_per_page = $this->container->getParameter('max_product_per_page');

        $filters = array();

        if (!empty($request->get('family_search')))
            $filters['f'] = $request->get('family_search');
        if (!empty($request->get('category_search')))
            $filters['ca'] = $request->get('category_search');
        if (!empty($request->get('code_search')))
            $filters['code'] = $request->get('code_search');
        if (!empty($request->get('brand_search')))
            $filters['b'] = $request->get('brand_search');

        if (!empty($request->get('isNew')))
            $filters['isNew'] = $request->get('isNew');

        if (!empty($request->get('isOffer')))
            $filters['isOffer'] = $request->get('isOffer');

        if (!empty($request->get('isFragrance')))
            $filters['isFragrance'] = $request->get('isFragrance');

        if (!empty($request->get('isSpecial')))
            $filters['isSpecial'] = $request->get('isSpecial');

        $articles = $articleManager->getList($page, $max_product_per_page, $filters);
        $articles_count = count($articles);
        $pagination = array(
            'page' => $page,
            'route' => 'article_index',
            'max_per_page' => $max_product_per_page,
            'pages_count' => ceil($articles_count / $max_product_per_page),
            'nb_products' => $articles_count,
            'route_params' => array()
        );

        //Data for filter action
        $brands = $em->getRepository('AppBundle:Brand')->findAll();
        $categories = $this->get('category_manager')->getListCategories(array());


        return $this->render('article/index.html.twig', array(
            'articles' => $articles,
            'pagination' => $pagination,
            'brands' => $brands,
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new article entity.
     *
     * @Route("/new/{id}", name="article_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id = null)
    {

        $service_article = $this->get('article_manager');
        if (!is_null($id)) {
            $article = $service_article->getArticleById($id);
        } else
            $article = new Article();
        $form = $this->createForm('AppBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('rank')->getData()<=0)
                 $article->setRank($service_article->getTotalProduct() + 1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush($article);
            $request->getSession()
                ->getFlashBag()
                ->add('add_success', 'Ajout effectué avec succés');
            return $this->redirectToRoute('article_index', array('page' => 1));
        }

        return $this->render('article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{id}/edit", name="article_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('AppBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('update_success', 'Mise à jour effectué avec succés');
            //return $this->redirectToRoute('article_edit', array('id' => $article->getId()));
            return $this->redirectToRoute('article_index', array('page' => 1));
        }

        return $this->render('article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{id}/status", name="article_status")
     * @Method({"GET", "POST"})
     */
    public function statusAction(Article $article)
    {
        if ($article->getIsPublished()) {
            $article->setIsPublished(false);
        } else
            $article->setIsPublished(true);

        try {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('update_success', 'Mise à jour effectué avec succés');
        } catch (\Exception $ex) {

        }


        return $this->redirectToRoute('article_index');

    }
    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{id}/clone", name="article_clone")
     * @Method({"GET", "POST"})
     */
    public function cloneAction(Article $article)
    {
        $article_clone=clone $article;
        /***copy image to directory***/
        $img_dir=$this->container->getParameter('img_dir').'/p/';
        $img_link=$img_dir.$article->getUrlPicture();
        $fs=new Filesystem();
        $new_file_name='default.png';
        if($fs->exists(array($img_link))){
            $path_info=pathinfo($img_link);
            $new_file_name=md5(uniqid()).'.'.$path_info['extension'];
           try{
               $fs->copy($img_link,$img_dir.$new_file_name);
           }catch (IOException $exception){
               echo $exception;
           }
        }
        try {
            $em=$this->getDoctrine()->getManager();
            $article_clone->setIsPublished(false);
            $article_clone->setUrlPicture($new_file_name);
            $article_clone->setCreatedOn(new \DateTime());
            $article_clone->setUpdatedOn(new \DateTime());
            $em->persist($article_clone);
            $em->flush();
            $this->addFlash('update_success', 'Dupplication effectué avec succés');
            //return $this->redirectToRoute('article_edit',array('id'=>$article_clone->getId()));
        } catch (\Exception $ex) {

        }


        return $this->redirectToRoute('article_index');

    }


    /**
     * Deletes a article entity.
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Route("/{id}/delete", name="article_delete")
     * @Method({"GET","POST","DELETE"})
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush($article);
        // }
        $request->getSession()
            ->getFlashBag()
            ->add('delete_success', 'Suppression effectué avec succés');
        return $this->redirectToRoute('article_index', array('page' => 1));
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

}
