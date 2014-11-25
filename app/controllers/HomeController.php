<?php

class HomeController extends JOController
{

    private $view;

    public function __construct()
    {
        parent::get(array(
            'JOView'
        ));
        
        $this->view = new JOView();
    }

    public function index()
    {
        $this->view->render('index.phtml', array(
            'template' => 'home.phtml'
        ));
    }

}
