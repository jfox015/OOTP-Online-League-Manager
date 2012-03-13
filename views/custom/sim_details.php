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
            <label><?php echo lang('sim_settings_autocalc') ?></label>
            <div class="controls">
				<?php
				$use_selection = ((isset($settings['ootp.auto_sim_length']) && $settings['ootp.auto_sim_length'] == 1) || !isset($settings['ootp.auto_sim_length'])) ? true : false;
				echo form_checkbox('auto_sim_length',1, $use_selection,'id="auto_sim_length"');
				?>
                <span class="help-inline">
				<?php if (form_error('auto_sim_length')) echo form_error('auto_sim_length'); ?>
				<?php echo lang('dbrd_settings_calclen')." ".((isset($settings['ootp.calc_length'])) ? $settings['ootp.calc_length']: '---')." ".lang('sim_setting_simlen_note'); ?>
				</span>
            </div>
        </div>
		
			<!-- Manual Sim length -->
		<div class="control-group <?php echo form_error('sim_length') ? 'error' : '' ?>">
            <label><?php echo lang('sim_setting_simlen') ?></label>
            <div class="controls">
				<input type="text" style="width:3em;" id="sim_length" name="sim_length" value="<?php echo (isset($settings['ootp.sim_length'])) ? $settings['ootp.sim_length']: set_value('ootp.sim_length'); ?>" />
				<span class="help-inline"><?php if (form_error('sim_length')) echo form_error('sim_length'); else echo lang('sim_setting_simlen_note'); ?></span>
            </div>
        </div>
	
			<!-- Sims per week -->
		 <div class="control-group <?php echo form_error('sims_per_week') ? 'error' : '' ?>">
			<label><?php echo lang('sim_setting_perweek') ?></label>
			<div class="controls">
				<select class="tiny" id="sims_per_week" name="sims_per_week">
				<?php
					for( $i = 1; $i < 8; $i++){
						echo('<option value="'.$i.'"');
						if (isset($settings['ootp.sims_per_week']) && $settings['ootp.sims_per_week'] == $i) {
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
			<label><?php echo lang('') ?></label>
			<div class="controls">
				
				<span class="help-inline"><?php if (form_error('')) echo form_error(''); else echo lang(''); ?></span>
			</div>
		</div>
		
		<div>
		<label class="required"><?php echo lang('sim_setting_occuron'); ?></label>
		<?php
		$dayList = (isset($settings['ootp.sims_occur_on']) ? unserialize($settings['ootp.sims_occur_on']) : array(-1));
		$days = array(1=>'Su',2=>'Mo',3=>'Tu',4=>'We',5=>'Th',6=>'Fr',7=>'Sa');
		foreach($days as $num => $day) :
			$use_selection = in_array($num,$dayList) ? true : false;
			echo form_checkbox('sims_occur_on[]',$num, $use_selection,'id="sims_occur_on_'.$num.'"')." ".$day." ";
		endforeach;
		?>
	</div>

		<!-- OOTP DETAILS OVERRIDE -->

	 <div class="control-group <?php echo form_error('sim_details') ? 'error' : '' ?>">
		<label><?php echo lang('lm_settings_useootp') ?></label>
		<div class="controls">
			<?php
			$use_selection = ((isset($settings['ootp.sim_details']) && $settings['ootp.sim_details'] == 1) || !isset($settings['ootp.sim_details'])) ? true : false;
			echo form_checkbox('sim_details',1, $use_selection,'id="sim_details"');
			?>
			<span class="help-inline"><?php if (form_error('sim_details')) echo form_error('sim_details'); ?></span>
		</div>
	</div>
	
	<div id="ootp_block">
            <!-- LEAGUE NAME -->
		 <div class="control-group <?php echo form_error('league_file_date') ? 'error' : '' ?>">
			<label><?php echo lang('sim_setting_league_file_date') ?></label>
			<div class="controls">
				<input type="text" id="" name="league_file_date" value="<?php echo (isset($settings['ootp.league_file_date'])) ? date('m/d/Y',$settings['ootp.league_file_date']): set_value('ootp.league_file_date'); ?>" />
				<?php if (form_error('league_file_date')) echo '<span class="help-inline">'.form_error('league_file_date').'</span>'; ?>
			</div>
		</div>

            <!-- LEAGUE ABBR -->
		 <div class="control-group <?php echo form_error('next_sim') ? 'error' : '' ?>">
			<label><?php echo lang('sim_setting_next_sim') ?></label>
			<div class="controls">
				<input type="text" class="small" id="next_sim" name="next_sim" value="<?php echo (isset($settings['ootp.next_sim'])) ? date('m/d/Y',$settings['ootp.next_sim']): set_value('ootp.next_sim'); ?>" />
				<?php if (form_error('next_sim')) echo '<span class="help-inline">'.form_error('next_sim').'</span>'; ?>
			</div>
		</div>

            <!-- LEAGUE ICON -->
		 <div class="control-group <?php echo form_error('league_date') ? 'error' : '' ?>">
			<label><?php echo lang('sim_setting_league_date') ?></label>
			<div class="controls">
				<input type="text" class="small" id="league_date" name="league_date" value="<?php echo (isset($settings['ootp.league_date']) && !empty($settings['ootp.league_date'])) ? date('m/d/Y',$settings['ootp.league_date']): set_value('ootp.league_date'); ?>" />
				<?php if (form_error('league_date')) echo '<span class="help-inline">'.form_error('league_date').'</span>'; ?>
			</div>
		</div>

            <!-- LEAGUE Text Color -->
		 <div class="control-group <?php echo form_error('league_event') ? 'error' : '' ?>">
			<label><?php echo lang('sim_setting_league_event') ?></label>
			<div class="controls">
				<select id="league_event" name="" class="small" >
				<?php
				if (isset($events) && is_array($events) && count($events)):
					foreach( $events as $event):
						echo('<option value="'.$event['name'].'"');
						if (isset($settings['ootp.league_event']) && $settings['ootp.league_event'] == $event['name']):
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

<script type="text/javascript">
    head.ready(function(){
        $(document).ready(function() {
            $('input#sim_details').click(function() {
                $('#ootp_block').toggle(!this.checked);
            });
            $('#ootp_block').toggle(!$('input#sim_details').is(':checked'));
			
			$("#next_sim").datepicker();
			$("#league_date").datepicker();
			$("#league_file_date").datepicker();
        });
    });

</script>
