<?php

/**
* 
*/
class ControllerBoard extends MainController{
	
	function __construct($argument=null){
		# code...
	}

	public function index($get){
		$user = $this->loadModel("user");
		if($user->is_login()){
			$user = $this->loadModel("user");
			$this->db_connect();
			$user->link_db = $this->db;
			$user->setonline();
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
				if($_POST){
					$this->viewpage();
				}
				else{
					$this->loadController("header");
					$this->viewpage();
					$this->loadController("footer");
				}
			}
		}
		else{
			header("Location: /diplom");
		}
		
	}
	private function viewpage(){
		$data['pagetitle'] = "Доска событий";
		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$user->link_db = $this->db;
		$project->link_db = $this->db;
		$project->link_user = $user;

		$data['lastmyprojects'] = $project->getmyprojects($user->getUserId(),4);
		$data['lastprojectswhereisset'] = $project->getprojectswhereisset($user->getUserId(),4,1);

		$contacts_list = $user->getContactsList(3);
		$contacts = array();
		if(count($contacts_list)>0){
			for($i=0;$i<count($contacts_list);$i++){
				$login = $user->getLogin($contacts_list[$i]['id_user_added']);
				$contacts[] = array('id'=>$contacts_list[$i]['id_user_added'],'login'=>$login,'info'=>$user->getInformation($contacts_list[$i]['id_user_added']));
			}
		}

		$str = time("Y-m-d");
		$dateunder = date('Y-m-d',($str+86400*2));

		$notification = array();
		$lastnotification = $user->getlastnotification();
		if(!empty($lastnotification)){
			
			$id_item = $lastnotification['id_item'];
			$type = $lastnotification['type'];
			$id = $lastnotification['id'];

			switch ($type) {
				case '1':	
					$id_user = $project->getprojectmanager($id_item);
					$login = $user->getLogin($id_user);
					$title = $project->getProjectTitle($id_item);
					break;
				case '2':
					$_project = $project->getProjectByTaskId($id_item);
					$id_user = $project->getprojectmanager($_project['id']);
					$login = $user->getLogin($id_user);
					$title = $project->getTaskMainTitle($id_item);
					break;
			}
			$notification = array(
				'id'=>$id,
				'id_item'=>$id_item,
				'type'=>$type,
				'login'=>$login,
				'title'=>$title,
				'id_user'=>$id_user
			);
		}
		$data['notification'] = $notification;
		$data['text_login'] = "Пользователь ";
		$data['text_invite'][1] = " приглашает вас в свой проект - ";
		$data['text_invite'][2] = " приглашает вас в задачу - ";

		$data['contacts'] = $contacts;
		$data['text_myprojects'] = "Последние, созданные проекты";
		$data['text_projectswhereisset'] = "Последние принятые приглашения";
		$data['text_lastcontacts'] = "Последние добавленые контакты";
		$data['text_active'][0] = "Проект не начат";
		$data['text_active'][1] = "Проект стартовал";
		$data['text_active'][2] = "Проект окончен";

		$data['text_no_summa'] = "Стоимость не указана";
		$this->outputContent("board-page",$data);
	}
}