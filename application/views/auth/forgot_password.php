


<div class="container page">
			<div class="row">
				<div class="col-md-12 mTop20 text-center">
					<h3 class="mBottom30"> <?php echo lang('forgot_password_heading');?></h3>
					<p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>

					<div id="infoMessage"><?php echo $message;?></div>

					<?php echo form_open("auth/forgot_password", array('class'=>'form-inline'));?>
					<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9"><?php echo sprintf(lang('forgot_password_email_label'), $identity_label);?></label>
					<?php echo form_input($email);?>
					</div>
						<div class="form-group">    
						<input type="submit" class="btn btn-primary  uppercase" value="<?php echo lang('forgot_password_submit_btn');?>">
					</div>

					<?php echo form_close();?>
					
				</div>
				

			</div>	
		</div>
