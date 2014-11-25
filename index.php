<?php

/**
 * Joroot Framework(PHP)
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
require 'jocore/JOBootstrap.php';

$run = new JOBootstrap();

//Configuracoes gerais
$run->joConfig = array(
    'ROOT' => 'http://local/joroot/', //url do seu projeto 
    'CHARSET' => 'UTF-8', //Charset ultilizado no projeto
    'ROUTE_DEFAULT' => 'home', //Controller que sera carregado inicialmente 
    'MAX_PARAM' => 5, //Quantidade maxima de parametros que devera ser passados pela url exemplo .: dominio/controller/action/param1/param2 etc.
    'ERROR_REPORTING' => E_ALL, // 0(zero) para esconder E_ALL para mostrar os erros
    'TIMEZONE' => 'America/Sao_Paulo', //Essencial para funcoes de date(),strtotime() etc.
    'SHOW_MSG_ERROR' => true //TRUE para mostrar, FALSE para esconder as menssagens indicando erros identificadas pelo JF
);

//Configuracoes de banco de dados
$run->joDb = array(
    'BANCO_1' => array(
        'DRIVER' => 'pdo',
        'SGBD' => 'mysql',
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
$run->joFirewall = array(
    'URL_FAILURE' => 'login',
    'INDEX_AUTH' => 'ADMIN_AUTH',
    'INDEX_ROLE' => 'ADMIN_ACCESS',
    'REQUIRED_CREDENTIALS' => array('ADMIN_EMAIL', 'ADMIN_PASSWORD'),
    'REQUIRED_ACCESS' => array(
        'noticias:listar' => array(1, 2, 3),
        'noticias:editar' => array(1, 2),
        'noticias:excluir' => array(1, 3, 5),
        'noticias:editar-imagem' => array(1, 3, 4),
        'noticias:upload-imagem' => array(1)
    )
);

$run->joInit();
