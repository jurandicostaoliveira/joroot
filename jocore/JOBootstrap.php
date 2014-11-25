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
$JOCOREPATH = __DIR__ . DIRECTORY_SEPARATOR;

class JOBootstrap
{

    /**
     * Configuracoes das funcionalidades
     * @var array 
     */
    public $joConfig = array();
    private $joConfigDefault = array(
        'ROOT' => false,
        'ROUTE_DEFAULT' => 'index',
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
    private $joDbDefault = array(
        'DRIVER' => 'pdo',
        'SGBD' => 'mysql',
        'PORT' => 3306,
        'HOSTNAME' => 'localhost',
        'USERNAME' => 'root',
        'PASSWORD' => '',
        'DATABASE' => NULL,
        'PERSISTENT' => true
    );

    /**
     * Configuracoes de firewalls
     * @var type 
     */
    public $joFirewall = array();
    private $joFirewallDefault = array(
        'URL_FAILURE' => NULL,
        'INDEX_AUTH' => 'JOROOT_AUTH',
        'INDEX_ROLE' => false,
        'REQUIRED_CREDENTIALS' => false,
        'REQUIRED_ACCESS' => array()
    );

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return Page - Pagina que imprime a mensagem de erro  
     */
    public static function joError($error = null)
    {
        if (!SHOW_MSG_ERROR) {
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        }
        die(require_once($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Dados para efetuar as configuracoes do banco de dados
     * @throws Exception
     */
    private function joConfigDb()
    {
        foreach ($this->joDb as $key => $vals) {
            if (is_array($vals)) {
                $this->joDb[$key] = array_merge($this->joDbDefault, $this->joDb[$key]);
            }
        }
        return $this->joDb;
    }

    /**
     * Dispara a execucao da aplicacao 
     * Inicia a sessao
     * Armazena os dados de saida no buffer	
     * Exibe ou esconde os erros, conforme for configurado 
     */
    public function joInit()
    {
        try {
            $this->joConfig = array_merge($this->joConfigDefault, $this->joConfig);

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
            if ((int) substr(phpversion(), 0, 1) < 5) {
                throw new Exception('Suporte apenas, para vers&otilde;es, iguais ou superiores &agrave; 5.0.0');
            }

            //$this->joConfig['ROOT'] = ($this->joConfig['ROOT']) ? $this->joConfig['ROOT'] : "http://{$_SERVER['HTTP_HOST']}/joroot/";
            if (substr($this->joConfig['ROOT'], -1) != '/') {
                $this->joConfig['ROOT'] = $this->joConfig['ROOT'] . '/';
            }

            define('ROOT', $this->joConfig['ROOT']);
            define('CONTROLLERS', 'app/controllers/');
            define('VIEWS', 'app/views/');
            define('MODELS', 'app/models/');
            define('ROUTE_DEFAULT', $this->joConfig['ROUTE_DEFAULT']);
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
            $controller .= 'Controller';
            require_once(CONTROLLERS . $controller . '.php');

            /**
             * Configura o controller requisitado e checa a action
             */
            $action = $start->joAuthAction($controller, $start->joCamelCaseAction($JOURL['ACTION']));

            if (count($this->joFirewall) > 0) {
                $this->getFirewall();
            }

            /**
             * Instacia o objeto e executa o metodo(action)
             */
            $view = new $controller();
            $view->$action();
            /**
             * Envia e apaga o buffer;
             */
            ob_end_flush();
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Prepara os firewalls e os inicia
     * @global array $JOURL
     */
    private function getFirewall()
    {
        global $JOURL;
        require_once('JOFirewall.php');
        $configFirewall = array_merge($this->joFirewallDefault, $this->joFirewall);
        $firewall = new JOFirewall($configFirewall, "{$JOURL['CONTROLLER']}:{$JOURL['ACTION']}");
        $firewall->start();
    }

}
