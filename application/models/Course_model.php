<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get total course revenue
     */
    public function get_total_revenue($user_id = null, $role_id = null)
    {
        $this->db->select_sum('amount');
        $this->db->from('course_enrollments');
        $this->db->where('status', 'completed');
        $this->db->where('type', 'course'); // Exclude clinic visits
        
        if ($role_id == 4) { // Doctor - only assigned courses
            $this->db->where('instructor_id', $user_id);
        }
        
        $result = $this->db->get()->row();
        return $result->amount ? $result->amount : 0;
    }

    /**
     * Get course enrollments count
     */
    public function get_enrollments_count($user_id = null, $role_id = null)
    {
        $this->db->from('course_enrollments');
        $this->db->where('type', 'course');
        
        if ($role_id == 4) {
            $this->db->where('instructor_id', $user_id);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Get patient visits count (for Doctor)
     */
    public function get_patients_count($doctor_id)
    {
        $this->db->from('course_enrollments');
        $this->db->where('type', 'clinic');
        $this->db->where('instructor_id', $doctor_id);
        return $this->db->count_all_results();
    }
}
