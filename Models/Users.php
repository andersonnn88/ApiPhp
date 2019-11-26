<?php
namespace Models;

use \Core\Model;
use \Models\Jwt;

class Users extends Model {

	private $id_user;
	 
	public function checkCredentials($email, $pass){

		$sql = "SELECT id, pass FROM users WHERE email = :email";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':email', $email);
		$sql->execute();

		if($sql->rowCount() > 0){
			
			$info = $sql->fetch();

			
			//if(password_verify($pass, $info['pass'])){
				if($pass == $info['pass']){
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

	public function createJwt(){
		$jwt = new Jwt();
		return $jwt->create(array('id_user' => $this->id_user));
	}
}