<div class="container-fluid">
    <div class="row-fluid">
        <div class="span8 offset1">
            <?php 
            if ($settings_incomplete) { ?>
                <div class="row-fluid">
                <div class="span9"><p><b>WARNING</b> You must review your <?php echo anchor('admin/settings/league_manager','league settings'); ?> before using the tools on this page.</p></div>
                </div>
            <?php } else { ?>
            
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
		  
		  <?php
             $drawn = false;
                if ($tables_loaded == 0) { ?>
		  <div class="row-fluid">
            <div class="span1">
              <img src="<?php echo Template::theme_url('images/icons/database_up.png'); ?>" width="24" height="24" border="0" />
			 </div>
			 <div class="span8">
              <b><?php echo anchor(SITE_AREA.'/custom/league_manager/load_all_sql','Load OOTP MySQL Data'); ?></b>
              <p>Automatically load all MySQL data. You can limit the loading to only those files that are 
			  marked as required on the <?php echo anchor(SITE_AREA.'/custom/league_manager/table_list','required table editor'); ?> page.</p>
            </div><!--/span-->
          </div><!--/row-->
		  <?php
                $drawn = true;
                } else {
			// ADD FUNCTIONALITY WHEN TABLES EXIST AND ARE LOADED
				
				if (isset($team_count) && $team_count > 0 && $user_count > 0) : ?>
				<div class="row-fluid">
					<div class="span1">
						<img src="<?php echo Template::theme_url('images/icons/users.png'); ?>" width="24" height="24" border="0" />
						</div>
						<div class="span8">
						<b><?php echo anchor(SITE_AREA.'/custom/league_manager/map_users_to_teams','Manage Team Owners'); ?></b>
						<p>Assign ownership of OOTP teams to web site users.</p>
					</div><!--/span-->
				</div><!--/row-->
				<?php
					if ($owner_count == 0 || $owner_count < $team_count) : ?> 
					<div class="row-fluid">
						<div class="span9"><p>&nbsp;</p></div>
					</div>
					  
					<div class="row-fluid">
						<div class="span1">
							<img src="<?php echo Template::theme_url('images/icons/user_accept.png'); ?>" width="24" height="24" border="0" />
							</div>
							<div class="span8">
							<b><?php echo anchor(SITE_AREA.'/custom/league_manager/import_members_from_game','Import users from OOTP game'); ?></b>
							<p>Create new users for the site based on human manager profiles from an OOTP league and automatically 
							map their team ownership.</p>
						</div><!--/span-->
					</div><!--/row-->
					<?php	
					endif;
                    $drawn = true;
                endif;
				}
            }
            if (!$drawn) {
                echo("No tasks are aviable at this time");
            }
            ?>
        </div><!--/span-->
        <div class="span3 offset1">
            <h2>Database Profile</h2>
            <p>
            <?php if ($tables_loaded > 0) { ?>
				<b>Tables:</b> <?php echo $tables_loaded ?><br />
				<b>Latest File Updated:</b> <?php echo $last_file_time ?><br />
				<b>Last Uploaded:</b> <?php echo $last_loaded ?><br />
				<b>Required Table List:</b> <b>Custom</b> <?php echo anchor(SITE_AREA.'/custom/league_manager/table_list','Edit'); ?><br />
				</p>
				<h2>Validation Report</h2>
				<?php if (isset($missing_tables) && sizeof($missing_tables)>0) { ?>
					
					<div class="row-fluid">
                        <div class="span3">
                            <img src="<?php echo Template::theme_url('images/icons/database_remove.png'); ?>" width="48" height="48" border="0" />
                        </div>
                        <div class="span9">
                            <span class="error" style="margin:0px; width:98%;"><b>Error: Required data files missing!</b></span>
							<p>
							The following <b>required</b> OOTP MySQL tables were not found in your 
							database. Please assure all required files listed below are loaded on the server
							before proceeding.
							</p>
							<ul>
								<?php foreach ($missing_tables as $tableName) {
								echo("<li><b>".$tableName."</b></li><br />");
								} ?>
							</ul>
                        </div><!--/span-->
                    </div><!--/row-->
					
				<?php } else { ?>
                   
				   <div class="row-fluid">
                        <div class="span3">
                            <img src="<?php echo Template::theme_url('images/icons/process_accept.png'); ?>" width="48" height="48" border="0" />
                        </div>
                        <div class="span9">
                            <b>Congrats!</b>
                            <p>You have all required tables loaded!</p>
                        </div><!--/span-->
                    </div><!--/row-->

                <?php }?>
			<?php } else { ?>
				The leagues database files have not been loaded yet. Please <?php echo anchor(SITE_AREA.'/custom/league_manager/load_all_sql','Load OOTP MySQL Data'); ?> to 
				view the database profile information here.
			<?php } ?>
        </div><!--/span-->
    </div><!--/row-->
</div><!--/container-->