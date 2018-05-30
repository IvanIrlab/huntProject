<?php

namespace Src\Model;
use Src\Classes\Database;
use Src\Classes\Utilities;
Use \DomainException;
Use \InvalidArgumentException;

class HuntModel
{

  public function getHuntIdFromChallenges($id, $type)
  {
    $query = new Database();

    $sql = "	SELECT hunt_id FROM ".$type."
              WHERE id = ?
              ";

    var_dump($sql);

    $hunt_id = $query->query($sql, [$id]);
var_dump($hunt_id);
    return $hunt_id;
  }

  public function listAllChallengesQcm($huntId)
  {
    $query = new Database();

    $sql = "	SELECT * FROM Qcm
              WHERE hunt_id = ?
              ";

    $challengesQcm = $query->query($sql, [$huntId]);

    return $challengesQcm;
  }

  public function listAllChallengesQru($huntId)
  {
    $query = new Database();

    $sql = "	SELECT * FROM Qru
              WHERE hunt_id = ?
              ";

    $challengesQru = $query->query($sql, [$huntId]);

    return $challengesQru;
  }

  public function saveChallenge($huntId, $spotId, $qcmQuestion, $qcmChoice1, $qcmChoice2, $qcmChoice3, $qcmChoice4, $qcmResponse, $qruQuestion, $qruResponse)
  {

    if(empty($huntId) || !is_numeric($huntId)){
      throw new DomainException("L'identifiant du circuit n'est pas renseigné ou n'est pas un nombre entier!");
    }

    if(empty($spotId) || !is_numeric($spotId)){
      throw new DomainException("L'identifiant de la place n'est pas renseigné ou n'est pas un nombre entier!");
    }

    if(empty($qruQuestion) && empty($qruResponse)){
      $typeQuestion = "qcm";
    }
    else{
      $typeQuestion = "qru";
    }

    if($typeQuestion == "qcm" && (empty($qcmQuestion) || empty($qcmChoice1) || empty($qcmChoice2) || empty($qcmChoice3) || empty($qcmChoice4) || empty($qcmResponse))){
      throw new DomainException("Pour le challenge QCM, veuillez renseigner la question, les 4 possibilités de réponses et la réponse!");
    }

    if($typeQuestion == "qru" && (empty($qruQuestion) || empty($qruResponse))){
      throw new DomainException("Pour le challenge Question ouverte ou fermée, veuillez renseigner la question et la réponse!");
    }


    $db = new Database();

    if($typeQuestion == "qcm"){
    $db->executeSql(
            "
            INSERT INTO Qcm
                 (type,question,response1,response2,response3,response4,response,spot_id,hunt_id)
            VALUES(?,?,?,?,?,?,?,?,?)
            ",[$typeQuestion,$qcmQuestion, $qcmChoice1, $qcmChoice2, $qcmChoice3, $qcmChoice4, $qcmResponse, $spotId,$huntId]);
    }
    else{ //($typeQuestion == "qru")
      $db->executeSql(
              "
              INSERT INTO Qru
                   (type,question,response,spot_id,hunt_id)
              VALUES(?,?,?,?,?)
              ",[$typeQuestion,$qruQuestion, $qruResponse, $spotId,$huntId]);
    }
  }

  public function countSpots($id)
  {
    $query = new Database();

    $sql = "	SELECT COUNT(*) FROM spots
              WHERE hunt_id = ?
              ";

    $count = $query->query($sql, [$id]);

    return $count;
  }

    public function listAll()
    {
  		$query = new Database();

  		$sql = "	SELECT *
  					FROM hunts
  					ORDER BY id DESC
  				";

  		$hunts = $query->query($sql);

  		return $hunts;
    }

    public function listAllSpots($id)
    {
  		$query = new Database();

  		$sql = "	SELECT * FROM spots
                WHERE hunt_id = ?
  					    ORDER BY id
  				      ";

  		$spots = $query->query($sql, [$id]);

  		return $spots;
    }

    public function listOne($id)
    {
  		$query = new Database();

  		$sql = "	SELECT *
  					FROM hunts
  					WHERE hunts.id = ?";

  		$hunt = $query->queryOne($sql,[$id]);

  		return $hunt;
    }

    public function getLastHunt()
    {
		$query = new Database();

    $sql = "SELECT *
            FROM hunts
            ORDER BY id DESC
            LIMIT 1";

    $hunt = $query->queryOne($sql);

    return $hunt;
    }

    public function save($hunt_title, $place_name, $place_coord_lat, $place_coord_long, $place_address, $admin_id)
    {

    	if(empty($hunt_title)){
    		throw new DomainException("Veuillez renseigner le titre de la chasse!");
    	}

    	if(empty($place_name)){
    		throw new DomainException("Veuillez renseigner le nom de la place de départ!");
    	}

      if(empty($place_address)){
    		$place_address = 'NULL' ;
    	}

    	if(is_float($place_coord_lat)){
      		throw new InvalidArgumentException("Coordonnée Latitude doit être une suite de chiffre!");
    	}

      if(is_float($place_coord_long)){
      		throw new InvalidArgumentException("Coordonnée Longitude doit être une suite de chiffre!");
    	}

      $status = 'wait';
      $utilities =  new Utilities();
      $code = $utilities->generateCode(); //color code for app mobile + hunt code


  		$db = new Database();

  		$db->executeSql(
  						"
  						INSERT INTO hunts
  							   (name,
  							   creation_timestamp,
  							   admin_id,status,code,start_lat,start_long, address)
  						VALUES(?,NOW(),?,?,?,?,?,?)
  						",[$hunt_title, $admin_id, $status, $code, $place_coord_lat, $place_coord_long, $place_address]);
    }

    public function saveSpot($spot_name, $spot_lat, $spot_long, $spot_alt, $spot_rad, $spot_picture, $spot_desc, $hunt_id)
    {

    	if(empty($spot_name)){
    		throw new DomainException("Veuillez renseigner le nom de la place!");
    	}

      if(empty($spot_desc)){
    		throw new DomainException("Veuillez renseigner la description!");
    	}

      if(empty($spot_picture)){
    		$spot_picture = 'NULL' ;
    	}

    	if(is_float($spot_lat)){
      		throw new InvalidArgumentException("Coordonnée Latitude doit être une suite de chiffre et décimale!");
    	}

      if(is_float($spot_lat)){
      		throw new InvalidArgumentException("Coordonnée Longitude doit être une suite de chiffre et décimale!");
    	}

      if(!is_numeric($spot_rad)){
      		throw new InvalidArgumentException("Coordonnée radius doit être un nombre entier!");
    	}

      if(empty($hunt_id)){
    		throw new DomainException("N'oubliez pas de lier la place à une Chasse!");
    	}

  		$db = new Database();

  		$db->executeSql(
  						"
  						INSERT INTO spots
  							   (name,
  							   longitude,
  							   latitude,altitude,radius,picture,description, hunt_id)
  						VALUES(?,?,?,?,?,?,?,?)
  						",[$spot_name, $spot_lat, $spot_long, $spot_alt, $spot_rad, $spot_picture, $spot_desc, $hunt_id]);
    }


    public function delete($id)
    {
		$db = new Database();

		$db->executeSql(
						"
							DELETE
		    				FROM hunts
		    				WHERE id = ?
						",[$id]);
    }

    public function deleteChallengeQcm($id)
    {
		$db = new Database();

		$db->executeSql(
						"
							DELETE
		    				FROM Qcm
		    				WHERE id = ?
						",[$id]);
    }

    public function deleteChallengeQru($id)
    {
		$db = new Database();

		$db->executeSql(
						"
							DELETE
		    				FROM Qru
		    				WHERE id = ?
						",[$id]);
    }

    public function update($id)
    {
		$db = new Database();

		$db->executeSql(
						"
							UPDATE
		    				FROM hunts
		    				WHERE id = ?
						",[$id]);
    }

}
