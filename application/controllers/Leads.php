<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Base_Controller.php';

class Leads extends Base_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Lead_model');
        $this->load->model('User_model');
        $this->load->model('Activity_log_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }

    public function index()
    {
        // Check module access
        $this->check_module_access('leads');
        
        try {
            // Get filter parameters
            $search = $this->input->get('search');
            $stage = $this->input->get('stage');
            $source = $this->input->get('source');
            
            // Get leads based on role
            $leads = $this->get_leads($search, $stage, $source);
            $total_count = is_array($leads) ? count($leads) : 0;
            
            // Get stats for filters
            $stages = $this->get_stages();
            $sources = $this->get_sources();
            
            // Ensure arrays are not null
            if (!is_array($leads)) $leads = array();
            if (!is_array($stages)) $stages = array();
            if (!is_array($sources)) $sources = array();
            
            // Debug: Log counts and role info
            log_message('debug', 'Leads::index() - Role: ' . $this->role_id . ', User: ' . $this->user_id . ', Total leads: ' . $total_count . ', Stages: ' . count($stages) . ', Sources: ' . count($sources));
            
            // If no leads but filters show counts, there might be a query issue
            if ($total_count == 0 && (count($stages) > 0 || count($sources) > 0)) {
                log_message('warning', 'Leads::index() - Filters show leads exist but get_leads() returned empty. Possible query mismatch.');
                // For Admin, try a simpler query to debug
                if ($this->role_id == 1) {
                    $this->db->select('COUNT(*) as total');
                    $this->db->from('leads');
                    $this->db->where('status', 1);
                    $count_result = $this->db->get()->row();
                    log_message('debug', 'Leads::index() - Direct count query: ' . ($count_result ? $count_result->total : 0));
                }
            }
            
        } catch (Exception $e) {
            // Handle errors gracefully
            $leads = array();
            $total_count = 0;
            $stages = array();
            $sources = array();
            $error_msg = 'Error loading leads: ' . $e->getMessage();
            log_message('error', 'Leads::index() - ' . $error_msg);
            $this->session->set_flashdata('error', $error_msg);
        } catch (Error $e) {
            // Handle PHP errors
            $leads = array();
            $total_count = 0;
            $stages = array();
            $sources = array();
            $error_msg = 'System error: ' . $e->getMessage();
            log_message('error', 'Leads::index() - ' . $error_msg);
            $this->session->set_flashdata('error', $error_msg);
        }
        
        // Ensure all variables have default values
        $view_data = array(
            'leads' => $leads ?: array(),
            'total_count' => $total_count ?: 0,
            'stages' => $stages ?: array(),
            'sources' => $sources ?: array(),
            'current_stage' => $stage ?: '',
            'current_source' => $source ?: '',
            'current_search' => $search ?: '',
            'role_id' => isset($this->role_id) ? $this->role_id : 1
        );
        
        // Prepare data for view
        $data = array(
            'title' => 'Leads - LÀ CRM',
            'body_class' => 'layout-default',
            'show_preloader' => false,
            'header' => array(
                'user_name' => $this->user_data->name,
                'user_email' => $this->user_data->email,
                'user_id' => $this->user_id,
                'role_id' => $this->role_id,
                'profile_photo' => isset($this->user_data->profile_photo) ? $this->user_data->profile_photo : ''
            ),
            'sidebar' => array(
                'user_name' => $this->user_data->name,
                'user_role' => $this->user_data->role_name,
                'role_id' => $this->role_id
            ),
            'page_header' => array(
                'page_title' => 'Leads',
                'page_description' => 'Manage your leads',
                'breadcrumbs' => array(
                    array('label' => 'Home', 'url' => 'dashboard'),
                    array('label' => 'Leads')
                )
            ),
            'content' => $this->load->view('leads_content', $view_data, true)
        );
        
        $this->load->view('layouts/base', $data);
    }

    private function get_leads($search = null, $stage = null, $source = null)
    {
        try {
            // Check if database is connected
            if (!$this->db->conn_id) {
                log_message('error', 'Database not connected in Leads controller');
                $this->session->set_flashdata('error', 'Database connection error. Please check your database configuration.');
                return array();
            }
            
            // Check if leads table exists
            if (!$this->db->table_exists('leads')) {
                log_message('error', 'Leads table does not exist');
                $this->session->set_flashdata('error', 'Leads table not found. Please run the database setup script.');
                return array();
            }
            
            $this->db->select('leads.*, users.name as assigned_name');
            $this->db->from('leads');
            $this->db->join('users', 'users.id = leads.assigned_to', 'left');
            $this->db->where('leads.status', 1);
            
            // Role-based filtering
            if ($this->role_id == 3) { // Sales Person - only their assigned leads
                $this->db->where('leads.assigned_to', $this->user_id);
            } elseif ($this->role_id == 2) { // Sales Manager - team leads + unassigned
                // Get team members (includes manager)
                $team_ids = $this->get_team_member_ids();
                if (!empty($team_ids)) {
                    // Show leads assigned to team members OR unassigned leads
                    $this->db->group_start();
                    $this->db->where_in('leads.assigned_to', $team_ids);
                    $this->db->or_where('leads.assigned_to IS NULL', null, false);
                    $this->db->group_end();
                } else {
                    // If no team members, show unassigned or manager's own leads
                    $this->db->group_start();
                    $this->db->where('leads.assigned_to', $this->user_id);
                    $this->db->or_where('leads.assigned_to IS NULL', null, false);
                    $this->db->group_end();
                }
            }
            // Admin (role_id == 1) sees all - no additional filter needed
            
            // Search filter
            if ($search) {
                $this->db->group_start();
                $this->db->like('leads.name', $search);
                $this->db->or_like('leads.email', $search);
                $this->db->or_like('leads.phone', $search);
                $this->db->group_end();
            }
            
            // Stage filter
            if ($stage) {
                $this->db->where('leads.stage', $stage);
            }
            
            // Source filter
            if ($source) {
                $this->db->where('leads.source', $source);
            }
            
            $this->db->order_by('leads.created_at', 'DESC');
            
            $result = $this->db->get();
            
            if ($result) {
                $leads = $result->result();
                log_message('debug', 'Leads::get_leads() - Found ' . count($leads) . ' leads for role_id: ' . $this->role_id . ', user_id: ' . $this->user_id);
                return $leads;
            }
            
            log_message('error', 'Leads::get_leads() - Query returned no result');
            return array();
        } catch (Exception $e) {
            $error_msg = 'Leads::get_leads() - ' . $e->getMessage();
            log_message('error', $error_msg);
            $this->session->set_flashdata('error', 'Error fetching leads: ' . $e->getMessage());
            return array();
        } catch (Error $e) {
            $error_msg = 'Leads::get_leads() - ' . $e->getMessage();
            log_message('error', $error_msg);
            $this->session->set_flashdata('error', 'System error: ' . $e->getMessage());
            return array();
        }
    }

    private function get_stages()
    {
        try {
            $this->db->select('stage, COUNT(*) as count');
            $this->db->from('leads');
            $this->db->where('status', 1);
            
            if ($this->role_id == 3) {
                $this->db->where('assigned_to', $this->user_id);
            } elseif ($this->role_id == 2) {
                $team_ids = $this->get_team_member_ids();
                if (!empty($team_ids)) {
                    $this->db->where_in('assigned_to', $team_ids);
                }
            }
            
            $this->db->group_by('stage');
            $result = $this->db->get();
            return $result ? $result->result() : array();
        } catch (Exception $e) {
            log_message('error', 'Leads::get_stages() - ' . $e->getMessage());
            return array();
        }
    }

    private function get_sources()
    {
        try {
            $this->db->select('source, COUNT(*) as count');
            $this->db->from('leads');
            $this->db->where('status', 1);
            $this->db->where('source IS NOT NULL');
            
            if ($this->role_id == 3) {
                $this->db->where('assigned_to', $this->user_id);
            } elseif ($this->role_id == 2) {
                $team_ids = $this->get_team_member_ids();
                if (!empty($team_ids)) {
                    $this->db->where_in('assigned_to', $team_ids);
                }
            }
            
            $this->db->group_by('source');
            $result = $this->db->get();
            return $result ? $result->result() : array();
        } catch (Exception $e) {
            log_message('error', 'Leads::get_sources() - ' . $e->getMessage());
            return array();
        }
    }

    private function get_team_member_ids()
    {
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('role_id', 3); // Sales Person
        $this->db->where('status', 1);
        $result = $this->db->get()->result();
        
        $ids = array($this->user_id); // Include manager
        foreach ($result as $user) {
            $ids[] = $user->id;
        }
        
        return $ids;
    }

    /**
     * Add Lead Page
     */
    public function add()
    {
        // Check permission - only Admin and Sales Manager can add leads
        if ($this->role_id != 1 && $this->role_id != 2) {
            show_error('Access forbidden. You do not have permission to add leads.', 403);
            return;
        }

        // Get users for assignment (Sales Persons)
        $sales_users = $this->get_sales_users();

        $view_data = array(
            'sales_users' => $sales_users,
            'role_id' => $this->role_id
        );

        $data = array(
            'title' => 'Add Lead - LÀ CRM',
            'body_class' => 'layout-default',
            'show_preloader' => false,
            'header' => array(
                'user_name' => $this->user_data->name,
                'user_email' => $this->user_data->email,
                'user_id' => $this->user_id,
                'role_id' => $this->role_id,
                'profile_photo' => isset($this->user_data->profile_photo) ? $this->user_data->profile_photo : ''
            ),
            'sidebar' => array(
                'user_name' => $this->user_data->name,
                'user_role' => $this->user_data->role_name,
                'role_id' => $this->role_id
            ),
            'page_header' => array(
                'page_title' => 'Add Lead',
                'page_description' => 'Create a new lead',
                'breadcrumbs' => array(
                    array('label' => 'Home', 'url' => 'dashboard'),
                    array('label' => 'Leads', 'url' => 'leads'),
                    array('label' => 'Add Lead')
                )
            ),
            'content' => $this->load->view('leads_add', $view_data, true)
        );
        
        $this->load->view('layouts/base', $data);
    }

    /**
     * Process Add Lead
     */
    public function process_add()
    {
        // Check permission
        if ($this->role_id != 1 && $this->role_id != 2) {
            show_error('Access forbidden.', 403);
            return;
        }

        if ($this->input->method() !== 'post') {
            redirect('leads/add');
            return;
        }

        // Validation rules
        $this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[100]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('source', 'Source', 'trim|max_length[50]');
        $this->form_validation->set_rules('stage', 'Stage', 'trim|max_length[50]');
        $this->form_validation->set_rules('assigned_to', 'Assigned To', 'trim|integer');
        $this->form_validation->set_rules('follow_up_date', 'Follow Up Date', 'trim');
        $this->form_validation->set_rules('notes', 'Notes', 'trim|max_length[1000]');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('leads/add');
            return;
        }

        // Prepare lead data
        $lead_data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email') ?: null,
            'phone' => $this->input->post('phone') ?: null,
            'source' => $this->input->post('source') ?: 'Manual Entry',
            'stage' => $this->input->post('stage') ?: 'New',
            'assigned_to' => $this->input->post('assigned_to') ?: null,
            'follow_up_date' => $this->input->post('follow_up_date') ? date('Y-m-d H:i:s', strtotime($this->input->post('follow_up_date'))) : null,
            'notes' => $this->input->post('notes') ?: null,
            'status' => 1,
            'created_by' => $this->user_id
        );

        // Insert lead
        $this->db->insert('leads', $lead_data);
        $lead_id = $this->db->insert_id();

        if ($lead_id) {
            // Log activity
            $this->Activity_log_model->log($this->user_id, 'lead_created', 'Lead created: ' . $lead_data['name']);
            
            $this->session->set_flashdata('success', 'Lead created successfully.');
            redirect('leads');
        } else {
            $this->session->set_flashdata('error', 'Failed to create lead. Please try again.');
            redirect('leads/add');
        }
    }

    /**
     * Get Sales Users for Assignment
     */
    private function get_sales_users()
    {
        try {
            $this->db->select('users.id, users.name, users.email, roles.role_name');
            $this->db->from('users');
            $this->db->join('roles', 'roles.id = users.role_id', 'left');
            $this->db->where('users.status', 1);
            
            // Admin can assign to anyone, Sales Manager can assign to Sales Persons
            if ($this->role_id == 2) {
                $this->db->where_in('users.role_id', array(2, 3)); // Sales Manager and Sales Person
            }
            
            $this->db->order_by('users.name', 'ASC');
            $result = $this->db->get();
            return $result ? $result->result() : array();
        } catch (Exception $e) {
            log_message('error', 'Leads::get_sales_users() - ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get Companies List
     */
    private function get_companies()
    {
        try {
            $this->db->select('id, name');
            $this->db->from('companies');
            $this->db->where('status', 1);
            $this->db->order_by('name', 'ASC');
            $result = $this->db->get();
            return $result ? $result->result() : array();
        } catch (Exception $e) {
            log_message('error', 'Leads::get_companies() - ' . $e->getMessage());
            return array();
        }
    }
}

