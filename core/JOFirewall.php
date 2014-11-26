<?php

/**
 * Joroot Framework(PHP)
 * 
 * Responsavel para bloquear acessos em paginas especificadas
 *  
 * @author      Jurandi C. Oliveira (jurandi@jurandioliveira.com.br)
 * @link        https://github.com/jurandicostaoliveira/joroot 
 * @since       2011
 * @version     1.5.0
 * @license     Gratuito para estudo, desenvolvimento e contribuicao
 */
class JOFirewall extends JOController
{

    private $session,
            $request,
            $urlCurrent,
            $urlFailure,
            $indexAuth,
            $indexRole,
            $requiredCredentials,
            $requiredAccess;

    public function __construct($firewall, $urlCurrent = NULL)
    {
        parent::get(array('JOSession', 'JORequest'));
        $this->session = new JOSession();
        $this->request = new JORequest();
        //
        $this->urlCurrent = $urlCurrent;
        $this->urlFailure = $firewall['URL_FAILURE'];
        $this->indexAuth = $firewall['INDEX_AUTH'];
        $this->indexRole = $firewall['INDEX_ROLE'];
        $this->requiredCredentials = $firewall['REQUIRED_CREDENTIALS'];
        $this->requiredAccess = $firewall['REQUIRED_ACCESS'];
    }

    /**
     * Verifica se a url consta nas configuracoes do firewall 
     */
    public function start()
    {
        $isRequiredRoute = isset($this->requiredAccess[$this->urlCurrent]) ? true : false;
        if ($isRequiredRoute) {
            if (!$this->checkCredentials() || !$this->checkPermission()) {
                $this->request->redirect(ROOT . $this->urlFailure);
            }
        }
    }

    /**
     * Verifica se as credenciais estao ativas
     * @return boolean
     */
    private function checkCredentials()
    {
        $isAllowed = true;
        if ($this->requiredCredentials) {
            $isAllowed = $this->session->index($this->indexAuth)->isAuthorized($this->requiredCredentials);
        }
        return $isAllowed;
    }

    /**
     * Verifica a permissao do usuario ao acessar a url  
     * @return boolean
     */
    private function checkPermission()
    {
        $isAllowed = true;
        if ($this->indexRole) {
            $isAllowed = $this->session->index($this->indexAuth)->in($this->indexRole, $this->requiredAccess[$this->urlCurrent]);
        }
        return $isAllowed;
    }

}
