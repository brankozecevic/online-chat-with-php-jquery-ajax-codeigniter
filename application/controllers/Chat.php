<?php
/** 
  * @desc This class is used for all online chat functionality
  * @author Branko Zecevic
*/
class Chat extends CI_Controller {
    public function index(){
        $data['title'] = 'Login to online chat';
        $this->load->view('templates/header');
        $this->load->view('chat/chat', $data);
        $this->load->view('templates/footer');
    }
    public function login(){
        $data['title'] = 'Login to online chat';
        $this->form_validation->set_error_delimiters('<p class="alert alert-danger">', '</p>');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[2]|max_length[20]|htmlspecialchars|callback_validate_username');
        //Login form validation
        if($this->form_validation->run() === false){
            $this->load->view('templates/header');
            $this->load->view('chat/chat', $data);
            $this->load->view('templates/footer');
        }else{
            $username = $this->input->post('username');
            //Login
            $logovanje = $this->chat_model->login($username);
            if($logovanje){
                //Create sesssion
                $user_data = array(
                    'login' => 'User is logged in for chatting.',
                    'user_id' => $logovanje->user_id
                );
                $this->session->set_userdata($user_data);
                //set flash message
                $this->session->set_flashdata('user_loggedin', 'You are now logged in to chat.');
                redirect(base_url().'chat-start');
            }else{
                //set flash message
                $this->session->set_flashdata('user_failed', 'This username is already taken.');
                redirect(base_url().'chat');
            }
        }
    }
    /*
    * Validation on input from login form
    */
    public function validate_username($username){
        $reg_exp = '/^[a-zA-Z0-9]*$/';
        if(!(preg_match($reg_exp,$username))){
            $this->form_validation->set_message('validate_username', 'The {field} field can contain only letters and numbers.');
            return false;
        }else{
            return true;
        }
    }
    /*
    * After successful login
    */
    public function chat_start(){
        if($this->session->userdata('login') !== 'User is logged in for chatting.'){
            redirect(base_url().'chat');
        }
        $data['title'] = 'Welcome to Chat'; 
        $this->load->view('templates/header');
        $this->load->view('chat/chat-start', $data);
        $this->load->view('templates/footer');
    }
    /*
    * Adding and validating chat messages
    */
    public function add_message(){
        if($this->session->userdata('login') !== 'User is logged in for chatting.'){
            redirect(base_url().'chat');
        }
        $user_id = $this->input->post('user_id');
        $user_id = (int)$user_id;
        $res = $this->chat_model->check_user($user_id);
        $invalid = array('invalid');
        if($res->num_rows() == 0) {
            echo json_encode($invalid);
            exit();
        }
        $message = $this->input->post('message');
        $message = self::validate_message($message);   
        if($message === false) return false; 
        $result = $this->chat_model->add_message($user_id, $message);
    }
    /*
    * Validation of chat messages
    */
    public static function validate_message($message){
        $reg_exp = "/^[-a-zA-Z0-9\s.:,@]*$/";
        if(
        (strlen($message)>200)
        ||
        (!(preg_match($reg_exp,$message)))
        ) {
            return false;
        } else return $message;
    }
    /*
    * Showwing all chat messages from database which aren't older than 10 min.
    */
    public function all_messages(){
        if($this->session->userdata('login') !== 'User is logged in for chatting.'){
            redirect(base_url().'chat');
        }
        $this->chat_model->delete_messages();
        $no_messages = array('No new messages.');
        $messages = $this->chat_model->get_messages();  
        if($messages->num_rows() > 0){
            $result = array();
            $res = array();
            foreach($messages->result() as $msg){
                array_push($res, $msg->username);
                array_push($res, $msg->message);
                array_push($res, $msg->created_at);
                array_push($result, $res);
                $res = array();
            }
            echo json_encode($result);
        }else echo json_encode($no_messages);
    }
    /*
    * Showwing current users of chat
    */
    public function all_users(){
        if($this->session->userdata('login') !== 'User is logged in for chatting.'){
            redirect(base_url().'chat');
        }
        $this->chat_model->delete_users();
        echo json_encode($this->chat_model->all_users());
    }
    /*
    * Checking if user exists
    */
    public function check_user(){
        $user_id = $this->input->post('userid');
        $user_id = (int)$user_id;
        $res = $this->chat_model->check_user($user_id);
        $invalid = array('invalid');
        if($res->num_rows() == 0) {
            session_destroy();
            echo json_encode($invalid);
        }
    }
}