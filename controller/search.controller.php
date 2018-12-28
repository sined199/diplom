<?php

/**
* 
*/
class ControllerSearch extends MainController
{
	
	function __construct($argument = null){
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
	public function viewpage(){
		if(!empty($_GET['search_type'])){
			$position_ids = array();
			$request = "";
			$positions_filter = "";

			if(!empty($_GET['request'])){
				$request = $_GET['request'];	
			}
			if(!empty($_GET['position_filter'])){
				$position_ids = explode(",", $_GET['position_filter']);
				$positions_filter = $_GET['position_filter'];
			}			
			switch ($_GET['search_type']) {
				case 'users':
					$this->db_connect();
					$search = $this->loadModel("search");
					$search->link_user = $this->loadModel("user");
					$search->link_db = $this->db;
					$search->link_user->link_db = $this->db;
					$only_online = false;
				
					if(!empty($_GET['only_online']) && $_GET['only_online']==1){
						$only_online = true;	
					}
					$result = $search->searchUsers(array('request'=>$request,'positions'=>$positions_filter,'only_online'=>$only_online));

					for($i=0;$i<count($result);$i++){
						if($search->link_user->checkProfileInContactsList($result[$i]['id'])){
							$result[$i]['inContacts'] = true;
						}
						else{
							$result[$i]['inContacts'] = false;
						}
					}

					$data['timelastenter'] = time("Y-m-d H:i:s")-600; 
					$data['only_online'] = $only_online;
					$data['positions'] = $search->link_user->getAllPosition();
					$data['search_request_text'] = "Поиск по пользователям";
					if(!empty($request)) $data['search_request_text'].="  по запросу '".$request."'";
					$data['request'] = $request;
					$data['s_users'] = $result;
					$data['id_user'] = $search->link_user->getUserId();
					$data['position_ids'] = $position_ids;
					$data['all_count_result'] = $search->count_search_items;
					$this->outputcontent("search-users-page",$data);
					break;
				case 'projects':
					$this->db_connect();
					$search = $this->loadModel("search");
					$search->link_project = $this->loadModel("project");
					$search->link_user = $this->loadModel("user");
					$search->link_db = $this->db;
					$search->link_project->link_db = $this->db;
					$search->link_user->link_db = $this->db;

					
					$date_start = "";
					$date_end = "";
					$only_active = false;
					if(!empty($_GET['date_start'])){
						$date_start = $_GET['date_start'];
						
					}
					if(!empty($_GET['date_end'])){
						$date_end = $_GET['date_end'];
						
					}
					if(!empty($_GET['only_active']) && $_GET['only_active']==1){
						$only_active = true;
					}

					$result = $search->searchProjects(array('request'=>$request,'positions'=>$positions_filter,'only_active'=>$only_active,'date_start'=>$date_start,'date_end'=>$date_end));

					$data['s_projects'] = $result;
					$data['search_request_text'] = "Поиск по проектам";
					if(!empty($request)) $data['search_request_text'].="  по запросу '".$request."'";
					$data['request'] = $request;
					$data['positions'] = $search->link_user->getAllMainPosition();
					$data['position_ids'] = $position_ids;
					$data['date_start'] = $date_start;
					$data['date_end'] = $date_end;
					$data['only_active'] = $only_active;
					$data['all_count_result'] = $search->count_search_items;

					$data['text_active'][0] = "Проект не начат";
					$data['text_active'][1] = "Проект стартовал";
					$data['text_active'][2] = "Проект окончен";

					$this->outputcontent("search-projects-page",$data);
					break;
			}
		}
		else{
			header("Location: /diplom");
		}
	}

}