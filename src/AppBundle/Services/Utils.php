<?php
/**
 * Created by PhpStorm.
 * User: Hamdi
 * Date: 23/11/2016
 * Time: 16:14
 */
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\HttpException;
class Utils
{
    private $em;
    private $serviceContainer;
    public function __construct(EntityManager $em, $serviceContainer, $session, $router)
    {
        $this->em = $em;
        $this->serviceContainer = $serviceContainer;
        $this->session = $session;
        $this->router = $router;
    }

    public static function formatMethodName($name) {
        $name = str_replace('_', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        $name = 'set'.$name;
        return $name;
    }

    public function generateToken()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);
        $token = '';
        $counter = 0;
        while ($counter < 30) {
            $index = rand(0, $count - 1);
            $token .= mb_substr($chars, $index, 1);
            $counter++;
        }
        return $token;
    }

    public function setToken($user)
    {
        $sessions = $this->em->getRepository('AppBundle:Session')->findBy(array('user' => $user));
        foreach($sessions as $session){
            // delete old;
            $this->em->remove($session);
        }
        $session = new $this->session();
        //   $user = $this->em->getRepository('AppBundle:User')->findBy(array('id'=> $userId));
        $token = $this->generateToken();
        $session->setToken($token);
        $session->setUser($user);
        $session->setUpdated(new \DateTime());
        $session->setCreated(new \DateTime());
        $this->em->persist($session);
        $this->em->flush();
        return $token;
    }


    /**
     * @param $token
     * @return \AppBundle\Entity\Session[]|array
     */
    public function verifyToken($token)
    {
        //$token = $this->generateToken();
        $session = $this->em->getRepository('AppBundle:Session')->findBy(array('token' => $token));
        return $session;
    }

    /**
     * @param $username
     * @param $token
     * @return \AppBundle\Entity\Session
     */
    public function verifyUserToken($username, $token)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('username' => $username));
        $session = $this->em->getRepository('AppBundle:Session')->findOneBy(array('token' => $token, 'user' => $user));
        return $session;
    }


    /**
     * @param $status
     * @param $data
     * @param string $message
     * @return array
     */
    public function returnData($data, $status=1, $message='OK')
    {
        $result = array('message'=> $message,'data'=>$data );
        $return= array('status'=> $status,'result'=> $result);
        return $return ;
    }

    /**
     * Convert Image Base 64 bits to physical image
     *
     * @param type $base64_string
     * @param type $output_file
     * @return string
     */
    public function base64_to_jpeg($base64_string, $output_file) {
        $ifp = fopen($output_file, "wb");
        $data = explode(',', $base64_string);
        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);
        return $output_file;
    }
}