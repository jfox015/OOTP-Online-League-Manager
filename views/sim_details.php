<div class="widget">
	<table class="sim_details" border="0">
    <tr class="flex_row">
        <th class="flex_cell left" colspan="2"><?php echo lang('sim_setting_details'); ?></th>
    </tr>
    <tr class="flex_row">
		<td class="flex_cell left"><?php echo lang('sim_setting_league_file_date') ?></td>
		<td class="flex_cell right"><?php echo(isset($league_file_date) ? $league_file_date : lang('sim_date_na')); ?></td>
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