<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para trata entradas de dados e requisicoes, protegendo-os contra injection 
 *   
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JORequest
{

    private $joUrl;

    public function __construct()
    {
        global $JOURL;
        $this->joUrl = $JOURL;
    }

    /**
     * Retorna uma string com tratamento contra injection
     * 
     * @param string $string
     * @return string
     */
    public static function setAntiInjection($string)
    {
        if (is_string($string)) {
            $string = preg_replace("/(select\s|insert\s|update\s|delete\s|truncate\s|drop\s|create\s|alter\s|delimiter\s)|(SELECT\s|INSERT\s|UPDATE\s|DELETE\s|WHERE\s|TRUNCATE\s|DROP\s|CREATE\s|ALTER\s|DELIMITER\s)/", "", $string);
            $string = trim($string);
            $string = addslashes($string);
            //$string = htmlentities($string);
        }
        return $string;
    }

    /**
     * Redicionamento de URL, sendo necessario informa-la 
     * @param string $url
     */
    public function redirect($url = null)
    {
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
    public function requestMethod($method = 'POST', $redirect = ROOT)
    {
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') !== $method) {
            $this->redirect($redirect);
        }
    }

    /**
     * Verifica se o metodo requisitado e POST.
     * 
     * @return boolean
     */
    public function isPost()
    {
        return (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') ? true : false;
    }

    /**
     * Retona o valor do controller atual
     * @global array $JOURL
     * @return string
     */
    public function getController()
    {
        return $this->joUrl['CONTROLLER'];
    }

    /**
     * Retona o valor da action atual
     * @global array $JOURL
     * @return string
     */
    public function getAction()
    {
        return $this->joUrl['ACTION'];
    }

    /**
     * Retorna o valor do parametro vindo do GET de acordo com a posicao informada EX: 1, 2, 3 
     * 
     * @param int $key
     * @return string or int
     */
    public function getParam($key = null)
    {
        $value = null;
        if (($key !== null) && (is_numeric($key))) {
            $value = self::setAntiInjection($this->joUrl["PARAM{$key}"]);
        }
        return $value;
    }

    /**
     * Retorna os valores dos parametros passados pelo GET 
     *
     * @return array
     */
    public function getParams()
    {
        $values = array();
        foreach ($this->joUrl as $key => $value) {
            if (($key !== 'CONTROLLER') && ($key !== 'ACTION')) {
                $values[] = self::setAntiInjection($value);
            }
        }
        return $values;
    }

    /**
     * Retorna os valores dos parametros passados pela QUERY STRING
     * 
     * @return array
     */
    public function getUriParams()
    {
        $values = array();
        $string = parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI'));
        if (isset($string['query'])) {
            $exp = explode('&', $string['query']);
            $count = count($exp);
            for ($i = 0; $i < $count; $i++) {
                $value = explode('=', $exp[$i]);
                $values[$value[0]] = self::setAntiInjection($value[1]);
            }
        }
        return $values;
    }

    /**
     * Retorna o valor do item que foi enviado via POST
     * 
     * @param type $key = indice associativo
     * @param type $processed = TRUE para receber o dado processado contra injecao FALSE para nao processado
     * @return type string or null
     */
    public function post($key = null, $processed = true)
    {
        $value = null;
        $post = filter_input(INPUT_POST, $key);
        if ($post) {
            $value = ($processed) ? self::setAntiInjection($post) : $post;
        }
        return $value;
    }

    /**
     * Retona um array do que foi enviado via POST
     * 
     * @param null $data
     * @return type
     */
    public function posts()
    {
        $post = filter_input_array(INPUT_POST);
        array_walk_recursive($post, function(&$value) {
            $value = JORequest::setAntiInjection($value);
        });
        return $post;
    }

    /**
     * Retorna os valores em multiplo array 
     * 
     * @return array
     */
    public function files($key = null)
    {
        $files = filter_var_array($_FILES);
        array_walk_recursive($files, function(&$value) {
            $value = JORequest::setAntiInjection($value);
        });

        if (isset($files[$key])) {
            return $files[$key];
        } else {
            return $files;
        }
    }

}
