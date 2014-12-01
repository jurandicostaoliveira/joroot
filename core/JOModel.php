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
     * Rotina para obter a conexao com o banco de dados atraves do paramentro que foi configurado
     * 
     * @global array $JODB
     * @param string $index
     * @return \PDO
     * @throws Exception
     */
    public function getDatabaseAdapter($index = NULL)
    {
        try {
            global $JODB;
            if (isset($JODB[$index])) {
                $data = (object) $JODB[$index];

                $dsn = "{$data->DRIVER}:host={$data->HOSTNAME};port={$data->PORT};dbname={$data->DATABASE}";
                $options = array(PDO::ATTR_PERSISTENT => $data->PERSISTENT);

                return new PDO($dsn, $data->USERNAME, $data->PASSWORD, $options);
            } else {
                throw new PDOException("A chave {$index} n&atilde;o foi registrada nas configura&ccedil;&otilde;es de .: configDatabase[{$index}]");
            }
        } catch (PDOException $e) {
            JOBootstrap::error($e->getMessage());
        }
    }

    /**
     * Preparar os valores dinamicos, para a clausula (SQL) LIMIT utilizada em paginacao de resultados
     * 
     * @global array $JOURL
     * @param int $numParam
     * @param int $limitResult
     * @return string
     */
    public function getLimitPagination($numParam = 1, $limitResult = 20)
    {
        global $JOURL;
        $end = 1;

        if (isset($JOURL['PARAM' . $numParam])) {
            $param = (int) $JOURL['PARAM' . $numParam];
            $end = ($param <= 0) ? 1 : $param; //Conversao de 0 para 1
        }

        $limitResult = (int) $limitResult;
        $start = ($end * $limitResult) - $limitResult;
        return "{$start}, {$limitResult}";
    }

}
