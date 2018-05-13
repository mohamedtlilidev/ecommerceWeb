<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     *
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        $mailer_user=$this->getParameter('mailer_user');
        $em = $this->getDoctrine()->getManager();
        $repository=$em->getRepository('AppBundle:Orders');
       /// dump($repository->basicAreaData($this->getUser()->getFamilies()));
        $nbrMessages=10;
        $totals_orders=count($repository->findBy(array('viewed'=>false)));
        $lastActiveUser=$em->getRepository('AppBundle:User')->getLastActiveUser();
        $data=array('overageRevenue'=>20000,
            'weeklyRevenue'=>100,
            'monthlyRevenue'=>1234,
            'nbrMessages'=>$nbrMessages,
            'totals_orders'=>$totals_orders,
            'lastActiveUser'=>$lastActiveUser,
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        );
        return $this->render('dashboard/index.html.twig',$data);
    }

    /**
     * @Route("/basicArea", name="dashboard_area",options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function ajaxDataAreaChartAction(Request $req)
    {


        if($req->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $repository=$em->getRepository('AppBundle:Orders');
            return new JsonResponse($repository->basicAreaData($this->getUser()->getFamilies()));
        }
        return 0;
    }
}
