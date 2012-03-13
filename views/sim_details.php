<div class="widget">
	<h3><?php echo lang('sim_setting_details'); ?></h3>
    <table class="table table-striped table-bordered" border="0">
    <tr class="flex_row">
		<td class="text-left"><?php echo lang('sim_setting_league_file_date') ?></td>
		<td class="text-right"><?php echo(isset($league_file_date) ? $league_file_date : lang('sim_date_na')); ?></td>
		</tr>
	<tr>
		<td class="text-left"><?php echo lang('sim_setting_next_sim') ?></td>
		<td class="text-right"><?php echo(isset($next_sim) ? $next_sim : lang('sim_date_na')); ?></td>
	</tr>
	<tr>
		<td class="text-left"><?php echo lang('sim_setting_league_date') ?></td>
		<td class="text-right"><?php echo(isset($league_date) ? $league_date : lang('sim_date_na')); ?></td>
	</tr>
	<tr>
		<td class="text-left"><?php echo lang('sim_setting_league_event') ?></td>
		<td class="text-right"><?php echo(isset($league_event) ? $league_event : lang('sim_date_na')); ?></td>
	</tr>
	</table>
</div>