<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<?php echo form_open($this->uri->uri_string()); ?>

<div class="admin-box">
	<h3><?php echo lang('lm_team_owners'); ?></h3>
	<div class="well"><?php echo lang('lm_otu_notes'); ?></div>
	<?php
    if (isset($leagues) && is_array($leagues) && count($leagues)) : ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th></th>
				<th>OOTP Team</th>
				<th>Select User</th>
			</tr>
		</thead>
		<tbody>
		<?php
        if (isset($team_owners) && is_array($team_owners) && count($team_owners)) :
			asort($team_owners);
			foreach ($team_owners as $team):
				$team_name = $team->name." ".$team->nickname; ?>
			<tr>
				<td><img src="<?php echo $settings['ootp.asset_url'].'images/'.str_replace(".","_50.",$team->logo_file); ?>" width="50" height="50" border="0" alt="<?php echo $team_name; ?>" title="<?php echo $team_name; ?>" /></td>
				<td><?php echo $team_name; ?></td>
				<td>
				<?php 
				$selected = '';
				foreach ($users as $id => $display_name) :
					if ($id == $team->user_id) :
						$selected = $id;
						break;
					endif;
				endforeach;
				echo form_dropdown($team->team_id, $users, $selected, '', ' id="'.$team->team_id.'" class="span4"'); ?></td>
			</tr>
				<?php 
			endforeach;
        endif; ?>
		</tbody>
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