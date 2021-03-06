<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<div class="admin-box">

    <h3><?php echo lang('sim_setting_title') ?></h3>

    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

    <fieldset>
		
			<!-- Auto Calculate Sim Length -->
        <div class="control-group <?php echo form_error('auto_sim_length') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('sim_settings_autocalc') ?></label>
            <div class="controls">
				<?php
				$use_selection = ((isset($settings['osp.auto_sim_length']) && $settings['osp.auto_sim_length'] == 1) || !isset($settings['osp.auto_sim_length'])) ? true : false;
				echo form_checkbox('auto_sim_length',1, $use_selection,'id="auto_sim_length"');
				?>
                <span class="help-inline">
				<?php if (form_error('auto_sim_length')) echo form_error('auto_sim_length'); ?>
				<?php echo lang('dbrd_settings_calclen')." ".((isset($settings['osp.calc_length'])) ? $settings['osp.calc_length']: '---')." ".lang('sim_setting_simlen_note'); ?>
				</span>
            </div>
        </div>
		
			<!-- Manual Sim length -->
		<div class="control-group <?php echo form_error('sim_length') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('sim_setting_simlen') ?></label>
            <div class="controls">
				<input type="text" class="span1" id="sim_length" name="sim_length" value="<?php echo (isset($settings['osp.sim_length'])) ? $settings['osp.sim_length']: set_value('osp.sim_length'); ?>" />
				<span class="help-inline"><?php if (form_error('sim_length')) echo form_error('sim_length'); else echo lang('sim_setting_simlen_note'); ?></span>
            </div>
        </div>
	
			<!-- Sims per week -->
		 <div class="control-group <?php echo form_error('sims_per_week') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sim_setting_perweek') ?></label>
			<div class="controls">
				<select class="span1" id="sims_per_week" name="sims_per_week">
				<?php
					for( $i = 1; $i < 8; $i++){
						echo('<option value="'.$i.'"');
						if (isset($settings['osp.sims_per_week']) && $settings['osp.sims_per_week'] == $i) {
							echo(' selected="selected"');
						}
						echo('">'.$i.'</option>');
					} 
				?>
				</select><span class="help-inline"><?php if (form_error('sims_per_week')) echo form_error('sims_per_week'); ?></span>
			</div>
		</div>

			<!-- Sims occur on -->

		 <div class="control-group <?php echo form_error('') ? 'error' : '' ?>">
			 <label class="control-label"><?php echo lang('sim_setting_occuron'); ?></label>
			 <div class="controls">
				 <?php
				 $dayList = (isset($settings['osp.sims_occur_on']) ? unserialize($settings['osp.sims_occur_on']) : array(-1));
				 $days = array(1=>'Su',2=>'Mo',3=>'Tu',4=>'We',5=>'Th',6=>'Fr',7=>'Sa');
				 foreach($days as $num => $day) :
					 $use_selection = (isset($dayList) && is_array($dayList) && in_array($num,$dayList)) ? true : false;
					 echo form_checkbox('sims_occur_on[]',$num, $use_selection,'id="sims_occur_on_'.$num.'"')." ".$day." ";
				 endforeach;
				 ?>
				<span class="help-inline"><?php if (form_error('')) echo form_error(''); else echo lang(''); ?></span>
			</div>
		</div>
		
		<div>

	</div>

		<!-- OOTP DETAILS OVERRIDE -->

	 <div class="control-group <?php echo form_error('sim_details') ? 'error' : '' ?>">
		<label class="control-label"><?php echo lang('lm_settings_useootp') ?></label>
		<div class="controls">
			<?php
			$use_selection = ((isset($settings['osp.sim_details']) && $settings['osp.sim_details'] == 1) || !isset($settings['osp.sim_details'])) ? true : false;
			echo form_checkbox('sim_details',1, $use_selection,'id="sim_details"');
			?>
			<span class="help-inline"><?php if (form_error('sim_details')) echo form_error('sim_details'); ?></span>
		</div>
	</div>
	
	<div id="ootp_block">
            <!-- LEAGUE NAME -->
		 <div class="control-group <?php echo form_error('league_file_date') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sim_setting_league_file_date') ?></label>
			<div class="controls">
				<input type="text" class="span3" id="league_file_date" name="league_file_date" value="<?php echo (isset($settings['osp.league_file_date']) && !empty($settings['osp.league_file_date'])) ? date('m/d/Y',$settings['osp.league_file_date']): set_value('osp.league_file_date'); ?>" />
				<?php if (form_error('league_file_date')) echo '<span class="help-inline">'.form_error('league_file_date').'</span>'; ?>
			</div>
		</div>

            <!-- LEAGUE ABBR -->
		 <div class="control-group <?php echo form_error('next_sim') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sim_setting_next_sim') ?></label>
			<div class="controls">
				<input type="text" class="span3" id="next_sim" name="next_sim" value="<?php echo (isset($settings['osp.next_sim']) && !empty($settings['osp.next_sim'])) ? date('m/d/Y',$settings['osp.next_sim']): set_value('osp.next_sim'); ?>" />
				<?php if (form_error('next_sim')) echo '<span class="help-inline">'.form_error('next_sim').'</span>'; ?>
			</div>
		</div>

            <!-- LEAGUE ICON -->
		 <div class="control-group <?php echo form_error('league_date') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sim_setting_league_date') ?></label>
			<div class="controls">
				<input type="text" class="span3" id="league_date" name="league_date" value="<?php echo (isset($settings['osp.league_date']) && !empty($settings['osp.league_date'])) ? date('m/d/Y',$settings['osp.league_date']): set_value('osp.league_date'); ?>" />
				<?php if (form_error('league_date')) echo '<span class="help-inline">'.form_error('league_date').'</span>'; ?>
			</div>
		</div>

            <!-- LEAGUE Text Color -->
		 <div class="control-group <?php echo form_error('league_event') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sim_setting_league_event') ?></label>
			<div class="controls">
				<select id="league_event" name="league_event" class="span3" >
				<?php
				if (isset($events) && is_array($events) && count($events)):
					foreach( $events as $event):
						echo('<option value="'.$event['name'].'"');
						if (isset($settings['osp.league_event']) && $settings['osp.league_event'] == $event['name']):
							echo(' selected="selected"');
						endif;
						echo('">'.date('m/d/Y',strtotime($event['start_date'])).' - '.$event['name'].'</option>');
					endforeach;
				endif;
				?>
				</select>
				<?php if (form_error('league_event')) echo '<span class="help-inline">'.form_error('league_event').'</span>'; ?>
			</div>
		</div>
    </div>
	
	<div class="form-actions">
        <input type="submit" name="submit" class="btn primary" value="<?php echo lang('bf_action_save') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/settings', lang('bf_action_cancel')); ?>
    </div>
	
<?php echo form_close(); ?>
