<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOBootstrap todas as funcionalidades passam por aqui 
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
$JOURL = array();
$JODB = array();
$JOCOREPATH = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR;

class JOBootstrap {

    /**
     * Configuracoes das funcionalidades
     * @var array 
     */
    public $joConfig = array(
        'ROOT' => false,
        'FILE_DEFAULT' => 'home',
        'MAX_PARAM' => 5,
        'ERROR_REPORTING' => E_ALL,
        'CHARSET' => 'UTF-8',
        'TIMEZONE' => 'America/Sao_Paulo',
        'SHOW_MSG_ERROR' => true
    );

    /**
     * Configuracoes do banco de dados 
     * @var array
     */
    public $joDb = array();

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return Page - Pagina que imprime a mensagem de erro  
     */
    protected static function joError($error = null) {
        die(include($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Dados para efetuar as configuracoes do banco de dados
     * @throws Exception
     */
    private function joConfigDb() {
        $config = array();
        foreach ($this->joDb as $key => $vals) {
            if (is_array($vals)) {
                $config[$key]['DRIVER'] = (isset($vals['DRIVER'])) ? $vals['DRIVER'] : 'pdo';
                $config[$key]['SGBD'] = (isset($vals['SGBD'])) ? $vals['SGBD'] : 'mysql';
                $config[$key]['PORT'] = (isset($vals['PORT'])) ? $vals['PORT'] : 3306;
                $config[$key]['HOSTNAME'] = (isset($vals['HOSTNAME'])) ? $vals['HOSTNAME'] : 'localhost';
                $config[$key]['USERNAME'] = (isset($vals['USERNAME'])) ? $vals['USERNAME'] : 'username';
                $config[$key]['PASSWORD'] = (isset($vals['PASSWORD'])) ? $vals['PASSWORD'] : 'password';
                $config[$key]['DATABASE'] = (isset($vals['DATABASE'])) ? $vals['DATABASE'] : 'database';
                $config[$key]['PERSISTENT'] = (isset($vals['PERSISTENT'])) ? $vals['PERSISTENT'] : true;
            }
        }
        return $config;
    }

    /**
     * Dispara a execucao da aplicacao 
     * Inicia a sessao
     * Armazena os dados de saida no buffer	
     * Exibe ou esconde os erros, conforme for configurado 
     */
    public function joInit() {

        try {
            /**
             * Seta o charset das paginas
             */
            header('Content-Type:text/html; charset=' . $this->joConfig['CHARSET']);

            ob_start();
            session_start();
            error_reporting($this->joConfig['ERROR_REPORTING']);
            /**
             * Definido o time zone da regiao, necessario no PHP5.3  
             */
            date_default_timezone_set($this->joConfig['TIMEZONE']);

            /**
             * Verifica a versao do php
             */
            if ((int) substr(phpversion(), 0, 1) < 5)
                throw new Exception('Suporte apenas, para vers&otilde;es, iguais ou superiores &agrave; 5.0.0');

            $this->joConfig['ROOT'] = ($this->joConfig['ROOT']) ? $this->joConfig['ROOT'] : "http://{$_SERVER['HTTP_HOST']}/joroot/";
            if (substr($this->joConfig['ROOT'], -1) != '/')
                $this->joConfig['ROOT'] = $this->joConfig['ROOT'] . '/';

            define('ROOT', $this->joConfig['ROOT']);
            define('CONTROLLERS', 'app/controllers/');
            define('VIEWS', 'app/views/');
            define('MODELS', 'app/models/');
            define('FILE_DEFAULT', $this->joConfig['FILE_DEFAULT']);
            define('MAX_PARAM', $this->joConfig['MAX_PARAM']);
            define('SHOW_MSG_ERROR', $this->joConfig['SHOW_MSG_ERROR']);

            /**
             * Objeto JOSystem, funciona como um motor de todo o projeto.
             */
            global $JOURL, $JODB;
            require_once('JOSystem.php');
            $start = new JOSystem();
            $start->joSetUrl();
            $JOURL = $start->joGetUrl();

            $JODB = $this->joConfigDb();
            require_once('JOModel.php');

            require_once('JOController.php');
            $controller = ucfirst($JOURL['CONTROLLER']);
            require_once(CONTROLLERS . $controller . 'Controller.php');

            /**
             * Configura o controller requisitado e checa a action
             */
            $controller .= 'Controller';
            $valAction = $start->joAuthAction($controller, $JOURL['ACTION']);

            /**
             * Instacia o objeto e executa o metodo(action)
             */
            $view = new $controller();
            $view->$valAction();
            /**
             * Envia e apaga o buffer;
             */
            ob_end_flush();
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

}