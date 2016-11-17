<?php

namespace AppBundle\Component;

use Symfony\Component\Translation\TranslatorInterface;
use AppBundle\Component\URL;

class MailManager
{
    protected $mailer;
    protected $twig;
    protected $translator;

    protected $fromEmail;
    protected $fromName;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->translator = $translator;
    }

    /**
     * Sets the from email & name.
     *
     * @param string $fromEmail The from email address.
     * @param string $fromName  The from name.
     */
    public function setFrom($fromEmail, $fromName)
    {
        $this->fromEmail = $this->translator->trans($fromEmail);
        $this->fromName = $this->translator->trans($fromName);
    }

    /**
     * Renders and send an email.
     * For the email template, if ".html.twig" is included, it will be assumed the $template var contains the entire template path/name.
     *
     * @param   string   $template      email template (inside the Mail view folder). Appended with ".html.twig".
     * @param   mixed    $parameters    custom params for template
     * @param   string   $to            to email address or array of email addresses
     * @param   string   $replyTo       Reply to address
     *
     * @return  boolean                 send status
     */
    public function sendEmail($template, $parameters, $to = null, $replyTo = null)
    {
        if (strrpos($template, '.html.twig') === false) {
            $template = 'Mail/'.$template.'.html.twig';
        }

        // render the different blocks of the email
        /** @var \Twig_TemplateInterface $template */
        $template = $this->twig->loadTemplate($template);
        $subject  = $template->renderBlock('subject', $parameters);
        $bodyHtml = $template->renderBlock('body_html', $parameters);
        $bodyText = $template->renderBlock('body_text', $parameters);

        try {
            $message = \Swift_Message::newInstance()
                 ->setSubject($subject)
                 ->setFrom($this->fromEmail, $this->fromName)
                 ->setTo($to)
            ;
            if (!empty($replyTo)) {
                $message->setReplyTo($replyTo);
            }
            if (!empty($bodyHtml)) {
                $message->setBody($bodyHtml, 'text/html');
            }
            if (!empty($bodyText)) {
                $message->addPart($bodyText, 'text/plain');
            }

            $response = $this->mailer->send($message);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $response;
    }
}