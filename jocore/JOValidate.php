<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOValidate trata entradas de dados, requisicoes, e contra sql injection
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOValidate {

    protected $values = array();

    public function joRequestMethod($method = 'POST', $redirect = ROOT) {
        if (!($_SERVER['REQUEST_METHOD'] == $method)) {
            header('Location: ' . $redirect);
            exit();
        }
    }

    protected function joAntiInject($string) {
        $string = preg_replace("/(select\s|insert\s|update\s|delete\s|truncate\s|drop\s|create\s|alter\s|delimiter\s)|(SELECT\s|INSERT\s|UPDATE\s|DELETE\s|WHERE\s|TRUNCATE\s|DROP\s|CREATE\s|ALTER\s|DELIMITER\s)/", "", $string);
        $string = trim($string);
        $string = addslashes($string);
        //$string = mysql_real_escape_string($string);
        //$string = htmlentities($string);
        return $string;
    }

    protected function joAntiInjectLogin($string) {
        $string = $this->joAntiInject($string);
        $string = strip_tags($string);
        return $string;
    }

    public function joGetR() {
        global $JOURL;
        $get = array();
        reset($JOURL);
        while (list($key, $value) = each($JOURL))
            $get[] = self::joAntiInject($value);
        return $get;
    }

    public function joParamR() {
        global $JOURL;
        $param = array();
        reset($JOURL);
        while (list($key, $value) = each($JOURL)) {
            if ($key <> 'CONTROLLER' && $key <> 'ACTION')
                $param[] = self::joAntiInject($value);
        }
        return $param;
    }

    public function joXhrGet() {
        $string = parse_url($_SERVER['REQUEST_URI']);
        $exp = explode('&', $string['query']);
        $count = count($exp);
        for ($i = 0; $i < $count; $i++) {
            $value = explode('=', $exp[$i]);
            $param[] = self::joAntiInject($value[1]);
        }
        return $param;
    }

    public function joPostSimple($name = null) {
        if (isset($_POST[$name]))
            return ($_POST[$name]);
        else
            return null;
    }

    public function joPost($name = null) {
        if (isset($_POST[$name]))
            return self::joAntiInject($_POST[$name]);
        else
            return null;
    }

    public function joPostR() {
        $post = array();
        while (list($key, $value) = each($_POST)) {
            if (is_array($value)) {
                while (list($k, $v) = each($value))
                    $dado[] = self::joAntiInject($v);
                $post[] = $dado;
            } else {
                $post[] = self::joAntiInject($value);
            }
        }
        return $post;
    }

    public function joPostAssoc() {
        $post = array();
        while (list($key, $value) = each($_POST)) {
            if (is_array($value)) {
                while (list($k, $v) = each($value))
                    $post[$key][$k] = self::joAntiInject($v);
            } else {
                $post[$key] = self::joAntiInject($value);
            }
        }
        return $post;
    }

    public function joPostLogin($name = null) {
        if (isset($_POST[$name]))
            return self::joAntiInjectLogin($_POST[$name]);
        else
            return null;
    }

    public function joFile() {
        if ($_FILES) {
            foreach ($_FILES as $key => $vals)
                $file[$key] = $vals;

            return $file;
        }
    }

    public function joSetFieldValue($id = false, $value = null, $type = 'VOID') {
        if ($id)
            $this->values[] = array('id' => $id, 'value' => $value, 'type' => $type);
    }

    public function joValidateField() {

        $validated = array('status' => true, 'id' => null, 'format' => null);

        $regex['ALNUM'] = '/^[[:alnum:]]*$/';
        $regex['ALPHA'] = '/^[[:alpha:]]*$/';
        $regex['CEP'] = '/^[0-9]{5}\-[0-9]{3}$/';
        $regex['CNPJ'] = '/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}\-[0-9]{2}$/';
        $regex['CPF'] = '/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[a-zA-Z0-9]{2}$/';
        $regex['DATE'] = '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/';
        $regex['DDD'] = '/^[0-9]{2,3}$/';
        $regex['EMAIL'] = '/^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/';
        $regex['NUMBER'] = '/^[[:digit:]]*$/';
        $regex['PHONE'] = '/^[0-9]{4,5}\-[0-9]{4}$/';
        $regex['RG'] = '/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\-[a-zA-Z0-9]{1}$/';
        //
        $format['ALNUM'] = 'Letras e n&uacute;meros';
        $format['ALPHA'] = 'Letras';
        $format['CEP'] = '00000-000';
        $format['CNPJ'] = '00.000.000/0000-00';
        $format['CPF'] = '000.000.000-00';
        $format['DATE'] = '00/00/0000';
        $format['DDD'] = '00 ou 000';
        $format['EMAIL'] = 'exemplo@nomedominio.servico ou exemplo@nomedominio.servico.pais';
        $format['NUMBER'] = 'N&uacute;meros';
        $format['PHONE'] = '0000-0000 ou 00000-0000';
        $format['RG'] = '00.000.000-0';
        $format['VOID'] = 'N&atilde;o aceita campo vazio';

        $size = count($this->values);
        for ($i = 0; $i < $size; $i++) {
            $upper = strtoupper($this->values[$i]['type']);
            $type = (isset($regex[$upper])) ? $upper : 'VOID';

            if ($type == 'VOID') {
                if (empty($this->values[$i]['value'])) {
                    $validated = array('status' => false, 'id' => $this->values[$i]['id'], 'format' => $format[$type]);
                    break;
                }
            } else {
                if (!preg_match($regex[$type], $this->values[$i]['value'])) {
                    $validated = array('status' => false, 'id' => $this->values[$i]['id'], 'format' => $format[$type]);
                    break;
                }
            }
        }
        return $validated;
    }

}