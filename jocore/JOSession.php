<?php

/**
 * Joroot Framework(PHP)
 * 
 * JOSession gerencimento, validacao de usuario e nivel de acesso pela a sessao 
 *  
 * @autor       Jurandi Costa Oliveira (jurandi@jurandioliveira.com.br)
 * @link        http://www.jurandioliveira.com.br/joroot 
 * @desde       2011
 * @versao      1.2.0
 * @licenca     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOSession {

    private $index = 'JOROOT';
    private $id = null;
    private $name = null;
    private $login = null;
    private $password = null;
    private $access = null;
    private $status = null;
    private $token = null;

    /**
     * Retorna o erro caso houver um.
     * @param String $error
     * @return Page - Pagina imprimindo a mensagem de erro  
     */
    protected static function joError($error = null) {
        if (!SHOW_MSG_ERROR)
            $error = 'N&atilde;o entre em p&acirc;nico, pode ser apenas um erro de rota, verifique a URL digitada!';
        die(include($GLOBALS['JOCOREPATH'] . 'JOError.php'));
    }

    public function joSessionId($value = null) {
        $this->id = $value;
    }

    public function joSessionName($value = null) {
        $this->name = $value;
    }

    public function joSessionLogin($value = null) {
        $this->login = $value;
    }

    public function joSessionPassword($value = null) {
        $this->password = $value;
    }

    public function joSessionAccess($value = null) {
        $this->access = $value;
    }

    public function joSessionStatus($value = null) {
        $this->status = $value;
    }

    public function joSessionToken($value = null) {
        $this->token = $value;
    }

    public function joRecordSession($value = false, $url = ROOT) {
        try {
            if ($value)
                $this->index = $value;

            $_SESSION[$this->index]['ID'] = $this->id;
            $_SESSION[$this->index]['NAME'] = $this->name;
            $_SESSION[$this->index]['LOGIN'] = $this->login;
            $_SESSION[$this->index]['PASSWORD'] = $this->password;
            $_SESSION[$this->index]['ACCESS'] = $this->access;
            $_SESSION[$this->index]['STATUS'] = $this->status;
            $_SESSION[$this->index]['TOKEN'] = $this->token;
            header('Location:' . $url);
            exit();
        } catch (Exception $e) {
            self::joError('Erro na tentativa de estabelecer uma sess&atilde;o');
        }
    }

    /**
     * Checagem de logim e senha
     * @param String $index = Index para controle de sessao
     * @param String $url = Url para envio caso nao haja as credenciais
     * @throws Exception retorna para o inicio
     */
    public function joCheckLogin($index = 'JOROOT', $url = ROOT) {
        try {
            if (isset($_SESSION[$index]['LOGIN'], $_SESSION[$index]['PASSWORD'])) {
                if (($_SESSION[$index]['LOGIN'] == null) && ($_SESSION[$index]['PASSWORD'] == null))
                    throw new Exception($url);
            } else {
                throw new Exception($url);
            }
        } catch (Exception $e) {
            header('Location: '.$e->getMessage());
        }
    }

    /**
     * Checagem de logim ,senha e nivel de acesso
     * @param String $index = Index para controle de sessao
     * @param Array $access = Numero(s) inteiro(s) com o niveil(s) de acesso que for permitido
     * @param String $url = Url para envio caso nao haja as credenciais
     * @throws Exception retorna para o inicio
     */
    public function joCheckAccess($index = 'JOROOT', $access = array(), $url = ROOT) {
        try {
            if (isset($_SESSION[$index]['LOGIN'], $_SESSION[$index]['PASSWORD'])) {
                if (($_SESSION[$index]['LOGIN'] != null) && ($_SESSION[$index]['PASSWORD'] != null)) {
                    $array_access = (is_array($access)) ? $access : array();
                    if (!in_array($_SESSION[$index]['ACCESS'], $array_access))
                        throw new Exception($url);
                } else {
                    throw new Exception($url);
                }
            } else {
                throw new Exception($url);
            }
        } catch (Exception $e) {
            header('Location: '.$e->getMessage());
        }
    }

    /**
     * Checagem de logim ,senha e token 
     * @param String $index = Index para controle de sessao
     * @param type $token = Tkoen para acesso
     * @param String $url = Url para envio caso nao haja as credenciais
     * @throws Exception retorna para o inicio
     */
    public function joCheckToken($index = 'JOROOT', $token = null, $url = ROOT) {
        try {
            if (isset($_SESSION[$index]['LOGIN'], $_SESSION[$index]['PASSWORD'])) {
                if (($_SESSION[$index]['LOGIN'] != null) && ($_SESSION[$index]['PASSWORD'] != null)) {
                    if ($token != $_SESSION[$index]['TOKEN'])
                        throw new Exception($url);
                } else {
                    throw new Exception($url);
                }
            } else {
                throw new Exception($url);
            }
        } catch (Exception $e) {
           header('Location: '.$e->getMessage());
        }
    }

    /**
     * Destroi a sessao
     */
    public function joLogout($url = ROOT) {
        session_unset();
        session_destroy();
        header('Location:' . $url);
    }

}