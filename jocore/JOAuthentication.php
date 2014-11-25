<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para criar autenticacao de usuarios no sistema atraves de e-mail e senha
 *   
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOAuthentication extends JOModel
{

    private $pdo,
            $select = '',
            $tableName,
            $arrayWhere = array();

    /**
     * Verifica se o e-mail informado e valido
     * 
     * @param string $email
     * @return boolean
     */
    public function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Verifica se a senha foi informada
     * 
     * @param string $password
     * @return boolean
     */
    public function isPassword($password = false)
    {
        return ($password) ? true : false;
    }

    /**
     * Compara e forca o tamanho da senha
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
     * Confirma a senha
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
     * Conecta com o banco pre configurado, informando a chave do conector
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
     * Nome da tabela que sera consultada
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
     * Possiveis condicoes
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
     * Possiveis condicoes
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
     * Verifica se o usuario informado esta resgistrado
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
     * Obtem os dados do usuario registrado
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
