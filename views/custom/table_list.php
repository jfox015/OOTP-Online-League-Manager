<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<h1><?php echo lang('sql_required_tables_edit'); ?></h1>

<p class="small"><?php echo lang('bf_required_note'); ?></p>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"'); ?>

<div class="inst_left"><b>Select Tables</b></div>
<div class="inst_right">Tables Available in OOTP: <b><?php echo($ootp_version); ?></b></div>

<div>
	<table cellpadding=2 cellspacing=0 border=0 style="width:98%;">
		<thead>
		<tr class='title'>
			<td width="10%"></td>
			<td width="90%">Table Name</td>
		</tr>
		</thead>
		<tbody>
		<?php
        if (isset($table_list) && is_array($table_list) && count($table_list)) :
		$loadCnt = sizeof($table_list);
		$cnt=0;
		if ($loadCnt>0) {asort($table_list);}
		foreach ($table_list as $table){
		?>
		<tr class='row_<?php echo(((($cnt%2) == 0) ? "even" : "odd")); ?>'>
			<td class='<?php echo($cls); ?>'><?php 
			$checked = false;
			if (isset($required_tables) && count($required_tables)>0) {
				foreach ($required_tables as $table_name) {
					if ($table_name == $table) {
						$checked = true;
						break;
					} // END if
				} // END foreach
			} // END if
			echo form_checkbox('required_tables[]',$table,$checked);
			?>
			</td>
			<td><?php echo($table); ?></td>
		</tr>
			<?php 
			$cnt++;
		}
        endif; ?>
		</tbody>
	</table>
</div>
<div>
	<a href="#" onclick="setCheckBoxState(true); return false;">Select All</a> | <a 
	href="#" onclick="setCheckBoxState(false); return false;">Select None</a>
</div>
<div class="submits">
	<input type="submit" name="submit" value="<?php echo lang('bf_action_save') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/settings', lang('bf_action_cancel')); ?>
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