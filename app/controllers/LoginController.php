<?php

class LoginController extends JOController
{

    private $session, $auth, $log, $request, $view;

    public function __construct()
    {
        parent::get(array(
            'JOSession',
            'JOAuthentication',
            'JOLog',
            'JORequest',
            'JOView'
        ));

        $this->session = new JOSession();
        $this->auth = new JOAuthentication();
        $this->log = new JOLog();
        $this->request = new JORequest();
        $this->view = new JOView();
    }

    public function index()
    {
        $this->view->render('index.phtml', array(
            'template' => 'login-form.phtml',
            'message' => null
        ));
    }

    public function check()
    {
        $this->request->requestMethod('POST', ROOT . 'login');

        $email = $this->request->post('email');
        $password = $this->request->post('password');

        try {
            if (!$this->auth->isEmail($email)) {
                throw new Exception('email-is-void-or-invalid');
            }

            if (!$this->auth->isPassword($password)) {
                throw new Exception('password-is-void');
            }

            $admin = $this->auth->setConnector('DB1')
                    ->setTableName('admin')
                    ->setWhere('email', $email)
                    ->setAndWhere('password', sha1($password));


            if ($admin->isRegistered()) {
                $data = $admin->getData();
                $this->checkStatusAdmin($data);
                $this->recordSessionAdmin($data);
                $this->request->redirect(ROOT . 'news/list-all');
            } else {
                throw new Exception('login-invalid');
            }
        } catch (Exception $e) {
            $this->log->setName('log_error_login.txt');
            $this->log->write("{$email} - {$e->getMessage()}");
            $this->request->redirect(ROOT . 'login/error/' . $e->getMessage());
            exit();
        }
    }

    public function checkStatusAdmin($data)
    {
        if ($data['status'] !== 'A') {
            throw new Exception('login-inactive');
        }
    }

    public function recordSessionAdmin($data)
    {
        $this->session->index('ADMIN_AUTH')->set(array(
            'ADMIN_ID' => $data['id'],
            'ADMIN_NAME' => $data['name'],
            'ADMIN_EMAIL' => $data['email'],
            'ADMIN_PASSWORD' => $data['password'],
            'ADMIN_ROLE' => $data['role']
        ));
    }

    public function error()
    {
        $messageError = $this->request->getParam(1);
        switch ($messageError) {
            case 'email-is-void-or-invalid':
                $message = 'Preencha o campo e-mail ou o e-mail informado &eacute; inv&aacute;lido.';
                break;
            case 'password-is-void':
                $message = 'Preencha o campo senha.';
                break;
            case 'login-invalid':
                $message = 'Login inv&aacute;lido.';
                break;
            case 'login-inactive':
                $message = 'Esse usu&aacute;rio foi desativado pelo administrador.';
                break;
            default:
                $message = 'Opera&ccedil;&atilde;o abortada.';
                break;
        }

        $this->view->render('index.phtml', array(
            'template' => 'login-form.phtml',
            'message' => $message
        ));
    }

}
