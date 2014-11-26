<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para iniciar e trabalhar as funcionalidades
 *  
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
$JOURL = array();
$JODB = array();

class JOBootstrap
{

    /**
     * Configuracoes das funcionalidades
     * @var array 
     */
    public $configGeneral = array();
    private $configGeneralDefault = array(
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
    public $configDatabase = array();
    private $configDatabaseDefault = array(
        'DRIVER' => 'mysql',
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
    public $configFirewall = array();
    private $configFirewallDefault = array(
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
    public static function error($error = null)
    {
        if (!SHOW_MSG_ERROR) {
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        }
        die(require_once(__DIR__ . DIRECTORY_SEPARATOR . 'JOError.php'));
    }

    /**
     * Rotina que identifica o protocolo e dominio atual
     * 
     * @return string
     */
    public function getDomain()
    {
        $protocol = (strpos(strtolower(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL')), 'https') === false) ? 'http://' : 'https://';
        return $protocol . filter_input(INPUT_SERVER, 'HTTP_HOST');
    }

    /**
     * Dados para efetuar as configuracoes do banco de dados
     * @throws Exception
     */
    private function setDatabases()
    {
        foreach ($this->configDatabase as $key => $vals) {
            if (is_array($vals)) {
                $this->configDatabase[$key] = array_merge($this->configDatabaseDefault, $this->configDatabase[$key]);
            }
        }
        return $this->configDatabase;
    }

    /**
     * Definicao das constatantes
     */
    private function setConstants()
    {
        define('ROOT', $this->configGeneral['ROOT']);
        define('CONTROLLERS', 'app/controllers/');
        define('VIEWS', 'app/views/');
        define('MODELS', 'app/models/');
        define('ROUTE_DEFAULT', $this->configGeneral['ROUTE_DEFAULT']);
        define('MAX_PARAM', $this->configGeneral['MAX_PARAM']);
        define('SHOW_MSG_ERROR', $this->configGeneral['SHOW_MSG_ERROR']);
    }

    /**
     * Prepara os firewalls e os inicia
     * 
     * @global array $JOURL
     */
    private function setFirewall()
    {
        global $JOURL;
        require_once('JOFirewall.php');
        $configFirewall = array_merge($this->configFirewallDefault, $this->configFirewall);
        $firewall = new JOFirewall($configFirewall, "{$JOURL['CONTROLLER']}:{$JOURL['ACTION']}");
        $firewall->start();
    }

    /**
     * Verifica a versao do php
     */
    private function checkPhpVersion()
    {
        if ((int) substr(phpversion(), 0, 1) < 5) {
            throw new Exception('Suporte apenas, para vers&otilde;es, iguais ou superiores &agrave; 5.0.0.');
        }
    }

    /**
     * Dispara a execucao da aplicacao 
     * Inicia a sessao
     * Armazena os dados de saida no buffer	
     * Exibe ou esconde os erros, conforme for configurado 
     */
    public function run()
    {
        try {
            $this->configGeneral = array_merge($this->configGeneralDefault, $this->configGeneral);
            header('Content-Type:text/html; charset=' . $this->configGeneral['CHARSET']);
            ob_start();
            session_start();
            error_reporting($this->configGeneral['ERROR_REPORTING']);
            date_default_timezone_set($this->configGeneral['TIMEZONE']);

            if (substr($this->configGeneral['ROOT'], -1) != '/') {
                $this->configGeneral['ROOT'] .= '/';
            }

            $this->setConstants();
            $this->checkPhpVersion();

            global $JOURL, $JODB;
            require_once('JOSystem.php');
            $system = new JOSystem();
            $JOURL = $system->getUrl();
            $JODB = $this->setDatabases();
            require_once('JOModel.php');
            require_once('JOController.php');

            $controller = ucfirst($JOURL['CONTROLLER']);
            $controller .= 'Controller';
            require_once(CONTROLLERS . $controller . '.php');

            $action = $system->authAction($controller, $system->getCamelCaseAction($JOURL['ACTION']));

            if (count($this->configFirewall) > 0) {
                $this->setFirewall();
            }
            
            $exec = new $controller();
            $exec->$action();
            ob_end_flush();
        } catch (Exception $e) {
            self::error($e->getMessage());
        }
    }

}
