<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOViews para renderizacao das paginas 
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOViews {

    public $joData = array();

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return Page - Pagina imprimindo a mensagem de erro  
     */
    protected static function joError($error = null) {
        if (!SHOW_MSG_ERROR)
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        die(include($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Recupera variaveis referenciada pela joData[] 
     * @param String $var = nome da variavel
     */
    public function joVar($var = null, $return = false) {
        $value = $return; 
        if (($var != null) && (isset($this->joData[$var])))
            $value = $this->joData[$var];
        return $value;
    }

    /**
     * Renderiza a view, vinda de /app/views/nome_da_pasta_origem/
     */
    public function joViewIndex() {
        try {
            global $JOURL;
            if (file_exists(VIEWS . 'index.php')) {
                if (is_array($this->joData)) {
                    extract($JOURL);
                    if (count($this->joData) > 0)
                        extract($this->joData);
                    include_once(VIEWS . 'index.php');
                }else {
                    throw new Exception('Para enviar dados pela a refer&ecirc;ncia joData, deve ser no formato de ARRAY.');
                }
            } else {
                throw new Exception('O arquivo index.php n&atilde;o existe em .: ' . VIEWS);
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Renderiza a view, vinda de /app/views/
     */
    public function joView($file = false) {
        try {
            global $JOURL;
            if ($file) {
                if (file_exists(VIEWS . $file)) {
                    if (is_array($this->joData)) {
                        extract($JOURL);
                        if (count($this->joData) > 0)
                            extract($this->joData);
                        include_once(VIEWS . $file);
                    }else {
                        throw new Exception('Para enviar dados pela a refer&ecirc;ncia joData, deve ser no formato de ARRAY.');
                    }
                } else {
                    throw new Exception('O arquivo ' . $file . ' n&atilde;o existe em .: ' . VIEWS);
                }
            } else {
                throw new Exception('Informe a view.');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Renderiza a view, vinda de um caminho que você ira especificar Ex : /app/testes/nome_do_arquivo.[html|php]
     */
    public function joViewPath($filepath = false) {
        try {
            global $JOURL;
            if ($filepath) {
                if (file_exists($filepath)) {
                    if (is_array($this->joData)) {
                        extract($JOURL);
                        if (count($this->joData) > 0)
                            extract($this->joData);
                        include_once($filepath);
                    }else {
                        throw new Exception('Para enviar dados pela a refer&ecirc;ncia joData, deve ser no formato de ARRAY.');
                    }
                } else {
                    throw new Exception('O caminho ou o arquivo n&atilde;o existe .: ' . $filepath);
                }
            } else {
                throw new Exception('Informe o caminho completo da view.');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

}
