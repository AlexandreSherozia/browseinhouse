<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 20/08/2018
 * Time: 23:50
 */

namespace App\Service;


use Symfony\Component\Form\Form;

class Mailer
{
    private $templating;
    private $swift_mailer;

    /**
     * Mailer constructor.
     * @param \Twig_Environment $templating
     * @param \Swift_Mailer $swift_Mailer
     */
    public function __construct(\Twig_Environment $templating,
                                \Swift_Mailer $swift_Mailer)
    {
        $this->templating   = $templating;
        $this->swift_mailer = $swift_Mailer;
    }

    /**
     * @param Form $form
     * @param string $token
     */
    public function sendEmail(Form $form, string $token)
    {
        $message = (new \Swift_Message("Your registration on B'N'H"))
            ->setFrom('browseinhouse@gmail.com')
            ->setTo($form->get('email')->getData())
            ->setBody(
                $this->templating->render(
                    'mail/registration.html.twig',
                    array(  'name'  => $form->get('pseudo')->getData(),
                            'token' => $token
                    )
                ),
                'text/html'
            );

        $this->swift_mailer->send($message);
    }
}

