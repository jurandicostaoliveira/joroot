<?php

class LogoutController extends JOController
{

    public function index()
    {
        parent::get(array(
            'JOSession',
            'JORequest'
        ));

        $session = new JOSession();
        $request = new JORequest();
        
        //$session->destroy();
        $session->index('ADMIN_AUTH')->remove();
        $request->joRedirect(ROOT);
    }

}
