<?php

/**
* 
*/
class ModelProject
{
	public $link_db=null;
	public $link_user=null;

	function __construct($argument = null)
	{
		# code...
	}

	public function addproject($arrdata){
		$res = array();
		$keys = array();
		$values = array();
		$id_user = $arrdata['id_user'];
		$id_position = $arrdata['id_position'];
		foreach ($arrdata as $key => $value) {
			if($key!="id_user" && $key!="id_position"){
				$keys[] = $key;
				$values[] = $value;
			}
		}	
		$this->link_db->insert("projects",$values,$keys);
		$last_id = $this->link_db->select("id",'projects','','','id DESC','1');
		$datenow = date("Y-m-d");
		$this->link_db->insert("projects_users",array($last_id[0]['id'],$id_user,'0','1','1',$datenow),array('id_project','id_user','id_position','status','permission','date_event'));
		$this->link_db->insert("position_projects",array($last_id[0]['id'],$id_position),array("id_project","id_position"));
		$res['error'] = false;
		
		return $res;
	}

	public function addmaintask($arrdata){
		//print_r($arrdata);
		$res = array();
		$keys = array();
		$values = array();
		//$id_user = $arrdata['id_user'];
		foreach ($arrdata as $key => $value) {
			if($key!="user" && $key!="task"){
				$keys[] = $key;
				$values[] = $value;
			}
		}
		$id_user = $arrdata['user'];
		$tasks = (!empty($arrdata['task'])) ? $arrdata['task'] : array();
		$this->link_db->insert("tasks_main",$values,$keys);

		$date = date("Y-m-d");
		$last_id = $this->link_db->select("id","tasks_main","","","id DESC","1");
		$this->link_db->insert("task_main_users",array($last_id[0]['id'],$id_user,'0',$date),array("id_task_main","id_user","status",'date_event'));
		if(count($tasks)>0){
			foreach($tasks as $key => $value){
				$this->link_db->insert("tasks",array($value,$last_id[0]['id'],'0'),array("title","id_task_main","completed"));
			}
		}
		$task['id_task_main'] = $last_id[0]['id'];
		return $task;

	}

	public function getmyprojects($id_user,$limit_count=null){
		$res = array();
		$limit = (!empty($limit_count)) ? $limit_count : "";
		$this->link_user->link_db = $this->link_db;
		$result = $this->link_db->select("id_project","projects_users","`id_user` like '".$id_user."' AND permission = 1","","id DESC",$limit);
		for ($i=0; $i < count($result); $i++) { 
			$res[$i]['project'] = $this->getprojectbyid($result[$i]['id_project']);
			$res[$i]['manager'] = $this->link_user->getLogin($this->getprojectmanager($result[$i]['id_project']));
		}
		return $res;
	}
	public function getprojectswhereisset($id_user,$limit_count=null){
		$res = array();
		$limit = (!empty($limit_count)) ? $limit_count : "";
		$this->link_user->link_db = $this->link_db;
		$result = $this->link_db->select("id_project","projects_users","`id_user` like '".$id_user."' AND permission = 0 AND status = 1","","id DESC",$limit);
		for ($i=0; $i < count($result); $i++) { 
			$res[$i]['project'] = $this->getprojectbyid($result[$i]['id_project']);
			$res[$i]['manager'] = $this->link_user->getLogin($this->getprojectmanager($result[$i]['id_project']));
		}
		return $res;
	}
	public function gettasksmain($id_project){
		$result = $this->link_db->select("tasks_main.title, tasks_main.about, tasks_main.comment, tasks_main.id, tasks_main.status, tasks_main.privacy, tasks_main.date_start, tasks_main.date_end, task_main_users.id_user, task_main_users.status as status_user","tasks_main LEFT JOIN task_main_users ON task_main_users.id_task_main = tasks_main.id","`id_project` like '".$id_project."'","","id DESC");
		return $result;
	}
	public function getmyworkedtasks($id_user){
		$result = $this->link_db->select("tasks_main.title, tasks_main.about, tasks_main.comment, tasks_main.id, tasks_main.status, tasks_main.privacy, tasks_main.date_end, task_main_users.id_user, task_main_users.status as status_user","tasks_main LEFT JOIN task_main_users ON task_main_users.id_task_main = tasks_main.id","tasks_main.status = 1 AND task_main_users.id_user = ".$id_user,"","id DESC");
		return $result;
	}
	public function getmycompletedtasks($id_user){
		$result = $this->link_db->select("tasks_main.title, tasks_main.about, tasks_main.comment, tasks_main.id, tasks_main.status, tasks_main.privacy, tasks_main.date_end, task_main_users.id_user, task_main_users.status as status_user","tasks_main LEFT JOIN task_main_users ON task_main_users.id_task_main = tasks_main.id","tasks_main.status = 2 AND task_main_users.id_user = ".$id_user,"","id DESC");
		return $result;
	}
	public function gettaskinfo($id_task_main){
		$result = $this->link_db->select("tasks_main.title, tasks_main.about, tasks_main.comment, tasks_main.id, tasks_main.status, tasks_main.privacy, tasks_main.date_end, task_main_users.id_user, task_main_users.status as status_user, position_list.name","tasks_main LEFT JOIN task_main_users ON task_main_users.id_task_main = tasks_main.id LEFT JOIN projects_users ON projects_users.id_user = task_main_users.id_user LEFT JOIN position_list ON position_list.id = projects_users.id_position","tasks_main.id = ".$id_task_main." AND projects_users.permission <> 1");
		return $result[0];
	}
	public function gettasks($id_task_main){
		$result = $this->link_db->select("*","tasks","id_task_main = ".$id_task_main);
		return (!empty($result)) ? $result : null;
	}
	public function getprojectmanager($id_project){
		$result = $this->link_db->select("id_user","projects_users","`id_project` like '".$id_project."' AND permission = 1");
		return $result[0]['id_user'];
	}
	public function gettaskworker($id_task){
		$result = $this->link_db->select("id_user","task_main_users","id_task_main = ".$id_task);
		return $result[0]['id_user'];
	}
	public function getprojectbyid($id_project){
		$result = $this->link_db->select("projects.id,projects.title,projects.about,projects.summa,projects.date_start,projects.date_end,projects.active,projects.privacy,position_list.name","projects LEFT JOIN position_projects ON position_projects.id_project = projects.id LEFT JOIN position_list ON position_list.id = position_projects.id_position","projects.id = ".$id_project);
		return $result[0];
	}
	public function getParticipants($id_project){
		$result = $this->link_db->select("projects_users.id, users.id as id_user, users.login, position_list.name as position_name","projects_users LEFT JOIN position_list ON projects_users.id_position = position_list.id LEFT JOIN users ON users.id = projects_users.id_user","projects_users.id_project = ".$id_project." AND projects_users.status = 1 AND projects_users.permission = 0");
		return (empty($result)) ? array() : $result;
	}
	public function getParticipantsNotActive($id_project){
		$result = $this->link_db->select("projects_users.id, users.id as id_user, users.login, projects_users.status, position_list.name as position_name","projects_users LEFT JOIN position_list ON projects_users.id_position = position_list.id LEFT JOIN users ON users.id = projects_users.id_user","projects_users.id_project = ".$id_project." AND projects_users.status <> 1 AND projects_users.status <> 3 AND projects_users.permission = 0");
		return (empty($result)) ? array() : $result;
	}
	public function getDeletedParticipants($id_project){
		$result = $this->link_db->select("projects_users.id, users.id as id_user, users.login, projects_users.status, position_list.name as position_name","projects_users LEFT JOIN position_list ON projects_users.id_position = position_list.id LEFT JOIN users ON users.id = projects_users.id_user","projects_users.id_project = ".$id_project." AND projects_users.status = 3 AND projects_users.permission = 0");
		return (empty($result)) ? array() : $result;
	}
	public function ispermission($id_user,$id_project){
		$result = $this->link_db->select("id","projects_users","`id_user` like '".$id_user."' AND `id_project` like '".$id_project."' AND `permission` like '1'");
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}
	public function isprojectuser($id_user,$id_project){
		$result = $this->link_db->select("id","projects_users","`id_user` like '".$id_user."' AND `id_project` like '".$id_project."' AND status <> 2");
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}
	public function isactiveprojectuser($id_user,$id_project){
		$result = $this->link_db->select("id","projects_users","`id_user` like '".$id_user."' AND `id_project` like '".$id_project."' AND `status` like '1'");
		if(!empty($result)){
			return true;
		}else{
			return false;
		}
	}
	public function getprivacy($id_project){
		$result = $this->link_db->select("privacy","projects","`id` like '".$id_project."'");
		return $result[0]['privacy'];
	}
	public function getProjectPosition($id_project){
		$result = $this->link_db->select("id_position","position_projects","`id_project` like '".$id_project."'");
		return $result[0]['id_position'];
	}
	public function adduserstoprojects($selected_users,$id_project){
		for ($i=0; $i < count($selected_users); $i++) { 
			$date = date("Y-m-d");
			$count = $this->link_db->select("count(*)","projects_users","id_user = ".$selected_users[$i]['id_user']." AND id_project = ".$id_project);
			if($count[0]['count(*)']==0){
				$this->link_db->insert("projects_users",array($id_project,$selected_users[$i]['id_user'],$selected_users[$i]['id_position'],$date),array('id_project','id_user','id_position','date_event'));
			}
		}
	}
	public function getProjectTitle($id_project){
		$result = $this->link_db->select("title","projects","id = ".$id_project);
		return $result[0]['title'];
	}
	public function getTaskMainTitle($id_task_main){
		$result = $this->link_db->select("title","tasks_main","id = ".$id_task_main);
		return $result[0]['title'];
	}
	public function getDateEnd($id_project){
		$result = $this->link_db->select("date_end","projects","id = ".$id_project);
		return $result[0]['date_end'];
	}
	public function getDateStart($id_project){
		$result = $this->link_db->select("date_start","projects","id = ".$id_project);
		return $result[0]['date_start'];
	}
	public function startproject($id_project){
		$datenow = date("Y-m-d");
		$result = $this->link_db->update("projects",array("date_start","active"),array($datenow,"1"),"id = ".$id_project);
		return $result;
	}
	public function stopproject($id_project){
		$datenow = date("Y-m-d");
		if($this->isactiveproject($id_project)){
			$this->link_db->update("projects",array("active","date_end"),array("2",$datenow),"id = ".$id_project);
			$tasks = $this->gettasksmain($id_project);
			if(count($tasks)>0){
				foreach($tasks as $key){
					$this->link_db->update("tasks_main",array("date_end","status"),array($datenow,"2"),"id = ".$key['id']);
					$this->link_db->delete("notifications","id_item = ".$key['id']." AND type = 2");
				}
			}
			$this->link_db->delete("notifications","id_item = ".$id_project." AND type = 1");
			$this->link_db->delete("projects_users","id_project = ".$id_project." AND status = 0 AND permission = 0");
		}
		return true;
	}
	public function getProjectByTaskId($id_task_main){
		$result = $this->link_db->select("projects.id,projects.title,projects.active,projects.privacy","projects LEFT JOIN tasks_main ON tasks_main.id_project = projects.id","tasks_main.id = ".$id_task_main,"projects.id");
		return $result[0];
	}
	public function isactiveproject($id_project){
		$result = $this->link_db->select("active","projects","id = ".$id_project);
		if($result[0]['active']==1) return true;
		else return false;
	}
	public function deletetask($id_task_main){
		$result = $this->link_db->delete("tasks_main","id = ".$id_task_main);
		if($result){
			$result = $this->link_db->delete("tasks","id_task_main = ".$id_task_main);
			if($result){
				$result = $this->link_db->delete("task_main_users","id_task_main = ".$id_task_main);
				if($result){
					$result = $this->link_db->delete("notifications","id_item = ".$id_task_main." AND type = 2");
					return $result;
				}
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	public function deleteproject($id_project){
		$tasks = $this->gettasksmain($id_project);
		if(!empty($tasks)){
			foreach ($tasks as $key) {
				$this->deletetask($key['id']);
			}
		}
		$this->link_db->delete("projects","id = ".$id_project);
		$this->link_db->delete("projects_users","id_project = ".$id_project);
		$this->link_db->delete("position_projects","id_project = ".$id_project);
		$this->link_db->delete("notifications","id_item = ".$id_project." AND type = 1");
		return true;
	}

	public function completetask($id_task){
		$datenow = date("Y-m-d");
		$result = $this->link_db->update("tasks_main",array("status","date_end"),array("2",$datenow),"id = ".$id_task);
		if($result){
			$result = $this->link_db->delete("notifications","id_item = ".$id_task." AND type = 2");
			return $result;
		}
	}
	public function p_isset($id_project){
		$result = $this->link_db->select("id","projects","id = ".$id_project);
		if(count($result)>0) return true;
		else return false;
	}
	public function deleteuserfromproject($id_user,$id_project){
		$date = date("Y-m-d");
		$status = $this->link_db->select("status","projects_users","id_user = ".$id_user." AND id_project = ".$id_project);
		switch ($status[0]['status']) {
			case '1':
				$this->link_db->update("projects_users",array("status","date_event"),array("3",$date),"id_user = ".$id_user." AND id_project = ".$id_project);
				$this->link_db->delete("notifications","id_user = ".$id_user." AND id_item = ".$id_project." AND type = 1");
				break;
			case '0': case '2': case '3':
				$this->link_db->delete("projects_users","id_user = ".$id_user." AND id_project = ".$id_project);
				break;
		}
		
		
		//$this->link_db->update("tasks_main",array("status"),array("0"),"id_project =".$id_project);
		return true;
	}
	public function invivecanceleduser($id_user,$id_project){
		$date = date("Y-m-d");
		$result = $this->link_db->update("projects_users",array("status","date_event"),array("0",$date),"id_user = ".$id_user." AND id_project = ".$id_project);
		return $result;
	}
	public function getCountIdTasksUserHasTasks($id_user,$id_project){
		$result = $this->link_db->select("count(*)","tasks_main LEFT JOIN task_main_users ON task_main_users.id_task_main = tasks_main.id","task_main_users.id_user = ".$id_user." AND tasks_main.id_project = ".$id_project);
		return $result[0]['count(*)'];
	}
	public function resetuser($id_user,$id_project){
		$date = date("Y-m-d");
		$result = $this->link_db->update("projects_users",array("status","date_event"),array("1",$date),"id_user = ".$id_user." AND id_project = ".$id_project);
		return $result;
	}

	public function getStatusUserTask($id_user,$id_task){
		$result = $this->link_db->select("status","task_main_users","id_user = ".$id_user." AND id_task_main = ".$id_task);
		return (!empty($result)) ? $result[0]['status'] : null;
	}

	public function getprivacytask($id_task_main){
		$result = $this->link_db->select("privacy","tasks_main","id = ".$id_task_main);
		return (!empty($result)) ? $result[0]['privacy'] : null;
	}
	public function getPercentTask($id_task){
		$works = $this->link_db->select("*","tasks","id_task_main = ".$id_task);
		$count = count($works);
		$percent_item = 100/$count;
		$count_complete = 0;
		for($i=0;$i<$count;$i++){
			if($works[$i]['completed']==1){
				$count_complete++;
			}
		}
		return round($percent_item*$count_complete,2);
	}
	public function getPercentProject($id_project){
		$tasks_main = $this->link_db->select("id","tasks_main","id_project = ".$id_project);
		$count_tasks_main = count($tasks_main);
		if($count_tasks_main!=0){
			$percent_tasks_main_item = 100/$count_tasks_main;

			$percent_project = 0;

			for($i=0;$i<$count_tasks_main;$i++){
				$t_per = $this->getPercentTask($tasks_main[$i]['id']);
				$percent_project += ($percent_tasks_main_item/100)*$t_per;
			}
			return round($percent_project,2);
		}
		else{
			return 0;
		}
	}
	public function getCountMinitask($id_task){
		$count = $this->link_db->select("count(*)","tasks","id_task_main = ".$id_task);
		if(!empty($count)) return $count[0]['count(*)'];
		else return null;
	}
	public function deleteminitask($id_minitask){
		return $this->link_db->delete("tasks","id = ".$id_minitask);
	}
	public function completeminitask($id_minitask,$id_task,$id_user){
		$result = $this->link_db->update("tasks",array("completed"),array("1"),"id = ".$id_minitask." AND id_task_main = ".$id_task);
		if($result){
			$percent = $this->getPercentTask($id_task);
			if($percent==100){
				$project = $this->getProjectByTaskId($id_task);
				$id_project_manager = $this->getprojectmanager($project['id']);
				$this->link_user->addNotification($id_task,$id_project_manager,"3");
			}
		}
		return true;
	}
	public function resetminitask($id_minitask){
		return $this->link_db->update("tasks",array("completed"),array("0"),"id = ".$id_minitask);
	}
	public function saveanotheruser($id_task,$id_user){
		$date = date("Y-m-d");
		$this->link_db->delete("task_main_users","id_task_main = ".$id_task);
		$this->link_db->delete("notifications","id_item = ".$id_task." AND type = 2");
		$this->link_db->insert("task_main_users",array($id_task,$id_user,$date),array("id_task_main","id_user","date_event"));
		$this->link_db->update("tasks_main",array("status"),array("0"),"id = ".$id_task);

		return true;
	}
}