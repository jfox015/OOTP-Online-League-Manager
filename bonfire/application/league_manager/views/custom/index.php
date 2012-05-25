<div class="container-fluid">
    <div class="row-fluid">
        <div class="span7 offset1">
            <?php
            $drawn = false;
            if ($settings_incomplete) { ?>
                <div class="row-fluid">
                <div class="span9"><p><b>WARNING</b> You must review your <?php echo anchor('admin/settings/league_manager','league settings'); ?> before using the tools on this page.</p></div>
                </div>
            <?php } else { ?>
            
            <div class="row-fluid">
                <div class="span7"><p>&nbsp;</p></div>
            </div>
          <div class="row-fluid">
            <div class="span1">
              <img src="<?php echo Template::theme_url('images/icons/database_up.png'); ?>" width="24" height="24" border="0" />
			 </div>
			 <div class="span6">
              <b><?php echo anchor(SITE_AREA.'/custom/league_manager/load_sql','Manage SQL Files'); ?></b>
              <p>Select updated SQL files to load. Views table modification dates. Split large files.</p>
            </div><!--/span-->
          </div><!--/row-->

            <div class="row-fluid">
                <div class="span7"><p>&nbsp;</p></div>
            </div>

		  <div class="row-fluid">
            <div class="span1">
              <img src="<?php echo Template::theme_url('images/icons/note_edit.png'); ?>" width="24" height="24" border="0" />
			 </div>
			 <div class="span6">
              <b><?php echo anchor(SITE_AREA.'/custom/league_manager/sim_details','Set Sim Details'); ?></b>
              <p>Update sim details such as sim length, weekly # of sims or manually set properties to display.</p>
            </div><!--/span-->
          </div><!--/row-->

            <div class="row-fluid">
                <div class="span7"><p>&nbsp;</p></div>
            </div>

		  <div class="row-fluid">
            <div class="span1">
              <img src="<?php echo Template::theme_url('images/icons/database_search.png'); ?>" width="24" height="24" border="0" />
			 </div>
			 <div class="span6">
              <b><?php echo anchor(SITE_AREA.'/custom/league_manager/table_list','Manage Required Tables'); ?></b>
              <p>Select which tables are required for upload each time.</p>
            </div><!--/span-->
          </div><!--/row-->
		 
		
          <div class="row-fluid">
            <div class="span7"><p><h2>League Tasks</h2></p></div>
          </div>
		  
		  <?php

                if ($tables_loaded == 0) { ?>
		  <div class="row-fluid">
            <div class="span1">
              <img src="<?php echo Template::theme_url('images/icons/database_up.png'); ?>" width="24" height="24" border="0" />
			 </div>
			 <div class="span6">
              <b><?php echo anchor(SITE_AREA.'/custom/league_manager/load_sql','Load OOTP MySQL Data'); ?></b>
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
						<div class="span6">
						<b><?php echo anchor(SITE_AREA.'/custom/league_manager/map_users_to_teams','Manage Team Owners'); ?></b>
						<p>Assign ownership of OOTP teams to web site users.</p>
                        <div class="alert <?php echo ($owner_count == $team_count ? "alert-success" : "alert-error"); ?>">
                                <?php echo "<b>".$owner_count."</b> of <b>".$team_count."</b> teams have owners."; ?>
                        </div>
					</div><!--/span-->
				</div><!--/row-->
				<?php
					if ($owner_count == 0 || $owner_count < $team_count) : ?> 
					<div class="row-fluid">
						<div class="span7"><p>&nbsp;</p></div>
					</div>
					  
					<div class="row-fluid">
						<div class="span1">
							<img src="<?php echo Template::theme_url('images/icons/user_accept.png'); ?>" width="24" height="24" border="0" />
							</div>
							<div class="span6">
							<b><?php echo anchor(SITE_AREA.'/custom/league_manager/create_members_from_game','Import users from OOTP game'); ?></b>
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
                echo("No tasks are available at this time");
            }
            ?>
        </div><!--/span-->
        <div class="span5 offset1">
            <h2>Database Profile</h2>
            <p>
            <?php if ($tables_loaded > 0) { ?>
                <table class="table table-stripped table-bordered">
                <tr>
                    <th width="45%">Required Tables:</th>
                    <td width="55%"><?php echo $required_table_count ?></td>
                </tr>
                <tr>
                    <th>OOTP Tables Loaded:</th>
                    <td>
                        <?php if ($tables_loaded < $required_table_count) { ?>
                        <span class="alert alert-error"><?php } ?>
                        <?php echo $tables_loaded ?></td>
                        <?php if ($tables_loaded < $required_table_count) { ?>
                        </span><?php } ?>
                </tr>
                <tr>
                    <th>Latest SQL File Updated:</th>
                    <td><?php echo $last_file_time ?></td>
                </tr>
                <tr>
                    <th>SQL Last Uploaded:</th>
                    <td><?php echo $last_loaded ?></td>
                </tr>
                </table>
				</p>
				<h2>Validation Report</h2>


                <?php if (isset($missing_tables) && sizeof($missing_tables)>0) { ?>

                    <?php if (isset($missing_files) && sizeof($missing_files)>0) { ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="alert alert-error"><b>Error: Required data files missing!</b></div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3">
                            <img src="<?php echo Template::theme_url('images/icons/database_remove.png'); ?>" width="48" height="48" border="0" />
                        </div>
                        <div class="span9">
                           <p>
                                The following <b>required</b> OOTP MySQL files were not found locally. Please assure all
                                required SQL files listed below are loaded on the server before continuing to try and load you database.
                            </p>
                            <ul>
                                <?php foreach ($missing_files as $file_name) {
                                echo("<li><b>".$file_name."</b></li><br />");
                            } ?>
                            </ul>
                        </div><!--/span-->
                    </div><!--/row-->
                    <?php } ?>

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="alert alert-error"><b>Error: Required tables are missing!</b></div>
                        </div>
                    </div>
                    <div class="row-fluid">
                       <div class="span3">
                            <img src="<?php echo Template::theme_url('images/icons/database_remove.png'); ?>" width="48" height="48" border="0" />
                        </div>
                        <div class="span9">
                            <p>
							The following <b>required</b> tables were not found in the database. Please assure all
                                required tables listed below are loaded. Cetrtain features of the site may not work until all required tables are loaded.
							</p>
							<ul>
								<?php foreach ($missing_tables as $table_name) {
								echo("<li><b>".$table_name."</b></li><br />");
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