<?php

class LoginController extends JOController
{

    private $session, $request, $view;

    public function __construct()
    {
        parent::get(array(
            'JOSession',
            'JORequest',
            'JOView'
        ));
        
        $this->session = new JOSession();
        $this->request = new JORequest();
        $this->view = new JOView();
    }

    public function index()
    {
        $this->view->render('index.phtml', array(
            'template' => 'login-form.phtml'
        ));
    }

    public function check()
    {
        $this->session->index('ADMIN_AUTH')->set(array(
            'ADMIN_EMAIL' => $this->request->joPost('email'),
            'ADMIN_PASSWORD' => $this->request->joPost('senha'),
            'ADMIN_ACCESS' => 1 //Nivel de acesso do usuario
        ));

        $this->request->redirect(ROOT . 'news/list-all');
    }

}
