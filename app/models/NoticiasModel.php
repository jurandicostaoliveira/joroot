<?php

class NoticiasModel extends JOModel
{

    private $db;

    public function __construct()
    {
        /**
         * Modelo de conexao usando o PDO
         * Recomendo ultilizar a camada de abstracao PDO e melhor em tudo
         * http://php.net/manual/en/book.pdo.php 
         */
        $this->db = parent::joConnector('BANCO_1')->joOpen();

        /**
         * Modelo de conexao usando o velho mysql_connect()
         * $this->db = parent::joConnector('banco1');
         */
    }

    public function listar()
    {
        $qry = $this->db->query("SELECT * FROM tb_noticias");
        return $qry->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editar($id)
    {
        $qry = $this->db->prepare("SELECT * FROM tb_noticias WHERE `id` = :id");
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        return $qry->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar(&$dados)
    {
        $qry = $this->db->prepare("INSERT INTO tb_noticias VALUES(NULL, :titulo, :descricao, 'temp.png');");
        $qry->bindParam(':titulo', $dados['titulo'], PDO::PARAM_STR);
        $qry->bindParam(':descricao', $dados['descricao'], PDO::PARAM_STR);
        $qry->execute();
    }

    public function atualizar(&$dados)
    {
        $qry = $this->db->prepare("UPDATE tb_noticias SET `titulo` = :titulo, `descricao` = :descricao WHERE `id` = :id;");
        $qry->bindParam(':titulo', $dados['titulo'], PDO::PARAM_STR);
        $qry->bindParam(':descricao', $dados['descricao'], PDO::PARAM_STR);
        $qry->bindParam(':id', $dados['id'], PDO::PARAM_INT);
        $qry->execute();
    }

    public function alterarImagem($id, $imagem)
    {
        $qry = $this->db->prepare("UPDATE tb_noticias SET `imagem` = :imagem WHERE `id` = :id;");
        $qry->bindParam(':imagem', $imagem, PDO::PARAM_STR);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
    }

    public function excluir($id)
    {
        $qry = $this->db->prepare("DELETE FROM tb_noticias WHERE `id` = :id;");
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
    }

    public function imagemNome($id)
    {
        $qry = $this->db->prepare("SELECT `imagem` FROM tb_noticias WHERE `id` = :id");
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        return $qry->fetch(PDO::FETCH_ASSOC);
    }

}
