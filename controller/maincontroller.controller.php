<?php

/**
* 
*/
class MainController{
	protected $var = array();
	protected $db = null;
	
	function __construct(){


	}
	public function route(){
		if(!empty($_GET['page'])){
			$this->loadController($_GET['page']);
		}
		else{
			$this->loadController("home");
		}
	}

	protected function loadController($controller){
		if(file_exists("controller/".$controller.".controller.php")){
			include_once("controller/".$controller.".controller.php");
			$className = "Controller".ucfirst($controller);
			$obj = new $className();
			$obj->index($_GET);
			/*if($controller=='header' || $controller=='footer'){
				$obj->index();
			}
			else{
				if(empty($_GET['action'])){
					$obj->index();
				}
				else{				
					if(method_exists($obj,$_GET['action'])){
						$obj->$_GET['action']();	
					}			
					else{
						$this->outputContent("404");
					}										
				}	
			}*/
		}	
		else{
			$this->outputContent("404");
		}
	}

	protected function loadModel($model){
		if(file_exists("model/".$model.".class.php")){
			include_once("model/".$model.".class.php");
			$modelName = "Model".ucfirst($model);
			return new $modelName();
		}
	}

	protected function outputContent($template,$arr=null){
		if(!empty($arr)){
			extract($arr,EXTR_SKIP);
		}
		
		include_once("view/".$template.".tpl");
	}

	protected function db_connect(){
		$this->db = $this->loadModel("db");
		$this->db->host = DB_HOST;
		$this->db->user = DB_USER_NAME;
		$this->db->password = DB_USER_PASSWORD;
		$this->db->database = DB_NAME;
		$this->db->connect();
	}

}