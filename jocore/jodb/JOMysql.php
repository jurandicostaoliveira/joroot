<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOMysql faz abstracao de banco de dados MYSQL
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOMysql
{

    private $hostname, $username, $password, $database;
    private $conn, $select, $query;

    public function __construct($index, $JODB)
    {
        $this->hostname = $JODB[$index]['HOSTNAME'];
        $this->username = $JODB[$index]['USERNAME'];
        $this->password = $JODB[$index]['PASSWORD'];
        $this->database = $JODB[$index]['DATABASE'];
        $this->joOpen();
    }

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return HTML 
     */
    protected static function joError($error)
    {
        if (!SHOW_MSG_ERROR){
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        }
        die(require_once($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Conexao com o banco de dados
     * @throws Exception  
     */
    private function joOpen()
    {
        try {
            $this->conn = @mysql_connect($this->hostname, $this->username, $this->password);
            if ($this->conn) {
                $this->select = @mysql_select_db($this->database, $this->conn);
                if (!$this->select) {
                    //Nao foi possivel selecionar o banco de dados!
                    throw new Exception('* A Base de Dados n&atilde;o existe!');
                }
            } else {
                throw new Exception('* Erro no fornecimento de dados!');
            }
        } catch (Exception $e) {
            self::joError($e->getMessage());
        }
    }

    /**
     * Execulta a query
     * @param String $sql 
     */
    public function joQuery($sql = null)
    {
        if ($sql) {
            $this->query = @mysql_query($sql);
            if (!$this->query) {
                $this->query['error'] = @mysql_error();
            }
        } else {
            $this->query['error'] = 'A query n&atilde;o foi escrita';
        }
    }

    /**
     * Verifica se ha algum erro na query, se sim, mostra o erro e intermope o restante da acao 
     */
    public function joDebugQuery()
    {
        if (isset($this->query['error'])) {
            echo $this->query['error'];
        } else {
            echo 'Nenhum erro foi encontrado, nessa consulta';
        }
        exit();
    }

    /**
     * Retorna o array da consulta ou mysql_error
     * @return array $result 
     */
    public function joFetchAll()
    {
        if ($this->query['error']) {
            $result['error'] = $this->query['error'];
        } else {
            $result = array();
            while ($line = $this->joFetchArray()){
                $result[] = $line;
            }
        }
        return $result;
    }

    public function joNumRows()
    {
        if ($this->query['error']) {
            return $this->query['error'];
        } else {
            return @mysql_num_rows($this->query);
        }
    }

    public function joFetchArray()
    {
        if ($this->query['error']) {
            return $this->query['error'];
        } else {
            return @mysql_fetch_array($this->query);
        }
    }

    public function joFetchAssoc()
    {
        if ($this->query['error']) {
            return $this->query['error'];
        } else {
            return @mysql_fetch_assoc($this->query);
        }
    }

    public function joListTables($sql)
    {
        return @mysql_list_tables($sql);
    }

    public function joFetchRow()
    {
        if ($this->query['error']) {
            return $this->query['error'];
        } else {
            return @mysql_fetch_row($this->query);
        }
    }

    public function joFetchObject()
    {
        if ($this->query['error']) {
            return $this->query['error'];
        } else {
            return @mysql_fetch_object($this->query);
        }
    }

    public function joInsertId()
    {
        if ($this->query['error']) {
            return $this->query['error'];
        } else {
            return @mysql_insert_id();
        }
    }

    public function joFreeResult()
    {
        return @mysql_free_result($this->query);
    }

    public function joClose()
    {
        return @mysql_close($this->conn);
    }

    public function __destruct()
    {
        $this->joClose($this->conn);
    }

}
