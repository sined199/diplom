<?php

/**
* 
*/
class ControllerProjects extends MainController{
	
	function __construct($argument=null){
		# code...
	}

	public function index($get){
		$user = $this->loadModel("user");
		if($user->is_login()){
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
			$this->db_connect();
			$user = $this->loadModel("user");
			$project = $this->loadModel("project");
			$project->link_db = $this->db;
			$project->link_user = $user;
			$data['projects'] = $project->getmyprojects($user->getUserId());
			$data['inProjects'] = $project->getprojectswhereisset($user->getUserId());
			$data['pagetitle'] = "Проекты";
			$data['text_myproject'] = "Проекты";
			$data['text_inProjects'] = "Принимаю участие";
			$data['text_active'][0] = "Проект не начат";
			$data['text_active'][1] = "Проект стартовал";
			$data['text_active'][2] = "Проект окончен";
			$this->outputContent("projects-page",$data);
	}
	public function addproject(){
		if($_POST){
			$json = array();
			$arrdata = array();
			$this->db_connect();
			$user = $this->loadModel("user");
			$user->link_db = $this->db;

			$user->setonline();

			foreach ($_POST as $key => $value) {
				$arrdata[$key] = htmlspecialchars(trim($value));
			}
			if(empty($arrdata['date_start'])) $arrdata['date_start'] = date("Y-m-d");
			$arrdata['real_date_start'] = date("Y-m-d");
			
			if(strtotime($arrdata['date_start'])<time("Y-m-d") && $arrdata['date_start']!=date("Y-m-d")){
				$json['error'] = true;
				$json['error_message'] = "Дата начала проекта не может быть раньше сегодняшнего дня";
			}
			else{
				if((strtotime($arrdata['date_end'])-strtotime($arrdata['date_start']))<0 ){
					$json['error'] = true;
					$json['error_message'] = "Дата окончания не может быть ранее начало создания";
				}
				else{
					if((strtotime($arrdata['date_end'])-strtotime($arrdata['date_start']))<=1209600 ){
						$json['error'] = true;
						$json['error_message'] = "Длительность выполнения проекта должно быть более, чем 14 дней";
					}
					else{
						$arrdata['id_user'] = $user->getUserId();

						
						
						$project = $this->loadModel("project");
						$project->link_db = $this->db;

						$arrdata['active'] = (strtotime($arrdata['date_start'])>time("Y-m-d")) ? '0' : '1';

						$result = $project->addproject($arrdata);
						$result['error'] = false;
						if(!$result['error']){
							$json['error'] = false;
							$json['message'] = "Проект создан!";
							
						}
						else{
							$json['error'] = true;
							$json['error_message'] = "Ошибка";
						}
					}
				}
			}
			echo json_encode($json);
		}
	}
	public function addmaintask(){
		if($_POST){
			$json = array();
			
			$this->db_connect();
			
			$project = $this->loadModel("project");
			$user = $this->loadModel("user");
			$project->link_db = $this->db;
			$user->link_db = $this->db;
			$user->setonline();
			if($project->ispermission($user->getUserId(),$_POST['id_project'])){


				$arrdata = array();
				foreach ($_POST as $key => $value) {
					if($key!="task") $arrdata[$key] = htmlspecialchars(trim($value));
					else $arrdata[$key] = $value;
				}
				if($project->isactiveprojectuser($arrdata['user'],$arrdata['id_project'])){
					$date_start = date("Y-m-d");
					$arrdata['date_start'] = $date_start;
					
					$date_end = $project->getDateEnd($_POST['id_project']);

					if((strtotime($arrdata['date_end']) - strtotime($date_end))<0){
						if((strtotime($arrdata['date_end']) - strtotime($date_start))>0){
							$task = $project->addmaintask($arrdata);
							
							$result = $user->addNotification($task['id_task_main'],$arrdata['user'],'2');
							
							if($result){
								$json['error'] = false;
								$json['message'] = "Задача добавлена и выслано приглашение пользователю";
							}
							else{
								$json['error'] = true;
								$json['error_message'] = "Ошибка!";
							}
						}
						else{
							$json['error'] = true;
							$json['error_message'] = "Дата окончания не может быть раньше сегодняшнего дня";
						}
					}
					else{
						$json['error'] = true;
						$json['error_message'] = "Дата окончания не может быть больше даты завершения проекта";
					}
				}
				else{
					$json['error'] = true;
					$json['error_message'] = "Участник не активен";
				}
			}
			else{
				$json['error'] = true;
				$json['error_message'] = "Не ваш проект ".$user->getUserId()." ".$_POST['id_project'];
			}
			echo json_encode($json);
		}
	}
	public function view(){
		$user = $this->loadModel("user");
		if(!empty($_GET['id'])){
			$id_project = htmlspecialchars(trim($_GET['id']));
			$this->db_connect();
			$project = $this->loadModel("project");
			$project->link_db = $this->db;
			$user->link_db = $this->db;
			$user->setonline();
			if($project->p_isset($id_project)){

				if($project->isprojectuser($user->getUserId(),$id_project)){
					if($project->isactiveprojectuser($user->getUserId(),$id_project) || $project->ispermission($user->getUserId(),$id_project)){
						$statustype = 2;
					}
					else{
						if($project->getprivacy($id_project)!=2) $statustype = 1;
						else { $statustype = 2; }
					}
				}
				else{
					$statustype = $project->getprivacy($id_project);
				}

				$data['statustype'] = $statustype;
				$data['gantti'] = "";
				if($statustype==0){
					$data['private_message'] = "Это приватный проект.";
					$data['pagetitle'] = "Приватный проект";
				}
				else{
					$info = $project->getprojectbyid($id_project);
					$id_manager = $project->getprojectmanager($id_project);
					$manager = $user->getLogin($id_manager);
					$data['participants'] = $project->getParticipants($id_project);
					$data['deleteparticipants'] = $project->getDeletedParticipants($id_project);
					$data['title'] = $info['title'];
					$data['manager'] = $manager;
					$data['about'] = $info['about'];
					$data['date_start'] = $info['date_start'];
					$data['date_end'] = $info['date_end'];
					$data['summa'] = $info['summa'];
					$data['position_name'] = $info['name'];
					$data['active'] = $info['active'];
					$data['id_project'] = $id_project;
					$data['id_manager'] = $id_manager;
					$data['percent_complete_project'] = $project->getPercentProject($id_project);
					$data['nodata'] = "Отсутствует";
					switch ($project->getprivacy($id_project)) {
						case '0':
							$data['text_privacy'] = "Приватный тип";
							break;
						case '1':
							$data['text_privacy'] = "Ограниченый тип";
							break;
						case '2':
							$data['text_privacy'] = "Общедоступный тип";
							break;
					}
					$data['text_active'][0] = "Проект не начат";
					$data['text_active'][1] = "Проект стартовал";
					$data['text_active'][2] = "Проект окончен";
					if($statustype==2){
						$tasks_main = $project->gettasksmain($id_project);
						for($i=0;$i<count($tasks_main);$i++){
							$tasks_main[$i]['percent_complete'] = $project->getPercentTask($tasks_main[$i]['id']);
						}
						$data['tasks'] = $tasks_main;
						$tasks_data = array();
						if(count($tasks_main)>0){
							for($i=0;$i<count($tasks_main);$i++){
								if($tasks_main[$i]['status']=='2'){
									$tasks_data[] = array(
										'label' => $tasks_main[$i]['title'],
										'start' => $tasks_main[$i]['date_start'],
										'end'	=> $tasks_main[$i]['date_end']
									);
								}
							}
							if(count($tasks_data)>0){
								require('gantti.php'); 
								$data['gantti'] = new Gantti($tasks_data, array(
								  'title'      => $info['title'],
								  'cellwidth'  => 25,
								  'cellheight' => 35,
								  'today'      => true
								));
							}
						}
					}
					else{
						$data['private_message'] = "К сожалению увас нет прав для просмотра данной информации";
					}
					$data['pagetitle'] = $info['title'];
					$data['id_user'] = $user->getUserId();
				}
				$ispermission = $project->ispermission($user->getUserId(),$id_project);
				
				$data['permission'] = $ispermission;

				if($ispermission){
					$data['participantsNotActive'] = $project->getParticipantsNotActive($id_project);
					$this->outputContent("project-admin-page",$data);
				}
				else{
					$this->outputContent("project-user-page",$data);
				}
			}
			else{
				$this->outputcontent("404");
			}
		}
		else{
			header("Location: /diplom");
		}
	}
	public function viewcontactlist(){
		$id_project = $_POST['id_project'];
		$contacts = array();
		$this->db_connect();
		
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		$contacts_list = $user->getContactsList();
		$participants_list = $project->getParticipants($id_project);

		if(count($contacts_list)>0){
			for($i=0;$i<count($contacts_list);$i++){
				if(!$project->isprojectuser($contacts_list[$i]['id_user_added'],$id_project) && $user->getSettingsInvite($contacts_list[$i]['id_user_added'])==1){
					$login = $user->getLogin($contacts_list[$i]['id_user_added']);
					$contacts[] = array('id'=>$contacts_list[$i]['id_user_added'],'login'=>$login);
				}
			}
		}
		$data['contacts'] = $contacts;
		$data['id_project'] = $id_project;
		$this->outputcontent("modalWins/selectusers",$data);
	}
	public function viewselectedusers(){
		$users_id = $_POST['users_id'];
		$id_project = $_POST['id_project'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		for($i=0;$i<count($users_id);$i++){
			$selected[] = array('id'=>$users_id[$i],'login'=>$user->getLogin($users_id[$i]));
		}
		$data['selected'] = $selected;
		$data['positions'] = $user->getAllPosition($project->getProjectPosition($id_project));
		$data['id_project'] = $id_project;
		$this->outputcontent("modalWins/viewselectedusers",$data);
	}
	public function adduserstoprojects(){
		$json = array();
		$id_project = $_POST['id_project'];
		$selected_users = $_POST['selected_users'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		$project->adduserstoprojects($selected_users,$id_project);

		for($i=0;$i<count($selected_users);$i++){
			$user->addNotification($id_project,$selected_users[$i]['id_user'],'1');
		}
		$json['error'] = false;
		$json['message'] = "Приглашения в проект успешно отправлены";
		echo json_encode($json);	
	}
	public function startproject(){
		$json = array();
		$id_project = $_POST['id_project'];
		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		if($project->ispermission($user->getUserId(),$id_project)){
			if((strtotime($project->getDateEnd($id_project)) - time("Y-m-d")) <= 1209600 ){
				$json['error'] = true;
				$json['error_message'] = "Досрочное начало невозможно. Длительность проекта должно быть более, чем 14 дней";
			}
			else{
				$result = $project->startproject($id_project);
				if($result){
					$json['error'] = false;
					$json['message'] = "Проект досрочно начат!";
				}
				else{
					$json['error'] = true;
					$json['error_message'] = "Ошибка";
				}
			}
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		echo json_encode($json);
	}
	public function stopproject(){
		$json = array();
		$id_project = $_POST['id_project'];
		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		if($project->ispermission($user->getUserId(),$id_project)){
			$date_project_start = strtotime($project->getDateStart($id_project));
			$date_project_end = strtotime($project->getDateEnd($id_project));
			$date_project = $date_project_end - $date_project_start;

			if( (time("Y-m-d")-$date_project_start) < ($date_project/2) ){
				$json['error'] = true;
				$json['error_message'] = "Досрочное завершение невозможно.<br>Для досрочного завершение необходимо, чтобы действующая длительность проекта составляла более, чем половина всего срока действия проекта.";
			}
			else{
				$result = $project->stopproject($id_project);
				$result = true;
				if($result){
					$json['error'] = false;
					$json['message'] = "Проект досрочно завершен!";
				}
				else{
					$json['error'] = true;
					$json['error_message'] = "Ошибка";
				}
			}
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		echo json_encode($json);
	}
	public function deletetask(){
		$json = array();
		$id_task = $_POST['id_task'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		$projects = $project->getProjectByTaskId($id_task);
		if($project->ispermission($user->getUserId(),$projects['id'])){
			$result = $project->deletetask($id_task);
			if($result){
				$json['error'] = false;
				$json['message'] = "Вы успешно удалили задачу";
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
	public function completetask(){
		$json = array();
		$id_task = $_POST['id_task'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		$projects = $project->getProjectByTaskId($id_task);
		if($project->ispermission($user->getUserId(),$projects['id'])){
			$result = $project->completetask($id_task);
			if($result){
				$json['error'] = false;
				$json['message'] = "Вы успешно завершили задачу";
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
	public function deleteuserfromproject(){
		$json = array();
		$id_project = $_POST['id_project'];
		$id_user = $_POST['id_user'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		if($project->ispermission($user->getUserId(),$id_project)){
			$result = $project->getCountIdTasksUserHasTasks($id_user,$id_project);
			if($result==0){
				$result = $project->deleteuserfromproject($id_user,$id_project);
				if($result){
					$json['error'] = false;
					$json['message'] = "Участник переведен в список удаленных";
				}
				else{
					$json['error'] = true;
					$json['error_message'] = "Ошибка";
				}
			}
			else{
				$json['error'] = true;
				$json['error_message'] = "Удаление пользователя невозможно. Минимум в ".$result." задачах учавствует данный пользователь.";
			}
			
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		echo json_encode($json);
	}
	public function invivecanceleduser(){
		$json = array();
		$id_user = $_POST['id_user'];
		$id_project = $_POST['id_project'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		if($project->ispermission($user->getUserId(),$id_project)){
			$result = $project->invivecanceleduser($id_user,$id_project);
			if($result){
				$result = $user->addNotification($id_project,$id_user,'1');
				if($result){
					$json['error'] = false;
					$json['message'] = "Приглашение отправлено";
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
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		echo json_encode($json);
	}
	public function resetuser(){
		$json = array();
		$id_user = $_POST['id_user'];
		$id_project = $_POST['id_project'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		if($project->ispermission($user->getUserId(),$id_project)){
			$result = $project->resetuser($id_user,$id_project);
			if($result){
				$json['error'] = false;
				$json['message'] = "Участник восстановлен";
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
	public function deleteminitask(){
		$json = array();
		$id_minitask = $_POST['id_minitask'];
		$id_task = $_POST['id_task'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		$count = $project->getCountMinitask($id_task);
		if($count==1){
			$json['error'] = true;
			$json['error_message'] = "Должно остаться минимум 1 задача";
		}
		else{
			$result = $project->deleteminitask($id_minitask);
			if($result){
				$json['error'] = false;
				$json['message'] = "Работа удалена";
			}
			else{
				$json['error'] = true;
				$json['error_message'] = "Ошибка";
			}
		}
		
		echo json_encode($json);
	}
	public function completeminitask(){
		$json = array();
		$id_minitask = $_POST['id_minitask'];
		$id_task_main = $_POST['id_task'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;
		$project->link_user = $user;


		$result = $project->completeminitask($id_minitask,$id_task_main,$user->getUserId());
		if($result){
			$json['error'] = false;
			$json['message'] = "Работа завершена";
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		echo json_encode($json);
	}
	public function resetminitask(){
		$json = array();
		$id_minitask = $_POST['id_minitask'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;

		$result = $project->resetminitask($id_minitask);
		if($result){
			$json['error'] = false;
			$json['message'] = "Работа восстановлена";
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Ошибка";
		}
		echo json_encode($json);
	}
	public function saveanotheruser(){
		$json = array();
		$id_task = $_POST['id_task'];
		$id_user = $_POST['id_user'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;
		$id_worker = $project->gettaskworker($id_task);
		if($id_worker != $id_user){
			$result = $project->saveanotheruser($id_task,$id_user);
			if($result){
				$result = $user->addNotification($id_task,$id_user,'2');
				if($result){
					$json['error'] = false;
					$json['message'] = "Приглашение в задачу отправлено";
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
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Выберите другого исполнителя";
		}

		echo json_encode($json);
	}
	public function deleteproject(){
		$id_project = $_POST['id_project'];

		$this->db_connect();
		$user = $this->loadModel("user");
		$project = $this->loadModel("project");
		$project->link_db = $this->db;
		$user->link_db = $this->db;
		$datenow = time("Y-m-d");

		$dateproject = strtotime($project->getDateEnd($id_project))+172800;

		if($datenow>$dateproject){
			if($project->deleteproject($id_project)){
				$json['error'] = false;
				$json['message'] = "Удаление произошло успешно";
			}
			else{
				$json['error'] = true;
				$json['error_message'] = "Ошибка";
			}
		}
		else{
			$json['error'] = true;
			$json['error_message'] = "Удалить проект можно лишь спустя 2 дня после его завершения";
		}
		echo json_encode($json);
	}
}