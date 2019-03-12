<?php
/**
 * Created by PhpStorm.
 * User: Mahmoud
 * Date: 06/03/2019
 * Time: 19:42
 */


class Database
{
    private $db_name;
    private $db_user;
    private $db_password;
    private $db_host;
    private $pdo;

    /*
     *
     *
     *
     */


    public function __construct($db_name = 'BDD_Projet', $db_user = 'root', $db_password = 'root', $db_host = '')
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_password = $db_password;
        $this->db_host = $db_host;

    }

    public function getPDO()
    {
        if ($this->pdo === null) {
            try {
                $pdo = new PDO('mysql:dbname=BDD_Projet;host=localhost', 'root', 'root'); // Instance de la classe PDO.
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Traitement des erreurs contrôlé.
                $this->pdo = $pdo;
            } catch (PDOException $e) {
                echo 'La connection a échoué(PDO).'.$e->getMessage();
            }
        }

        return $this->pdo;
    }

    public function request($requete)
    {
        $req = $this->getPDO()->query($requete);
        $result = $req->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
}