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
		
			<!-- SPORT -->
        <div class="control-group <?php echo form_error('game_sport') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('lm_settings_sport') ?></label>
            <div class="controls">
                <select id="game_sport" name="game_sport" class="span2">
				<?php
					$sport_id = 0;
					$sports = sports_map();
					if (isset($sports) && is_array($sports) && count($sports)) :
							foreach( $sports as $id => $label) :
							echo('<option value="'.$label.'"');
							if (isset($settings['osp.game_sport']) && $settings['osp.game_sport'] == $id) {
								echo(' selected="selected"');
								$sport_id = $label;
							}
							echo('">'.$label.'</option>');
						endforeach;
					endif;
				?>
				</select>
                <span class="help-inline"><?php if (form_error('game_sport')) echo form_error('game_sport'); else echo lang('lm_game_sport_note'); ?></span>
            </div>
        </div>	
			<!-- SOURCE -->
        <div class="control-group <?php echo form_error('game_source') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('lm_settings_source') ?></label>
            <div class="controls">
                <select id="game_source" name="game_source" class="span3" <?php if (!isset($sport_id)) { echo('disabled="disabled"'); } ?>>
				<?php
					$source_id = '';
					$sources = source_map();
					if (isset($sport_id)) {
						$source_list = $sources[$sport_id];
					}
					if (isset($source_list) && is_array($source_list) && count($source_list)) :
						foreach( $source_list as $id => $label) :
							echo('<option value="'.$id.'"');
							if (isset($settings['osp.game_source']) && $settings['osp.game_source'] == $id) {
								echo(' selected="selected"');
								$source_id = $id;
							}
							echo('">'.$label.'</option>');
						endforeach;
					endif;
				?>
				</select>
                <span class="help-inline"><?php if (form_error('game_source')) echo form_error('game_source'); else echo lang('lm_game_source_note'); ?></span>
            </div>
        </div>	
			<!-- SOURCE VERSION -->
        <div class="control-group <?php echo form_error('source_version') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('lm_settings_source_version') ?></label>
            <div class="controls">
                <select id="source_version" name="source_version" class="span2" <?php if (!isset($source_id)) { echo('disabled="disabled"'); } ?>>
				<?php
					$versions = source_version_map();
					if (isset($source_id)) {
						$versions_list = $versions[$source_id];
					}
					if (isset($versions_list) && is_array($versions_list) && count($versions_list)) :
						foreach( $versions_list as $id => $label) :
							echo('<option value="'.$id.'"');
							if (isset($settings['osp.source_version']) && $settings['osp.source_version'] == $id) {
								echo(' selected="selected"');
							}
							echo('">'.$label.'</option>');
						endforeach;
					endif;
				?>
				</select>
                <span class="help-inline"><?php if (form_error('source_version')) { echo form_error('source_version'); } ?></span>
            </div>
        </div>
		
			<!-- LEAGUE ID -->
        <div class="control-group <?php echo form_error('league_id') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('lm_settings_leagueid') ?></label>
            <div class="controls">
                <input type="text" class="span1" id="league_id" name="league_id" value="<?php echo (isset($settings['osp.league_id'])) ? $settings['osp.league_id']: set_value('osp.league_id'); ?>" />
				<span class="help-inline"><?php if (form_error('league_id')) echo form_error('league_id'); else echo lang('lm_settings_leagueid_note'); ?></span>
            </div>
        </div>
			<!-- OOTP DETAILS OVERRIDE -->
		<div class="control-group <?php echo form_error('use_game_details') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('lm_settings_usedetails') ?></label>
            <div class="controls">
			<?php
			$use_selection = ((isset($settings['osp.use_game_details']) && $settings['osp.use_game_details'] == 1) || !isset($settings['osp.use_game_details'])) ? true : false;
			echo form_checkbox('use_game_details',1, $use_selection,'id="use_game_details"');
			?>
			</div>
        </div>
		
		<legend><?php echo lang('lm_settings_ootp') ?></legend>
		
		<div id="ootp_block">
				<!-- LEAGUE NAME -->
			<div class="control-group <?php echo form_error('league_name') ? 'error' : '' ?>">
				<label class="control-label"><?php echo lang('lm_settings_lgname') ?></label>
				<div class="controls">
					<input type="text" class="span5" id="league_name" name="league_name" value="<?php echo (isset($settings['ootp.league_name'])) ? $settings['ootp.league_name']: set_value('ootp.league_name'); ?>" />
					<?php if (form_error('league_name')) echo '<span class="help-inline">'.form_error('league_name').'</span>'; ?>
				</div>
			</div>

				<!-- LEAGUE ABBR -->
			<div class="control-group <?php echo form_error('league_abbr') ? 'error' : '' ?>">
				<label class="control-label"><?php echo lang('lm_settings_lgabbr') ?></label>
				<div class="controls">
					<input type="text" class="span2" class="small" id="league_abbr" name="league_abbr" value="<?php echo (isset($settings['ootp.league_abbr'])) ? $settings['ootp.league_abbr']: set_value('ootp.league_abbr'); ?>" />
					<?php if (form_error('league_abbr')) echo '<span class="help-inline">'.form_error('league_abbr').'</span>'; ?>
				</div>
			</div>

				<!-- LEAGUE ICON -->
			<div class="control-group <?php echo form_error('league_icon') ? 'error' : '' ?>">
				<label class="control-label"><?php echo lang('lm_settings_lgicon') ?></label>
				<div class="controls">
					<input type="text" class="span5" class="small" id="league_icon" name="league_icon" value="<?php echo (isset($settings['ootp.league_icon'])) ? $settings['ootp.league_icon']: set_value('ootp.league_icon'); ?>" />
					<?php if (form_error('league_icon')) echo '<span class="help-inline">'.form_error('league_icon').'</span>'; ?>
				</div>
			</div>
			
				<!-- LEAGUE Text Color -->
			<div class="control-group <?php echo form_error('league_txtcolor') ? 'error' : '' ?>">
				<label class="control-label"><?php echo lang('lm_settings_txtcolor') ?></label>
				<div class="controls">
					<input type="text" class="span2" id="league_txtcolor" name="league_txtcolor" value="<?php echo (isset($settings['ootp.league_txtcolor'])) ? $settings['ootp.league_txtcolor']: set_value('ootp.league_txtcolor'); ?>" />
					<?php if (form_error('league_txtcolor')) echo '<span class="help-inline">'.form_error('league_txtcolor').'</span>'; ?>
				</div>
			</div>

				<!-- LEAGUE BG Color -->
			<div class="control-group <?php echo form_error('league_bgcolor') ? 'error' : '' ?>">
				<label class="control-label"><?php echo lang('lm_settings_bgcolor') ?></label>
				<div class="controls">
					<input type="text" class="span52" class="small" id="league_bgcolor" name="league_bgcolor" value="<?php echo (isset($settings['ootp.league_bgcolor'])) ? $settings['ootp.league_bgcolor']: set_value('ootp.league_bgcolor'); ?>" />
					<?php if (form_error('league_bgcolor')) echo '<span class="help-inline">'.form_error('league_bgcolor').'</span>'; ?>
				</div>
			</div>
			
		</div>

		<legend><?php echo lang('lm_settings_home'); ?></legend>

			<!-- Twitter Handle -->
		<div class="control-group <?php echo form_error('twitter_string') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('lm_settings_twitter') ?></label>
			<div class="controls">
				<input type="text" class="span3" id="twitter_string" name="twitter_string" value="<?php echo (isset($settings['osp.twitter_string'])) ? $settings['osp.twitter_string']: set_value('osp.twitter_string'); ?>" /><br />
				<span class="help-inline"><?php if (form_error('twitter_string')) echo form_error('twitter_string'); else echo lang('lm_settings_twtrwnote'); ?></span>
			</div>
		</div>

			<!-- Tweet Count -->
		<div class="control-group <?php echo form_error('tweet_count') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('lm_settings_tweets') ?></label>
			<div class="controls">
				<input type="text" class="span1" id="" name="tweet_count" value="<?php echo (isset($settings['osp.tweet_count'])) ? $settings['osp.tweet_count']: set_value('osp.tweet_count'); ?>" /><br />
				<?php if (form_error('tweet_count')) echo '<span class="help-inline">'.form_error('tweet_count').'</span>'; ?>
			</div>
		</div>

		<legend><?php echo lang('lm_settings_paths'); ?></legend>


			<!-- League File Server Path -->
		<div class="control-group <?php echo form_error('league_file_path') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('lm_settings_lgdfile') ?></label>
			<div class="controls">
				<input type="text" class="span5" id="league_file_path" name="league_file_path" value="<?php echo (isset($settings['osp.league_file_path'])) ? $settings['osp.league_file_path']: set_value('osp.league_file_path'); ?>" /><br />
				<span class="help-inline"><?php if (form_error('league_file_path')) echo form_error('league_file_path'); else echo lang('lm_settings_lgdfilenote'); ?></span>
			</div>
		</div>
		
			<!-- Asset Path -->
		<div class="control-group <?php echo form_error('asset_path') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('lm_settings_assetpath') ?></label>
			<div class="controls">
				<input type="text" class="span5" id="asset_path" name="asset_path" value="<?php echo (isset($settings['osp.asset_path'])) ? $settings['osp.asset_path']: set_value('osp.asset_path'); ?>" /><br />
				<span class="help-inline"><?php if (form_error('asset_path')) echo form_error('asset_path'); else echo lang('lm_settings_assetpnote'); ?></span>
			</div>
		</div>
		
			<!-- Asset URL -->
		<div class="control-group <?php echo form_error('asset_url') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('lm_settings_asseturl') ?></label>
			<div class="controls">
				<input type="text" class="span5" id="asset_url" name="asset_url" value="<?php echo (isset($settings['osp.asset_url'])) ? $settings['osp.asset_url']: set_value('osp.asset_url'); ?>" /><br />
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
				<input type="text" class="span5" id="sql_path" name="sql_path" value="<?php echo (isset($settings['osp.sql_path'])) ? $settings['osp.sql_path']: set_value('osp.sql_path'); ?>" /><br />
				<span class="help-inline"><?php if (form_error('sql_path')) echo form_error('sql_path'); else echo lang('sql_settings_path_note'); ?></span>
			</div>
		</div>
		
			<!-- USE PREFIX -->
		<div class="control-group <?php echo form_error('use_db_prefix') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sql_use_db_prefix') ?></label>
			<div class="controls">
				<?php
				$use_selection = ((isset($settings['osp.use_db_prefix']) && $settings['osp.use_db_prefix'] == 1)) ? true : false;
				echo form_checkbox('use_db_prefix',1, $use_selection,'id="use_db_prefix"');
				?>
				<span class="help-inline"><?php if (form_error('use_db_prefix')) echo form_error('use_db_prefix'); else echo lang('sql_use_db_prefix_note'); ?></span>
			</div>
		</div>

			<!-- MAX SIZE -->
		<div class="control-group <?php echo form_error('max_sql_size') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sql_settings_max') ?></label>
			<div class="controls">
				<input type="text" class="span1" id="max_sql_size" name="max_sql_size" value="<?php echo (isset($settings['osp.max_sql_size'])) ? $settings['osp.max_sql_size']: set_value('osp.max_sql_size'); ?>" />
				<span class="help-inline"><?php if (form_error('max_sql_size')) echo form_error('max_sql_size'); else echo lang('sql_settings_max_note'); ?></span>
			</div>
		</div>

			<!-- SQL TImeout -->
		<div class="control-group <?php echo form_error('sql_timeout') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sql_settings_timeout') ?></label>
			<div class="controls">
				<input type="text" class="span1" id="sql_timeout" name="sql_timeout" value="<?php echo (isset($settings['osp.sql_timeout'])) ? $settings['osp.sql_timeout']: set_value('osp.sql_timeout'); ?>" />
				<span class="help-inline"><?php if (form_error('sql_timeout')) echo form_error('sql_timeout'); else echo lang('sql_settings_timeout_note'); ?></span>
			</div>
		</div>

			<!-- AUTO SPLIT -->
		<div class="control-group <?php echo form_error('auto_split') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sql_settings_autosplit') ?></label>
			<div class="controls">
				<?php
				$use_selection = ((isset($settings['osp.auto_split']) && $settings['osp.auto_split'] == 1)) ? true : false;
				echo form_checkbox('osp.auto_split',1, $use_selection,'id="auto_split"');
				?>
				<span class="help-inline"><?php if (form_error('auto_split')) echo form_error('auto_split'); else echo lang('sql_settings_autosplit_note'); ?></span>
			</div>
		</div>

			<!-- AUTO LOAD -->
		<div class="control-group <?php echo form_error('limit_load') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('sql_settings_auto_load') ?></label>
			<div class="controls">
				<?php
				$limit_selection = (isset($settings['osp.limit_load']) && $settings['osp.limit_load'] == 1) ? true : false;
				echo form_radio('limit_load',1, $limit_selection);
				echo(lang('sql_settings_load_limit')."<br />");
				?>
				<label for="limit_load">&nbsp;</label>
				<?php
				$limit_selection = (isset($settings['osp.limit_load']) && $settings['osp.limit_load'] == -1) ? true : false;
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
