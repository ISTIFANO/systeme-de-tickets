<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
 class User_model extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        
    }

    public function register($user){

        $this->db->insert("users",$user);

        return $this->db->insert_id();
    }
  public function findone($id){
 $user =   $this->db->get_where("users",array("user_id",$id));
// var_dump($user);
 return $user;

  }
  public function login($email, $password) {
    $query = $this->db->get_where('users', array('email' => $email));
    
    if ($query->num_rows() == 1) {
        $user = $query->row();
        if (password_verify($password, $user->password)) {
            return $user;
        }
    }
    
    return false;
}

  public function is_admin($user_id) {
    $query = $this->db->get_where('users', array('user_id' => $user_id, 'role' => 'admin'));
    return $query->num_rows() > 0;

}

public function get_all_users() {
    $query = $this->db->get('users');
    return $query->result();
}

 }
 
 
 
 
 
 
 
 
 
 
 ?>