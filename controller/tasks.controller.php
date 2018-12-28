<?php 

 /**
 * 
 */
 class ControllerTasks extends MainController
 {

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
		$user->link_db = $this->db;
		$data['pagetitle'] = "Задачи";

		$data['pagetitle_worked'] = "Выполняемые задачи";
		$worked_tasks= $project->getmyworkedtasks($user->getUserid());
		for($i=0;$i<count($worked_tasks);$i++){
			$worked_tasks[$i]['percent_complete'] = $project->getPercentTask($worked_tasks[$i]['id']);
		}
		$data['worked_tasks'] = $worked_tasks;

		$data['pagetitle_completed'] = "Выполненые задачи";
		$completed_tasks= $project->getmycompletedtasks($user->getUserid());
		for($i=0;$i<count($completed_tasks);$i++){
			$completed_tasks[$i]['percent_complete'] = $project->getPercentTask($completed_tasks[$i]['id']);
		}

		$data['completed_tasks'] = $completed_tasks;
		$this->outputcontent("tasks-page",$data);
	}
 }