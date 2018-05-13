<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * @Route("/test", name="homepage")
     * @Security("has_role('ROLE_MANAGER') or has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        $data = $request->request->all();
        $family_manager = $this->get('family_manager');
        $family = $family_manager->findOneBy(array("keyword" => "goulette", "isPublished" => true));

        echo "<pre>";
        var_dump($family);
        echo "</pre>";
        /*
        // replace this example code with whatever you need
        return $this->render(':default:index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
        */
    }





}
