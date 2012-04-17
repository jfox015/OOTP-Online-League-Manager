<div class="row-fluid">
	<div class="span12">	
		<p class="intro"><?php echo lang('bf_required_note'); ?></p>
		<div class="row-fluid">
			<div class="span6"><b>Select Tables</b></div>
			<div class="span6 text-right">Tables Available in OOTP: <b><?php echo($ootp_version); ?></b></div>	
		</div>
	</div>
</div>

<?php echo form_open($this->uri->uri_string(), 'name="file_list" id="file_list"'); ?>

<div class="admin-box">
	<h3><?php echo lang('sql_required_tables_edit') ?></h3>

	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width: 10%"></th>
				<th style="width: 90%"><?php echo lang('lm_required_tblname'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
        if (isset($table_list) && is_array($table_list) && count($table_list)) :
			asort($table_list);
			foreach ($table_list as $table){
			?>
			<tr>
				<td>
				<?php 
				$checked = false;
				if (isset($required_tables) && is_array($required_tables) && count($required_tables)>0) {
					foreach ($required_tables as $table_name) {
						if ($table_name->name == $table->name) {
							$checked = true;
							break;
						} // END if
					} // END foreach
				} // END if
				echo form_checkbox("required_tables[]",$table->name,$checked);
				?>
				</td>
				<td><?php echo($table->name); ?></td>
			</tr>
				<?php 
			}
        endif; ?>
		</tbody>
	</table>
</div>

<div class="row-fluid">
	<div class="span12 text-right">	
		<a href="#" onclick="setCheckBoxState(true); return false;">Select All</a> | <a 
		href="#" onclick="setCheckBoxState(false); return false;">Select None</a><br />
		<div class="form-actions">
			<input type="submit" name="submit" class="btn primary" value="<?php echo lang('lm_action_load_checked') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/custom/league_manager', lang('bf_action_cancel')); ?>
		</div>
	</div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
function setCheckBoxState(state) {
    var form = document.file_list;
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type == 'checkbox')
            form.elements[i].checked = state; // END if
    } // END for
} // END function
</script>