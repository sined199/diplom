<?php

/**
* 
*/
class ControllerHome extends MainController{
	
	function __construct($argument=null)
	{
		# code...
	}
	
	public function index($get=null){

		$user = $this->loadModel("user");
		if($user->is_login()){
			//$data['is_login'] = true;
			
			$this->loadController("board");
		}
		else{
			//$data['is_login'] = false;
			//$this->loadController("auth");
			$this->loadController("header");
			$this->outputcontent("leading-page");
			$this->loadController("footer");
		}
	}


}