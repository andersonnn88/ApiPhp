<?php
namespace Controllers;

use \Core\Controller;
use \Models\Users;


class UsersController extends Controller {

	public function index() {}

	public function login(){
		$array = array('error'=>'');
		$method = $this->getMethod();
		$data = $this->getRequestData();

		if($method == 'POST'){
			
			if(!empty($data['email']) && !empty($data['pass'])){
				$users = new Users();
				
				if($users->checkCredentials($data['email'], $data['pass'])){
					// Gerar o JWT
					$array['jwt'] = $users->createJwt();
				}else{
					$array['error'] = "Acesso  negado";
				}
			}else{
				$array['error'] = "E-mail e/ou senha não preencido";
			}
		}else{
			$array['error'] = "Metodo de requisição incompativel";
		}

		$this->returnJson($array);
	}

	public function new_record(){
		$array  = array('error' => '');
		$method = $this->getMethod();
		$data = $this->getRequestData();
		
		if($method == 'POST'){
			if(!empty($data['name']) && !empty($data['email']) && !empty($data['pass'])){
				if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
					$users = new Users();
					if($users->create($data['name'],$data['email'], $data['pass'])){
						$array['jwt'] = $users->createJwt();
					}else{
						$array['error'] = 'E-mail já existente';
					}
				}else{
					$array['error'] = 'E-mail inválido';
				}
			}else{
				$array['error'] = 'Dados não preenchidos';
			}
		}else{
			$array['error'] = 'Método de requisição incompatível';
		}

		$this->returnJson($array);
	}

	public function view($id){
		$array = array('error'=>'', 'logged'=>false);
		$method = $this->getMethod();
		$data = $this->getRequestData();

		$users = new Users();

		if(!empty($data['jwt']) && $users->validadeJwt($data['jwt'])){
			$array['logged'] = true;
			$array['is_me'] = false;

			if($id == $users->getId()){
				$array['is_me'] = true;	
			}
			switch($method){
				case 'GET':
					$array['data'] = $users->getInfo($id);
					if(count($array['data']) === 0 ){
						$array['error'] = 'Usuário não existe';
					}
					break;
				case 'PUT':
					break;
				case 'DELETE':
					break;
				default:
					$array['error'] = 'Método '.$method.' não disponível';
					break;
			}
		}else{

			$array['error'] = 'Acesso negado';
		}
		$this->returnJson($array);
	}

	

}