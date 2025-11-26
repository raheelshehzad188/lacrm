<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_log_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Log user activity
     */
    public function log($user_id, $activity, $details = null)
    {
        $data = array(
            'user_id' => $user_id,
            'activity' => $activity,
            'ip_address' => $this->input->ip_address(),
            'details' => $details,
            'timestamp' => date('Y-m-d H:i:s')
        );
        return $this->db->insert('user_activity_log', $data);
    }

    /**
     * Get user activity logs
     */
    public function get_user_logs($user_id, $limit = 50)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('timestamp', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('user_activity_log')->result();
    }
}
