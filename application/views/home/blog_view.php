<!-- Main Banner Starts -->
<div class="main-banner" style="background: url(<?php echo base_url('uploads/frontend/blogs/blog.png'); ?>) center top;">
    <div class="container px-md-0">
        <h2><span>Blog Posts</span></h2>
    </div>
</div>

<div class="breadcrumb">
    <div class="container px-md-0">
        <ul class="list-unstyled list-inline">
            <li class="list-inline-item"><a href="<?php echo base_url('home') ?>">Home</a></li>
            <li class="list-inline-item active">Blog Post</li>
        </ul>
    </div>
</div>
<div class="container px-md-0 main-container">
    <div class="row">
        <div class="col-lg-9 col-md-8 col-sm-12">
            <div class="news-post-list">
                <article class="news-post">
                    <div class="text-center">
                        <img src="<?=base_url('uploads/frontend/blogs/' . $blog['image'] )?>" alt="Blog Image" class="img-fluid">
                    </div>
                    <div class="inner">
                        <h4>
                            <a href="#"><?php echo $blog['title'] ?></a>
                        </h4>
                        <ul class="list-unstyled list-inline post-meta">
                            
                            <li class="list-inline-item">
                                <i class="fa fa-user"></i> 
                                By <a href="#"><?=get_type_name_by_id('staff', $blog['created_by'], 'name')?></a>
                            </li>
                            <li class="list-inline-item">
                                <i class="fa fa-tag"></i>
                                <?php
                                    if($blog['type'] != 'holiday'){
                                        echo get_type_name_by_id('blog_types', $blog['type']);
                                    }else{
                                        echo translate('holiday'); 
                                    }
                                ?>
                            </li>
                        </ul>
                        <?php
                        if (!empty($blog['audition'])) {
                            $auditions = array(
                                "1" => "everybody",
                                "2" => "class",
                                "3" => "section",
                            ); 
                            $audition = $auditions[$blog['audition']];
                            $subtxt = "";
                            if($blog['audition'] != 1) {
                                $array = array();
                                if ($blog['audition'] == 2) {
                                    $selecteds = json_decode($blog['selected_list']); 
                                    foreach ($selecteds as $selected) {
                                       $array[] = get_type_name_by_id('class', $selected);
                                    }
                                } 
                                if ($blog['audition'] == 3) {
                                    $selecteds = json_decode($blog['selected_list']); 
                                    foreach ($selecteds as $selected) {
                                        $selected = explode('-', $selected);
                                        $array[] =  get_type_name_by_id('class', $selected[0]) . " (" . get_type_name_by_id('section', $selected[1])  . ')' ;
                                    }
                                }
                                $subtxt = " (" . implode(', ', $array) . ")";
                            }
                            echo "<h5>Blog For : " . translate($audition) . $subtxt . "</h5>";
                        }
                        ?>
                        <p><?php echo $blog['remark'] ?></p>
                    </div>
                </article>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-12">
            <h4 class="side-heading1 top">Recent Blogs</h4>
            <ul class="list-unstyled recent-comments-list">
                <?php
                $url_alias = $cms_setting['url_alias'];
                $start_date = date('Y-m-d', strtotime("+7 day"));
                $end_date = date('Y-m-d');
                $this->db->limit(6);
                $this->db->where('start_date >=', $end_date);
                $this->db->where('start_date <=', $start_date);
                $this->db->where('branch_id', $branchID);
                $this->db->where('status', 1);
                $this->db->where('show_web', 1);
                $this->db->order_by("id", "desc");
                $q = $this->db->get('blog');
                if ($q->num_rows() > 0) {
                    $result = $q->result_array();
                    foreach ($result as $key => $value) {
                ?>
                <li>
                    <p>
                        <a href="<?=base_url('home/blog_view/'. $value['id'] . "/" . $url_alias)?>">
                            <?php echo $value['title'] ?>
                        </a>
                    </p>
                    <span class="date-stamp"><?php echo get_nicetime($value['created_at']) ?></span>
                </li>
                <?php } } ?>
            </ul>
            </div>
        </div>
    </div>
</div>
<!-- Main Container Ends -->