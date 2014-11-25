<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel pela a renderizacao de paginas 
 *  
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOView
{
    /**
     * Rotina que verifica se ha o prefixo @
     * caso houver ele busca pelo o caminho absoluto
     * caso contrario busca a partir de : app/views/   
     * 
     * @param string $filename
     * @return string
     */
    private function getPath($filename)
    {
        if (false === strpos($filename, '@')) {
            return VIEWS . $filename;
        } else {
            return str_replace('@', '', $filename);
        }
    }

    /**
     * Rotina que procura e renderiza uma view.
     * 
     * @global type $JOURL
     * @param type $filename
     * @param type $data
     * @throws Exception
     */
    public function render($filename = NULL, $data = array())
    {
        try {
            $pathFile = $this->getPath($filename);
            if (file_exists($pathFile)) {
                global $JOURL;
                extract($JOURL);
                if (is_array($data)) {
                    extract($data);
                }
                require_once($pathFile);
            } else {
                throw new Exception('O arquivo ' . $pathFile . ' n&atilde;o foi encontrado.');
            }
        } catch (Exception $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

}
