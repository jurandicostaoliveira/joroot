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
include('jocore/JOBootstrap.php');

$run = new JOBootstrap();

//Configuracoes gerais
$run->joConfig['ROOT'] = 'http://local/joroot/'; //url do seu projeto 
$run->joConfig['FILE_DEFAULT'] = 'home'; //Controller que sera carregado inicialmente 
$run->joConfig['MAX_PARAM'] = 5; //Quantidade maxima de parametros que devera ser passados pela url exemplo .: dominio/controller/action/param1/param2 etc.
$run->joConfig['ERROR_REPORTING'] = E_ALL; // 0(zero) para esconder E_ALL para mostrar os erros
$run->joConfig['CHARSET'] = 'UTF-8'; //Charset ultilizado no projeto
$run->joConfig['TIMEZONE'] = 'America/Sao_Paulo'; //Essencial para funcoes de date(),strtotime() etc.
$run->joConfig['SHOW_MSG_ERROR'] = true; //TRUE para mostrar, FALSE para esconder as menssagens indicando erros identificadas pelo JF

/**
 * Configuracoes de banco(s)
 * $run->joDb['banco1'] = banco1 eh a chave, caso precisar de mais bancos, adicione mais chaves com suas respectivas configuracoes
 */
$run->joDb['banco1']['DRIVER'] = 'pdo'; //Conversando atraves do PDO recomendado
$run->joDb['banco1']['SGBD'] = 'mysql'; //SGBD
//$run->joDb['banco1']['PORT'] = 3306;//Porta se precisar alterar a padrao
$run->joDb['banco1']['HOSTNAME'] = 'localhost'; //Host onde se encontra o banco
$run->joDb['banco1']['USERNAME'] = 'root'; //Usuario
$run->joDb['banco1']['PASSWORD'] = ''; //Senha
$run->joDb['banco1']['DATABASE'] = 'noticias'; //Banco de dados
//$run->joDb['banco1']['PERSISTENT'] = true;//Para manter conectado no caso do PDO

$run->joInit();