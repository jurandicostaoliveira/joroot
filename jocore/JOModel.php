<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para fazer abstracao de banco de dados 
 *   
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
abstract class JOModel
{

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return HTML 
     */
    protected static function joError($error = null)
    {
        if (!SHOW_MSG_ERROR) {
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        }
        die(require_once($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Conexao com o banco de dados
     * @throws Exception  
     */
    public function joConnector($index = false)
    {
        try {
            if ($index) {
                global $JODB;
                if (isset($JODB[$index])) {
                    switch ($JODB[$index]['DRIVER']) {
                        case 'mysql':
                            require_once('jodb/JOMysql.php');
                            return new JOMysql($index, $JODB);
                            break;
                        case 'pdo':
                            require_once('jodb/JOPdo.php');
                            return new JOPdo($index, $JODB);
                            break;
                        default:
                            throw new Exception('Nenhum driver, foi encontrado com esse nome .: ' . $JODB[$index]['DRIVER']);
                            break;
                    }
                } else {
                    throw new Exception("A chave {$index} n&atilde;o foi registrada nas configura&ccedil;&otilde;es de .: joDb[chave]");
                }
            } else {
                throw new Exception('Informe a mesma chave que foi registrada nas configura&ccedil;&otilde;es de .: joDb[chave]');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Prepara os valores dinamicos, para a clausula (SQL) LIMIT ultizanda em paginacao de resultados
     * @param Int $limitPage = numero de resultados por pagina
     * @param Int $numParam = posicao do parametro da URL, responsavel pela contabilizacao das paginas
     * @return String
     */
    public function joLimitPaginate($limitPage = 25, $numParam = 1)
    {
        $end = 1;
        if ($GLOBALS['JOURL']['PARAM' . $numParam]) {
            $param = (int) $GLOBALS['JOURL']['PARAM' . $numParam];
            $end = ($param <= 0) ? 1 : $param; //Conversao de 0 para 1
        }
        $begin = ($end * $limitPage) - $limitPage;
        return $begin . ', ' . $limitPage;
    }

}
