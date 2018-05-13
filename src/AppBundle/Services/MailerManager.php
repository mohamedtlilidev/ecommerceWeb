<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05/03/2017
 * Time: 00:10
 */

namespace AppBundle\Services;


use Symfony\Component\Templating\EngineInterface;

class MailerManager
{
    private $mailer;
    private $template;
    private $subject;
    private $mailTo;
    private $body='';

    /**
     * MailerManager constructor.
     * @param $mailer
     * @param EngineInterface $template
     */
    public  function __construct($mailer, EngineInterface $template)
    {
        $this->mailer=$mailer;
        $this->template=$template;
    }

    /**
     * @param $template
     * @param array $options
     */
    public function renderTemplate($template,array $options){
        $this->body=$this->template->render($template,$options);
    }

    /**
     * @param $subject
     * @param $mailTo
     * @param $mailFrom
     */
    public function sendMail($subject,$mailTo,$mailFrom,$cc=null){
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($mailFrom)
            ->setTo($mailTo)
            ->setCc($cc)
            ->setBody($this->body,'text/html')
        ;
       $this->mailer->send($message);
    }


}