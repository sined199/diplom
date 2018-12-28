<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 29.10.2016
 * Time: 15:08
 */
class ModelUser
{
    public $link_db=null;
    public $link_mail=null;

    private $id_user;
    private $login;

    public function __construct($link_db=null){
        if(!empty($link_db)){
            $this->link_db = $link_db;
            $this->link_db->connect("diplom");
        }
        
    }
    public function setonline(){
        $timenow = time("Y-m-d H:i:s");
        $id_user = $this->getUserId();
        $result = $this->link_db->select("hidden_profile","users_settings","id_user = ".$id_user);
        if($result[0]['hidden_profile']!=1){
            $this->link_db->update("users",array("online"),array($timenow),"id = ".$id_user);
        }
        
    }
    public function isonline($id_user=null){
        $timenow = time("Y-m-d H:i:s");
        $result = null;
        if(!empty($id_user)){
            $id = $id_user;
            $result = $this->link_db->select("id","users","`id` = ".$id." AND `online` > '".$timenow-600);
        }
        else{
            $id = $this->getUserId();
            $timenow = time("Y-m-d H:i:s");
            $result = $this->link_db->select("id","users","id = ".$id." AND online > ".$timenow-600);
        }
        return (!empty($result)) ? true : false;
    }
    public function login($user_data){
        $res = array();
        $checkLogin = $this->check_user_data($user_data);
        if($checkLogin){
            /*$result['login'] = $this->login;
            $result['id'] = $this->id_user;*/
            $_SESSION['user']['login'] = $this->login;
            $_SESSION['user']['id'] = $this->id_user;
            $res['error'] = false;     
        }
        else{
            $res['error'] = true;
        }
        return $res;
    }

    public function registration($user_data){
        $this->link_db->insert("users",array($user_data['login'],md5($user_data['password']),$user_data['email'],'0',$user_data['id_invited']),array('login','password','email','active','id_invite_user'));
        $result = $this->link_db->select("id","users","","","id DESC","1");
        return $result[0]['id'];
    }
    public function authexit(){
        unset($_SESSION);
        session_destroy();
    }
    public function send_regkey_to_mail($user_data){
        $res = array();
        $result = $this->check_login($user_data['login']);
        if(!$result){
            $result = $this->check_email($user_data['email']);
            if(!$result){
                //$key = md5($user_data['login'].":".$user_data['email']);
                $date = date("Y-m-d H:i:s");
                $key = date("is").rand(0,100);
                $last_id = $this->registration($user_data);
                
                $this->link_db->insert("users_keys",array($key,$last_id,$date),array("key_reg","id_user","date_add"));
                
                $result = $this->link_mail->send_registration_mail($user_data,$key);

                $_SESSION['temp_reg_key_id_user'] = $last_id;
               
                $res['error'] = false;
                $res['key'] = $key;
                return $res;

            }
            else{
                $res['error'] = true;
                $res['error_type'] = 'email';
                return $res;
            }
        }
        else{
            $res['error'] = true;
            $res['error_type'] = 'login';
            return $res;
        }
    }

    public function activation($key){
        $res = array();
        $id_user = $_SESSION['temp_reg_key_id_user'];
        $result = $this->link_db->select("id_user","users_keys","`id_user` like '".$id_user."' and `key_reg` like '".$key."'");
        if(!empty($result)){
            $date = date("Y-m-d H:i:s");
            $this->link_db->update("users",array("active","date_activation"),array("1",$date),"`id` = ".$id_user);
            $this->link_db->delete("users_keys","`id_user` like '".$id_user."'");
            $this->link_db->insert("users_info",array($id_user),array("id_user"));
            $this->link_db->insert("users_settings",array($id_user,"1","1","1"),array("id_user","search_user","view_statistics","send_invite"));
            $id_invite_user = $this->link_db->select("id_invite_user","users","id = ".$id_user);
            if(count($id_invite_user)>0){
                if($id_invite_user[0]['id_invite_user']!=0){
                    $this->addToContact($id_invite_user[0]['id_invite_user'],$id_user);
                    $this->addToContact($id_user,$id_invite_user[0]['id_invite_user']);
                }
            }
            $res['error'] = false;
            unset($_SESSION['temp_reg_key_id_user']);
        }
        else{
            $res['error'] = true;
        }
        return $res;
    }
    public function resetpass($email){
        $result = $this->link_db->select("id,email","resetpass","`email` like '".$email."'");
        if(!empty($result[0]['email'])){
            $this->link_db->delete("resetpass","`id` like '".$result[0]['id']."'");
        }

        $date = date("Y-m-d h:i:s");
        $code = md5($email.$date.$email);

        $this->link_db->insert("resetpass",array($date,$code,$email),array("date","code","email"));

        return $code;
    }
    public function check_code_resetpass($code){
        $result = $this->link_db->select("code","resetpass","`code` like '".$code."'");
        return ($result) ? true : false;     
    }
    public function setnewpasswordwithcode($password,$code){
        $result = $this->link_db->select("id, email","resetpass","`code` like '".$code."'");
        $password = md5($password);
        $this->link_db->update("users",array("password"),array($password),"`email` like '".$result[0]['email']."'");
        $this->link_db->delete("resetpass","`id` like '".$result[0]['id']."'");
        return true;
    }
    public function is_login(){
        if(!empty($_SESSION['user'])){
            return true;
        }
        else{
            return false;
        }
    }
    public function getLogin($byId=null){
        if(!empty($byId)){
            $result = $this->link_db->select("login","users","`id` like '".$byId."'");
            return $result[0]['login'];
        }
        else{
            return $_SESSION['user']['login'];
        }
    }
    public function getUserId(){
        return $_SESSION['user']['id'];
    }

    public function getInformation($id=null){
        $id_user = $this->getUserId();
        if(!empty($id)){
            $id_user = $id;
        }
        $info = $this->link_db->select("*","users_info","`id_user` = '".$id_user."'");
        return $info[0];
    }
    public function getSettings($id=null){
        $id_user = $this->getUserId();
        if(!empty($id)){
            $id_user = $id;
        }
        $settings = $this->link_db->select("*","users_settings","`id_user` = '".$id_user."'");
        return $settings[0];
    }
    public function getSettingsInvite($id=null){
        $id_user = $this->getUserId();
        if(!empty($id)){
            $id_user = $id;
        }
        $settings = $this->link_db->select("send_invite","users_settings","`id_user` = '".$id_user."'");
        return $settings[0]['send_invite'];
    }
    public function editinfo($item,$text){
        $id_user = $this->getUserId();
        return $this->link_db->update("users_info",array($item),array($text),"`id_user` = '".$id_user."'");
    }
    public function editsettings($arr_settings){
        $keys = array();
        $values = array();
        $id_user = $this->getUserId();
        foreach ($arr_settings as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }
        return $this->link_db->update("users_settings",$keys,$values,"`id_user` = '".$id_user."'");
    }
    public function getUserPositions($id=null){
        $id_user = $this->getUserId();
        if(!empty($id)){
            $id_user = $id;
        }
        $positions = $this->link_db->select("pl.name,pl.id_parent, pu.id_position, pu.id","position_users pu LEFT JOIN position_list pl ON pu.id_position = pl.id","`id_user` = ".$id_user);
        return $positions;
    }
    public function getStatistics($id,$permission){
        $data = array();
        switch ($permission) {
            case '0':{
                $data['projects'] = $this->link_db->select("p.title, pu.id_project, pu.status, pu.id_position, pu.date_event, p.active, p.privacy","projects p LEFT JOIN projects_users pu ON pu.id_project = p.id","`id_user` like '".$id."' AND p.privacy <> 0");
                $data['tasks'] = $this->link_db->select("t.title, tu.id_task_main, tu.status, tu.date_event, t.status, t.privacy","tasks_main t LEFT JOIN task_main_users tu ON tu.id_task_main = t.id","`id_user` like '".$id."' AND t.privacy <> 0");
                break;
            }                
            case '1':{
                $data['projects'] = $this->link_db->select("p.title, pu.id_project, pu.status, pu.id_position, pu.date_event, p.active, p.privacy","projects p LEFT JOIN projects_users pu ON pu.id_project = p.id","`id_user` like '".$id."'");
                $data['tasks'] = $this->link_db->select("t.title, tu.id_task_main, tu.status, tu.date_event, t.status, t.privacy","tasks_main t LEFT JOIN task_main_users tu ON tu.id_task_main = t.id","`id_user` like '".$id."'");
                break;
            }
        }
       
        return $data;
    }
    public function getAllPosition($id_parent=null){
        $result = null;
        if($id_parent!=null){
            $result = $this->link_db->select("id,name","position_list","id_parent like ".$id_parent);
        }
        else{
            $result = $this->link_db->select("id,name,id_parent","position_list");
        }
        
        return $result;
    }
    public function getAllMainPosition(){
        return $this->link_db->select("id,name","position_list","id_parent = 0");
    }
    public function userIsHavePositionByListId($id_user,$id_position){
        $result = $this->link_db->select("id","position_users","`id` = '".$id_position."' AND `id_user` = '".$id_user."'");
        if($result){
            return true;
        }
        else{
            return false;
        }
    }
    public function userHavePosition($id_user,$id_position){
        $result = $this->link_db->select("id_position","position_users","`id_position` = '".$id_position."' AND `id_user` = '".$id_user."'");
        if($result){
            return true;
        }
        else{
            return false;
        }
    }
    public function deleteuserposition($id_position){
        return $this->link_db->delete("position_users","`id` = '".$id_position."'");
    }
    public function addUserPosition($list_positions){
        $id_user = $this->getUserId();
        for($i=0;$i<count($list_positions);$i++){
            if(!$this->userHavePosition($this->getUserId(),$list_positions[$i])){
                $this->link_db->insert("position_users",array($list_positions[$i],$id_user),array("id_position","id_user"));
            }
            
        }
        return true;
    }
    public function addToContact($id,$id_u=null){
        $datenow = date("Y-m-d");
        $id_user = ($id_u!=null) ? $id_u : $this->getUserId();
        return $this->link_db->insert("users_contacts",array($id_user,$id,$datenow),array('id_user','id_user_added','date_add'));
    }
    public function deleteFromContacts($id,$id_u=null){
        $id_user = (!empty($id_u)) ? $id_u : $this->getUserId();
        return $this->link_db->delete("users_contacts","id_user_added = ".$id." AND id_user = ".$id_user);
    }
    public function checkProfileInContactsList($id){
        $id_user = $this->getUserId();
        $result = $this->link_db->select("id_user_added","users_contacts","`id_user` like '".$id_user."' AND `id_user_added` like '".$id."'");
        return (!empty($result)) ? true : false;
    } 
    public function getContactsList($limin_count=null){
        $limit = (!empty($limin_count)) ? $limin_count : "";
        $id_user = $this->getUserId();
        $result = $this->link_db->select("id,id_user_added","users_contacts","`id_user` like '".$id_user."'","","id DESC",$limit);
        return (empty($result)) ? array() : $result;
    }
    public function getcountcontacts(){
        $id_user = $this->getUserId();
        $result = $this->link_db->select("count(*)","users_contacts","`id_user` like '".$id_user."'");
        return $result[0]['count(*)'];
    }
    public function addNotification($id_item,$id_user,$type){
        $datetime = date("Y-m-d H:i:s");
        $this->link_db->insert("notifications",array($type,$id_user,$id_item,$datetime),array("type","id_user","id_item","date_add"));
        return true;
    }
    public function getCountNotifications(){
        $id_user = $this->getUserId();
        $result = $this->link_db->select("count(*)","notifications","id_user = ".$id_user);
        return $result[0]['count(*)'];
    }
    public function getallnotifications(){
        $id_user = $this->getUserId();
        $result = $this->link_db->select("*",'notifications','id_user = '.$id_user);
        return $result;
    }
    public function getlastnotification(){
        $id_user = $this->getUserId();
        $result = $this->link_db->select("*",'notifications','id_user = '.$id_user.' AND type <> 3',"","id DESC","1");
        return (count($result)>0) ? $result[0] : null;
    }

    public function generaterefurl(){
        $id_user = $this->getUserId();
        $result = $this->link_db->select("login,email","users","id = ".$id_user);
        $datenow = date("Y-m-d H:i:s");

        $generatekey = md5($result[0]['login'].$result[0]['email'].$datenow);
        $result = $this->link_db->update("users",array("refurl"),array($generatekey),"id = ".$id_user);
        return $result;
    }
    public function getrefurl($id=null){
        $id_user = $this->getUserId();
        if(!empty($id)) $id_user = $id;
        $result = $this->link_db->select("refurl","users","id = ".$id_user);
        return (count($result)>0) ? $result[0]['refurl'] : null;
    }
    public function getIdUserRefurl($code){
        $result = $this->link_db->select("id","users","refurl like '".$code."'");
        return (count($result)>0) ? $result[0]['id'] : null;
    }
    public function getInvited(){
        $id_user = $this->getUserId();
        $result = $this->link_db->select("*","users","id_invite_user = ".$id_user." AND active = 1");
        return $result;
    }
    public function checkLastNotifications(){
        $id_user = $this->getUserId();
        $timenow = time("Y-m-d H:i:s")-3;
        $result = $this->link_db->select("*","notifications","id_user = ".$id_user,"","id DESC","1");
        if(count($result)!=0){
            if(strtotime($result[0]['date_add'])>$timenow){
                return $result;
            }
            else{
                return null;
            }
        }
        else{
            return null;
        }
        //return $result;
    }
    public function acceptproject($id_project){
        $id_user = $this->getUserId();
        $result = $this->link_db->delete("notifications","id_user = ".$id_user." AND id_item = ".$id_project." AND type = 1");
        if($result){
            $date = date("Y-m-d");
            $result = $this->link_db->update("projects_users",array("status","date_event"),array("1",$date),"id_project = ".$id_project." AND id_user = ".$id_user);
            return ($result) ? true : false;
        }
        else{
            return false;
        }  
    }
    public function cancelproject($id_project){
        $id_user = $this->getUserId();
        $result = $this->link_db->delete("notifications","id_user = ".$id_user." AND id_item = ".$id_project." AND type = 1");
        if($result){
            $date = date("Y-m-d");
            $result = $this->link_db->update("projects_users",array("status","date_event"),array("2",$date),"id_project = ".$id_project." AND id_user = ".$id_user);
            return ($result) ? true : false;
        }
        else{
            return false;
        }  
    }
    public function accepttaskmain($id_task_main){
        $id_user = $this->getUserId();
        $result = $this->link_db->delete("notifications","id_user = ".$id_user." AND id_item = ".$id_task_main." AND type = 2");
        if($result){
            $date = date("Y-m-d");
            $this->link_db->update("task_main_users",array("status","date_event"),array("1",$date),"id_user = ".$id_user." AND id_task_main = ".$id_task_main);
            $this->link_db->update("tasks_main",array("status"),array("1"),"id = ".$id_task_main);
            return true;
        }
        else return false;
    }
    public function canceltaskmain($id_task_main){
        $id_user = $this->getUserId();
        $result = $this->link_db->delete("notifications","id_user = ".$id_user." AND id_item = ".$id_task_main." AND type = 2");
        if($result){
            $date = date("Y-m-d");
            $this->link_db->update("task_main_users",array("status","date_event"),array("2",$date),"id_user = ".$id_user." AND id_task_main = ".$id_task_main);
            //$this->link_db->update("tasks_main",array("status"),array("2"),"id = ".$id_task_main);
            return true;
        }
        else return false;
    }
    public function gettypenotification($id_notification){
        $id_user = $this->getUserId();
        $result = $this->link_db->select("type","notifications","id_user =".$id_user." AND id =".$id_notification);
        return $result[0]['type'];
    }
    public function getitemnotification($id_notification){
        $result = $this->link_db->select("id_item","notifications","id =".$id_notification);
        return $result[0]['id_item'];
    }
    public function deletenotification($id_notification){
        $result = $this->link_db->delete("notifications","id = ".$id_notification);
        return $result;
    }
    public function check_email($email){
        $result = $this->link_db->select("email","users","`email` like '".$email."'");
        return ($result) ? true : false;       
    }
   //************************{}
    
    
      
    /**
     * @param $login
     */
    /**** check data *****/
    private function check_login($login){
        $result = $this->link_db->select("login","users","`login` like '".$login."'");
        return ($result) ? true : false;
    }
    
    private function check_user_data($user_data){
        $result = $this->link_db->select("*","users","`login` like '".$user_data['login']."' and `password` like '".md5($user_data['password'])."' AND `active` like '1'");
        if(!empty($result)){
            $this->login = $result[0]['login'];
            $this->id_user = $result[0]['id'];
            return true;
        }
        else{
            return false;
        }
    }
    /************************/
}