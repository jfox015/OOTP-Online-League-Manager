<div class="container-fluid">
    <div class="row-fluid">

        <div class="span9">
            <div class="row-fluid">
                <div class="span9"><p>&nbsp;</p></div>
            </div>
          <div class="row-fluid">
            <div class="span1">
              <img src="<?php echo Template::theme_url('images/icons/database_up.png'); ?>" width="24" height="24" border="0" />
			 </div>
			 <div class="span8">
              <b><?php echo anchor(SITE_AREA.'/custom/league_manager/load_sql','Manage SQL Files'); ?></b>
              <p>Select updated SQL files to load. Views table modification dates. Split large files.</p>
            </div><!--/span-->
          </div><!--/row-->

            <div class="row-fluid">
                <div class="span9"><p>&nbsp;</p></div>
            </div>

		  <div class="row-fluid">
            <div class="span1">
              <img src="<?php echo Template::theme_url('images/icons/note_edit.png'); ?>" width="24" height="24" border="0" />
			 </div>
			 <div class="span8">
              <b><?php echo anchor(SITE_AREA.'/custom/league_manager/sim_details','Set Sim Details'); ?></b>
              <p>Update sim details such as sim length, weekly # of sims or manually set properties to display.</p>
            </div><!--/span-->
          </div><!--/row-->

            <div class="row-fluid">
                <div class="span9"><p>&nbsp;</p></div>
            </div>

		  <div class="row-fluid">
            <div class="span1">
              <img src="<?php echo Template::theme_url('images/icons/database_search.png'); ?>" width="24" height="24" border="0" />
			 </div>
			 <div class="span8">
              <b><?php echo anchor(SITE_AREA.'/custom/league_manager/table_list','Manage Required Tables'); ?></b>
              <p>Select which tables are required for upload each time.</p>
            </div><!--/span-->
          </div><!--/row-->
		 
		
          <div class="row-fluid">
            <div class="span9"><p><h2>League Tasks</h2></p></div>
          </div>
		  
		  <?php if (sizeof($tables_loaded) == 0) { ?>
		  <div class="row-fluid">
            <div class="span1">
              <img src="<?php echo Template::theme_url('images/icons/database_up.png'); ?>" width="24" height="24" border="0" />
			 </div>
			 <div class="span8">
              <b><?php echo anchor(SITE_AREA.'/custom/league_manager/load_all_sql','Load OOTP MySQL Data'); ?></b>
              <p>Automatically load all MySQL data. You can limit the loading to only those files that are 
			  marked as required in the <?php echo anchor('admin/settings/league_manager','league settings screen'); ?>.</p>
            </div><!--/span-->
          </div><!--/row-->
		  <?php } else { ?>
		  
			  <?php if (!isset($owner_count) || $owner_count == 0) { ?>
			  <div class="row-fluid">
				<div class="span1">
				  <img src="<?php echo Template::theme_url('images/icons/users.png'); ?>" width="24" height="24" border="0" />
				 </div>
				 <div class="span8">
				  <b><?php echo anchor(SITE_AREA.'/custom/league_manager/import_team_owners','Import Team Owners'); ?></b>
				  <p>There are no users assigned as owners to any teams. Run this tool to import team owners and create 
				  web users or map them to existing users.</p>
				</div><!--/span-->
			  </div><!--/row-->
			  <?php } ?>
		  
		  <?php } ?>
        </div><!--/span-->

        <div class="span3">
            <h2>Database Profile </h2>
            <p>
            <?php if (sizeof($tables_loaded) == 0) { ?>
				<label>Tables:</label> <? echo $tables_loaded ?><br />
				<label>Latest File Updated:</label> <? echo $last_file_time ?><br />
				<label>Last Uploaded:</label> <? echo $get_latest_load_time ?><br />
				<label>Required Table List:</label> <b>Custom</b> <?php echo anchor(SITE_AREA.'/custom/league_manager/table_list','Edit'); ?><br />
				</p>
				<h2>Validation Report</h2>
				<?php if (isset($missing_tables) && sizeof($missing_tables)>0) { ?>
					<span class="error" style="margin:0px; width:98%;"><b>Required data files missing!</b>
					<p/>
					The following <b>required</b> OOTP MySQL tables were not found in your 
					database. Please assure all required files listed below are loaded on the server
					before proceeding.
					</p>
					<ul>
					<?php foreach ($missing_tables as $tableName) {
					echo("<li><b>".$tableName."</b></li><br />");
					} ?>
					</ul>
					</span>
				<?php } ?>
			<?php } else { ?>
				The leagues database files have not been loaded yet. Please <?php echo anchor(SITE_AREA.'/custom/league_manager/load_all_sql','Load OOTP MySQL Data'); ?> to 
				view the database profile.
			<?php } ?>
        </div><!--/span-->
    </div><!--/row-->
</div><!--/container-->