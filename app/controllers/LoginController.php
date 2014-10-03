<?php

class LoginController extends JOController
{

    protected $session, $request, $view;

    public function __construct()
    {
        $this->session = parent::joSession();
        $this->request = parent::joRequest();
        $this->view = parent::joView();
    }

    public function index()
    {
        $this->view->joData = array(
            'conteudo' => 'LoginForm.php'
        );
        $this->view->joViewIndex();
    }

    public function check()
    {
        $this->session->index('ADMIN_AUTH')->set(array(
            'ADMIN_EMAIL' => 'admin@joroot.com.br',
            'ADMIN_PASSWORD' => '123456',
            'ADMIN_ACCESS' => 1 //Nivel de acesso do usuario
        ));
        $this->request->joRedirect(ROOT . 'noticias/listar');
    }

    public function logout()
    {
        //$this->session->destroy();
        $this->session->index('ADMIN_AUTH')->remove();
        $this->request->joRedirect(ROOT);
    }

}
