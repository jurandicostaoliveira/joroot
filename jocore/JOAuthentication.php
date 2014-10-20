<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOFirewall para bloquear acessos em paginas especificadas
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOAuthentication extends JOModel
{

    private $pdo,
            $select = '',
            $tableName,
            $arrayWhere = array();

    /**
     * 
     * @param string $email
     * @return boolean
     */
    public function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * 
     * @param string $password
     * @return boolean
     */
    public function isPassword($password = false)
    {
        return ($password) ? true : false;
    }

    /**
     * 
     * @param stirng $password
     * @param int $minLength
     * @param int $maxLength
     * @return boolean
     */
    public function isPasswordLength($password, $minLength = 6, $maxLength = 18)
    {
        return ((strlen($password) >= $minLength) && (strlen($password) <= $maxLength)) ? true : false;
    }

    /**
     * 
     * @param string $password
     * @param string $confirm
     * @return boolean
     */
    public function confirmPassword($password, $confirm)
    {
        return ($password === $confirm) ? true : false;
    }

    /**
     * 
     * @param string $strConnect
     * @return \JOAuthentication
     */
    public function setConnector($strConnect = NULL)
    {
        $this->pdo = parent::joConnector($strConnect)->joOpen();
        return $this;
    }

    /**
     * 
     * @param string $tableName
     * @return \JOAuthentication    
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $value
     * @return \JOAuthentication
     */
    public function setWhere($name, $value)
    {
        $this->arrayWhere[$name] = $value;
        $this->select .= "WHERE `{$name}` = :{$name} ";
        return $this;
    }

    /**
     * 
     * @param string $name
     * @param string $value
     * @return \JOAuthentication
     */
    public function setAndWhere($name, $value)
    {
        $this->arrayWhere[$name] = $value;
        $this->select .= "AND `{$name}` = :{$name} ";
        return $this;
    }

    /**
     * 
     * @return boolean
     */
    public function isRegistered()
    {
        $qry = $this->pdo->prepare("SELECT COUNT(*) AS total FROM `{$this->tableName}` {$this->select};");
        foreach ($this->arrayWhere as $name => $value) {
            $qry->bindValue(":{$name}", $value);
        }
        $qry->execute();
        $result = $qry->fetch(PDO::FETCH_ASSOC);
        return ((int) $result['total'] > 0) ? true : false;
    }

    /**
     * 
     * @return array
     */
    public function getData()
    {
        $qry = $this->pdo->prepare("SELECT * FROM `{$this->tableName}` {$this->select};");
        foreach ($this->arrayWhere as $name => $value) {
            $qry->bindValue(":{$name}", $value);
        }
        $qry->execute();
        return $qry->fetch(PDO::FETCH_ASSOC);
    }

}
