<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para manipular elementos html 
 *   
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOHtml
{

    private $html = null,
            $attr = null,
            $content = null,
            $htmlArray = array();

    /**
     * Inserir os atributos na tag
     * 
     * @param array $attr
     */
    private function setAtributes($attr)
    {
        $setAttr = (is_array($attr)) ? $attr : array();
        while (list($key, $val) = each($setAttr)) {
            $this->attr .= " {$key}=\"{$val}\"";
        }
    }

    /**
     * Criar o inicio da tag
     * 
     * @param string $tag
     * @param array $attr
     */
    public function openTag($tag = false, $attr = array())
    {
        if ($tag) {
            $this->setAtributes($attr);
            $this->html .= "<{$tag}{$this->attr}>";
            $this->attr = null;
        }
    }

    /**
     * Criar a tag unica, como inicio e final
     * 
     * @param string $tag
     * @param string $value
     * @param array $attr
     */
    public function setTag($tag = false, $value = null, $attr = array())
    {
        if ($tag) {
            $this->setAtributes($attr);
            $checkElem = preg_match('/^(area|AREA|base|BASE|basefont|BASEFONT|br|BR|col|COL|embed|EMBED|frame|FRAME|hr|HR|img|IMG|input|INPUT|keygen|KEYGEN|link|LINK|meta|META|param|PARAM|source|SOURCE|track|TRACK)$/', $tag);
            if ($checkElem) {
                $this->html .= "<{$tag}{$this->attr} />";
            } else {
                $this->html .= "<{$tag}{$this->attr}>{$value}</{$tag}>";
            }
        }
        $this->attr = null;
    }

    /**
     * Criar o final da tag
     * 
     * @param string $tag
     */
    public function closeTag($tag = false)
    {
        if ($tag) {
            $this->html .= "</{$tag}>";
        }
    }

    /**
     * Quando precisar escrever tag manualmente
     * 
     * @param string $html
     */
    public function write($html = false)
    {
        if ($html) {
            $this->html .= $html;
        }
    }

    /**
     * Incluir outro arquivo, no HTML
     * 
     * @param string $filename
     */
    public function fileInclude($filename = false)
    {
        if ($filename) {
            if (file_exists(VIEWS . $filename)) {
                $this->html = file_get_contents(VIEWS . $filename);
            } else {
                $this->html = '<code>O arquivo <b>' . VIEWS . $filename . '</b> n&atilde;o foi encontrado</code>';
            }
        }
    }

    /**
     * Palavra que se encarrega de subtituir pela a ultima tag que foi criada
     * 
     * @param string $string
     */
    public function tagReplace($string = false)
    {
        if ($string) {
            $this->htmlArray[$string] = $this->html;
        }
        $this->html = null;
    }

    /**
     * Obtem o html que foi criado
     */
    public function get()
    {
        return $this->html;
    }

    /**
     * Apresenta o layout estatico mais as tag dinamicas que foram criadas
     * 
     * @param string $filename
     * @throws Exception ERROS
     */
    public function show($filename = false)
    {
        try {
            if ($filename) {
                if (file_exists(VIEWS . $filename)) {
                    $this->content = file_get_contents(VIEWS . $filename);

                    foreach ($this->htmlArray as $key => $val) {
                        $this->content = str_replace('{' . $key . '}', $val, $this->content);
                    }
                } else {
                    throw new Exception('O arquivo ' . VIEWS . $filename . ' n&atilde;o existe.');
                }
            } else {
                throw new Exception('Informe o caminho do layout, para o carregamento.');
            }
            echo $this->content;
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

}
