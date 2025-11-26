<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Base_Controller.php';

class Dashboard extends Base_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Lead_model');
        $this->load->model('Course_model');
        $this->load->model('Activity_log_model');
    }

    public function index()
    {
        // Check module access
        $this->check_module_access('dashboard');
        
        // Fetch dashboard data based on role
        $dashboard_data = $this->get_dashboard_data();
        
        // Prepare data for view
        $data = array(
            'title' => 'Dashboard - LÀ CRM',
            'body_class' => 'layout-mini',
            'show_preloader' => true,
            'header' => array(
                'user_name' => $this->user_data->name,
                'user_email' => $this->user_data->email,
                'user_id' => $this->user_id,
                'role_id' => $this->role_id,
                'profile_photo' => $this->user_data->profile_photo
            ),
            'sidebar' => array(
                'user_name' => $this->user_data->name,
                'user_role' => $this->user_data->role_name,
                'role_id' => $this->role_id
            ),
            'page_header' => array(
                'page_title' => 'Dashboard',
                'page_description' => 'Welcome to your CRM dashboard'
            ),
            'dashboard_data' => $dashboard_data,
            'content' => $this->load->view('dashboard_content', $dashboard_data, true)
        );
        $this->load->view('layouts/base', $data);
    }

    /**
     * Get dashboard data based on user role
     */
    private function get_dashboard_data()
    {
        $data = array();
        $user_id = $this->user_id;
        $role_id = $this->role_id;
        
        // Role-based data fetching
        if ($role_id == 4) {
            // Doctor - show only patient/course data
            $data['patient_count'] = $this->Course_model->get_patients_count($user_id);
            $data['course_revenue'] = $this->Course_model->get_total_revenue($user_id, $role_id);
            $data['enrollment_count'] = $this->Course_model->get_enrollments_count($user_id, $role_id);
        } else {
            // Admin, Sales Manager, Sales Person - show lead data
            $data['total_leads'] = $this->Lead_model->get_total_count($user_id, $role_id);
            $data['today_leads'] = $this->Lead_model->get_today_count($user_id, $role_id);
            $data['leads_by_source'] = $this->Lead_model->get_by_source($user_id, $role_id);
            $data['converted_leads'] = $this->Lead_model->get_converted_count($user_id, $role_id);
            $data['lost_leads'] = $this->Lead_model->get_lost_count($user_id, $role_id);
            
            // Pipeline summary
            $pipeline_data = $this->Lead_model->get_by_stage($user_id, $role_id);
            $data['pipeline_summary'] = array();
            foreach ($pipeline_data as $item) {
                $data['pipeline_summary'][$item->stage] = $item->count;
            }
            
            $data['upcoming_followups'] = $this->Lead_model->get_upcoming_followups($user_id, $role_id, 8);
            $data['leads_trend'] = $this->Lead_model->get_trend(30, $user_id, $role_id);
            
            // Course revenue (only for Admin and Sales Manager)
            if ($role_id == 1 || $role_id == 2) {
                $data['course_revenue'] = $this->Course_model->get_total_revenue($user_id, $role_id);
            }
        }
        
        // Recent activity log (all roles) - get system-wide for admin, user-specific for others
        if ($role_id == 1) {
            // Admin sees all activities
            $this->db->select('user_activity_log.*, users.name as user_name');
            $this->db->from('user_activity_log');
            $this->db->join('users', 'users.id = user_activity_log.user_id', 'left');
            $this->db->order_by('user_activity_log.timestamp', 'DESC');
            $this->db->limit(8);
            $data['recent_activities'] = $this->db->get()->result();
        } else {
            $data['recent_activities'] = $this->Activity_log_model->get_user_logs($user_id, 8);
        }
        
        // Role info
        $data['role_id'] = $role_id;
        $data['role_name'] = $this->user_data->role_name;
        
        return $data;
    }
}

