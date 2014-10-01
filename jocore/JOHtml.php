<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOHtml manipula elementos html  
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOHtml
{

    private $joHtml = null;
    private $joAttr = null;
    private $joContent = null;
    private $joHtmlArray = array();

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return Page - Pagina imprimindo a mensagem de erro  
     */
    protected static function joError($error)
    {
        if (!SHOW_MSG_ERROR) {
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        }
        die(require_once($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Insere os atributos na tag
     * @param Array $attr
     */
    private function joSetAtributes($attr)
    {
        $setAttr = (is_array($attr)) ? $attr : array();
        while (list($key, $val) = each($setAttr)) {
            $this->joAttr .= " {$key}=\"{$val}\"";
        }
    }

    /**
     * Cria inicio da tag
     * @param String $tag
     * @param Array $attr
     */
    public function joOpenTag($tag = false, $attr = array())
    {
        if ($tag) {
            $this->joSetAtributes($attr);
            $this->joHtml .= "<{$tag}{$this->joAttr}>";
            $this->joAttr = null;
        }
    }

    /**
     * Cria a tag unica, como inicio e final
     * @param String $tag
     * @param String $value
     * @param Array $attr
     */
    public function joTag($tag = false, $value = null, $attr = array())
    {
        if ($tag) {
            $this->joSetAtributes($attr);
            $checkElem = preg_match('/^(area|AREA|base|BASE|basefont|BASEFONT|br|BR|col|COL|embed|EMBED|frame|FRAME|hr|HR|img|IMG|input|INPUT|keygen|KEYGEN|link|LINK|meta|META|param|PARAM|source|SOURCE|track|TRACK)$/', $tag);
            if ($checkElem) {
                $this->joHtml .= "<{$tag}{$this->joAttr} />";
            } else {
                $this->joHtml .= "<{$tag}{$this->joAttr}>{$value}</{$tag}>";
            }
        }
        $this->joAttr = null;
    }

    /**
     * Cria o final da tag
     * @param String $tag
     */
    public function joCloseTag($tag = false)
    {
        if ($tag) {
            $this->joHtml .= "</{$tag}>";
        }
    }

    /**
     * Quando precisar escrever tag manualmente
     * @param String $tag
     */
    public function joWriteHtml($html = false)
    {
        if ($html) {
            $this->joHtml .= $html;
        }
    }

    /**
     * Inclui outro arquivo, no HTML
     * @param String $file
     */
    public function joFileInclude($file = false)
    {
        if ($file) {
            if (file_exists(VIEWS . $file)) {
                $this->joHtml = file_get_contents(VIEWS . $file);
            } else {
                $this->joHtml = '<code>O arquivo <b>' . VIEWS . $file . '</b> n&atilde;o foi encontrado</code>';
            }
        }
    }

    /**
     * Palavra que se encarrega de subtituir pela a ultima tag que foi criada
     * @param String $string
     */
    public function joTagReplace($string = false)
    {
        if ($string) {
            $this->joHtmlArray[$string] = $this->joHtml;
        }
        $this->joHtml = null;
    }

    /**
     * Obtem o html que foi criado
     */
    public function joCreateHtml()
    {
        return $this->joHtml;
    }

    /**
     * Apresenta o layout estatico mais as tag dinamicas que foram criadas
     * @param String $file
     * @throws Exception ERROS
     */
    public function joShowHtml($file = false)
    {
        try {
            if ($file) {
                if (file_exists(VIEWS . $file)) {
                    $this->joContent = file_get_contents(VIEWS . $file);

                    foreach ($this->joHtmlArray as $key => $val) {
                        $this->joContent = str_replace('{' . $key . '}', $val, $this->joContent);
                    }
                } else {
                    throw new Exception('O arquivo ' . VIEWS . $file . ' n&atilde;o existe');
                }
            } else {
                throw new Exception("Informe o caminho do layout, para o carregamento");
            }
            echo $this->joContent;
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

}
