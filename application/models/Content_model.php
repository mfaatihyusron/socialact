<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content_model extends CI_Model {

    // --- REPORTS ---
    public function get_reports() { 
        $this->db->order_by('created_at', 'DESC'); 
        return $this->db->get('waste_reports')->result(); 
    }
    
    public function update_report_status($id, $status, $img_url = null) {
        $data = ['status' => $status];
        if($img_url) $data['image_after_url'] = $img_url;
        if($status == 'resolved') $data['cleaned_at'] = date('Y-m-d');
        $this->db->where('id', $id);
        return $this->db->update('waste_reports', $data);
    }

    // --- EVENTS ---
    public function get_events() {
        $this->db->order_by('event_date', 'ASC');
        return $this->db->get('volunteer_events')->result();
    }
    public function insert_event($data) { 
        return $this->db->insert('volunteer_events', $data); 
    }
    public function update_event($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('volunteer_events', $data);
    }
    public function delete_event($id) { 
        $this->db->where('id', $id); 
        return $this->db->delete('volunteer_events'); 
    }
    
    // --- VOLUNTEERS ---
    public function get_event_volunteers($event_id) {
        $this->db->where('event_id', $event_id);
        $this->db->order_by('registered_at', 'DESC');
        return $this->db->get('volunteers')->result();
    }
}