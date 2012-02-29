<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<p class="small"><?php echo lang('bf_required_note'); ?></p>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"'); ?>

<fieldset>
<legend><?php echo lang('dbrd_settings_details'); ?></legend>
</fieldset>
		<!-- Auto Calculate Sim Length -->
	<div>
		<label><?php echo lang('dbrd_settings_autocalc'); ?></label>
		<?php
		$use_selection = ((isset($settings['ootp.auto_sim_length']) && $settings['ootp.auto_sim_length'] == 1) || !isset($settings['ootp.auto_sim_length'])) ? true : false;
		echo form_checkbox('auto_sim_length',1, $use_selection,'id="auto_sim_length"');
		?>
		<span><?php echo lang('dbrd_settings_calclen')." ".((isset($settings['ootp.calc_length'])) ? $settings['ootp.calc_length']: '---')." ".lang('sim_setting_simlen_note'); ?></span>
	</div>
		<!-- Manual Sim length -->
	<div>
		<label for="sim_length"><?php echo lang('sim_setting_simlen'); ?></label>
		<input type="text" class="tiny" id="sim_length" name="sim_length" value="<?php echo (isset($settings['ootp.sim_length'])) ? $settings['ootp.sim_length']: set_value('ootp.sim_length'); ?>" />
		<span><?php echo lang('sim_setting_simlen_note'); ?></span>
	</div>
		<!-- Sims per week -->
	<div>
		<label class="required" for="sims_per_week"><?php echo lang('sim_setting_perweek'); ?></label>
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
		</select>
	</div>
		<!-- Sims occur on -->
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
	<div>
		<label><?php echo lang('dbrd_settings_useootp'); ?></label>
		<?php
		$use_selection = ((isset($settings['ootp.sim_details']) && $settings['ootp.sim_details'] == 1) || !isset($settings['ootp.sim_details'])) ? true : false;
		echo form_checkbox('sim_details',1, $use_selection,'id="sim_details"');
		?>
	</div>
    <div id="ootp_block">
            <!-- LEAGUE NAME -->
        <div>
            <label for="league_file_date"><?php echo lang('sim_setting_league_file_date'); ?></label>
            <input type="text" id="league_file_date" name="league_file_date" value="<?php echo (isset($settings['ootp.league_file_date'])) ? date('m/d/Y',$settings['ootp.league_file_date']): set_value('ootp.league_file_date'); ?>" />
        </div>
            <!-- LEAGUE ABBR -->
        <div>
            <label for="next_sim"><?php echo lang('sim_setting_next_sim'); ?></label>
            <input type="text" class="small" id="next_sim" name="next_sim" value="<?php echo (isset($settings['ootp.next_sim'])) ? date('m/d/Y',$settings['ootp.next_sim']): set_value('ootp.next_sim'); ?>" />
        </div>
            <!-- LEAGUE ICON -->
        <div>
            <label for="league_date"><?php echo lang('sim_setting_league_date'); ?></label>
            <input type="text" class="small" id="league_date" name="league_date" value="<?php echo (isset($settings['ootp.league_date']) && !empty($settings['ootp.league_date'])) ? date('m/d/Y',$settings['ootp.league_date']): set_value('ootp.league_date'); ?>" />
        </div>
            <!-- LEAGUE Text Color -->
        <div>
            <label for="league_event"><?php echo lang('sim_setting_league_event'); ?></label>
            <select id="league_event" name="league_event" class="small" >
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
        </div>
    </div>
	
	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('bf_action_save') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/settings', lang('bf_action_cancel')); ?>
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
