<?php

/**
* 
*/
class ControllerUser extends MainController
{
	
	function __construct($argument=null)
	{
		# code...
	}

	public function index($get){
		$user = $this->loadModel("user");
		if($user->is_login()){
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
		$data = array();
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		$user->setonline();
		if(empty($_GET['user_id']) || (!empty($_GET['user_id']) && $_GET['user_id'] == $user->getUserId())){
			$data['pagetitle'] = "Мой профиль";
			$data['key'] = $user->getrefurl();
			if(!empty($data['key'])) $data['url'] = "http://".$_SERVER['HTTP_HOST']."/diplom/auth?regrefurl=".$data['key'];
			
			$data['invited'] = $user->getInvited();
			$data['countInvited'] = count($data['invited']);

			$data['info'] = $user->getInformation($user->getUserId());
			$data['login'] = $user->getLogin($user->getUserId());

			$statistics = $user->getStatistics($user->getUserId(),1);
			$projects = $statistics['projects'];
			$data['statistics']['projects']['allcount'] = count($projects);
			$data['statistics']['projects']['maincount'] = 0;
			$data['statistics']['projects']['readycount'] = 0;
			for($i=0;$i<count($projects);$i++){
				if($projects[$i]['id_position']==0) $data['statistics']['projects']['maincount']++;
				if($projects[$i]['active'] == 2) $data['statistics']['projects']['readycount']++;
			}
			$tasks = $statistics['tasks'];
			$data['statistics']['tasks']['allcount'] = count($tasks);
			$data['statistics']['tasks']['readycount'] = 0;
			for($i=0;$i<count($tasks);$i++){
				if($projects[$i]['status'] == 2) $data['statistics']['tasks']['readycount']++;
			}
			$data['count_contacts'] = $user->getcountcontacts();
			$data['is_online'] = $user->isonline();
			$this->outputcontent("user-myself-page",$data);
		}
		else{
			$data['id'] = htmlspecialchars(trim($_GET['user_id']));
			$login = $user->getLogin($_GET['user_id']);
			$data['pagetitle'] = "Профиль ".$login;
			$data['info'] = $user->getInformation($data['id']);
			$data['login'] = $user->getLogin($data['id']);
			$setting = $user->getSettings($data['id']);
			$data['statistics'] = null;
			if($setting['view_statistics'] == 1){ 
				$statistics = $user->getStatistics($data['id'],0);
				$projects = $statistics['projects'];
				$data['statistics']['projects']['allcount'] = count($projects);
				$data['statistics']['projects']['maincount'] = 0;
				$data['statistics']['projects']['readycount'] = 0;
				for($i=0;$i<count($projects);$i++){
					if($projects[$i]['id_position']==0) $data['statistics']['projects']['maincount']++;
					if($projects[$i]['active'] == 2) $data['statistics']['projects']['readycount']++;
				}
				$tasks = $statistics['tasks'];
				$data['statistics']['tasks']['allcount'] = count($tasks);
				$data['statistics']['tasks']['readycount'] = 0;
				for($i=0;$i<count($tasks);$i++){
					if($projects[$i]['status'] == 2) $data['statistics']['tasks']['readycount']++;
				}
			}
			$this->outputcontent("user-byid-page",$data);
		}
	}
	public function edit(){
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		$user->setonline();
		$data['info'] = $user->getInformation();
		$data['positions'] = $user->getUserPositions();
		$data['allpositions'] = $user->getAllPosition();
		$data['pagetitle'] = "Изменить профиль";
		$this->outputcontent("user-edit-profile-page",$data);
	}
	public function settings(){
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		$user->setonline();
		$data['settings'] = $user->getSettings();
		$data['pagetitle'] = "Настройки";
		$this->outputcontent("user-settings-page",$data);
	}
	public function contacts(){
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		$user->setonline();
		$contacts = array();
		$contacts_list = $user->getContactsList();
		if(count($contacts_list)>0){
			for($i=0;$i<count($contacts_list);$i++){
				$login = $user->getLogin($contacts_list[$i]['id_user_added']);
				$contacts[] = array('id'=>$contacts_list[$i]['id_user_added'],'login'=>$login,'info'=>$user->getInformation($contacts_list[$i]['id_user_added']));
			}
		}
		$data['pagetitle'] = "Контакты";
		$data['contacts'] = $contacts;
		$data['count_contacts'] = $user->getcountcontacts();
		$this->outputcontent("user-contacts-page",$data);

	}

	public function createrefurl(){
		$json = array();
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		$result = $user->generaterefurl();
		if($result){
			$json['error'] = false;
			$json['message'] = "Реферальна ссылка создана";
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Error";
		}
		echo json_encode($json);
	}
	public function editinfo(){
		$json = array();
		$item = $_POST['item'];
		$text = $_POST['text'];
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		$result = $user->editinfo($item,$text);
		//$result = true;
		if($result){
			$json['error'] = false;
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		echo json_encode($json);
	}

	public function editsettings(){
		$json = array();
		$search_user = ($_POST['search_user']=="true") ? "1" : "0";
		$view_statistics = ($_POST['view_statistics']=="true") ? "1" : "0";
		$send_invite = ($_POST['send_invite']=="true") ? "1" : "0";
		$hidden_profile = ($_POST['hidden_profile']=="true") ? "1" : "0";
		$mail_invite = ($_POST['mail_invite']=="true") ? "1" : "0";
		$mail_new_ads = ($_POST['mail_new_ads']=="true") ? "1" : "0";
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		$result = $user->editsettings(array(
			'search_user'=>$search_user,
			'view_statistics'=>$view_statistics,
			'send_invite'=>$send_invite,
			'hidden_profile'=>$hidden_profile,
			'mail_invite'=>$mail_invite,
			'mail_new_ads'=>$mail_new_ads
			));
		if($result){
			$json['error'] = false;
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		echo json_encode($json);
	}
	public function deleteuserposition(){
		$json = array();
		$id_position = $_POST['id_position'];
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		if($user->userIsHavePositionByListId($user->getUserId(),$id_position)){
			$result = $user->deleteuserposition($id_position);
			if($result){
				$json['error'] = false;
			}
			else{
				$json['error'] = true;
				$json['error_message'] = "Ошибка";
			}
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		echo json_encode($json);
	}
	
	public function getAllListPositionsForUser(){
		$json = array();
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		$result = $user->getAllPosition();
		$userPositions = $user->getUserPositions();

		$arr_idsUserPositions = array();
		for($i=0;$i<count($userPositions);$i++){
			$arr_idsUserPositions[] = $userPositions[$i]['id_position'];
		}
		
		for($i=0;$i<count($result);$i++){
			if(in_array($result[$i]['id'], $arr_idsUserPositions)){
				$json['allpositions'][] = array('id' => $result[$i]['id'],'name' => $result[$i]['name'],'id_parent' => $result[$i]['id_parent'], 'equal'=>true);
			}
			else{
				$json['allpositions'][] = array('id' => $result[$i]['id'],'name' => $result[$i]['name'],'id_parent' => $result[$i]['id_parent'], 'equal'=>false);
			}
		}

		$json['error'] = false;
		echo json_encode($json);
	}
	public function addUserPosition(){
		$json = array();
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		$result = $user->addUserPosition($_POST['list_positions']);
		if($result){
			$json['error'] = false;
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		echo json_encode($json);
	}
	public function addToContact(){
		$json = array();
		$temp_arr = array();
		$id_user_contact = $_POST['id_user_contact'];
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;

		if(!$user->checkProfileInContactsList($id_user_contact)){
			$result = $user->addToContact($id_user_contact);

			if($result){
				$json['error'] = false;
				$json['message'] = "Новый контакт добавлен";
			}
			else{
				$json['error'] = true;
				$json['error_message'] = "Ошибка";
			}
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		
		echo json_encode($json);
	}
	public function deletefromcontacts(){
		$json = array();
		$id_user_contact = $_POST['id_user'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;

		$result = $user->deleteFromContacts($id_user_contact);
		if($result){
			$json['error'] = false;
			$json['message'] = "Контакт удален";
			$json['count'] = $user->getcountcontacts();
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Fail";
		}

		echo json_encode($json);
	}
	public function checkNotifications(){
		$json = array();
		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		if($user->is_login()){
			$result = $user->checkLastNotifications();
			if(!empty($result) && count($result)>0){
				$data = array();
				$json['error'] = false;
				$json['count'] = $user->getCountNotifications();
				switch ($result[0]['type']) {
					case '1':
						$data['id_notification'] = $result[0]['id'];
						$data['id_project'] = $result[0]['id_item'];
						$data['id_user'] = $project->getprojectmanager($result[0]['id_item']);
						$data['login'] = $user->getLogin($data['id_user']);
						$data['title'] = $project->getProjectTitle($result[0]['id_item']);
						ob_start();
						$this->outputcontent("/modalWins/viewnotificationproject",$data);
						$json['html'] = ob_get_clean();
						$json['notific_browser'] = "У вас новое приглашение в проект.";
						break;
					case '2':
						$data['id_notification'] = $result[0]['id'];
						$data['id_main_task'] = $result[0]['id_item'];
						$data['project'] = $project->getProjectByTaskId($result[0]['id_item']);
						$data['id_project'] = $data['project']['id'];
						$data['id_user'] = $project->getprojectmanager($data['id_project']);
						$data['login'] = $user->getLogin($data['id_user']);
						$data['title'] = $project->getTaskMainTitle($result[0]['id_item']);
						ob_start();
						$this->outputcontent("/modalWins/viewnotificationtask",$data);
						$json['html'] = ob_get_clean();
						$json['notific_browser'] = "У вас новое приглашение в задачу.";
						break;
					case '3':
						$data['id_notification'] = $result[0]['id'];
						$data['id_main_task'] = $result[0]['id_item'];
						$data['project'] = $project->getProjectByTaskId($result[0]['id_item']);
						$data['id_project'] = $data['project']['id'];
						$data['title'] = $project->getTaskMainTitle($result[0]['id_item']);

						ob_start();
						$this->outputcontent("/modalWins/viewnotificationevent",$data);
						$json['html'] = ob_get_clean();
						$json['notific_browser'] = "У вас новое оповещение.";

						break;
				}	
			}
			else{
				$json['error'] = true;
			}
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Not login is system";
		}
		
		echo json_encode($json);
	}
	public function acceptedinvite(){
		$json = array();
		$id_notification = $_POST['id_notification'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;

		$type = $user->gettypenotification($id_notification);
		$id_item  = $user->getitemnotification($id_notification);
		switch ($type) {
			case '1':
				$result = $user->acceptproject($id_item);
				if($result){
					$json['error'] = false;
					$json['message'] = "Вы приняли приглашение";
					$json['count'] = $user->getCountNotifications();
				}
				else{
					$json['error'] = true;
					$json['error_message'] = "error";

				}
				break;
			
			case '2':
				$result = $user->accepttaskmain($id_item);
				if($result){
					$json['error'] = false;
					$json['message'] = "Вы приняли приглашение";
					$json['count'] = $user->getCountNotifications();
				}
				else{
					$json['error'] = true;
					$json['error_message'] = "error";

				}
				break;
		}
		echo json_encode($json);

	}
	public function canceledinvite(){
		$json = array();
		$id_notification = $_POST['id_notification'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$user->link_db = $this->db;

		$type = $user->gettypenotification($id_notification);
		$id_item  = $user->getitemnotification($id_notification);
		switch ($type) {
			case '1':
				$result = $user->cancelproject($id_item);
				if($result){
					$json['error'] = false;
					$json['message'] = "Вы отклонили приглашение";
					$json['count'] = $user->getCountNotifications();
				}
				else{
					$json['error'] = true;
					$json['error_message'] = "error";

				}
				break;
			
			case '2':
				$result = $user->canceltaskmain($id_item);
				if($result){
					$json['error'] = false;
					$json['message'] = "Вы приняли приглашение";
					$json['count'] = $user->getCountNotifications();
				}
				else{
					$json['error'] = true;
					$json['error_message'] = "error";

				}
				break;
		}
		echo json_encode($json);

	}
	public function getallnotifications(){
		$data = array();
		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$user->link_db = $this->db;
		$project->link_db = $this->db;

		$result = $user->getallnotifications();
		$notifications = array();
		foreach ($result as $key){
			$login = null;
			$id_user = null;
			$id_item = $key['id_item'];
			$type = $key['type'];
			$id_notification = $key['id'];

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
				case '3':
					$title = $project->getTaskMainTitle($id_item);
					break;
			}
			$notifications[] = array(
				'id_item'=>$id_item,
				'type'=>$type,
				'login'=>$login,
				'title'=>$title,
				'id_user'=>$id_user,
				'id_notification'=>$id_notification
			);
		}
		$data['text_invite'][1] = " приглашает вас в свой проект - ";
		$data['text_invite'][2] = " приглашает вас в задачу - ";
		$data['text_event'][3][0] = "В задаче ";
		$data['text_event'][3][1] = " выполнены все работы";
		$data['notifications'] = $notifications;
		$this->outputcontent("modalWins/viewnotification",$data);
	}
	public function deletenotification(){
		$id_notification = $_POST['id_notification'];

		$json = array();
		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$user->link_db = $this->db;
		$project->link_db = $this->db;

		$result = $user->deletenotification($id_notification);
		if($result){
			$json['count'] = $user->getCountNotifications();
			$json['error'] = false;
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}

		echo json_encode($json);

	}

}