
<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
                <a href="#list" data-toggle="tab">
                    <i class="fas fa-list-ul"></i> <?=translate('blog_list')?>
                </a>
			</li>
<?php if (get_permission('blog', 'is_add')): ?>
			<li>
                <a href="#add" data-toggle="tab">
                   <i class="far fa-edit"></i> <?=translate('create_blog')?>
                </a>
			</li>
<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane box active mb-md" id="list">
				<table class="table table-bordered table-hover mb-none tbr-top table-export">
					<thead>
						<tr>
							<th>#</th>
						<?php if (is_superadmin_loggedin()): ?>
							<th><?=translate('branch')?></th>
						<?php endif; ?>
							<th><?=translate('title')?></th>
							<th><?=translate('image')?></th>
							<th><?=translate('type')?></th>
							<th><?=translate('date_of_start')?></th>
							<th><?=translate('date_of_end')?></th>
							<th><?=translate('audience')?></th>
							<th><?=translate('created_by')?></th>
							<th class="no-sort"><?=translate('show_website')?></th>
							<th class="no-sort"><?=translate('publish')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						if (!is_superadmin_loggedin()) {
							$this->db->where('branch_id', get_loggedin_branch_id());
						}
						$this->db->order_by('id', 'asc');
						$blogs = $this->db->get('blog')->result();
						foreach ($blogs as $blog):
						?>
						<tr>
							<td><?php echo $count++; ?></td>
						<?php if (is_superadmin_loggedin()): ?>
							<td><?php echo get_type_name_by_id('branch', $blog->branch_id);?></td>
						<?php endif; ?>
							<td><?php echo $blog->title; ?></td>
							<td class="center"><img src="<?=base_url('uploads/frontend/blogs/' . $blog->image )?>" height="60" /></td>
							<td><?php
									if($blog->type != 'holiday'){
										echo get_type_name_by_id('blog_types', $blog->type);
									}else{
										echo translate('holiday'); 
									}
								?></td>
							<td><?php echo _d($blog->start_date);?></td>
							<td><?php echo _d($blog->end_date);?></td>
							<td><?php
								$auditions = array(
									"1" => "everybody",
									"2" => "class",
									"3" => "section",
								);
								$audition = $auditions[$blog->audition];
								echo translate($audition);
								if($blog->audition != 1){
									if ($blog->audition == 2) {
										$selecteds = json_decode($blog->selected_list); 
										foreach ($selecteds as $selected) {
											echo "<br> <small> - " . get_type_name_by_id('class', $selected) . '</small>' ;
										}
									} 
									if ($blog->audition == 3) {
										$selecteds = json_decode($blog->selected_list); 
										foreach ($selecteds as $selected) {
											$selected = explode('-', $selected);
											echo "<br> <small> - " . get_type_name_by_id('class', $selected[0]) . " (" . get_type_name_by_id('section', $selected[1])  . ')</small>' ;
										}
									}
								}
							?></td>
							<td><?php echo get_type_name_by_id('staff', $blog->created_by); ?></td>
							<td>
							<?php if (get_permission('blog', 'is_edit')) { ?>
								<div class="material-switch ml-xs">
									<input class="blog-website" id="websiteswitch_<?=$blog->id?>" data-id="<?=$blog->id?>" name="evt_switch_website<?=$blog->id?>" 
									type="checkbox" <?php echo ($blog->show_web == 1 ? 'checked' : ''); ?> />
									<label for="websiteswitch_<?=$blog->id?>" class="label-primary"></label>
								</div>
							<?php } ?>
							</td>
							<td>
							<?php if (get_permission('blog', 'is_edit')) { ?>
								<div class="material-switch ml-xs">
									<input class="blog-switch" id="switch_<?=$blog->id?>" data-id="<?=$blog->id?>" name="evt_switch<?=$blog->id?>" 
									type="checkbox" <?php echo ($blog->status == 1 ? 'checked' : ''); ?> />
									<label for="switch_<?=$blog->id?>" class="label-primary"></label>
								</div>
							<?php } ?>
							</td>
							<td class="action">
								<!-- view modal link -->
								<a href="javascript:void(0);" class="btn btn-circle btn-default icon" onclick="viewblog('<?=$blog->id?>');">
									<i class="far fa-eye"></i>
								</a>
							<?php if (get_permission('blog', 'is_edit')) { ?>
								<!-- edit link -->
								<a href="<?php echo base_url('blog/edit/'.$blog->id); ?>" class="btn btn-circle btn-default icon"><i class="fas fa-pen-nib"></i></a>
							<?php } ?>
							<?php if (get_permission('blog', 'is_delete')) { ?>
								<!-- deletion link -->
								<?php echo btn_delete('blog/delete/'.$blog->id);?>
							<?php } ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
<?php if (get_permission('blog', 'is_add')): ?>
			<div class="tab-pane" id="add">
					<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-bordered form-horizontal frm-submit-data'));?>
					<?php if (is_superadmin_loggedin()): ?>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate('branch')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									$arrayBranch = $this->app_lib->getSelectList('branch');
									echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-width='100%' id='branch_id'
									data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('title')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title" value="" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-3" style="display: none;">
							<div class="ml-md checkbox-replace">
								<label class="i-checks"><input type="checkbox" name="holiday" id="chk_holiday"><i></i> Holiday</label>
							</div>
						</div>
						<div id="typeDiv">
							<div class="mt-md">
								<label class="col-md-3 control-label"><?=translate('type')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
										$array = $this->app_lib->getSelectByBranch('blog_types', $branch_id);
										echo form_dropdown("type_id", $array, set_value('type_id'), "class='form-control' id='type_id'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group" id='auditionDiv' style="display: none;">
						<label class="col-md-3 control-label"><?=translate('audience')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayAudition = array(
									 
									"1" => translate('everybody'),
									"2" => translate('selected_class'),
									"3" => translate('selected_section'),
								);
								echo form_dropdown("audition", $arrayAudition, set_value('audition'), "class='form-control' id='audition'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group" id="selected_user" style="display: none;">
						<label class="col-md-3 control-label" id="selected_label"> <?=translate('audience')?> <span class="required">*</span> </label>
						<div class="col-md-6">
							<?php
								$placeholder = '{"placeholder": "' . translate('select') . '"}';
								echo form_dropdown("selected_audience[]", $array, set_value('selected_audience'), "class='form-control' data-plugin-selectTwo multiple
								data-plugin-options='$placeholder' data-plugin-selectTwo data-width='100%' id='selected_audience' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('date')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
								<input type="text" class="form-control" name="daterange" id="daterange" 
								value="<?=set_value('daterange', date("Y/m/d") . ' - ' . date("Y/m/d", strtotime("+2 day")))?>" />
							</div>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('description')?></label>
						<div class="col-md-6">
							<textarea name="remarks" class="summernote"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('show_website')?></label>
						<div class="col-md-6">
							<div class="material-switch ml-xs">
								<input id="aswitch_1" name="show_website" 
								type="checkbox" />
								<label for="aswitch_1" class="label-primary"></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('image')?></label>
						<div class="col-md-6">
							<div class="fileupload fileupload-new" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview"></span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="user_photo" />
									</span>
									<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
								</div>
							</div>
							<span class="error"></span>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php endif; ?>
		</div>
	</div>
</section>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-btn">
				<button onclick="fn_printElem('printResult')" class="btn btn-default btn-circle icon" ><i class="fas fa-print"></i></button>
			</div>
			<h4 class="panel-title"><i class="fas fa-info-circle"></i> <?=translate('blog_details')?></h4>
		</header>
		<div class="panel-body">
			<div id="printResult" class="pt-sm pb-sm">
				<div class="table-responsive">						
					<table class="table table-bordered table-condensed text-dark tbr-top" id="ev_table"></table>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss">
						<?=translate('close')?>
					</button>
				</div>
			</div>
		</footer>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#daterange').daterangepicker({
			opens: 'left',
		    locale: {format: 'YYYY/MM/DD'}
		});

		$('#branch_id').on('change', function() {
			var branchID = $(this).val();
			$.ajax({
				url: "<?=base_url('ajax/getDataByBranch')?>",
				type: 'POST',
				data: {
					branch_id: branchID,
					table : 'blog_types'
				},
				success: function (data) {
					$('#type_id').html(data);
				}
			});
			$("#selected_audience").empty();
		});
		
		$('#audition').on('change', function() {
			var audition = $(this).val();
			var branchID = ($('#branch_id').length ? $('#branch_id').val() : "");
			if(audition == "1" || audition == null)
			{
				$("#selected_user").hide("slow");
			}
			if(audition == "2") {
			    $.ajax({
			        url: base_url + 'ajax/getClassByBranch',
			        type: 'POST',
			        data:{ branch_id: branchID },
			        success: function (data){
			            $('#selected_audience').html(data);
			        }
			    });
				$("#selected_user").show('slow');
				$("#selected_label").html("<?=translate('class')?> <span class='required'>*</span>");
			}
			if(audition == "3"){
				$.ajax({
					url: "<?=base_url('blog/getSectionByBranch')?>",
					type: 'POST',
					data: {branch_id: branchID},
					success: function (data) {
						$('#selected_audience').html(data);
					}
				});
				$("#selected_user").show('slow');
				$("#selected_label").html("<?=translate('section')?> <span class='required'>*</span>");
			}
		});
	});
</script>