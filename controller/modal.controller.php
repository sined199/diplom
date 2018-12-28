<?php

/**
* 
*/
class ControllerModal extends MainController{
	
	function __construct($argument=null){
		# code...
	}

	public function index($get){
		//header("Location: /diplom");
		if(!empty($get['action'])){
			if(method_exists($this,$get['action'])){
				if($_POST){
					$this->$get['action']();
				}
				else{
					header("Location: /diplom");
				}
			}
		}
	}
	public function loadmodal(){
		$modal = htmlspecialchars(trim($_POST['modal']));
		$data = array();
		switch ($modal) {
			case 'addproject':
				$user = $this->loadModel("user");
				$this->db_connect();
				$user->link_db = $this->db;
				$data['positions'] = $user->getAllMainPosition();
				echo $this->outputcontent("/modalWins/".$modal,$data);
				break;
			
			case 'addmaintask':
				$json = array();
				$id_project = $_POST['id_project'];
				$project = $this->loadModel("project");
				$this->db_connect();
				$project->link_db = $this->db;

				$users = $project->getParticipants($id_project);
				if(count($users)>0){
					$data['users'] = $users;
					$data['id_project'] = $id_project;
					ob_start();
					echo $this->outputcontent("/modalWins/".$modal,$data);
					$json['html'] = ob_get_clean();
					$json['error'] = false;
				}
				else{
					$json['error'] = true;
					$json['error_message'] = "Пригласите хоть 1 участника";
				}
				echo json_encode($json);
				break;

			case 'viewtask':
				$json = array();
				$data = array();
				$id_task = $_POST['id_task'];

				$this->db_connect();
				$user = $this->loadModel("user");
				$project = $this->loadModel("project");
				$project->link_db = $this->db;
				$user->link_db = $this->db;

				$id_user = $user->getUserId();

				$data['nodata'] = "Отсутствует";
				$projects = $project->getProjectByTaskId($id_task);
				if($project->ispermission($id_user,$projects['id'])){
					$json['error'] = false;
					$data['tasks'] = $project->gettasks($id_task);
					$data['id_task'] = $id_task;
					$data['id_project'] = $projects['id'];
					$data['status_project'] = $projects['active'];
					$data['title_project'] = $projects['title'];
					$data['info'] = $project->gettaskinfo($id_task);
					$data['login'] = $user->getLogin($data['info']['id_user']);
					$data['percent_complete'] = $project->getPercentTask($id_task);
					$data['text_privacy'][0] = "Закрытый";
					$data['text_privacy'][1] = "Ограниченый";
					$data['text_privacy'][2] = "Общедоступный";

					$data['text_status_user'][0] = "Приглашение отправлено";
					$data['text_status_user'][1] = "Принял приглашение";
					$data['text_status_user'][2] = "Отменил приглашение";

					$data['text_status_task'][0] = "В режиме ожидания";
					$data['text_status_task'][1] = "Активно";
					$data['text_status_task'][2] = "Завершено";
					ob_start();
					echo $this->outputcontent("/modalWins/viewtaskinfo-admin",$data);
					$json['html'] = ob_get_clean();
				}
				else{
					$privacy = null;
					$userstatus = null;
					$taskprivacy = null;
					$json['error'] = false;



					$data['tasks'] = $project->gettasks($id_task);
					$data['id_task'] = $id_task;
					$data['id_project'] = $projects['id'];
					$data['title_project'] = $projects['title'];
					$data['info'] = $project->gettaskinfo($id_task);
					$data['login'] = $user->getLogin($data['info']['id_user']);
					$data['status_project'] = $projects['active'];
					$data['privacy_project'] = $projects['privacy'];
					$data['percent_complete'] = $project->getPercentTask($id_task);

					$userstatus = $project->getStatusUserTask($user->getUserId(),$id_task);
					$taskprivacy = $project->getprivacytask($id_task);
					$data['userstatus'] = $userstatus;
					$data['taskprivacy'] = $taskprivacy;
					if($userstatus!=null){
						switch ($userstatus) {
							case '0':
								switch ($taskprivacy) {
									case '0':
										$privacy = 1;
										break;
									default:
										$privacy = $taskprivacy;
										break;
								}
								break;
							case '1':
								$privacy = 2;
								break;
							case '2':
								$privacy = $taskprivacy;
								break;
						}
					}
					else{
						//if($data['privacy_project']==2){
							$privacy = $taskprivacy;
						//}	
					}

					$data['privacy'] = $privacy;

					if(!empty($userstatus) && $userstatus==1){
						$data['user_permission'] = true;
					}
					else{
						$data['user_permission'] = false;
					}

					$data['text_privacy_warning'][0] = "Это приватная задача.<br>Просмотр недоступен";
					$data['text_privacy_warning'][1] = "У вас нет прав, для просмотра данной информации.";


					$data['text_privacy'][0] = "Закрытый";
					$data['text_privacy'][1] = "Ограниченый";
					$data['text_privacy'][2] = "Общедоступный";

					$data['text_status_user'][0] = "Приглашение отправлено";
					$data['text_status_user'][1] = "Принял приглашение";
					$data['text_status_user'][2] = "Отменил приглашение";

					$data['text_status_task'][0] = "В режиме ожидания";
					$data['text_status_task'][1] = "Активно";
					$data['text_status_task'][2] = "Завершено";
					ob_start();
					echo $this->outputcontent("/modalWins/viewtaskinfo-user",$data);
					$json['html'] = ob_get_clean();
				}

				echo json_encode($json);
				break;

			case 'selectanotheruser':
				$json = array();
				$data = array();

				$id_task = $_POST['id_task'];

				$this->db_connect();
				$user = $this->loadModel("user");
				$project = $this->loadModel("project");
				$project->link_db = $this->db;
				$user->link_db = $this->db;

				$data['id_user'] = $project->gettaskworker($id_task);
				
				$project_info = $project->getProjectByTaskId($id_task);
				$data['users'] = $project->getParticipants($project_info['id']);
				$data['id_task'] = $id_task;

				$json['error'] = false;
				ob_start();
				echo $this->outputcontent("/modalWins/selectanotheruser",$data);
				$json['html'] = ob_get_clean();

				echo json_encode($json);
				break;
			case 'question':
			$json = array();
				switch ($_POST['question_type']) {
					case 'privacy_project':
						ob_start();
						echo $this->outputcontent("/modalWins/privacy_project");
						$json['html'] = ob_get_clean();
						break;
					case 'privacy_task':
						ob_start();
						echo $this->outputcontent("/modalWins/privacy_task");
						$json['html'] = ob_get_clean();
						break;
					case 'active_project':
						ob_start();
						echo $this->outputcontent("/modalWins/active_project");
						$json['html'] = ob_get_clean();
						break;
				}
				echo json_encode($json);
				break;
		}
		
	}
}