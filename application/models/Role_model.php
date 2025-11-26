<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all roles
     */
    public function get_all()
    {
        return $this->db->get('roles')->result();
    }

    /**
     * Get role by ID
     */
    public function get_by_id($id)
    {
        return $this->db->get_where('roles', array('id' => $id))->row();
    }

    /**
     * Get role permissions
     */
    public function get_permissions($role_id, $module_name = null)
    {
        $this->db->where('role_id', $role_id);
        if ($module_name) {
            $this->db->where('module_name', $module_name);
        }
        return $this->db->get('role_permissions')->result();
    }

    /**
     * Check permission
     */
    public function has_permission($role_id, $module_name, $permission_type = 'can_view')
    {
        $this->db->where('role_id', $role_id);
        $this->db->where('module_name', $module_name);
        $permission = $this->db->get('role_permissions')->row();
        
        if ($permission && isset($permission->$permission_type)) {
            return (bool)$permission->$permission_type;
        }
        
        return false;
    }

    /**
     * Check if role can access module
     */
    public function can_access_module($role_id, $module_name)
    {
        return $this->has_permission($role_id, $module_name, 'can_view');
    }
}


