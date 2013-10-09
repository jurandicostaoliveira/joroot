<?php

class NoticiasController extends JOController {

    protected $valid, $model, $upload, $view;

    public function __construct() {
        $this->valid = parent::joValidate();
        $this->model = parent::joGetModel('Noticias');
        $this->upload = parent::joUpload();
        $this->view = parent::joView();
    }

    public function index() {
        self::listar(); //tecnica para deixa como default a action listar
    }

    public function listar() {
        $this->view->joData['dados'] = $this->model->listar();
        $this->view->joData['conteudo'] = 'NoticiasListar.php';
        $this->view->joViewIndex();
    }

    public function adicionar() {
        $this->view->joData['dados']['acao'] = 'cadastrar';
        $this->view->joData['dados']['id'] = 0;
        $this->view->joData['dados']['titulo'] = null;
        $this->view->joData['dados']['descricao'] = null;
        $this->view->joData['dados']['botao'] = 'Cadastrar';
        $this->view->joData['conteudo'] = 'NoticiasForm.php';
        $this->view->joViewIndex();
    }

    public function editar() {
        list($id) = $this->valid->joParamR();
        $dados = $this->model->editar($id);

        if (!$dados)
            header('Location: ' . ROOT . 'noticias/listar');

        $this->view->joData['dados'] = $dados;
        $this->view->joData['dados']['acao'] = 'atualizar';
        $this->view->joData['dados']['botao'] = 'Atualizar';
        $this->view->joData['conteudo'] = 'NoticiasForm.php';
        $this->view->joViewIndex();
    }

    public function cadastrar() {
        $this->valid->joRequestMethod('POST', ROOT . 'noticias/listar'); //Checa se o acesso veio do POST
        $dados = $this->valid->joPostAssoc(); //Recuperando os dados vindo do POST ja validados, eh como se fosse $_POST
        $this->model->cadastrar($dados); //Passando para camada de modelo
        self::jsAlert('Cadastro realizado com sucesso'); //Msg de sucesso 
    }

    public function atualizar() {
        $this->valid->joRequestMethod('POST', ROOT . 'noticias/listar'); //Checa se o acesso veio do POST
        $dados = $this->valid->joPostAssoc(); //Recuperando os dados vindo do POST ja validados, eh como se fosse $_POST
        $this->model->atualizar($dados); //Passando para camada de modelo
        self::jsAlert('Atualização feita com sucesso'); //Msg de sucesso 
    }

    public function excluir() {
        list($id) = $this->valid->joParamR();
        if ($id > 0) {
            self::excluirImagem($id); //Excluindo a imagem
            $this->model->excluir($id); //Excluindo o registro
            $msg = 'Registro excluido com sucesso';
        } else {
            $msg = 'O registro não foi encontrado';
        }
        self::jsAlert($msg);
    }

    /**
     * Para excluir a imagem
     * @param int $id
     */
    protected function excluirImagem($id) {
        $nome = $this->model->imagemNome($id);
        if ($nome['imagem'] != 'temp.png')
            $this->upload->joRemoveFile('lib/images/' . $nome['imagem']);
    }

    /**
     * Execulta um alert com mensagem de erro 
     * @param type $msg
     */
    protected function jsAlert($msg) {
        echo "<script>alert('{$msg}'); window.location.href = '" . ROOT . "noticias/listar';</script>";
    }

    /**
     * Apresenta a tela para fazer upload da imagem
     */
    public function editar_imagem() {
        list($id) = $this->valid->joParamR();
        $this->view->joData['id'] = $id;
        $this->view->joData['conteudo'] = 'NoticiasImagem.php';
        $this->view->joViewIndex();
    }

    /**
     * Faz verificacoes, redimensionamento e upload da imagem
     * @throws Exception
     */
    public function upload_imagem() {
        try {
            $this->valid->joRequestMethod('POST', ROOT . 'noticias/listar');
            $id = $this->valid->joPost('id');
            $arquivo = $this->valid->joFile();
            $imagem = $arquivo['imagem']; //

            $permitidos = array('jpg', 'jpeg', 'gif', 'png');

            if ($imagem['size'] > 1000024) {//1000024 eq 1MB
                throw new Exception('Arquivo muito pesado, máximo permitido : 1MB');
            } else if (array_search($this->upload->joFileType($imagem), $permitidos) === false) {
                throw new Exception('Só são permitidos arquivos .JPG, .GIF, .PNG');
            } else {

                $novo_nome = $this->upload->joRandomName($imagem); //Gera um novo nome randomico para nao sobre escrever
                $this->upload->joSetDir('lib/images/'); //Diretorio de destino
                $this->upload->joResizeImage($imagem, 100, $novo_nome); //Redimensiona a imagem
                $this->model->alterarImagem($id, $novo_nome); //Salva no banco
                throw new Exception('A Imagem foi alterada com sucesso');
            }

            unset($id, $arquivo, $imagem, $permitidos, $novo_nome);
        } catch (Exception $e) {
            self::jsAlert($e->getMessage());
        }
    }

}