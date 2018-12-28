<?php

/**
* 
*/
class ControllerAuth extends MainController
{
	
	public function index($get){
		$user = $this->loadModel("user");
	
		if(!empty($get['action'])){
			if(method_exists($this,$get['action'])){
				if($_POST){
					$this->$get['action']();
				}
				else{
					$this->loadController("header");
					$this->$get['action']();
					$this->loadController("footer");
				}
			}
		}
		else{
			if(!$user->is_login()){
				if($_POST){
					$this->viewpage();
				}
				else{
					$this->loadController("header");
					$this->viewpage();
					$this->loadController("footer");
				}
			}
			else{
				header("Location: /diplom");
			}
		}
	}
	public function viewpage(){
		$data = array();
		$refurl = "";
		if(!empty($_GET['regrefurl'])) $refurl = $_GET['regrefurl'];
		$data['refurl'] = $refurl;
		$this->outputcontent("auth-page",$data);
	}
	public function login(){
		if($_POST){
			$json = array();
			$login = htmlspecialchars(trim($_POST['login']));
			$password = htmlspecialchars(trim($_POST['password']));

			$this->db_connect();
			$user = $this->loadModel("user");
			$user->link_db = $this->db;

			$result = $user->login(array('login'=>$login,'password'=>$password));
			if(!$result['error']){
				$json['error'] = false;
			}
			else{
				$json['error'] = true;
				$json['error_message'] = "Неверный логин или пароль";
			}
			echo json_encode($json);
		}
	}

	public function registration(){
		if($_POST){
			$json = array();
			$this->db_connect();
			$user = $this->loadModel("user");
			$user->link_db = $this->db;
			$user->link_mail = $this->loadModel("mail");

			$refurl = htmlspecialchars(trim($_POST['refurl']));
			$idUserRefurl = 0;
			if(!empty($refurl)){
				$idUserRefurl = $user->getIdUserRefurl($refurl);
			}
			$login = htmlspecialchars(trim($_POST['login']));
			$password = htmlspecialchars(trim($_POST['password']));
			$email = htmlspecialchars(trim($_POST['email']));

			$result = $user->send_regkey_to_mail(array('login'=>$login,'password'=>$password,'email'=>$email,'id_invited'=>$idUserRefurl));
			if(!$result['error']){
				$json['error'] = false;
				$json['code'] = $result['key'];
			}
			else{
				$json['error'] = true;
				switch ($result['error_type']) {
					case 'login':
						$json['error_message'] = "Такой login уже используется.";
						break;
					case 'email':
						$json['error_message'] = "Такой email уже используется.";
						break;
				}
			}
			echo json_encode($json);	
		}
	}

	public function activation(){
		if($_POST){
			$json = array();
			$key = htmlspecialchars(trim($_POST['key']));

			$this->db_connect();
			$user = $this->loadModel("user");
			$user->link_db = $this->db;

			$result = $user->activation($key);
			if(!$result['error']){
				$json['error'] = false;
				$json['message'] = "Аккаунт активирован. Пройдите авторизацию.";
			}
			else{
				$json['error'] = true;
				$json['error_message'] = "Неверный ключ активации!";
			}
			echo json_encode($json);
		}
	}

	public function authexit(){
		$json = array();
		$user = $this->loadModel("user");
		$user->authexit();
		$json['error'] = false;
		echo json_encode($json);
	}

	public function resetpass(){
		if($_POST){
			$json = array();
			$email = htmlspecialchars(trim($_POST['email']));

			$this->db_connect();
			$user = $this->loadModel("user");
			$user->link_db = $this->db;

			$result = $user->check_email($email);

			
			if(!$result){
				$json['error_message'] = "Такого email адреса не существует";
				$json['error'] = true;
			}
			else{
				$result_reset = $user->resetpass($email);
				$json['error'] = false;
				$json['code'] = $result_reset;
			}
			echo json_encode($json);
		}
		else{
			$this->loadController("header");
			if(!empty($_GET['code'])){
				$data = array();
				$code = htmlspecialchars(trim($_GET['code']));

				$this->db_connect();
				$user = $this->loadModel("user");
				$user->link_db = $this->db;

				$result = $user->check_code_resetpass($code);
				$data['result'] = $result;
				$data['code'] = $code;
				$this->outputcontent("auth-page-resetpass_code",$data);
			}
			else{
				$this->outputcontent("auth-page-resetpass");
			}	
			$this->loadController("footer");
		}
	}
	public function setnewpassword(){
		if($_POST){
			$password = htmlspecialchars(trim($_POST['password']));
			$code = htmlspecialchars(trim($_POST['code']));

			$json = array();

			$this->db_connect();
			$user = $this->loadModel("user");
			$user->link_db = $this->db;

			$result = $user->setnewpasswordwithcode($password,$code);

			$json['error'] = !$result;

			echo json_encode($json);
		}
	}
}