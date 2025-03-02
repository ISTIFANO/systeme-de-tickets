<?php 



class Main extends CI_Controller{


function __construct()
{
    parent::__construct();
    
    $this->load->database();

}

public function index()
{
    // echo "<h1>Welcome to the world of Codeigniter</h1>";//Just an example to ensure that we get into the function
    // die();
}


public function showingUtilisateur(){

 return   $this->load->model("Utilisateur")->show();
}


} 


?>