<?php
defined('BASEPATH') or exit('No direct script access allowed');

 

class Blog extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('blog_model');
    }

    public function index()
    {
        // check access permission
        if (!get_permission('blog', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            // check access permission
            if (!get_permission('blog', 'is_add')) {
               ajax_access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }
            $this->form_validation->set_rules('title', translate('title'), 'trim|required');
            if (!isset($_POST['holiday'])) {
                $this->form_validation->set_rules('type_id', translate('type'), 'trim|required');
                $this->form_validation->set_rules('audition', translate('audition'), 'trim|required');
                $audition = $this->input->post('audition');
            } else {
                $audition = 1;
            }
            
            $this->form_validation->set_rules('daterange', translate('date'), 'trim|required');
            
            if ($audition == 2) {
                $this->form_validation->set_rules('selected_audience[]', translate('class'), 'trim|required');
            } elseif ($audition == 3) {
                $this->form_validation->set_rules('selected_audience[]', translate('section'), 'trim|required');
            }
            $this->form_validation->set_rules('user_photo', 'profile_picture', 'callback_photoHandleUpload[user_photo]');
            if ($this->form_validation->run() !== false) {
                if ($audition != 1) {
                    $selectedList = array();
                    foreach ($this->input->post('selected_audience') as $user) {
                        array_push($selectedList, $user);
                    }
                } else {
                    $selectedList = null;
                }
                $holiday = $this->input->post('holiday');
                if (empty($holiday)) {
                    $type = $this->input->post('type_id');
                } else {
                    $type = 'holiday';
                }
                $daterange = explode(' - ', $this->input->post('daterange'));
                $start_date = date("Y-m-d", strtotime($daterange[0]));
                $end_date = date("Y-m-d", strtotime($daterange[1]));

                $blog_image = 'defualt.png';
                if (isset($_FILES["user_photo"]) && $_FILES['user_photo']['name'] != '' && (!empty($_FILES['user_photo']['name']))) {
                    $blog_image = $this->blog_model->fileupload("user_photo", "./uploads/frontend/blogs/",'', false);
                }
                $arrayBlog = array(
                    'branch_id' => $branchID,
                    'type' => $type,
                    'audition' => $audition,
                    'image' => $blog_image,
                    'selected_list' => json_encode($selectedList),
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                );
                $this->blog_model->save($arrayBlog);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('blog');
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('blogs');
        $this->data['sub_page'] = 'blog/index';
        $this->data['main_menu'] = 'blog';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/summernote/summernote.css',
                'vendor/daterangepicker/daterangepicker.css',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.css',
            ),
            'js' => array(
                'vendor/summernote/summernote.js',
                'vendor/moment/moment.js',
                'vendor/daterangepicker/daterangepicker.js',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id='')
    {
        // check access permission
        if (!get_permission('blog', 'is_edit')) {
            access_denied();
        }
        $this->data['blog'] = $this->app_lib->getTable('blog', array('t.id' => $id), true);
        if (empty($this->data['blog'])) {
            redirect('dashboard');
        }

        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }
            $this->form_validation->set_rules('title', translate('title'), 'trim|required');
            if (!isset($_POST['holiday'])) {
                $this->form_validation->set_rules('type_id', translate('type'), 'trim|required');
                $this->form_validation->set_rules('audition', translate('audition'), 'trim|required');
                $audition = $this->input->post('audition');
            } else {
                $audition = 1;
            }
            
            $this->form_validation->set_rules('daterange', translate('date'), 'trim|required');
            
            if ($audition == 2) {
                $this->form_validation->set_rules('selected_audience[]', translate('class'), 'trim|required');
            } elseif ($audition == 3) {
                $this->form_validation->set_rules('selected_audience[]', translate('section'), 'trim|required');
            }
            $this->form_validation->set_rules('user_photo', 'profile_picture', 'callback_photoHandleUpload[user_photo]');
            if ($this->form_validation->run() !== false) {
                if ($audition != 1) {
                    $selectedList = array();
                    foreach ($this->input->post('selected_audience') as $user) {
                        array_push($selectedList, $user);
                    }
                } else {
                    $selectedList = null;
                }
                $holiday = $this->input->post('holiday');
                if (empty($holiday)) {
                    $type = $this->input->post('type_id');
                } else {
                    $type = 'holiday';
                }
                $daterange = explode(' - ', $this->input->post('daterange'));
                $start_date = date("Y-m-d", strtotime($daterange[0]));
                $end_date = date("Y-m-d", strtotime($daterange[1]));

                $blog_image = $this->input->post('old_blog_image');
                if (isset($_FILES["user_photo"]) && $_FILES['user_photo']['name'] != '' && (!empty($_FILES['user_photo']['name']))) {
                    $blogimage = ($blog_image == 'defualt.png' ? '' : $blog_image);
                    $blog_image = $this->blog_model->fileupload("user_photo", "./uploads/frontend/blogs/", $blogimage, false);
                }

                $arrayBlog = array(
                    'id' => $this->input->post('id'),
                    'branch_id' => $branchID,
                    'type' => $type,
                    'audition' => $audition,
                    'image' => $blog_image,
                    'selected_list' => json_encode($selectedList),
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                );
                $this->blog_model->save($arrayBlog);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('blog');
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('blogs');
        $this->data['sub_page'] = 'blog/edit';
        $this->data['main_menu'] = 'blog';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/summernote/summernote.css',
                'vendor/daterangepicker/daterangepicker.css',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.css',
            ),
            'js' => array(
                'vendor/summernote/summernote.js',
                'vendor/moment/moment.js',
                'vendor/daterangepicker/daterangepicker.js',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        // check access permission
        if (get_permission('blog', 'is_delete')) {
            $blog_db = $this->db->where('id', $id)->get('blog')->row_array();
            $file_name = $blog_db['image'];
            if ($blog_db['created_by'] == get_loggedin_user_id() || is_superadmin_loggedin()) {
                $this->db->where('id', $id);
                $this->db->delete('blog');
                if ($file_name !== 'defualt.png') {
                    $file_name = 'uploads/frontend/blogs/' . $file_name;
                    if (file_exists($file_name)) {
                        unlink($file_name);
                    }
                }
            } else {
                set_alert('error', 'You do not have permission to delete');
            }
        } else {
            set_alert('error', translate('access_denied'));
        }
    }

    /* types form validation rules */
    protected function types_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('type_name', translate('name'), 'trim|required|callback_unique_type');
    }

    // exam term information are prepared and stored in the database here
    public function types()
    {
        if (isset($_POST['save'])) {
            if (!get_permission('blog_type', 'is_add')) {
                access_denied();
            }
            $this->types_validation();
            if ($this->form_validation->run() !== false) {
                //save information in the database file
                $data['name'] = $this->input->post('type_name');
                $data['icon'] = $this->input->post('blog_icon');
                $data['branch_id'] = $this->application_model->get_branch_id();
                $this->db->insert('blog_types', $data);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(current_url());
            }
        }
        $this->data['typelist'] = $this->app_lib->getTable('blog_types');
        $this->data['sub_page'] = 'blog/types';
        $this->data['main_menu'] = 'blog';
        $this->data['title'] = translate('blog_type');
        $this->load->view('layout/index', $this->data);
    }

    public function types_edit()
    {
        if ($_POST) {
            if (!get_permission('blog_type', 'is_edit')) {
                ajax_access_denied();
            }
            $this->types_validation();
            if ($this->form_validation->run() !== false) {
                //save information in the database file
                $data['name'] = $this->input->post('type_name');
                $data['icon'] = $this->input->post('blog_icon');
                $data['branch_id'] = $this->application_model->get_branch_id();
                $this->db->where('id', $this->input->post('type_id'));
                $this->db->update('blog_types', $data);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('blog/types');
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function type_delete($id)
    {
        if (!get_permission('blog_type', 'is_delete')) {
            access_denied();
        }
        if (!is_superadmin_loggedin()) {
            $this->db->where('branch_id', get_loggedin_branch_id());
        }
        $this->db->where('id', $id);
        $this->db->delete('blog_types');
    }

    /* unique valid type name verification is done here */
    public function unique_type($name)
    {
        $branchID = $this->application_model->get_branch_id();
        $type_id = $this->input->post('type_id');
        if (!empty($type_id)) {
            $this->db->where_not_in('id', $type_id);
        }
        $this->db->where(array('name' => $name, 'branch_id' => $branchID));
        $uniform_row = $this->db->get('blog_types')->num_rows();
        if ($uniform_row == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_type", translate('already_taken'));
            return false;
        }
    }

    // publish on show website
    public function show_website()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        if ($status == 'true') {
            $arrayData['show_web'] = 1;
        } else {
            $arrayData['show_web'] = 0;
        }
        if (!is_superadmin_loggedin()) {
            $this->db->where('branch_id', get_loggedin_branch_id());
        }
        $this->db->where('id', $id);
        $this->db->update('blog', $arrayData);
        $return = array('msg' => translate('information_has_been_updated_successfully'), 'status' => true);
        echo json_encode($return);
    }

    // publish status
    public function status()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        if ($status == 'true') {
            $arrayData['status'] = 1;
        } else {
            $arrayData['status'] = 0;
        }
        if (!is_superadmin_loggedin()) {
            $this->db->where('branch_id', get_loggedin_branch_id());
        }
        $this->db->where('id', $id);
        $this->db->update('blog', $arrayData);
        $return = array('msg' => translate('information_has_been_updated_successfully'), 'status' => true);
        echo json_encode($return);
    }

    public function getDetails()
    {
        $id = $this->input->post('blog_id');
        if (empty($id)) {
            redirect(base_url(), 'refresh');
        }

        $auditions = array("1" => "everybody", "2" => "class", "3" => "section");
        if (!is_superadmin_loggedin()) {
            $this->db->where('branch_id', get_loggedin_branch_id());
        }
        $this->db->where('id', $id);
        $ev = $this->db->get('blog')->row_array();
        $type = $ev['type'] == 'holiday' ? translate('holiday') : get_type_name_by_id('blog_types', $ev['type']);
        $remark = (empty($ev['remark']) ? 'N/A' : $ev['remark']);
        $html = "<tbody><tr>";
        $html .= "<td>" . translate('title') . "</td>";
        $html .= "<td>" . $ev['title'] . "</td>";
        $html .= "</tr><tr>";
        $html .= "<td>" . translate('type') . "</td>";
        $html .= "<td>" . $type . "</td>";
        $html .= "</tr><tr>";
        $html .= "<td>" . translate('date_of_start') . "</td>";
        $html .= "<td>" . _d($ev['start_date']) . "</td>";
        $html .= "</tr><tr>";
        $html .= "<td>" . translate('date_of_end') . "</td>";
        $html .= "<td>" . _d($ev['end_date']) . "</td>";
        $html .= "</tr><tr>";
        $html .= "<td>" . translate('audience') . "</td>";
        $audition = $auditions[$ev['audition']];
        $html .= "<td>" . translate($audition);
        if ($ev['audition'] != 1) {
            $selecteds = json_decode($ev['selected_list']);
            if ($ev['audition'] == 2) {
                foreach ($selecteds as $selected) {
                    $html .= "<br> <small> - " .  get_type_name_by_id('class', $selected) . '</small>';
                }
            }
            if ($ev['audition'] == 3) {
                foreach ($selecteds as $selected) {
                    $selected = explode('-', $selected);
                    $html .= "<br> <small> - " .  get_type_name_by_id('class', $selected[0]) . " (" . get_type_name_by_id('section', $selected[1])  .  ')</small>';
                }
            }
        }
        $html .= "</td>";
        $html .= "</tr><tr>";
        $html .= "<td>" . translate('description') . "</td>";
        $html .= "<td>" . $remark . "</td>";
        $html .= "</tr></tbody>";
        echo $html;
    }

    /* generate section with class group */
    public function getSectionByBranch()
    {
        $html = "";
        $branchID = $this->application_model->get_branch_id();
        if (!empty($branchID)) {
            $result = $this->db->get_where('class', array('branch_id' => $branchID))->result_array();
            if (count($result)) {
                foreach ($result as $class) {
                    $html .= '<optgroup label="' . $class['name'] . '">';
                    $allocations = $this->db->get_where('sections_allocation', array('class_id' => $class['id']))->result_array();
                    if (count($allocations)) {
                        foreach ($allocations as $allocation) {
                            $section = $this->db->get_where('section', array('id' => $allocation['section_id']))->row_array();
                            $html .= '<option value="' . $class['id']. "-" .$allocation['section_id'] . '">' . $section['name'] . '</option>';
                        }
                    } else {
                        $html .= '<option value="">' . translate('no_selection_available') . '</option>';
                    }
                    $html .= '</optgroup>';
                }
            }
        }
        echo $html;
    }

    public function get_blogs_list($branchID = '')
    {
        if (is_loggedin()) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            } else {
                $this->db->where('branch_id', $branchID);
            }
            $this->db->where('status', 1);
            $blogs = $this->db->get('blog')->result();
            if (!empty($blogs)) {
                foreach ($blogs as $row) {
                    $arrayData = array(
                        'id' => $row->id,
                        'title' => $row->title,
                        'start' => $row->start_date,
                        'end' => date('Y-m-d', strtotime($row->end_date . "+1 days")),
                    );
                    if ($row->type == 'holiday') {
                        $arrayData['className'] = 'fc-blog-danger';
                        $arrayData['icon'] = 'umbrella-beach';
                    } else {
                        $icon = get_type_name_by_id('blog_types', $row->type, 'icon');
                        $arrayData['icon'] = $icon;
                    }
                    $blogdata[] = $arrayData;
                }
                echo json_encode($blogdata);
            }
        }
    }
}
