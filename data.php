<?php

  require_once('vendor/autoload.php');

  use Symfony\Component\Yaml\Yaml;

  header('Content-Type: application/json');

  try {

    $config = Yaml::parseFile('src/dbconfig.yaml');

    $conn = new \PDO("mysql:host=localhost;dbname=".$config['database'], $config['user'], $config['password']);


    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    $itemsPerPage = 10;
    $offset = 0;

    if(isset($_GET['page'])) {

      $offset = ($page - 1) * $itemsPerPage + 1;

    }

    $sql = "SELECT * FROM repos";

    if(isset($_GET['search'])) {
      $sql .= " WHERE name like :search";
    }

    $sql .= " LIMIT :limit OFFSET :offset";

    $sth = $conn->prepare($sql);
    $sth->bindValue(':limit', (int) $itemsPerPage, PDO::PARAM_INT);
    $sth->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

    if(isset($_GET['search'])) {
      $sth->bindValue(':search', '%'.$_GET['search'].'%', PDO::PARAM_STR);
    }

    $sth->execute();

    $result = $sth->fetchAll();

    /***********************/
    /* COUNT ***************/
    /**********************/
    $countSql = "SELECT count(id) FROM repos";

    if(isset($_GET['search'])) {
      $countSql .= " WHERE name like :search ORDER BY name";
    }

    $sth = $conn->prepare($countSql);

    if(isset($_GET['search'])) {
      $sth->bindValue(':search', '%'.$_GET['search'].'%', PDO::PARAM_STR);
    }

    $sth->execute();

    $count = $sth->fetchColumn();

    echo json_encode(['count'=>$count, 'data'=>$result]);


  } catch (PDOException $e) {

    echo $e->getMessage() ;

  }



?>
