<?php

/**
 * Joroot Framework(PHP)
 *  
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
require __DIR__ . '/core/JOBootstrap.php';

$joroot = new JOBootstrap();

//Configuracoes gerais
$joroot->configGeneral = array(
    'ROOT' => $joroot->getDomain() . '/joroot/', //url do seu projeto 
    'CHARSET' => 'utf-8', //Charset ultilizado no projeto
    'ROUTE_DEFAULT' => 'home', //Controller que sera carregado inicialmente 
    'MAX_PARAM' => 5, //Quantidade maxima de parametros que devera ser passados pela url exemplo .: dominio/controller/action/param1/param2 etc.
    'ERROR_REPORTING' => E_ALL, // 0(zero) para esconder E_ALL para mostrar os erros
    'TIMEZONE' => 'America/Sao_Paulo', //Essencial para funcoes de date(),strtotime() etc.
    'SHOW_MSG_ERROR' => true //TRUE para mostrar, FALSE para esconder as menssagens indicando erros identificadas pelo JF
);

//Configuracoes de banco de dados
$joroot->configDatabase = array(
    'DB1' => array(
        'DRIVER' => 'mysql',
        'HOSTNAME' => 'localhost',
        'USERNAME' => 'root',
        'PASSWORD' => '123456',
        'DATABASE' => 'joroot'
    )
);

/**
 * Configuracoes do firewall de protecao em paginas
 * - Caso nao queira protecao dos firewalls no sistema retire as configuracoes abaixo
 */
$joroot->configFirewall = array(
    'URL_FAILURE' => 'login',
    'INDEX_AUTH' => 'ADMIN_AUTH',
    'INDEX_ROLE' => 'ADMIN_ROLE',
    'REQUIRED_CREDENTIALS' => array('ADMIN_EMAIL', 'ADMIN_PASSWORD'),
    'REQUIRED_ACCESS' => array(
        'news:list-all' => array('ADMIN'),
        'news:edit-image' => array('ADMIN'),
        'news:add' => array('ADMIN'),
        'news:edit' => array('ADMIN'),
        'news:remove' => array('ADMIN')
    )
);

$joroot->run();
