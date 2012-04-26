<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string()); ?>

<div class="admin-box">
	<h3><?php echo lang('lm_import_owners'); ?></h3>
	<div class="well"><?php echo lang('lm_otu_notes'); ?></div>
	<?php
    if (isset($leagues) && is_array($leagues) && count($leagues)) : ?>

	<legend>Human Manager/User matches</legend>
	<div class="well">The following human manager names match with users in the site.</div>
	
	<?php
    if (isset($owner_matches) && is_array($owner_matches) && count($owner_matches)) : ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Human Manager</th>
				<th>OOTP Team</th>
				<th>Username</th>
				<th>Email</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
		<?php
		asort($owner_matches);
		foreach ($owner_matches as $owner):
		$team_name = $team->name." ".$team->nickname; ?>
		<tr>
			<td><?php echo $owner->first_name." ".$owner->last_name; ?></td>
			<td><img src="<?php echo $settings['ootp.asset_url'].'/images/'.str_replace(".","_50.",$team->logo_file); ?>" width="50" height="50" border="0" alt="<?php echo $team_name; ?>" title="<?php echo $team_name; ?>" /> <?php echo $team_name; ?></td>
			<td><?php echo $owner->username; ?></td>
			<td><?php echo $owner->email; ?></td>
			<td><?php echo form_checkbox($owner->team_id, 'Set as owner', false); ?></td>
		</tr>
		</tbody>
		<?php 
		endforeach;
		?>
	</table>
	<?php
	else:
	?>
	<div class="notification error">No league were found.</div>
	<?php
	endif;
	?>
</div>

<div class="row-fluid">
	<div class="span12 text-right">	
		<div class="form-actions">
			<input type="submit" name="submit" class="btn primary" value="<?php echo lang('lm_save_owner_map') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/custom/league_manager', lang('bf_action_cancel')); ?>
		</div>
	</div>
</div>

<?php echo form_close(); ?>