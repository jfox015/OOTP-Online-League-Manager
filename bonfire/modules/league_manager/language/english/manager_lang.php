<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$lang['lm_settings_title']			= 'League Management Tools';
$lang['lm_unauthorized']			= 'Unauthorized. Sorry you do not have the appropriate permission to this area.';
$lang['sql_settings_title']			= 'SQL Data Loader';
$lang['lm_about_title']			    = 'About the Online League Manager';
$lang['lm_license_title']			= 'License';
$lang['lm_required_title']			= 'Manage Required Tables';
$lang['lm_updated']					= 'Settings Last Updated: [DATE_TIME] AM by [USER_NAME]';

$lang['lm_league_settings']			= 'League Settings';
$lang['lm_settings_general']		= 'General Settings';
$lang['lm_settings_sport']			= 'Sport';
$lang['lm_game_sport_note']		    = 'Select major site sport';
$lang['lm_settings_source']			= 'Data Source';
$lang['lm_game_source_note']		= 'Select a game and version';
$lang['lm_settings_source_version']	= 'Game Version';
$lang['lm_settings_leagueid']		= 'Primary League ID';
$lang['lm_settings_leagueid_note']	= 'Select 100 for OOTP games.';
$lang['lm_settings_usedetails']		= 'Use Game Details';

$lang['lm_settings_ootp']			= 'Out of the Park Baseball Settings';
$lang['lm_settings_details']		= 'Game Details';
$lang['lm_settings_lgname']			= 'League Name';
$lang['lm_settings_lgabbr']			= 'Abbreviation';
$lang['lm_settings_lgicon']			= 'League Icon';
$lang['lm_settings_txtcolor']		= 'Text Color';
$lang['lm_settings_bgcolor']		= 'Background Color';

$lang['lm_settings_paths']			= 'Paths and Reports';
$lang['lm_settings_lgdfile']		= 'League File Path';
$lang['lm_settings_lgdfilenote']	= 'Full Server path to online league file, Including file name!';
$lang['lm_settings_assetpath']		= 'Asset Path';
$lang['lm_settings_assetpnote']		= 'Full server path to the static league assets folder. NO TRAILING SLASH!';
$lang['lm_settings_asseturl']		= 'Asset URL:';
$lang['lm_settings_assetunote']		= 'Web accessible URL to the static league assets folder.';

$lang['lm_settings_home']			= 'Home Page Settings';

$lang['lm_settings_twitter']		= 'Twitter User Handle';
$lang['lm_settings_twtrwnote']		= '<i>Example:</i> handleName';
$lang['lm_settings_tweets']			= 'No. of Tweets';

$lang['lm_import_owners']			= 'Create Users from Human Managers';
$lang['lm_create_user_notes']		= 'Use this tool to create user accounts based on human manager profiles in the game.';
$lang['lm_manager_matches']			= 'OOTP Human Manager/Site User Matches';
$lang['lm_manager_matches_notes']	= 'The following human manager names match with users in the site and no team owner is yet assigned.';
$lang['lm_manager_no_match_notes']	= 'The following human manager names do not match with any users in the site. Fill out the following fields to create new user accounts for any unowned teams. New user accounts will be automatically associated to the given team.';
$lang['lm_manager_new']				= 'Create New Users from Human Managers';
$lang['lm_team_owners']				= 'Map Users teams';
$lang['lm_otu_notes']				= 'Use this tool to map human managers from the OOTP league on the site to users on the web site. This allows site users to interact with tools based on their game profile.';
$lang['lm_save_owner_map']			= 'Save owner mapping';
$lang['lm_select_user']				= 'Select User';
$lang['lm_site_account_created']	= 'A new account on [SITE_TITLE] has been created for you';

$lang['sql_settings_subhead']		 	= 'SQL Settings';
$lang['sql_settings_path_note']			= 'Server path to MySQL Data Upload directory. NO TRAILING SLASH.';
$lang['sql_settings_mysqlpath']			= 'MySQL File Load Path:';
$lang['sql_use_db_prefix']			    = 'Use Database Prefix for data tables';
$lang['sql_use_db_prefix_note']			= 'Check this if your data tables use the same prefix as the site database. IF ootp table have no prefix, uncheck this option.';
$lang['sql_settings_timeout']		    = 'Max SQL Execution Time';
$lang['sql_settings_timeout_note']		= 'If your SQL imports are timing out, adjust the value of this setting to a higher number';
$lang['sql_settings_max']			    = 'Max SQL File Size';
$lang['sql_settings_max_note']			= 'Specify in Megabytes (mB)';
$lang['sql_settings_autosplit']			= 'Auto split files over max?';
$lang['sql_settings_autosplit_note']	= 'Action will occur every time the SQL Loader page is viewed and no splits are fouind for files over max mB size.';
$lang['sql_settings_auto_load']			= 'Load All Files action';
$lang['sql_settings_load_limit']		= 'Limit to only required';
$lang['sql_settings_load_all']		    = 'No limit, load all available files';

$lang['sql_required_tables']		    = 'Required Tables List';
$lang['sql_required_tables_edit']		= 'Edit Required Tables';

$lang['sim_date_na']		   			= 'N/A';
$lang['sim_setting_title']		   		= 'Sim Information';
$lang['sim_settings_autocalc']		    = 'Tools should determine Sim Length automatically';
$lang['sim_settings_calclen']		    = 'Calculated Sim Length:';
$lang['sim_setting_simlen']		    	= 'Sim Length Override';
$lang['sim_setting_simlen_note']		= 'days';
$lang['sim_setting_perweek']		    = 'Sims Per Week';
$lang['sim_setting_occuron']		    = 'Sims Occur On';
$lang['sim_setting_details']		    = 'Sims  Details';
$lang['sim_setting_league_file_date']	= 'League File Date';
$lang['sim_setting_next_sim']			= 'Next Sim Date';
$lang['sim_setting_league_date']		= 'League Date';
$lang['sim_setting_league_event']		= 'Next League Event';
$lang['sim_settings_useootp']			= 'Use OOTP Values';

$lang['own_map_id_error']				= 'Creation of owner/user link failed.';

$lang['lm_table_actions']				= 'Actions';
$lang['lm_sqlfiles_filename']			= 'Filename';
$lang['lm_sqlfiles_timestamp']			= 'Timestamp';
$lang['lm_sqlfiles_last_updated']		= 'Last Updated';
$lang['lm_sqlfiles_size']				= 'Size';
$lang['lm_action_load_checked']			= 'Load Checked';
$lang['lm_sqlfiles_no_files']			= 'No files were found.';
$lang['lm_required_tblname']			= 'Table Name';


