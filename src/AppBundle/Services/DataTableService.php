<?php

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class DataTableService
{
    private $requestStack;
    private $em;

    public function __construct(EntityManager $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    function getFilter()
    {
        $request = $this->requestStack->getCurrentRequest();

        $length = $request->get('length');
        $length = $length && ($length!=-1)?$length:0;

        $start = $request->get('start');
        $start = $length?($start && ($start!=-1)?$start:0)/$length:0;

        $column =  $search = $request->get('columns');

        $order = $request->get('order')[0];

        $columnOrder = $order['column'];
        $dirOrder = $order['dir'];
        $columNameOrder = $column[$columnOrder]['data'];
        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];
        return  array(
            'filters'=>$filters,
            'start'=>$start,
            'length'=>$length,
            'columNameOrder'=>$columNameOrder,
            'dirOrder'=>$dirOrder,

        );
    }

    /**
     * @param $date
     * @return string
     */
    function dateStringify($date )
    {
        return  empty($date) ? '' : $date->format('d-m-Y');
    }

    /**
     * @param $password
     * @param $passwordConfirm
     * @return null|string
     */
    function editUserCheck($password, $passwordConfirm, $email)
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $error = null;
        if(!$uppercase || !$lowercase || !$number || strlen($password) < 8 ) {
            $error = "Mot de passe incorrect de confirmation (8 caractères; différence majuscules, minuscules, caractères spéciaux).";
        }
        if($password != $passwordConfirm) {
            $error = "Le mot de passe et la confirmation ne correspondent pas.";
        }
        return $error;
    }

}