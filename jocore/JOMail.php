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

    private $source = null;
    private $destination = null;
    private $subject = 'Ol&aacute;';
    private $message = null;
    private $priority = 3;

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return HTML 
     */
    protected static function joError($error = null)
    {
        if (!SHOW_MSG_ERROR) {
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        }
        die(require_once($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Recebe os dados do e-mail
     * @param type $source = email de origem (Ex: origem@origem.com.br)
     * @param type $destination = email de destino (Ex: destino@destino.com.br)
     * @param type $subject = Assunto (Ex: 'Ola')
     * @param type $message = corpo da mensagem
     * @param type $priority = prioridade do e-mail (Ex: 1 = minima, 3 = media, 5 = maxima)
     */
    public function joWriteMail($source = null, $destination = null, $subject = 'Ol&aacute;', $message = null, $priority = 3)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->subject = $subject;
        $this->message = $message;
        $this->priority = $priority;
    }

    /**
     * Envia o e-mail no formato html.
     * @throws Exception => Mensagem de erro, em que o envio nao foi possivel
     */
    public function joSendMail($type = 'TEXT', $charset = 'utf-8')
    {
        try {
            if (($this->source != null) && ($this->destination != null)) {
                $contentType = ($type == 'TEXT') ? 'text/plain' : 'text/html';
                $headers = "MIME-Version: 1.1 \n";
                $headers .= "Content-type: " . $contentType . "; charset=" . $charset . " \n";
                $headers .= "From: " . $this->source . " \n";
                $headers .= "Return-Path: " . $this->source . " \n";
                $headers .= "Reply-To: " . $this->source . " \n";
                $headers .= "X-Priority: " . $this->priority . " \n";

                if (!mail($this->destination, $this->subject, $this->message, $headers)) {
                    throw new Exception('Problemas no envio do e-mail, certifique os dados informados');
                }
            } else {
                throw new Exception('Informe os e-mails de origem e destino');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

}
