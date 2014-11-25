<?php

class HomeController extends JOController
{

    private $view;

    public function __construct()
    {
        $this->view = parent::joView();
    }

    public function index()
    {
        $this->view->render('index.php', array(
            'conteudo' => 'Home.php'
        ));
    }

    public function test()
    {
        $this->view->render('index.phtml', array(
            'teste' => 'JOROOT'
        ));
    }

}
