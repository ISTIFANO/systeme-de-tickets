<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function create_ticket($data) {
        $this->db->insert('tickets', $data);
        return $this->db->insert_id();
    }
    
    public function get_ticket($ticket_id) {
        $this->db->select('tickets.*, users.username');
        $this->db->from('tickets');
        $this->db->join('users', 'users.user_id = tickets.user_id');
        $this->db->where('tickets.ticket_id', $ticket_id);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function get_user_tickets($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('tickets');
        return $query->result();
    }
    
    public function get_all_tickets() {
        $this->db->select('tickets.*, users.username');
        $this->db->from('tickets');
        $this->db->join('users', 'users.user_id = tickets.user_id');
        $this->db->order_by('tickets.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function update_ticket_status($ticket_id, $status) {
        $this->db->where('ticket_id', $ticket_id);
        $this->db->update('tickets', array('status' => $status));
        return $this->db->affected_rows() > 0;
    }
    
    public function create_reply($data) {
        $this->db->insert('replies', $data);
        return $this->db->insert_id();
    }
    
    public function get_ticket_replies($ticket_id) {
        $this->db->select('replies.*, users.username, users.role');
        $this->db->from('replies');
        $this->db->join('users', 'users.user_id = replies.user_id');
        $this->db->where('replies.ticket_id', $ticket_id);
        $this->db->order_by('replies.created_at', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function count_tickets_by_status($status = NULL) {
        if ($status !== NULL) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results('tickets');
    }
}
