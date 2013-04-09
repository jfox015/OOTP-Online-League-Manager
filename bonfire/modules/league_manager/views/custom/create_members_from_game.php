<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string()); ?>

<div class="admin-box">
	<h3><?php echo lang('lm_import_owners'); ?></h3>
	<?php
    $savable = true; 
	if (isset($leagues) && is_array($leagues) && count($leagues)) : ?>

	<!--legend><?php echo lang('lm_manager_matches'); ?></legend>
	<div class="well"><?php echo lang('lm_manager_matches_notes'); ?></div>
	
	<?php
    /*if (isset($owner_matches) && is_array($owner_matches) && count($owner_matches)) : ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Human Manager</th>
				<th>OOTP Team</th>
				<th>Username</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($owner_matches as $owner):
		$team_name = $owner['team_name']." ".$owner['team_nick']; ?>
		<tr>
			<td><?php echo $owner['first_name']." ".$owner['last_name']; ?></td>
			<td><img src="<?php echo $settings['osp.asset_url'].'/images/'.str_replace(".","_50.",$owner['logo_file']); ?>" width="50" height="50" border="0" alt="<?php echo $team_name; ?>" title="<?php echo $team_name; ?>" /> <?php echo $team_name; ?></td>
			<td><?php echo $owner['username']; ?></td>
			<td><?php echo form_checkbox($owner['team_id'], $owner['user_id'], 'Set as owner', false); ?></td>
		</tr>
		</tbody>
		<?php 
		endforeach;

    endif; */?>
	</table-->
	
	<legend><?php echo lang('lm_manager_new'); ?></legend>
	<div class="well"><?php echo lang('lm_manager_no_match_notes'); ?></div>
	
	<?php
    if (isset($non_matches) && is_array($non_matches) && count($non_matches)) : ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width: 55px;"></th>
				<th style="width: 15%;">OOTP Team</th>
				<th style="width: 15%;">Human Manager</th>
				<th style="width: 15%;">Display Name</th>
				<?php if ($use_usernames) { ?><th style="width: 15%;">Username</th><?php } ?>
				<th style="width: 15%;">Email</th>
				<th style="width: 15%;">Activation</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$activateOptions = array(1=>'Auto Activate', 2=>'Leave Inactive');
		asort($non_matches);
		foreach ($non_matches as $owner):
		$team_name = $owner['team_name']." ".$owner['team_nick']; ?>
		<tr valign="middle">
            <td><img src="<?php echo $settings['osp.team_logo_url'].str_replace(".","_50.",$owner['logo_file']); ?>" width="50" height="50" border="0" alt="<?php echo $team_name; ?>" title="<?php echo $team_name; ?>" /></td>
            <td><?php echo $team_name; ?></td>
			<td><?php echo $owner['first_name']." ".$owner['last_name']; ?></td>
			<td><input type="text" class="span2" name="<?php echo $owner['human_manager_id']; ?>_display_name" value="<?php echo set_value($owner['human_manager_id']."_display_name"); ?>" /></td>
            <?php if ($use_usernames) { ?>
                <td><input type="text" class="span2" name="<?php echo $owner['human_manager_id']; ?>_username" value="<?php echo set_value($owner['human_manager_id']."_username"); ?>" /></td>
            <?php } ?>
            <td><input type="text" class="span2" name="<?php echo $owner['human_manager_id']; ?>_email" value="<?php echo set_value($owner['human_manager_id']."_email"); ?>" /></td>
			<td><?php echo form_dropdown($owner['human_manager_id']."_activate", $activateOptions, '', '', ' class="span3"'); ?></td>
		</tr>
		</tbody>
		<?php 
		endforeach; ?>
	</table>
	<?php
	else:
		echo "No unowned teams were found.";
		$savable = false;
	endif;
        ?>
	<?php
	else:
	?>
	<div class="notification error">No league was found.</div>
	<?php
	endif;
	?>
</div>
<?php 
if ($savable) { ?>
<div class="row-fluid">
	<div class="span12 text-right">	
		<div class="form-actions">
			<input type="submit" name="submit" class="btn primary" value="<?php echo lang('bf_action_save') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/custom/league_manager', lang('bf_action_cancel')); ?>
		</div>
	</div>
</div>
<?php } ?>
<?php echo form_close(); ?>