<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get total leads count
     */
    public function get_total_count($user_id = null, $role_id = null)
    {
        $this->db->from('leads');
        
        // Role-based filtering
        if ($role_id == 3) { // Sales Person - only own leads
            $this->db->where('assigned_to', $user_id);
        } elseif ($role_id == 2) { // Sales Manager - team leads
            // Get team members (simplified - can be enhanced)
            $this->db->where_in('assigned_to', $this->get_team_members($user_id));
        }
        // Admin sees all
        
        return $this->db->count_all_results();
    }

    /**
     * Get today's leads
     */
    public function get_today_count($user_id = null, $role_id = null)
    {
        $this->db->from('leads');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        
        if ($role_id == 3) {
            $this->db->where('assigned_to', $user_id);
        } elseif ($role_id == 2) {
            $this->db->where_in('assigned_to', $this->get_team_members($user_id));
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Get leads by source
     */
    public function get_by_source($user_id = null, $role_id = null)
    {
        $this->db->select('source, COUNT(*) as count');
        $this->db->from('leads');
        $this->db->group_by('source');
        
        if ($role_id == 3) {
            $this->db->where('assigned_to', $user_id);
        } elseif ($role_id == 2) {
            $this->db->where_in('assigned_to', $this->get_team_members($user_id));
        }
        
        return $this->db->get()->result();
    }

    /**
     * Get leads by stage
     */
    public function get_by_stage($user_id = null, $role_id = null)
    {
        $this->db->select('stage, COUNT(*) as count');
        $this->db->from('leads');
        $this->db->group_by('stage');
        
        if ($role_id == 3) {
            $this->db->where('assigned_to', $user_id);
        } elseif ($role_id == 2) {
            $this->db->where_in('assigned_to', $this->get_team_members($user_id));
        }
        
        return $this->db->get()->result();
    }

    /**
     * Get converted leads count
     */
    public function get_converted_count($user_id = null, $role_id = null)
    {
        $this->db->from('leads');
        $this->db->where('stage', 'Converted');
        
        if ($role_id == 3) {
            $this->db->where('assigned_to', $user_id);
        } elseif ($role_id == 2) {
            $this->db->where_in('assigned_to', $this->get_team_members($user_id));
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Get lost leads count
     */
    public function get_lost_count($user_id = null, $role_id = null)
    {
        $this->db->from('leads');
        $this->db->where('stage', 'Lost');
        
        if ($role_id == 3) {
            $this->db->where('assigned_to', $user_id);
        } elseif ($role_id == 2) {
            $this->db->where_in('assigned_to', $this->get_team_members($user_id));
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Get upcoming follow-ups
     */
    public function get_upcoming_followups($user_id = null, $role_id = null, $limit = 10)
    {
        $this->db->select('leads.*, users.name as assigned_name');
        $this->db->from('leads');
        $this->db->join('users', 'users.id = leads.assigned_to', 'left');
        $this->db->where('follow_up_date >=', date('Y-m-d H:i:s'));
        $this->db->where('follow_up_date IS NOT NULL');
        $this->db->order_by('follow_up_date', 'ASC');
        $this->db->limit($limit);
        
        if ($role_id == 3) {
            $this->db->where('leads.assigned_to', $user_id);
        } elseif ($role_id == 2) {
            $this->db->where_in('leads.assigned_to', $this->get_team_members($user_id));
        }
        
        return $this->db->get()->result();
    }

    /**
     * Get team members (for Sales Manager)
     */
    private function get_team_members($manager_id)
    {
        // Get all sales persons (role_id = 3)
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('role_id', 3);
        $this->db->where('status', 1);
        $result = $this->db->get()->result();
        
        $ids = array($manager_id); // Include manager
        foreach ($result as $user) {
            $ids[] = $user->id;
        }
        
        return $ids;
    }

    /**
     * Get leads trend (for charts)
     */
    public function get_trend($days = 7, $user_id = null, $role_id = null)
    {
        $this->db->select('DATE(created_at) as date, COUNT(*) as count');
        $this->db->from('leads');
        $this->db->where('created_at >=', date('Y-m-d', strtotime("-{$days} days")));
        $this->db->group_by('DATE(created_at)');
        $this->db->order_by('date', 'ASC');
        
        if ($role_id == 3) {
            $this->db->where('assigned_to', $user_id);
        } elseif ($role_id == 2) {
            $this->db->where_in('assigned_to', $this->get_team_members($user_id));
        }
        
        return $this->db->get()->result();
    }
}
