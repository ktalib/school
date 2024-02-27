<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('blog/index') ?>">
				  <i class="fas fa-list-ul"></i> <?=translate('blog_list')?>
				</a>
			</li>
			<li class="active">
				<a href="#add" data-toggle="tab">
				 <i class="far fa-edit"></i> <?=translate('edit_blog')?>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="add">
					<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-bordered form-horizontal frm-submit-data'));?>
					<input type="hidden" name="id" value="<?php echo $blog['id'] ?>">	
					<?php if (is_superadmin_loggedin()): ?>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate('branch')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									$arrayBranch = $this->app_lib->getSelectList('branch');
									echo form_dropdown("branch_id", $arrayBranch, $blog['branch_id'], "class='form-control' data-width='100%' id='branch_id'
									data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('title')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title" value="<?php echo $blog['title'] ?>" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-3" style="display: none;">
							<div class="ml-md checkbox-replace">
								<label class="i-checks"><input type="checkbox" <?php echo $blog['type'] == 'holiday' ? 'checked' : '' ?> name="holiday" id="chk_holiday"><i></i> Holiday</label>
							</div>
						</div>
						<div id="typeDiv" <?php echo $blog['type'] == 'holiday' ? 'style="display: none;"' : '' ?> >
							<div class="mt-md">
								<label class="col-md-3 control-label"><?=translate('type')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<?php
										$array = $this->app_lib->getSelectByBranch('blog_types', $blog['branch_id']);
										echo form_dropdown("type_id", $array, $blog['type'], "class='form-control' id='type_id'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group" id='auditionDiv' <?php echo $blog['type'] == 'holiday' ? 'style="display: none;"' : '' ?> style="display: none;">
						<label class="col-md-3 control-label"><?=translate('audience')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayAudition = array(
									"" => translate('select'),
									"1" => translate('everybody'),
									"2" => translate('selected_class'),
									"3" => translate('selected_section'),
								);
								echo form_dropdown("audition", $arrayAudition, $blog['audition'], "class='form-control' id='audition'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group" id="selected_user" <?php echo $blog['audition'] == 1 ? 'style="display: none;"' : ''?>>
						<label class="col-md-3 control-label" id="selected_label"> <?=translate('audience')?> <span class="required">*</span> </label>
						<div class="col-md-6">
							<?php
								$placeholder = '{"placeholder": "' . translate('select') . '"}';
								echo form_dropdown("selected_audience[]", [], '', "class='form-control' data-plugin-selectTwo 
								data-plugin-options='$placeholder' data-plugin-selectTwo data-width='100%' id='selected_audience' multiple");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('date')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
								<input type="text" class="form-control" name="daterange" id="daterange" value="<?=set_value('daterange', $blog['start_date'] . ' - ' . $blog['end_date'])?>" />
							</div>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('description')?></label>
						<div class="col-md-6">
							<textarea name="remarks" class="summernote"><?php echo $blog['remark'] ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('show_website')?></label>
						<div class="col-md-6">
							<div class="material-switch ml-xs">
								<input id="aswitch_1" name="show_website" <?php echo $blog['show_web'] == 1 ? 'checked' : '' ?> type="checkbox" />
								<label for="aswitch_1" class="label-primary"></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('image')?></label>
						<div class="col-md-6">
							<input type="hidden" name="old_blog_image" value="<?php echo $blog['image'] ?>">
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
									<i class="fas fa-plus-circle"></i> <?=translate('update')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>

		</div>
	</div>
</section>

<script type = "text/javascript">
    $(document).ready(function() {
        $('#daterange').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY/MM/DD'
            }
        });

        $('#branch_id').on('change', function() {
            var branchID = $(this).val();
            $.ajax({
                url: "<?=base_url('ajax/getDataByBranch')?>",
                type: 'POST',
                data: {
                    branch_id: branchID,
                    table: 'blog_types'
                },
                success: function(data) {
                    $('#type_id').html(data);
                }
            });
            $("#selected_audience").empty();
        });
        $('#audition').on('change', function() {
            var audition = $(this).val();
            var branchID = ($('#branch_id').length ? $('#branch_id').val() : "");
            auditionAjax(audition, branchID);
        });
        auditionAjax(<?php echo $blog['audition'] ?>, <?php echo $blog['branch_id'] ?>);
    });

	function auditionAjax(audition = '', branchID = '') {
	    if (audition == "1" || audition == null) {
	        $("#selected_user").hide("slow");
	    } else {
	        if (audition == "2") {
	            $.ajax({
	                url: base_url + 'ajax/getClassByBranch',
	                type: 'POST',
	                data: {
	                    branch_id: branchID
	                },
	                success: function(data) {
	                    $('#selected_audience').html(data);
	                }
	            });
	            $("#selected_user").show('slow');
	            $("#selected_label").html("<?=translate('class')?> <span class='required'>*</span>");
	        }
	        if (audition == "3") {
	            $.ajax({
	                url: "<?=base_url('blog/getSectionByBranch')?>",
	                type: 'POST',
	                data: {
	                    branch_id: branchID
	                },
	                success: function(data) {
	                    $('#selected_audience').html(data);
	                }
	            });
	            $("#selected_user").show('slow');
	            $("#selected_label").html("<?=translate('section')?> <span class='required'>*</span>");
	        }
	        setTimeout(function() {
	            var JSONObject = JSON.parse('<?php echo $blog['selected_list'] ?>');
	            for (var i = 0, l = JSONObject.length; i < l; i++) {
	                $("#selected_audience option[value='" + JSONObject[i] + "']").prop("selected", true);
	            }
	            $('#selected_audience').trigger('change.select2');
	        }, 200);
	    }
	}
</script>