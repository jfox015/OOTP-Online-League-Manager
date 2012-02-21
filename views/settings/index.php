<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<p class="small"><?php echo lang('bf_required_note'); ?></p>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"'); ?>

<fieldset>
<legend><?php echo lang('sim_setting_title'); ?></legend>
</fieldset>
	<div>
		<label class="required"><?php echo lang('dbrd_settings_gamever'); ?></label>
		<select id="game_version" name="game_version" class="small" >
		<?php
			$versions = loadOOTPVersions();
			foreach( $versions as $ver => $label){
				echo('<option value="'.$ver.'"');
				if (isset($settings['ootp.game_version']) && $settings['ootp.game_version'] == $value) { 
					echo(' selected="selected"');
				}
				echo('">'.$label.'</option>');
			} 
		?>
		</select>
		<span>Versions 10 and higher supported</span>
	</div>

		<!-- LEAGUE ID -->
	<div>
		<label class="required" for="league_id"><?php echo lang('dbrd_settings_leagueid'); ?></label>
		<input type="text" class="tiny" id="league_id" name="league_id" value="<?php echo (isset($settings['ootp.league_id'])) ? $settings['ootp.league_id']: set_value('ootp.league_id'); ?>" /> <span><?php echo lang('dbrd_settings_leagueid_note'); ?></span>
	</div>
		<!-- OOTP DETAILS OVERRIDE -->
	<div>
		<label><?php echo lang('dbrd_settings_useootp'); ?></label>
		<?php
		$use_selection = ((isset($settings['ootp.use_ootp_details']) && $settings['ootp.use_ootp_details'] == 1) || !isset($settings['ootp.use_ootp_details'])) ? true : false;
		echo form_checkbox('use_ootp_details',1, $use_selection,'id="use_ootp_details"');
		?>
	</div>
    <div id="ootp_block">
            <!-- LEAGUE NAME -->
        <div>
            <label for="league_name"><?php echo lang('dbrd_settings_lgname'); ?></label>
            <input type="text" id="league_name" name="league_name" value="<?php echo (isset($settings['ootp.league_name'])) ? $settings['ootp.league_name']: set_value('ootp.league_name'); ?>" />
        </div>
            <!-- LEAGUE ABBR -->
        <div>
            <label for="league_abbr"><?php echo lang('dbrd_settings_lgabbr'); ?></label>
            <input type="text" class="small" id="league_abbr" name="league_abbr" value="<?php echo (isset($settings['ootp.league_abbr'])) ? $settings['ootp.league_abbr']: set_value('ootp.league_abbr'); ?>" />
        </div>
            <!-- LEAGUE ICON -->
        <div>
            <label for="league_icon"><?php echo lang('dbrd_settings_lgicon'); ?></label>
            <input type="text" class="small" id="league_icon" name="league_icon" value="<?php echo (isset($settings['ootp.league_icon'])) ? $settings['ootp.league_icon']: set_value('ootp.league_icon'); ?>" />
        </div>
            <!-- LEAGUE Text Color -->
        <div>
            <label for="league_txtcolor"><?php echo lang('dbrd_settings_txtcolor'); ?></label>
            <input type="text" class="small" id="league_txtcolor" name="league_txtcolor" value="<?php echo (isset($settings['ootp.league_txtcolor'])) ? $settings['ootp.league_txtcolor']: set_value('ootp.league_txtcolor'); ?>" />
        </div>
            <!-- LEAGUE BG Color -->
        <div>
            <label for="league_bgcolor"><?php echo lang('dbrd_settings_bgcolor'); ?></label>
            <input type="text" class="small" id="league_bgcolor" name="league_bgcolor" value="<?php echo (isset($settings['ootp.league_bgcolor'])) ? $settings['ootp.league_bgcolor']: set_value('ootp.league_bgcolor'); ?>" />
        </div>
    </div>
<fieldset>
<legend><?php echo lang('dbrd_settings_home'); ?></legend>
</fieldset>
		<!-- Header Image -->
	<div>
		<label for="header_img"><?php echo lang('home_settings_header'); ?></label>
		<input type="file" id="header_img" name="header_img" /><br />
		<span class="form_inst">Current Image: <?php echo (isset($settings['ootp.header_img'])) ? $settings['ootp.header_img']: set_value('ootp.header_img'); ?></span>
	</div>
		<!-- Twitter String -->
	<div>
		<label for="twitter_string"><?php echo lang('home_settings_twitter'); ?></label>
		<input type="text" id="twitter_string" name="twitter_string" value="<?php echo (isset($settings['ootp.twitter_string'])) ? $settings['ootp.twitter_string']: set_value('ootp.twitter_string'); ?>" /><br />
		<span class="subcaption"><?php echo lang('dbrd_settings_twtrwnote'); ?></span>
	</div>
			<!-- Tweet Count -->
	<div>
		<label for="tweet_count"><?php echo lang('home_settings_tweets'); ?></label>
		<input type="text" class="tiny" id="tweet_count" name="tweet_count" value="<?php echo (isset($settings['ootp.tweet_count'])) ? $settings['ootp.tweet_count']: set_value('ootp.tweet_count'); ?>" /><br />
	</div>
	
<fieldset>
<legend><?php echo lang('dbrd_settings_paths'); ?></legend>
</fieldset>

		<!-- League File Server Path -->
	<div>
		<label for="league_file_path"><?php echo lang('dbrd_settings_lgdfile'); ?></label>
		<input type="text" id="league_file_path" name="league_file_path" value="<?php echo (isset($settings['ootp.league_file_path'])) ? $settings['ootp.league_file_path']: set_value('ootp.league_file_path'); ?>" /><br />
		<span class="subcaption"><?php echo lang('dbrd_settings_lgdfilenote'); ?></span>
	</div>
	
		<!-- Asset Path -->
	<div>
		<label class="required" for="asset_path"><?php echo lang('dbrd_settings_assetpath'); ?></label>
		<input type="text" id="asset_path" name="asset_path" value="<?php echo (isset($settings['ootp.asset_path'])) ? $settings['ootp.asset_path']: set_value('ootp.asset_path'); ?>" /><br />
        <span class="subcaption"><?php echo lang('dbrd_settings_assetpnote'); ?></span>
	</div>
		<!-- Asset URL -->
	<div>
		<label class="required" for="asset_url"><?php echo lang('dbrd_settings_asseturl'); ?></label>
		<input type="text" id="asset_url" name="asset_url" value="<?php echo (isset($settings['ootp.asset_url'])) ? $settings['ootp.asset_url']: set_value('ootp.asset_url'); ?>" /><br />
		<span class="subcaption"><?php echo lang('dbrd_settings_assetunote'); ?></span>
	</div>

	<fieldset>
		<legend><?php echo lang('sql_settings_subhead'); ?></legend>
	</fieldset>
	<!-- SQL FILE PATH -->
	<div>
		<label class="required" for="sql_path"><?php echo lang('sql_settings_mysqlpath'); ?></label>
		<input type="text" id="sql_path" name="sql_path" value="<?php echo (isset($settings['ootp.sql_path'])) ? $settings['ootp.sql_path']: set_value('ootp.sql_path'); ?>" /><br />
        <span class="subcaption"><?php echo lang('sql_settings_path_note'); ?></span>
	</div>
	<!-- MAX SIZE -->
	<div>
		<label for="max_sql_size"><?php echo lang('sql_settings_max'); ?></label>
		<input type="text" class="tiny" id="max_sql_size" name="max_sql_size" value="<?php echo (isset($settings['ootp.max_sql_size'])) ? $settings['ootp.max_sql_size']: set_value('ootp.max_sql_size'); ?>" />
        <?php echo lang('sql_settings_max_note'); ?>
	</div>
	<!-- AUTO SPLIT -->
	<div>
		<label for="auto_split"><?php echo lang('sql_settings_autosplit'); ?></label>
        <?php
        $use_selection = ((isset($settings['ootp.auto_split']) && $settings['ootp.auto_split'] == 1)) ? true : false;
        echo form_checkbox('ootp.auto_split',1, $use_selection,'id="auto_split"');
        ?>
        <span><?php echo lang('sql_settings_autosplit_note'); ?></span>
	</div>

	<!-- AUTO LOAD -->
	<div>
		<label for="limit_load"><?php echo lang('sql_settings_loadaction'); ?></label>
		<?php
		$limit_selection = (isset($settings['ootp.limit_load']) && $settings['ootp.limit_load'] == 1) ? true : false;
		echo form_radio('limit_load',1, $limit_selection);
		echo(lang('sql_settings_load_limit')."<br />");
		?>
		<label for="limit_load">&nbsp;</label>
		<?php
        $limit_selection = (isset($settings['ootp.limit_load']) && $settings['ootp.limit_load'] == -1) ? true : false;
        echo form_radio('limit_load',-1, $limit_selection);
		echo(lang('sql_settings_load_all'));
		?>
	</div>
	
	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('bf_action_save') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/settings', lang('bf_action_cancel')); ?>
	</div>
	
<?php echo form_close(); ?>

<script type="text/javascript">
    head.ready(function(){
        $(document).ready(function() {
            $('input#use_ootp_details').click(function() {
                $('#ootp_block').toggle(!this.checked);
            });
            $('#ootp_block').toggle(!$('input#use_ootp_details').is(':checked'));
        });
    });

</script>

