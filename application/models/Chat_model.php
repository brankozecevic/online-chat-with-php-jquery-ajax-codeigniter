<?php
/** 
  * @desc This class is used for all online chat functionality
  * @author Branko Zecevic
*/
class Chat_model extends CI_Model{
    //login
    public function login($username){
        $query = $this->db->get_where('users', array('username' => $username));
        if($query->num_rows() === 1){
            return false;
        }else{
            $data = array(
                'username' => $username ,
             );
            $this->db->insert('users', $data);
            $query = $this->db->get_where('users', array('username' => $username));
            return $query->row();
        }   
    }
    public function add_message($user_id, $message){
        $message = $this->db->escape_str($message);
        $data = array(
            'user_id' => $user_id ,
            'message' => $message ,
         );
        return $this->db->insert('messages', $data); 
    }
    /*
    * Getting all messages from database
    */
    public function get_messages(){
        $this->db->select('users.username, messages.message, DATE_FORMAT(messages.created_at, "%H:%i:%s") AS created_at');
        $this->db->from('messages');
        $this->db->join('users', 'messages.user_id = users.user_id');
        $this->db->order_by("created_at", "desc");
        $query = $this->db->get();
        return $query;
    }
    /*
    * Checking if messages are older than 10 min. and deleting them if so
    */
    public function delete_messages(){
        $query = 'DELETE FROM messages WHERE created_at < (NOW() - INTERVAL 10 MINUTE)';
        $this->db->query($query);
    }
    public function all_users(){
        $query = 'SELECT username FROM users';
        $result = $this->db->query($query);
        return $result->result();
    }
    /*
    * Checking if usernames are older than 30 min. and deleting them if so
    * Every user need to relog every 30 min. approximately
    */
    public function delete_users(){
        $query = 'DELETE FROM users WHERE created_at < (NOW() - INTERVAL 30 MINUTE)';
        $this->db->query($query);
    }
    /*
    * Checking if username exists in database
    */
    public function check_user($user_id){
        $query = $this->db->get_where('users', array('user_id' => $user_id));
        return $query;
    }
}