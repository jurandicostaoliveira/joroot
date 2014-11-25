<?php

class NewsModel extends JOModel
{

    private $pdo;

    public function __construct()
    {
        /**
         * Modelo de conexao usando o PDO
         * Recomendo ultilizar a camada de abstracao PDO e melhor em tudo
         * http://php.net/manual/en/book.pdo.php 
         */
        $this->pdo = parent::joConnector('DB1')->joOpen();

        /**
         * Modelo de conexao usando o velho mysql_connect()
         * $this->pdo = parent::joConnector('DB1');
         */
    }

    public function listAll()
    {
        $qry = $this->pdo->query("SELECT * FROM `news`;");
        return $qry->fetchAll(PDO::FETCH_ASSOC);
    }

    public function edit($id)
    {
        $qry = $this->pdo->prepare("SELECT * FROM `news` WHERE `id` = :id;");
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        return $qry->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data)
    {
        $qry = $this->pdo->prepare("INSERT INTO `news` VALUES(DEFAULT, :title, :description, 'temp.png');");
        $qry->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $qry->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $qry->execute();
    }

    public function update($data)
    {
        $qry = $this->pdo->prepare("UPDATE `news` SET `title` = :title, `description` = :description WHERE `id` = :id;");
        $qry->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $qry->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $qry->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $qry->execute();
    }

    public function updateImage($id, $image)
    {
        $qry = $this->pdo->prepare("UPDATE `news` SET `image` = :image WHERE `id` = :id;");
        $qry->bindParam(':image', $image, PDO::PARAM_STR);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
    }

    public function imageName($id)
    {
        $qry = $this->pdo->prepare("SELECT `image` FROM `news` WHERE `id` = :id;");
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        $response = $qry->fetch(PDO::FETCH_ASSOC);
        return $response['image'];
    }

    public function remove($id)
    {
        $qry = $this->pdo->prepare("DELETE FROM `news` WHERE `id` = :id LIMIT 1;");
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
    }

}
