<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
    }
    
    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        } else {
            redirect('login');
        }
    }
    
    public function login() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Mot de passe', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            
            $user = $this->user_model->login($email, $password);
            
            if ($user) {
                $user_data = array(
                    'user_id' => $user->user_id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'logged_in' => TRUE
                );
                
                $this->session->set_userdata($user_data);
                
                if ($user->role == 'admin') {
                    redirect('admin');
                } else {
                    redirect('dashboard');
                }
            } else {
                $this->session->set_flashdata('login_failed', 'Email ou mot de passe incorrect.');
                redirect('login');
            }
        }
    }
    
    public function register() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        
        $this->form_validation->set_rules('username', 'Nom d\'utilisateur', 'required|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Mot de passe', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Confirmation du mot de passe', 'required|matches[password]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header');
            $this->load->view('auth/register');
            $this->load->view('templates/footer');
        } else {
            $password_hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
            
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => $password_hash,
                'role' => 'user'
            );
            
            $user_id = $this->user_model->register($data);
            
            if ($user_id) {
                $this->session->set_flashdata('user_registered', 'Inscription réussie. Vous pouvez maintenant vous connecter.');
                redirect('login');
            } else {
                $this->session->set_flashdata('register_failed', 'Erreur lors de l\'inscription. Veuillez réessayer.');
                redirect('register');
            }
        }
    }
    
    public function logout() {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('logged_in');
        
        $this->session->set_flashdata('user_logged_out', 'Vous avez été déconnecté.');
        redirect('login');
    }
}
