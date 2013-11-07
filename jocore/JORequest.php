<?php

/**
 * Joroot Framework(PHP)
 * 
 * JORequest trata entradas de dados e requisicoes, protegendo-os contra injection
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JORequest {

    /**
     * Redicionamento de URL, sendo necessario informa-la 
     * @param string $url
     */
    public function joRedirect($url = null) {
        if ($url <> null) {
            header("Location: {$url}");
            exit(0);
        }
    }
    
    /**
     * Verifica e restringe o metodo de entrada na pagina
     * @param string $method
     * @param string $redirect
     */
    public function joRequestMethod($method = 'POST', $redirect = ROOT) {
        if (!($_SERVER['REQUEST_METHOD'] == $method))
            self::joRedirect($redirect);
    }

    /**
     * Retorna uma string com tratamento contra injection
     * @param string $string
     * @return string
     */
    protected function joAntiInjection($string) {
        if (is_string($string)) {
            $string = preg_replace("/(select\s|insert\s|update\s|delete\s|truncate\s|drop\s|create\s|alter\s|delimiter\s)|(SELECT\s|INSERT\s|UPDATE\s|DELETE\s|WHERE\s|TRUNCATE\s|DROP\s|CREATE\s|ALTER\s|DELIMITER\s)/", "", $string);
            $string = trim($string);
            $string = addslashes($string);
            //$string = htmlentities($string);
        }
        return $string;
    }

    /**
     * Retona o valor do controller atual
     * @global array $JOURL
     * @return string
     */
    public function joController() {
        global $JOURL;
        return $JOURL['CONTROLLER'];
    }

    /**
     * Retona o valor da action atual
     * @global array $JOURL
     * @return string
     */
    public function joAction() {
        global $JOURL;
        return $JOURL['ACTION'];
    }

    /**
     * Retorna o valor do parametro vindo do GET de acordo com a posicao informada EX: 1, 2, 3 
     * @global array $JOURL
     * @param int $key
     * @return string int
     */
    public function joParam($key = null) {
        global $JOURL;
        $value = null;
        if (($key <> null) && (is_numeric($key)))
            $value = self::joAntiInjection($JOURL["PARAM{$key}"]);
        return $value;
    }

    /**
     * Retorna os valores dos parametros passados pelo GET 
     * @global array $JOURL
     * @return array
     */
    public function joParams() {
        global $JOURL;
        $values = array();
        reset($JOURL);
        while (list($key, $value) = each($JOURL)) {
            if (($key <> 'CONTROLLER') && ($key <> 'ACTION'))
                $values[] = self::joAntiInjection($value);
        }
        return $values;
    }

    /**
     * Retorna os valores dos parametros passados pela QUERY STRING
     * @return array
     */
    public function joUriParams() {
        $values = array();
        $string = parse_url($_SERVER['REQUEST_URI']);
        if (isset($string['query'])) {
            $exp = explode('&', $string['query']);
            $count = count($exp);
            $values = array();
            for ($i = 0; $i < $count; $i++) {
                $value = explode('=', $exp[$i]);
                $values[$value[0]] = self::joAntiInjection($value[1]);
            }
        }
        return $values;
    }

    /**
     * Retorna o valor do item que foi enviado via POST
     * @param type $key = indice associativo
     * @param type $processed = TRUE para receber o dado processado contra injecao FALSE para nao processado
     * @return type string or null
     */
    public function joPost($key = null, $processed = true) {
        $value = null;
        if (isset($_POST[$key]))
            $value = ($processed) ? self::joAntiInjection($_POST[$key]) : $_POST[$key];
        return $value;
    }

    /**
     * Processa o tratamento de dados recebido via POST e retorna em indices numericos 
     * @return array
     */
    private function joPostsNumeric() {
        $values = array();
        while (list($k1, $v1) = each($_POST)) {
            if (is_array($v1)) {
                while (list($k2, $v2) = each($v1)) {
                    if (is_array($v2)) {
                        while (list($k3, $v3) = each($v2))
                            $values[][][] = self::joAntiInjection($v3);
                    } else {
                        $values[][] = self::joAntiInjection($v2);
                    }
                }
            } else {
                $values[] = self::joAntiInjection($v1);
            }
        }
        return $values;
    }

    /**
     * Processa o tratamento de dados recebido via POST e retorna em indices associativo, em ate 3 niveis
     * @return array
     */
    private function joPostsAssoc() {
        $values = array();
        while (list($k1, $v1) = each($_POST)) {
            if (is_array($v1)) {
                while (list($k2, $v2) = each($v1)) {
                    if (is_array($v2)) {
                        while (list($k3, $v3) = each($v2))
                            $values[$k1][$k2][$k3] = self::joAntiInjection($v3);
                    } else {
                        $values[$k1][$k2] = self::joAntiInjection($v2);
                    }
                }
            } else {
                $values[$k1] = self::joAntiInjection($v1);
            }
        }
        return $values;
    }

    /**
     * Retona um array do que foi enviado via POST
     * @param string $type = ASSOC para array associativo e NUMERIC para numerico
     * @return array
     */
    public function joPosts($type = 'ASSOC') {
        switch ($type) {
            case 'NUMERIC':
                $posts = self::joPostsNumeric();
                break;
            default :
                $posts = self::joPostsAssoc();
                break;
        }
        return $posts;
    }

    /**
     * Retorna o valor vindo do POST protegido contra injection e tags maliciosas 
     * @param string $key
     * @return string
     */
    public function joPostStripTags($key = null) {
        $value = null;
        if (isset($_POST[$key])) {
            $value = self::joAntiInjection($_POST[$key]);
            $value = strip_tags($value);
        }
        return $value;
    }

    /**
     * Retorna os valores padrao enviado via FILES 
     * @param string $key 
     * @return array
     */
    public function joFile($key = null) {
        $values = array();
        if (isset($_FILES[$key])) {
            while (list($k, $v) = each($_FILES[$key]))
                $values[$k] = self::joAntiInjection($v);
        }
        return $values;
    }

    /**
     * Retorna os valores em multiplo array 
     * @return array
     */
    public function joFiles() {
        $values = array();
        if (isset($_FILES)) {
            while (list($k1, $v1) = each($_FILES)) {
                while (list($k2, $v2) = each($v1))
                    $values[$k1][$k2] = self::joAntiInjection($v2);
            }
        }
        return $values;
    }

}