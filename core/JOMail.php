<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para trabalhar envio de e-mails 
 *   
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOMail
{

    private $sender = null;
    private $receiver = null;
    private $subject = 'Ol&aacute;';
    private $message = null;
    private $priority = 3;

    /**
     * Recebe os dados do e-mail
     * 
     * @param string $sender = email de origem (Ex: origem@origem.com.br)
     * @param string $receiver = email de destino (Ex: destino@destino.com.br)
     * @param string $subject = Assunto (Ex: 'Ola')
     * @param string $message = corpo da mensagem
     * @param int $priority = prioridade do e-mail (Ex: 1 = minima, 3 = media, 5 = maxima)
     */
    public function write($sender = null, $receiver = null, $subject = 'Ol&aacute;', $message = null, $priority = 3)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->subject = $subject;
        $this->message = $message;
        $this->priority = $priority;
    }

    /**
     * Envia o e-mail no formato html.
     * 
     * @throws Exception 
     */
    public function send($contentType = 'HTML', $charset = 'utf-8')
    {
        try {
            if (($this->sender != null) && ($this->receiver != null)) {
                $ctype = ($contentType == 'HTML') ? 'text/html' : 'text/plain';
                $headers = "MIME-Version: 1.1 \n";
                $headers .= "Content-type: " . $ctype . "; charset=" . $charset . " \n";
                $headers .= "From: " . $this->sender . " \n";
                $headers .= "Return-Path: " . $this->sender . " \n";
                $headers .= "Reply-To: " . $this->sender . " \n";
                $headers .= "X-Priority: " . $this->priority . " \n";

                if (!mail($this->receiver, $this->subject, $this->message, $headers)) {
                    throw new Exception('Problemas no envio do e-mail, certifique os dados informados.');
                }
            } else {
                throw new Exception('Informe os e-mails de origem e destino.');
            }
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

}
