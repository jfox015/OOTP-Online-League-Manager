<?php if (isset($missing_files) && sizeof($missing_files) > 0) : ?>
<div class="row-fluid">
	<div class="span12">		
		<div class="alert alert-error">
			<p>
			<b>Required data files missing!</b>
			</p>
			<p>
			The following <b>required</b> OOTP MySQL data SQL files were not found in your 
			server SQL upload directory. Please assure all required files listed below 
			are loaded on the server before proceeding.
			</p>
			<ul>
		   <?php foreach ($missing_files as $tableName) {
				echo("<li><b>".$tableName."</b></li><br />");
		   } ?>
		   </ul>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="row-fluid">
	<div class="span12">	
	<p class="intro"><b style="color:#c00; font-weight:bold;">NOTE:</b> File names that are required for the site to work correctly are <span class="hilight">highlighted</span> below.</p>
	</div>
</div>

<div class="admin-box">
	<h3><?php echo lang('role_manage') ?></h3>
	
	<?php if (isset($file_list) && sizeof($file_list) > 0) { ?>
	
	<form action='<?php echo(site_url($this->uri->uri_string())); ?>' method='post' name="file_list" id="file_list">

	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width: 25%"><?php echo lang('lm_sqlfiles_filename'); ?></th>
				<th style="width: 20%"><?php echo lang('lm_sqlfiles_timestamp'); ?></th>
				<th style="width: 20%"><?php echo lang('lm_sqlfiles_last_updated'); ?></th>
				<th style="width: 15%"><?php echo lang('lm_sqlfiles_size'); ?></th>
				<th colspan="2" class="text-center" style="width: 20%"><?php echo lang('lm_table_actions'); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php 
	$loadCnt = sizeof($file_list);
	$cnt=0;
	if ($loadCnt>0) {asort($file_list);}
	$isSplit=0;
	$splitParents = array();
	foreach ($file_list as $file){
		$file_parts = explode(".",$file);
		$tblName=$file_parts[0];
		if (($isSplit == 0) && (substr_count($file,".mysql_") > 0)) 
		{  
			$isSplit=1; 
		}
		?>
		<tr>
			<td
			<?php
			$fileArr = explode(".",$file);
			$hilite = false;
			if (isset($required_tables) && is_array($required_tables) && sizeof($required_tables) > 0) 
			{
				foreach ($required_tables as $table_name) 
				{
					if ($table_name->name == $fileArr[0])
					{
						$hilite = true;
						break;
					} // END if
				} // END foreach
			} // END if
			if ($hilite === true) 
			{ 
				echo(' class="hilight"');
			} // END if
			echo(">".$file);
			if (isset($files_loaded[$settings['osp.sql_path'].DIRECTORY_SEPARATOR.$file]))
			{ 
				echo("- <b>LOADED</b>"); 
			} // END if
			$fsize=filesize($settings['osp.sql_path'].DIRECTORY_SEPARATOR.$file);
			$fileTime = filemtime($settings['osp.sql_path'].DIRECTORY_SEPARATOR.$file);
			?>
			</td>
			<td><?php echo(date("D M j, Y H:i",$fileTime)); ?></td>
			<td>
			<?php
			if (isset($load_times) && is_array($load_times) && sizeof($load_times) > 0) 
			{
				foreach ($load_times as $row) 
				{
					if ($row->name == $fileArr[0]) 
					{
						if ($row->modified_on < $fileTime) 
						{
							echo('<span style="color:#c00;">');
						}
						echo(date("D M j, Y H:i",$row->modified_on));
						if ($row->modified_on < $fileTime) 
						{
							echo('</span>');
						}
						break;
					} // END if
				} // END foreach
			} // END if
			?></td>
			<td><?php echo(formatBytes($fsize)); ?></td>
			<td><?php echo(anchor('admin/custom/league_manager/load_sql/'.urlencode($file),'Load')); ?>
			<?php 
			/*--------------------------------------
			/ Identify files with splits and add them 
			/	to an array so the larger parent file
			/ is skipped in favor of the splits.
			/----------------------------------------*/
			if (strpos($file,".mysql_") > 0) 
			{ 
				echo(anchor('admin/custom/league_manager/splitSQLFile/'.urlencode($file).'/1','Delete'));
				if (!in_array($fileArr[0].".mysql.sql",$splitParents)) 
				{
					array_push($splitParents,$fileArr[0].".mysql.sql");
				} // END if
			}
			else
			{ 
				echo(anchor('admin/custom/league_manager/splitSQLFile/'.urlencode($file),'Split'));
			} // END if
			?>
			</td>
			<td><input type='checkbox' name='loadList[]' value='<?php echo($file); ?>' /></td>
		</tr>
		<?php 
		$cnt++;
	} // END foreach
	?>
		</tbody>
	</table>
	
	<?php
	if ($isSplit==1)
    { ?>
	<div class="row-fluid">
		<div class="span12 text-right">
			<?php echo(anchor('/admin/custom/league_manager/splitSQLFile/DELSPLITS/1','Delete All Splits'));  ?> 
		</div>
	</div>
	<?php } ?>
	<div class="row-fluid">
		<div class="span12 text-right">
			<input type="hidden" name="returnPage" value="file_list" />
			<p>
			<a href="#" onclick="checkRequired(); <?php if ($isSplit==1){ ?>uncheckSplitParents();<?php } ?> return false;">Select Only Required</a> |
			<a href="#" onclick="setCheckBoxState(true); return false;">Select All</a> | 
			<a href="#" oncLick="setCheckBoxState(false); return false;">Select None</a>
			</p>
			<div class="form-actions">
				<input type="submit" name="submit" class="btn primary" value="<?php echo lang('lm_action_load_checked') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/custom/league_manager', lang('bf_action_cancel')); ?>
			</div>
		</div>
	</div>

   </form>
   <?php 
   } 
   else 
   {
   ?>
   <div class="row-fluid">
		<div class="span12 text-center">
			<?php echo lang('lm_sqlfiles_no_files'); ?>
		</div>
	</div>
	<?php
	} // END if 
	?>
</div>
<script>
    var required = null;
    function checkRequired() {
        var form = document.file_list;
        if (required != null) {
            var startIndex = 0;
            for (var i = 0; i < required.length; i++) {
                for (var j = startIndex; j < form.elements.length; j++) {
                    if (i == 0) {
                        //alert(form.elements[j].value);
                        //alert(required[i]);
                    }
                    if (form.elements[j].type == 'checkbox') {
                        var table = form.elements[j].value.split(".");
                        if (table[0] == required[i]) {
                            form.elements[j].checked = true; // END if
                            //startIndex = j;
                        }
                    }
                } // END for
            }
        }
    }
    function setCheckBoxState(state) {
        var form = document.file_list;
        for (var i = 0; i < form.elements.length; i++) {
            if (form.elements[i].type == 'checkbox')
                form.elements[i].checked = state; // END if
        } // END for
    } // END function
</script>
<?php
    //echo("Module path = ".module_path('league_manager')."<br />");
	$outJs = '';
	if (isset($splitParents) && sizeof($splitParents) > 0) {
		$outJs .= 'uncheckSplitParents();';
	}
	if (isset($required_tables) && sizeof($required_tables) > 0) {
		$outJs .= 'required = new Array('.sizeof($required_tables).');';
		$count = 0;
		foreach ($required_tables as $tableName) {
			$outJs .= 'required['.$count.'] = "'.$tableName->name.'";';
			$count++;
		}
	}
    if (isset($splitParents) && sizeof($splitParents) > 0) {
		$outJs .= 'var parentList = new Array('.sizeof($splitParents).');';
		$count = 0;
		foreach($splitParents as $parent) {
			$outJs .= 'parentList['.$count.'] = "'.$parent.'";';
			$count++;
		}
$outJs .= <<<EOL
		function uncheckSplitParents() {
			var form = document.file_list;
			if (parentList != null) {
				for (var i = 0; i < parentList.length; i++) {
					for (var j = 0; j < form.elements.length; j++) {
						if (form.elements[j].type == 'checkbox' && form.elements[j].value == parentList[i]) {
							form.elements[j].checked = false; // END if
							break;
						}
					}
				}
			}
		}

EOL;
	};
	$outJs .= 'checkRequired();';
Assets::add_js( $outJs, 'inline' );
unset ( $outJs );
?>