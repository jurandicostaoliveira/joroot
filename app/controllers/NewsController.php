<?php

class NewsController extends JOController
{

    protected $request, $model, $upload, $view;

    public function __construct()
    {
        parent::get(array(
            'JORequest',
            'MODEL@News',
            'JOUpload',
            'JOView'
        ));

        $this->request = new JORequest();
        $this->model = new NewsModel();
        $this->upload = new JOUpload();
        $this->view = new JOView();
    }

    public function index()
    {
        $this->listAll();
    }

    public function listAll()
    {
        $this->view->render('index.phtml', array(
            'data' => $this->model->listAll(),
            'template' => 'news-list-all.phtml'
        ));
    }

    public function add()
    {
        $message = false;
        if ($this->request->isPost()) {
            $data = $this->request->posts();
            $this->model->add($data);
            $message = 'Cadastro realizado com sucesso.';
        }

        $this->view->render('index.phtml', array(
            'template' => 'news-form.phtml',
            'data' => array(
                'action' => 'add',
                'id' => 0,
                'title' => null,
                'description' => null
            ),
            'message' => $message
        ));
    }

    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->posts();
            $this->model->update($data);
            $message = 'Atualização realizada com sucesso.';
        } else {
            $id = $this->request->getParam(1);
            $data = $this->model->edit($id);
            $message = false;

            if (!$data) {
                $this->request->redirect(ROOT . 'news/list-all');
            }
        }

        $data['action'] = 'edit';
        $this->view->render('index.phtml', array(
            'template' => 'news-form.phtml',
            'data' => $data,
            'message' => $message
        ));
    }

    /**
     * Apresenta a tela para fazer upload da imagem
     */
    public function editImage()
    {
        $id = (int) $this->request->getParam(1);
        $message = false;

        if ($this->request->isPost()) {
            $id = (int) $this->request->post('id');
            //$this->removeImage($id);
            $message = $this->uploadImage($id);
        }

        $this->view->render('index.phtml', array(
            'template' => 'news-edit-image.phtml',
            'id' => $id,
            'message' => $message
        ));
    }

    /**
     * Faz verificacoes, redimensionamento e upload da imagem
     * @throws Exception
     */
    public function uploadImage($id)
    {
        try {
            $image = $this->request->files('image');
            $allowed = array('jpg', 'jpeg', 'gif', 'png');

            if ((int) $image['size'] > 1000024) {//1000024 eq 1MB
                throw new Exception('Arquivo muito pesado, máximo permitido : 1MB.');
            } else if (array_search($this->upload->getFileExtension($image), $allowed) === false) {
                throw new Exception('Só são permitidos arquivos .JPG, .GIF, .PNG.');
            } else {
                $newName = $this->upload->getRandomName($image); //Gera um novo nome randomico para nao sobre escrever
                $this->upload->setDir('lib/images/'); //Diretorio de destino
                $this->upload->moveResizeImage($image, 100, $newName); //Redimensiona a imagem
                $this->model->updateImage($id, $newName); //Salva no banco
                throw new Exception('A Imagem foi alterada com sucesso.');
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function remove()
    {
        $id = (int) $this->request->getParam(1);
        if ($id > 0) {
            $this->removeImage($id); //Excluindo a imagem
            $this->model->remove($id); //Excluindo o registro
        }

        $this->request->redirect(ROOT . 'news/list-all');
    }

    /**
     * Para excluir a imagem
     * @param int $id
     */
    protected function removeImage($id)
    {
        $name = $this->model->imageName($id);
        if ($name != 'temp.png') {
            $this->upload->removeFile('lib/images/' . $name);
        }
    }

}
