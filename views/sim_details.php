<div class="widget">
	<h3><?php echo lang('sim_setting_details'); ?></h3>
    <table class="sim_details" border="0">
    <tr class="flex_row">
		<td width="50%" class="flex_cell left"><?php echo lang('sim_setting_league_file_date') ?></td>
		<td width="50%" class="flex_cell right"><?php echo(isset($league_file_date) ? $league_file_date : lang('sim_date_na')); ?></td>
		</tr>
	<tr>
		<td class="flex_cell left"><?php echo lang('sim_setting_next_sim') ?></td>
		<td class="flex_cell right"><?php echo(isset($next_sim) ? $next_sim : lang('sim_date_na')); ?></td>
	</tr>
	<tr>
		<td class="flex_cell left"><?php echo lang('sim_setting_league_date') ?></td>
		<td class="flex_cell right"><?php echo(isset($league_date) ? $league_date : lang('sim_date_na')); ?></td>
	</tr>
	<tr>
		<td class="flex_cell left"><?php echo lang('sim_setting_league_event') ?></td>
		<td class="flex_cell right"><?php echo(isset($league_event) ? $league_event : lang('sim_date_na')); ?></td>
	</tr>
	</table>
</div>