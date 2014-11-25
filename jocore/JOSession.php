<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel pelo armazenamento, gerencimento e validacao de usuario, nivel de acesso pela a sessao 
 *   
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOSession
{

    private $index = 'JOROOT';

    /**
     * Prepara o um indice para a sessao
     * @param string $index
     * @return \JOSession
     */
    public function index($index = 'JOROOT')
    {
        $this->index = $index;
        return $this;
    }

    /**
     * Verifica a existencia do indice informado 
     * @param string $index
     * @return boolean
     */
    public function isIndex($index = 'JOROOT')
    {
        return (isset($_SESSION[$index])) ? true : false;
    }

    /**
     * Recupera o valor do indice da sessao
     * @return int, string, array, boolean etc
     */
    public function get()
    {
        if (isset($_SESSION[$this->index])) {
            return $_SESSION[$this->index];
        }
    }

    /**
     * Persiste o valores
     * @param int, string, array, boolean etc $value
     */
    public function set($value = null)
    {
        $_SESSION[$this->index] = $value;
    }

    /**
     * Adiciona novos valores
     * @param int, string, array, boolean etc $value
     */
    public function add($value = null)
    {
        if (isset($_SESSION[$this->index])) {
            if (is_array($value)) {
                $_SESSION[$this->index] = array_merge($_SESSION[$this->index], $value);
            } else {
                if (is_array($_SESSION[$this->index])) {
                    array_push($_SESSION[$this->index], $value);
                } else {
                    $_SESSION[$this->index] .= $value;
                }
            }
        }
    }

    /**
     * Remove o indice existente
     */
    public function remove()
    {
        if (isset($_SESSION[$this->index])) {
            unset($_SESSION[$this->index]);
        }
    }

    /**
     * @Especifico para processo de autenticacao, chega se existe indices criados na sessao 
     * @param array $value
     * @return boolean
     */
    public function isAuthorized($value = null)
    {
        $result = true;
        if (is_array($value)) {
            while (list($k, $v) = each($value)) {
                if (!isset($_SESSION[$this->index][$v])) {
                    $result = false;
                }
            }
        } else if (is_string($value)) {
            if (!isset($_SESSION[$this->index][$value])) {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Compara a existencia de valor(es) em algum indice da sessao
     * @param int, string $index
     * @param array $values
     * @return boolean
     */
    public function in($index = 0, $values = array())
    {
        $result = false;
        if (isset($_SESSION[$this->index][$index])) {
            if (is_array($values)) {
                if (in_array($_SESSION[$this->index][$index], $values)) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * Destroi a sessao
     */
    public function destroy()
    {
        session_unset();
        session_destroy();
    }

}
