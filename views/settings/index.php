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
		<label class="required" for="view_type"><?php echo lang('dbrd_settings_gamever'); ?></label>
		<select id="ootp_version" name="ootp_version" class="small" >
		<?php
			$versions = loadOOTPVersions();
			foreach( $versions as $ver => $label){
				echo('<option value="'.$ver.'"');
				if (isset($ootp_version) && $ootp_version == $value) { 
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
		<input type="text" class="tiny" id="league_id" name="league_id" value="<?php echo (isset($league_id)) ? $league_id: set_value('league_id'); ?>" /> <span><?php echo lang('dbrd_settings_leagueid_note'); ?></span>
	</div>
		<!-- OOTP DETAILS OVERRIDE -->
	<div>
		<label for="use_ootp_details"><?php echo lang('dbrd_settings_useootp'); ?></label>
		<?php
		$use_selection = ((isset($use_ootp_details) && $use_ootp_details == 1) || !isset($use_ootp_details)) ? true : false;
		echo form_checkbox('use_ootp_details',1, $use_selection,'id="use_ootp_details"');
		?>
	</div>
    <div id="ootp_block">
            <!-- LEAGUE NAME -->
        <div>
            <label for="league_name"><?php echo lang('dbrd_settings_lgname'); ?></label>
            <input type="text" id="league_name" name="league_name" value="<?php echo (isset($league_name)) ? $league_name: set_value('league_name'); ?>" />
        </div>
            <!-- LEAGUE ABBR -->
        <div>
            <label for="league_abbr"><?php echo lang('dbrd_settings_lgabbr'); ?></label>
            <input type="text" class="small" id="league_abbr" name="league_abbr" value="<?php echo (isset($league_abbr)) ? $league_abbr: set_value('league_abbr'); ?>" />
        </div>
            <!-- LEAGUE ICON -->
        <div>
            <label for="league_icon"><?php echo lang('dbrd_settings_lgicon'); ?></label>
            <input type="text" class="small" id="league_icon" name="league_icon" value="<?php echo (isset($league_icon)) ? $league_icon: set_value('league_icon'); ?>" />
        </div>
            <!-- LEAGUE Text Color -->
        <div>
            <label for="league_txtcolor"><?php echo lang('dbrd_settings_txtcolor'); ?></label>
            <input type="text" class="small" id="league_txtcolor" name="league_txtcolor" value="<?php echo (isset($league_txtcolor)) ? $league_txtcolor: set_value('league_txtcolor'); ?>" />
        </div>
            <!-- LEAGUE BG Color -->
        <div>
            <label for="league_bgcolor"><?php echo lang('dbrd_settings_bgcolor'); ?></label>
            <input type="text" class="small" id="league_bgcolor" name="league_bgcolor" value="<?php echo (isset($league_bgcolor)) ? $league_bgcolor: set_value('league_bgcolor'); ?>" />
        </div>
    </div>
<fieldset>
<legend><?php echo lang('dbrd_settings_home'); ?></legend>
</fieldset>
		<!-- Header Image -->
	<div>
		<label for="header_img"><?php echo lang('home_settings_header'); ?></label>
		<input type="file" id="header_img" name="header_img" /><br />
		<span class="form_inst">Current Image: <?php echo (isset($header_img)) ? $header_img: set_value('header_img'); ?></span>
	</div>
		<!-- Twitter String -->
	<div>
		<label for="twitter_string"><?php echo lang('home_settings_twitter'); ?></label>
		<input type="text" id="twitter_string" name="twitter_string" value="<?php echo (isset($twitter_string)) ? $twitter_string: set_value('twitter_string'); ?>" /><br />
		<span class="subcaption"><?php echo lang('dbrd_settings_twtrwnote'); ?></span>
	</div>
			<!-- Tweet Count -->
	<div>
		<label for="tweet_count"><?php echo lang('home_settings_tweets'); ?></label>
		<input type="text" class="tiny" id="tweet_count" name="tweet_count" value="<?php echo (isset($tweet_count)) ? $tweet_count: set_value('tweet_count'); ?>" /><br />
	</div>
	
<fieldset>
<legend><?php echo lang('dbrd_settings_paths'); ?></legend>
</fieldset>

		<!-- League File Server Path -->
	<div>
		<label for="league_file_path"><?php echo lang('dbrd_settings_lgdfile'); ?></label>
		<input type="text" id="league_file_path" name="league_file_path" value="<?php echo (isset($league_file_path)) ? $league_file_path: set_value('league_file_path'); ?>" /><br />
		<span class="subcaption"><?php echo lang('dbrd_settings_lgdfilenote'); ?></span>
	</div>
	
		<!-- Asset Path -->
	<div>
		<label class="required" for="asset_path"><?php echo lang('dbrd_settings_assetpath'); ?></label>
		<input type="text" id="asset_path" name="asset_path" value="<?php echo (isset($asset_path)) ? $asset_path: set_value('asset_path'); ?>" /><br />
        <span class="subcaption"><?php echo lang('dbrd_settings_assetpnote'); ?></span>
	</div>
		<!-- Asset URL -->
	<div>
		<label class="required" for="asset_url"><?php echo lang('dbrd_settings_asseturl'); ?></label>
		<input type="text" id="asset_url" name="asset_url" value="<?php echo (isset($asset_url)) ? $asset_url: set_value('asset_url'); ?>" /><br />
		<span class="subcaption"><?php echo lang('dbrd_settings_assetunote'); ?></span>
	</div>

	<fieldset>
		<legend><?php echo lang('sql_settings_subhead'); ?></legend>
	</fieldset>
	<!-- SQL FILE PATH -->
	<div>
		<label class="required" for="sql_path"><?php echo lang('sql_settings_mysqlpath'); ?></label>
		<input type="text" id="sql_path" name="sql_path" value="<?php echo (isset($sql_path)) ? $sql_path: set_value('sql_path'); ?>" /><br />
        <span class="subcaption"><?php echo lang('sql_settings_path_note'); ?></span>
	</div>
	<!-- MAX SIZE -->
	<div>
		<label for="max_sql_size"><?php echo lang('sql_settings_max'); ?></label>
		<input type="text" class="tiny" id="max_sql_size" name="max_sql_size" value="<?php echo (isset($max_sql_size)) ? $max_sql_size: set_value('max_sql_size'); ?>" />
        <?php echo lang('sql_settings_max_note'); ?>
	</div>
	<!-- AUTO SPLIT -->
	<div>
		<label for="auto_split"><?php echo lang('sql_settings_autosplit'); ?></label>
        <?php
        $use_selection = ((isset($auto_split) && $auto_split == 1)) ? true : false;
        echo form_checkbox('auto_split',1, $use_selection,'id="auto_split"');
        ?>
        <span><?php echo lang('sql_settings_autosplit_note'); ?></span>
	</div>

	<!-- AUTO LOAD -->
	<div>
		<label for="limit_load"><?php echo lang('sql_settings_loadaction'); ?></label>
		<?php
		$limit_selection = (isset($limit_load) && $limit_load == 1) ? true : false;
		echo form_radio('limit_load',1, $limit_selection);
		echo(lang('sql_settings_load_limit')."<br />");
		?>
		<label for="limit_load">&nbsp;</label>
		<?php
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

