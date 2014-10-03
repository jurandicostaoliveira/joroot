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
        $this->session = parent::joSession();
        $this->request = parent::joRequest();
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
                $this->request->joRedirect(ROOT . $this->urlFailure);
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
