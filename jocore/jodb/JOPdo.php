<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOPdo faz abstracao de banco de dados com PDO
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOPdo {

    private $conn, $sgbd, $port, $hostname, $username, $password, $database, $persistent;

    public function __construct($index, $JODB) {
        $this->sgbd = $JODB[$index]['SGBD'];
        $this->port = $JODB[$index]['PORT'];
        $this->hostname = $JODB[$index]['HOSTNAME'];
        $this->username = $JODB[$index]['USERNAME'];
        $this->password = $JODB[$index]['PASSWORD'];
        $this->database = $JODB[$index]['DATABASE'];
        $this->persistent = $JODB[$index]['PERSISTENT'];
    }

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return HTML 
     */
    protected static function joError($error) {
        if (!SHOW_MSG_ERROR)
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        die(include($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    /**
     * Efetua a conexao com o banco de dados via PDO
     * @return connect
     */
    public function joOpen() {
        try {
            $dsn = "{$this->sgbd}:host={$this->hostname};port={$this->port};dbname={$this->database}";
            $options = array(PDO::ATTR_PERSISTENT => $this->persistent);
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            return $this->conn;
        } catch (PDOException $e) {
            self::joError($e->getMessage());
        }
    }
    
    public function joClose() {
        return $this->conn = null;
    }

}