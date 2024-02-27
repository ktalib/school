<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Blog_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function save($data = array())
    {
        $arrayBlog = array(
            'branch_id' => $data['branch_id'],
            'title' => $this->input->post('title'),
            'remark' => $this->input->post('remarks'),
            'type' => $data['type'],
            'audition' => $data['audition'],
            'image' => $data['image'],
            'show_web' => (isset($_POST['show_website']) ? 1 : 0),
            'selected_list' => $data['selected_list'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => 1,
        );

        if (isset($data['id']) && !empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('blog', $arrayBlog);
        } else {
            $arrayBlog['created_by'] = get_loggedin_user_id();
            $arrayBlog['session_id'] = get_session_id();
            $this->db->insert('blog', $arrayBlog);
        }
    }
}
