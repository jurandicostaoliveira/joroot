<?php

class HomeController extends JOController {

    protected $view;
    
    public function __construct() {
        $this->view = parent::joView();
    }

    public function index() {
        $this->view->joData['conteudo'] = 'Home.php';
        $this->view->joViewIndex();
    }

}