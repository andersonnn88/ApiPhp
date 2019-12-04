<?php
namespace Models;

use \Core\Model;
use \Models\Jwt;

class Users extends Model {

	private $id_user;
	
	public function create($name, $email, $pass){
		if(!$this->emailExists($email)){
			$hash = password_hash($pass, PASSWORD_DEFAULT);
			$sql = "INSERT INTO users (name, email, pass) VALUES (:name, :email, :pass)";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':name', $name);
			$sql->bindValue(':email',$email);
			$sql->bindValue(':pass', $hash);
			$sql->execute();

			$this->id_user = $this->db->lastInsertId();

			return true;
		}else{
			return false;
		}
	}
	 
	public function checkCredentials($email, $pass){

		$sql = "SELECT id, pass FROM users WHERE email = :email";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':email', $email);
		$sql->execute();

		if($sql->rowCount() > 0){
			
			$info = $sql->fetch();

			
			if(password_verify($pass, $info['pass'])){
				$this->id_user = $info['id'];
			

				return true;
			}else{
				
				return false;
			}
		}else{
			return false;
		}
	}

	public function getAll() {
		$array = array();

		$sql = "SELECT * FROM usuarios";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			$array = $sql->fetchAll();
		}

		return $array;
	}

	public function getId(){
		return $this->id_user;
	}
	public function getInfo($id){
		$array = array();
		$sql = "SELECT id, name, email, avatar FROM users WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

	
		if($sql->rowCount() > 0){
		
			
			$array = $sql->fetch(\PDO::FETCH_ASSOC);
			
			if(!empty($array['avatar'])){
				$array['avatar'] = BASE_URL.'Media/avatar/'.$array['avatar'];
			}else{
				$array['avatar'] = BASE_URL.'Media/avatar/default.jpg';
			}
			
		}
		
		return $array;
	}

	public function createJwt(){
		$jwt = new Jwt();
		return $jwt->create(array('id_user' => $this->id_user));
	}

	public function validadeJwt($token){
		 $jwt = new Jwt();
		 $info = $jwt->validate($token);

		 if(isset($info->id_user)){
			 $this->id_user = $info->id_user;
			 return true;
		 }else{
			 return false;
		 }
	}

	private function emailExists($email){
		$sql = 'SELECT id FROM users WHERE email = :email';
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':email', $email);
		$sql->execute();

		if($sql->rowCount() > 0){
			return true;
		}else{
			return false;
		}
	}
}