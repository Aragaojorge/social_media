<?php

    
  namespace App\Models;

  use MF\Model\Model;

  class Tweet extends Model {

    private $id;
    private $id_usuario;
    private $tweet;
    private $data;

    public function __get($atributo) {
      return $this->$atributo;
    }

    public function __set($atributo, $valor) {
      $this->$atributo = $valor;
    }

    // método salvar tweet
    public function salvar() {

      $query = 'INSERT into tweets(id_usuario, tweet)values(:id_usuario, :tweet)';
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
      $stmt->bindValue(':tweet', $this->__get('tweet'));
      $stmt->execute();

      return $this;

    }

      // método recuperar tweet
    public function getAll() {

      $query = 'SELECT t.id, t.id_usuario, u.nome, t.tweet, DATE_FORMAT(t.data, "%d/%m/%Y %H:%i") as data
        from 
          tweets as t
        left join usuarios as u on (t.id_usuario = u.id)
        where 
          t.id_usuario = :id_usuario
        or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores
        where id_usuario = :id_usuario)
        order by
          t.data desc';

      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

      // método recuperar tweet
    public function getPorPagina($limit, $offset) {

      $query = "SELECT t.id, t.id_usuario, u.nome, t.tweet,
        DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
        from 
          tweets as t
        left join usuarios as u on (t.id_usuario = u.id)
        where 
          t.id_usuario = :id_usuario
        or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores
        where id_usuario = :id_usuario)
        order by
          t.data desc
        limit $limit
          offset $offset";

      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    // método recuperar total tweet
    public function getTotalRegistros() {

      $query = "SELECT 
        count(*) as total
      from 
        tweets as t
      left join usuarios as u on (t.id_usuario = u.id)
      where 
        t.id_usuario = :id_usuario
      or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores
      where id_usuario = :id_usuario)
        ";

      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
      $stmt->execute();

      return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

    public function getOne() {

      $todelete = $_GET['tweet_content']; 

      $query = "SELECT id, id_usuario, tweet from tweets where id_usuario = :id_usuario and tweet = :tweet_content";

      $stmt = $this->db->prepare($query);

      $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
      $stmt->bindValue(':tweet_content', $todelete);

      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function remover() {

      $query = "DELETE from tweets where id = :id";
      $stmt = $this->db->prepare($query);  
      $stmt->bindValue(':id', $this->__get('id'));
      $stmt->execute();

      return true;

    }
  }
?>