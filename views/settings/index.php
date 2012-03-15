<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<div class="admin-box">

    <h3><?php echo lang('lm_league_settings') ?></h3>

    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

    <fieldset>
        <legend><?php echo lang('lm_settings_general') ?></legend>
		
			<!-- GAME VERSION -->
        <div class="control-group <?php echo form_error('game_version') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('lm_settings_gamever') ?></label>
            <div class="controls">
                <select id="game_version" name="game_version" class="small" >
				<?php
					$versions = loadOOTPVersions();
					foreach( $versions as $ver => $label){
						echo('<option value="'.$ver.'"');
						if (isset($settings['ootp.game_version']) && $settings['ootp.game_version'] == $ver) {
							echo(' selected="selected"');
						}
						echo('">'.$label.'</option>');
					} 
				?>
				</select>
                <span class="help-inline"><?php if (form_error('game_version')) echo form_error('game_version'); else echo lang('lm_game_version_note'); ?></span>
            </div>
        </div>
		
			<!-- LEAGUE ID -->
        <div class="control-group <?php echo form_error('league_id') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('lm_settings_leagueid') ?></label>
            <div class="controls">
                <input type="text" style="width:3em;" id="league_id" name="league_id" value="<?php echo (isset($settings['ootp.league_id'])) ? $settings['ootp.league_id']: set_value('ootp.league_id'); ?>" />
				<span class="help-inline"><?php if (form_error('league_id')) echo form_error('league_id'); else echo lang('lm_settings_leagueid_note'); ?></span>
            </div>
        </div>
			<!-- OOTP DETAILS OVERRIDE -->
		<div class="control-group <?php echo form_error('use_ootp_details') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('lm_settings_useootp') ?></label>
            <div class="controls">
			<?php
			$use_selection = ((isset($settings['ootp.use_ootp_details']) && $settings['ootp.use_ootp_details'] == 1) || !isset($settings['ootp.use_ootp_details'])) ? true : false;
			echo form_checkbox('use_ootp_details',1, $use_selection,'id="use_ootp_details"');
			?>
			</div>
        </div>
		
		<div id="ootp_block">
				<!-- LEAGUE NAME -->
			<div class="control-group <?php echo form_error('league_name') ? 'error' : '' ?>">
				<label class="control-label"><?php echo lang('lm_settings_lgname') ?></label>
				<div class="controls">
					<input type="text" id="league_name" name="league_name" value="<?php echo (isset($settings['ootp.league_name'])) ? $settings['ootp.league_name']: set_value('ootp.league_name'); ?>" />
					<?php if (form_error('league_name')) echo '<span class="help-inline">'.form_error('league_name').'</span>'; ?>
				</div>
			</div>

				<!-- LEAGUE ABBR -->
			<div class="control-group <?php echo form_error('league_abbr') ? 'error' : '' ?>">
				<label class="control-label"><?php echo lang('lm_settings_lgabbr') ?></label>
				<div class="controls">
					<input type="text" class="small" id="league_abbr" name="league_abbr" value="<?php echo (isset($settings['ootp.league_abbr'])) ? $settings['ootp.league_abbr']: set_value('ootp.league_abbr'); ?>" />
					<?php if (form_error('league_abbr')) echo '<span class="help-inline">'.form_error('league_abbr').'</span>'; ?>
				</div>
			</div>

				<!-- LEAGUE ICON -->
			<div class="control-group <?php echo form_error('league_icon') ? 'error' : '' ?>">
				<label class="control-label"><?php echo lang('lm_settings_lgicon') ?></label>
				<div class="controls">
					<input type="text" class="small" id="league_icon" name="league_icon" value="<?php echo (isset($settings['ootp.league_icon'])) ? $settings['ootp.league_icon']: set_value('ootp.league_icon'); ?>" />
					<?php if (form_error('league_icon')) echo '<span class="help-inline">'.form_error('league_icon').'</span>'; ?>
				</div>
			</div>
			
				<!-- LEAGUE Text Color -->
			<div class="control-group <?php echo form_error('league_txtcolor') ? 'error' : '' ?>">
				<label class="control-label"><?php echo lang('lm_settings_txtcolor') ?></label>
				<div class="controls">
					<input type="text" style="width:10em;" id="league_txtcolor" name="league_txtcolor" value="<?php echo (isset($settings['ootp.league_txtcolor'])) ? $settings['ootp.league_txtcolor']: set_value('ootp.league_txtcolor'); ?>" />
					<?php if (form_error('league_txtcolor')) echo '<span class="help-inline">'.form_error('league_txtcolor').'</span>'; ?>
				</div>
			</div>

				<!-- LEAGUE BG Color -->
			<div class="control-group <?php echo form_error('league_bgcolor') ? 'error' : '' ?>">
				<label class="control-label"><?php echo lang('lm_settings_bgcolor') ?></label>
				<div class="controls">
					<input type="text" class="small" id="league_bgcolor" name="league_bgcolor" value="<?php echo (isset($settings['ootp.league_bgcolor'])) ? $settings['ootp.league_bgcolor']: set_value('ootp.league_bgcolor'); ?>" />
					<?php if (form_error('league_bgcolor')) echo '<span class="help-inline">'.form_error('league_bgcolor').'</span>'; ?>
				</div>
			</div>
			
		</div>

		<legend><?php echo lang('lm_settings_home'); ?></legend>

			<!-- Twitter Handle -->
		<div class="control-group <?php echo form_error('twitter_string') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('lm_settings_twitter') ?></label>
			<div class="controls">
				<input type="text" id="twitter_string" name="twitter_string" value="<?php echo (isset($settings['ootp.twitter_string'])) ? $settings['ootp.twitter_string']: set_value('ootp.twitter_string'); ?>" /><br />
				<span class="help-inline"><?php if (form_error('twitter_string')) echo form_error('twitter_string'); else echo lang('lm_settings_twtrwnote'); ?></span>
			</div>
		</div>

			<!-- Tweet Count -->
		<div class="control-group <?php echo form_error('tweet_count') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('lm_settings_tweets') ?></label>
			<div class="controls">
				<input type="text" class="tiny" id="" name="tweet_count" value="<?php echo (isset($settings['ootp.tweet_count'])) ? $settings['ootp.tweet_count']: set_value('ootp.tweet_count'); ?>" /><br />
				<?php if (form_error('tweet_count')) echo '<span class="help-inline">'.form_error('tweet_count').'</span>'; ?>
			</div>
		</div>

		<legend><?php echo lang('lm_settings_paths'); ?></legend>


			<!-- League File Server Path -->
		<div class="control-group <?php echo form_error('league_file_path') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('lm_settings_lgdfile') ?></label>
			<div class="controls">
				<input type="text" id="league_file_path" name="league_file_path" value="<?php echo (isset($settings['ootp.league_file_path'])) ? $settings['ootp.league_file_path']: set_value('ootp.league_file_path'); ?>" /><br />
				<span class="help-inline"><?php if (form_error('league_file_path')) echo form_error('league_file_path'); else echo lang('lm_settings_lgdfilenote'); ?></span>
			</div>
		</div>
		
			<!-- Asset Path -->
		<div class="control-group <?php echo form_error('asset_path') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('lm_settings_assetpath') ?></label>
			<div class="controls">
				<input type="text" id="asset_path" name="asset_path" value="<?php echo (isset($settings['ootp.asset_path'])) ? $settings['ootp.asset_path']: set_value('ootp.asset_path'); ?>" /><br />
				<span class="help-inline"><?php if (form_error('asset_path')) echo form_error('asset_path'); else echo lang('lm_settings_assetpnote'); ?></span>
			</div>
		</div>
		
			<!-- Asset URL -->
		<div class="control-group <?php echo form_error('asset_url') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('lm_settings_asseturl') ?></label>
			<div class="controls">
				<input type="text" id="asset_url" name="asset_url" value="<?php echo (isset($settings['ootp.asset_url'])) ? $settings['ootp.asset_url']: set_value('ootp.asset_url'); ?>" /><br />
				<span class="help-inline"><?php if (form_error('asset_url')) echo form_error('asset_url'); else echo lang('lm_settings_assetunote'); ?></span>
			</div>
		</div>

		<fieldset>
			<legend><?php echo lang('sql_settings_subhead'); ?></legend>
		</fieldset>
			<!-- SQL FILE PATH -->
		<div class="control-group <?php echo form_error('sql_path') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sql_settings_mysqlpath') ?></label>
			<div class="controls">
				<input type="text" id="sql_path" name="sql_path" value="<?php echo (isset($settings['ootp.sql_path'])) ? $settings['ootp.sql_path']: set_value('ootp.sql_path'); ?>" /><br />
				<span class="help-inline"><?php if (form_error('sql_path')) echo form_error('sql_path'); else echo lang('sql_settings_path_note'); ?></span>
			</div>
		</div>

		
			<!-- MAX SIZE -->
		<div class="control-group <?php echo form_error('max_sql_size') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sql_settings_max') ?></label>
			<div class="controls">
				<input type="text" style="width:3em;" id="max_sql_size" name="max_sql_size" value="<?php echo (isset($settings['ootp.max_sql_size'])) ? $settings['ootp.max_sql_size']: set_value('ootp.max_sql_size'); ?>" />
				<span class="help-inline"><?php if (form_error('max_sql_size')) echo form_error('max_sql_size'); else echo lang('sql_settings_max_note'); ?></span>
			</div>
		</div>

			<!-- AUTO SPLIT -->
		<div class="control-group <?php echo form_error('auto_split') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sql_settings_autosplit') ?></label>
			<div class="controls">
				<?php
				$use_selection = ((isset($settings['ootp.auto_split']) && $settings['ootp.auto_split'] == 1)) ? true : false;
				echo form_checkbox('ootp.auto_split',1, $use_selection,'id="auto_split"');
				?>
				<span class="help-inline"><?php if (form_error('auto_split')) echo form_error('auto_split'); else echo lang('sql_settings_autosplit_note'); ?></span>
			</div>
		</div>

			<!-- AUTO LOAD -->
		<div class="control-group <?php echo form_error('limit_load') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sql_settings_loadaction') ?></label>
			<div class="controls">
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
				<span class="help-inline"><?php if (form_error('limit_load')) echo form_error('limit_load'); else echo lang(''); ?></span>
			</div>
		</div>
	</fieldset>
	
    <div class="form-actions">
        <input type="submit" name="submit" class="btn primary" value="<?php echo lang('bf_action_save') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/settings', lang('bf_action_cancel')); ?>
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

