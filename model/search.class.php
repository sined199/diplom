<?php 
/**
* 
*/
class ModelSearch
{
	public $link_db=null;
	public $link_user=null;
	public $link_project=null;
	public $count_search_items = null;

	function __construct($argument = null){
	}
	public function searchUsers($params){
		//$id_user = $this->link_user->getUserId();
		$request_sql = "";
		$position_filter="";
		$only_online = "";
		$sql_tables = "users LEFT JOIN users_settings ON users.id = users_settings.id_user LEFT JOIN users_info ON users_settings.id_user = users_info.id_user";
		if(!empty($params['positions'])){
			$position_filter = " AND position_users.id_position in (".$params['positions'].")";
			$sql_tables .= " LEFT JOIN position_users ON users.id = position_users.id_user";
		} 
		if(!empty($params['request'])) $request_sql = " AND users.login like '%".$params['request']."%'";

		$timetoonline = time("Y-m-d H:i:s")-600;
		if(!empty($params['only_online']) && $params['only_online']) $only_online = " AND users.online > ".$timetoonline;

		$result = $this->link_db->select(
			"users.id, users.login, users.online, users_info.name, users_info.surname, users_info.country, users_info.city",$sql_tables,
			"users.active = 1 AND users_settings.search_user = 1".$request_sql.$position_filter.$only_online,"users.id");

		

		$this->count_search_items = count($result);
		return $result;
	}
	public function searchProjects($params){
		$request_sql = "";
		$only_active = "";
		$date_filter = "";
		$position_filter = "";
		if(!empty($params['only_active']) && $params['only_active']) $only_active = " AND projects.active = 1";
		if(!empty($params['date_start'])) $date_filter .= " AND projects.date_start >= '".$params['date_start']."'";
		if(!empty($params['date_end'])) $date_filter .= " AND projects.date_end <= '".$params['date_end']."'";
		if(!empty($params['positions'])){
			$position_filter = " AND position_projects.id_position in (".$params['positions'].")";
		} 
		$sql_tables = "projects LEFT JOIN projects_users ON projects.id = projects_users.id_project LEFT JOIN users ON projects_users.id_user = users.id LEFT JOIN position_projects ON projects.id = position_projects.id_project LEFT JOIN position_list ON position_projects.id_position = position_list.id";

		$result = $this->link_db->select("projects.id, projects.title, projects.summa, projects.date_start, projects.date_end, projects.active,projects.privacy, users.login, position_list.name",$sql_tables,"projects.privacy <> 0 AND projects_users.permission = 1".$only_active.$date_filter.$position_filter,"projects.id");

		$this->count_search_items = count($result);

		return $result;
	}

}