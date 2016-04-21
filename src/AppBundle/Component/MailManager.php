<?php

namespace AppBundle\Component;

use Symfony\Component\Translation\TranslatorInterface;
use AppBundle\Component\URL;

class MailManager
{
    protected $mailer;
    protected $twig;
    protected $translator;
    protected $urlGenerator;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, TranslatorInterface $translator, URL $urlGenerator)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Send email
     *
     * @param   string   $template      email template (inside the Mail view folder)
     * @param   mixed    $parameters    custom params for template
     * @param   string   $to            to email address or array of email addresses
     * @param   string   $from          from email address
     * @param   string   $fromName      from name
     * @param   string   $replyTo       Reply to address
     *
     * @return  boolean                 send status
     */
    public function sendEmail($template, $parameters, $to = null, $from = null, $fromName = null, $replyTo = null)
    {
        $parameters['url_generator'] = $this->urlGenerator;
        // @todo translation
        $parameters['locale'] = 'en_CA';

        // render the different blocks of the email
        $template = $this->twig->loadTemplate('Mail/' . $template . '.html.twig');
        $subject  = $template->renderBlock('subject', $parameters);
        $bodyHtml = $template->renderBlock('body_html', $parameters);
        $bodyText = $template->renderBlock('body_text', $parameters);

        if (null === $from) {
            $from = $this->translator->trans('app.parameter.from_email');
            // only use the defualt from name if no from address passed
            // that way we don't use the wrong name/email address association
            if (null === $fromName) {
                $fromName = $this->translator->trans('app.parameter.name');
            }
        }

        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from, $fromName)
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