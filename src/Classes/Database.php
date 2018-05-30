<?php

namespace Src\Classes;
use \PDO;

class Database
{
	private $pdo;

	public function __construct()
	{

		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		];

//local
		$this->pdo = new PDO ('mysql:host=localhost:8889;dbname=cat', 'root', 'root', $options);

//Serveur
		//$this->pdo = new PDO ('mysql:host=nigmahunjj201887.mysql.db;dbname=nigmahunjj201887', 'nigmahunjj201887', 'Nigma187', $options);

		$this->pdo->exec('SET NAMES UTF8');
	}

	public function executeSql($sql, array $values = array())
	{
		$query = $this->pdo->prepare($sql);

		$query->execute($values);

		return $this->pdo->lastInsertId();
	}

    public function query($sql, array $criteria = array())
    {
        $query = $this->pdo->prepare($sql);

        $query->execute($criteria);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryOne($sql, array $criteria = array())
    {
        $query = $this->pdo->prepare($sql);

        $query->execute($criteria);

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
