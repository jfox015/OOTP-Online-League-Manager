<div id="center-column">
    <?php if (isset($missing_files) && sizeof($missing_files) > 0) { ?>
    <span class="error" style="margin:0px; width:98%;"><b>Required data files missing!</b>
        <br /><br />
        The following <b>required</b> OOTP MySQL data SQL files were not found in your 
        server SQL upload directory. Please assure all required files listed below 
        are loaded on the server before proceeding.
        <br /><br />
        <ul>
       <?php foreach ($missing_files as $tableName) {
        	echo("<li><b>".$tableName."</b></li><br />");
	   } ?>
       </ul>
       </span>
       <p /><br />
    <?php } ?>
    
    <?php if (isset($file_list) && sizeof($file_list) > 0) { ?>
    <b style="color:#c00; font-weight:bold;">NOTE:</b> File names that are required by the fantasy league mod to work correctly are highlighted.<br />
    Files <span class="hilight">highlighted</span> red are required for all OOTP versions.<br />

    <form action='<?php echo(site_url($this->uri->uri_string())); ?>' method='post' name="file_list" id="file_list">
   	<div id="activeStatusBox"><div id="activeStatus"></div></div>
    <div class='textbox'>
    <table cellpadding=2 cellspacing=0 border=0 style="width:95%">
     <thead>
     <tr class='title'>
     	<td width="20%" class='hsc_l'>Filename</td>
        <td width="20%" class='hsc'>Timestamp</td>
        <td width="20%" class='hsc'>Last Updated</td>
        <td width="20%" class='hsc'>Size</td>
        <td width="20%" class='hsc' colspan=2>Actions</td>
      </tr>
      </thead>
   <?php 
   $loadCnt = sizeof($file_list);
   $cnt=0;
   if ($loadCnt>0) {asort($file_list);}
   $isSplit=0;
   $splitParents = array();
   foreach ($file_list as $file){
      $ex = explode(".",$file);
      $fileTime=filemtime($settings['ootp.sql_path']."/".$file);
      $tblName=$ex[0];
      if (($isSplit==0)&&(substr_count($file,".mysql_")>0)) {$isSplit=1;}
      $cls='s'.($cnt%2+1); ?>
      <tr class='<?php echo($cls); ?>'>
      <td class='<?php echo($cls); ?>_l'><?php 
	  $fileArr = explode(".",$file);
	  $hilite = -1;
	  if (isset($required_tables) && is_array($required_tables) && sizeof($required_tables) > 0) {
		  foreach ($required_tables as $table_name) {
			  //echo("Table name from list = ".$tableName."<br />");
			  if ($table_name['name'] == $fileArr[0]) {
				  $hilite = 1;
				  break;
			  } // END if
		  } // END foreach
	  } // END if
	  if ($hilite == 1) { echo('<span class="hilight">'); } else if ($hilite == 2) { echo('<span class="hilight2">'); }// END if
	  echo($file); 
	  if ($hilite == 1 || $hilite == 2) { echo('</span>'); } // END if
      if (isset($files_loaded[$settings['ootp.sql_path']."/".$file])) { echo("- <b>LOADED</b>"); } // END if
      $fsize=filesize($settings['ootp.sql_path']."/".$file);
	  ?>
      </td>
      <td sorttable_customkey='<?php echo($fileTime); ?>'><?php echo(date("D M j, Y H:i",$fileTime)); ?></td>
      <td sorttable_customkey=''>
          <?php
          if (isset($files_loaded) && is_array($files_loaded) && sizeof($files_loaded) > 0) {
              foreach ($files_loaded as $row) {
              //echo("Table name from list = ".$tableName."<br />");
                  if ($row['name'] == $fileArr[0]) {
                    echo(date("D M j, Y H:i",$row->modified_on));
                    break;
                  } // END if
              } // END foreach
          } // END if
          ?></td>
      <td sorttable_customkey='<?php echo($fsize); ?>'><?php echo(formatBytes($fsize)); ?></td>
      <td sorttable_customkey=1><?php echo(anchor('custom/sql_loader/load_sql/filename/'.$file,'Load')); ?>
      <?php 
	  /*--------------------------------------
	  / Identify files with splits and add them 
	  /	to an array so the larger parent file
	  / is skipped in favor of the splits.
	  /----------------------------------------*/
	  if (strpos($file,".mysql_")>0) { 
	  	echo('/<a href="#" id="'.$file.'" rel="delete">Delete Split</a>');
		if (!in_array($fileArr[0].".mysql.sql",$splitParents)) {
			array_push($splitParents,$fileArr[0].".mysql.sql");
		} // END if
	  } else { 
	  	echo('/<a href="#" id="'.$file.'" rel="split">Split</a>');
	  } // END if
	  ?>
      </td>
      <td sorttable_customkey=1><input type='checkbox' name='loadList[]' value='<?php echo($file); ?>' /></td>
      </tr>
      <?php 
      $cnt++;
	} // END if
   if ($isSplit==1)
    { ?>
      <tfoot><tr class='headline'><td class='hsc2' colspan=2>&nbsp;</td>
      <td class='hsc2' colspan=2><?php echo(anchor('/admin/splitSQLFile/delete/1/filename/DELSPLITS','Delete All Splits'));  ?></td>
      <td>&nbsp;</td>
      </tr></tfoot>
    <?php } ?>
     <tfoot><tr><td colspan="5" align="right">
     <input type="hidden" name="returnPage" value="file_list" />
     <a href="#" onclick="checkRequired(); <?php if ($isSplit==1){ ?>uncheckSplitParents();<?php } ?> return false;">Select Only Required</a> |
     <a href="#" onclick="setCheckBoxState(true); return false;">Select All</a> | <a 
     href="#" oncLick="setCheckBoxState(false); return false;">Select None</a><br />
     <span class="button_bar"><input type='submit' name='submit' value='Load Checked' /></span></td></tr></tfoot>
    </table>
   </div>
   </form>
   <?php } else {
    echo("No files found.");
}// END if ?>
</div>

<br class="clear" />

<script type="text/javascript">
head.ready(function(){
    var ajaxWait = '<img src="<?php echo Template::theme_url('images/icons/ajax-loader.gif'); ?>" width="28" height="28" border="0" align="absmiddle" />&nbsp;Operation in progress. Please wait...';
    var responseError = '<img src="<?php echo Template::theme_url('images/icons/icon_fail.png'); ?>" width="24" height="24" border="0" align="absmiddle" />&nbsp;';
    var fader = null;
    var refreshAfterUpdate = false;

    $(document).ready(function(){
        $('a[rel=split]').click(function () {
            refreshAfterUpdate = true;
            runAjax("<?php echo(Template::theme_url('custom/sql_loader/splitSQLFile/filename/')); ?>"+this.id); return false;
        });
        $('a[rel=delete]').click(function () {
            refreshAfterUpdate = true;
            runAjax("<?php echo(Template::theme_url('custom/sql_loader/splitSQLFile/delete/1/filename/'));?>"+this.id); return false;
        });
        checkRequired();
        <?php
        if (isset($splitParents) && sizeof($splitParents) > 0) { ?>
        uncheckSplitParents();
        <?php
        }
        ?>
    });
    function runAjax (url) {
            //clearTimeout(fader);
            $('div#activeStatus').removeClass('error');
            $('div#activeStatus').removeClass('success');
            $('div#activeStatus').html(ajaxWait);
            $('div#activeStatusBox').fadeIn("slow");
            $.getJSON(url, function(data){
                error = false;
                if (data.status.indexOf(":") != -1) {
                    var status = data.status.split(":");
                    $('div#activeStatus').addClass(status[0].toLowerCase());
                    var response = status[1];
                    if (status[0].toLowerCase() == "error") {
                        response = responseError + response;
                        error = true;
                    }
                    $('div#activeStatus').html(response);
                } else {
                    $('div#activeStatus').addClass('success');
                    $('div#activeStatus').html('Operation Completed Successfully');
                }
                if (!error && refreshAfterUpdate) {
                    setTimeout('refreshPage()',3000);
                }
            });
        }

        function fadeStatus(type) {
            $('div#'+type+'StatusBox').fadeOut("normal",function() { clearTimeout(fader); $('div#'+type+'StatusBox').hide(); });
        }
        function refreshPage() {
            document.location.href = '<?php echo($_SERVER['PHP_SELF']); ?>';
        }

    <?php if (isset($required_tables) && sizeof($required_tables) > 0) { ?>
    var required = new Array(<?php echo(sizeof($required_tables)); ?>);
    <?php
      $count = 0;
      foreach ($required_tables as $tableName) {
        echo("required[".$count."] = '".$tableName."';");
        $count++;
      }
    }
    if (isset($splitParents) && sizeof($splitParents) > 0) { ?>
    var parentList = new Array(<?php echo(sizeof($splitParents)); ?>);
    <?php
        $count = 0;
        foreach($splitParents as $parent) {
            echo('parentList['.$count.'] = "'.$parent.'";');
            $count++;
        }
    ?>
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
    <?php
    }
    ?>
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
});
</script>