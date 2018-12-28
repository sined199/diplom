<?php

/**
* 
*/
class ControllerHeader extends MainController{
	
	function __construct($argument=null)
	{
		# code...
	}
	
	public function index(){
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		
		if($user->is_login()){
			$data['is_login'] = true;
			$data['login'] = $user->getLogin();
			$data['id'] = $user->getUserId();

			$request = "";
			$search_type = "";
			if(!empty($_GET['request'])) $request = $_GET['request'];
			if(!empty($_GET['search_type'])) $search_type = $_GET['search_type'];
			$data['request'] = $request;
			$data['search_type'] = $search_type;	
			$data['countNotifications'] = $user->getCountNotifications();	
		}
		else{
			$data['is_login'] = false;
		}
		$this->outputContent("header",$data);
	}

}