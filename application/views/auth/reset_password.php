




<div class="container page">
			<div class="row">
				<div class="col-md-12 mTop20 text-left">
					<h3 class="mBottom30"> <?php echo lang('reset_password_heading');?></h3>

					<div id="infoMessage"><?php echo $message;?></div>

					<?php echo form_open('auth/reset_password/' . $code);?>
					<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
					<?php echo form_input($new_password);?>
					</div>
					<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9"><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?></label>
					<?php echo form_input($new_password_confirm);?>
					</div>
						<div class="form-group">    
							<?php echo form_input($user_id);?>
							<?php echo form_hidden($csrf); ?>
						<input type="submit" class="btn btn-primary  uppercase" value="<?php echo lang('reset_password_submit_btn');?>">
					</div>

					<?php echo form_close();?>
					
				</div>
				

			</div>	
		</div>