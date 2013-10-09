<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOController responsavel pela usuabilidade do JF nos controllers
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
abstract class JOController {

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
     * Inclui um novo controller
     * @param String $controller - Nome do controller a ser incluso Ex.: "teste"
     * @return Objetc class - O objeto retornado
     * @throws Exception - caso houver imprime o erro na tela  
     */
    public static function joGetController($controller = false) {
        try {
            if ($controller) {
                $class = ucfirst($controller) . 'Controller';
                if (file_exists(CONTROLLERS . $class . '.php')) {
                    include(CONTROLLERS . $class . '.php');
                    if (class_exists($class)) {
                        return new $class();
                    } else {
                        throw new Exception('A Classe ' . $class . ' n&atilde;o foi definida no arquivo ' . $controller . 'Controller.php');
                    }
                } else {
                    throw new Exception('N&atilde;o existe o arquivo ' . $controller . 'Controller.php em ' . CONTROLLERS);
                }
            } else {
                throw new Exception('Informe o nome do controller');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Inclui um novo model
     * @param String $model
     * @return Object - O objeto retornado 
     * @throws Exception - caso houver imprime o erro na tela  
     */
    public static function joGetModel($model = false) {
        try {
            if ($model) {
                $class = ucfirst($model) . 'Model';
                if (file_exists(MODELS . $class . '.php')) {
                    include(MODELS . $class . '.php');
                    if (class_exists($class)) {
                        return new $class();
                    } else {
                        throw new Exception('A Classe ' . $class . ' n&atilde;o foi definida no arquivo ' . $model . 'Model.php');
                    }
                } else {
                    throw new Exception('N&atilde;o existe o arquivo ' . $model . 'Model.php em ' . MODELS);
                }
            } else {
                throw new Exception('Informe o nome do model');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Retorna o objeto \JOViews
     * @return Object  
     */
    public static function joView() {
        require_once('JOViews.php');
        return new JOViews();
    }

    /**
     * Retorna o objeto \JOSession
     * @return Object 
     */
    public static function joSession() {
        require_once('JOSession.php');
        return new JOSession();
    }

    /**
     * Retorna o objeto \JOValidate
     * @return Object 
     */
    public static function joValidate() {
        require_once('JOValidate.php');
        return new JOValidate();
    }

    /**
     * Retorna o objeto \JOPaginate
     * @return Object 
     */
    public static function joPaginate() {
        require_once('JOPaginate.php');
        return new JOPaginate();
    }

    /**
     * Retorna o objeto \JOResize
     * @return Object 
     */
    public static function joUpload() {
        require_once('JOUpload.php');
        return new JOUpload();
    }

    /**
     * Retorna o objeto \JOResize
     * @return Object 
     */
    public static function joLog() {
        require_once('JOLog.php');
        return new JOLog();
    }

    /**
     * Retorna o objeto \JOMail
     * @return Object 
     */
    public static function joMail() {
        require_once('JOMail.php');
        return new JOMail();
    }

    /**
     * Inclui uma API externa
     * @param String $path - caminho completo para encontrar a api
     * @throws Exception - caso houver imprime o erro na tela  
     */
    public static function joApiExternal($path = false) {
        try {
            if ($path) {
                if (file_exists($path)) {
                    require_once($path);
                } else {
                    throw new Exception('A API n&atilde;o foi encontrada nesse caminho.: ' . $path);
                }
            } else {
                throw new Exception('Informe o caminho completo da API Ex.: app/diretorio/nome_da_api.php');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Retorna o objeto \JOHtml
     * @return Object 
     */
    public static function joHtml() {
        require_once('JOHtml.php');
        return new JOHtml();
    }

    /**
     * Retorna o objeto \JODownload
     * @return Object 
     */
    public static function joDownload() {
        require_once('JODownload.php');
        return new JODownload();
    }

}