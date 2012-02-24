<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Load_tables_permissions extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		$data = array(
			'name'        => 'OOTPOL.Site.Manage' ,
			'description' => 'Manage OOTP Online Settings and Content' 
		);
		$this->db->insert("{$prefix}permissions", $data);
		
		$permission_id = $this->db->insert_id();
		
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1, ".$permission_id.")");
		
		$data = array(
			'name'        => 'OOTPOL.SQL.Manage' ,
			'description' => 'Manage SQL Loading and Settings' 
		);
		$this->db->insert("{$prefix}permissions", $data);
		
		$permission_id = $this->db->insert_id();
		
		// change the roles which don't have any specific login_destination set
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1, ".$permission_id.")");
		
		// SQL Table List
        $this->dbforge->add_field('`id` int(11) NOT NULL AUTO_INCREMENT');
        $this->dbforge->add_field('`name` varchar(255) NOT NULL');
        $this->dbforge->add_field("`required` tinyint(1) NOT NULL DEFAULT '0'");
        $this->dbforge->add_field("`modified_on` int(11) NOT NULL DEFAULT '0'");
        $this->dbforge->add_field("`modified_by` int(11) NOT NULL DEFAULT '-1'");
		$this->dbforge->add_field("`version_min` int(11) NOT NULL DEFAULT '7'");
        $this->dbforge->add_field("`version_max` int(11) NOT NULL DEFAULT '100'");
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('sql_tables');

        $this->db->query("INSERT INTO {$prefix}sql_tables VALUES(1, 'cities',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(2, 'coaches',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(3, 'continents',0,0,0,12,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(4, 'divisions',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(5, 'games',1,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(6, 'games_score',1,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(7, 'human_managers',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(8, 'human_manager_history',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(9, 'human_manager_history_batting_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(10, 'human_manager_history_fielding_stats_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(11, 'human_manager_history_financials',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(12, 'human_manager_history_pitching_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(13, 'human_manager_history_record',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(14, 'languages',0,0,0,12,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(15, 'language_data',0,0,0,12,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(16, 'leagues',1,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(17, 'league_events',1,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(18, 'league_history',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(19, 'league_history_all_star',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(20, 'league_history_batting_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(21, 'league_history_fielding_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(22, 'league_history_pitching_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(23, 'league_playoffs',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(24, 'league_playoff_fixtures',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(25, 'nations',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(26, 'parks',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(27, 'players',1,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(28, 'players_at_bat_batting_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(29, 'players_awards',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(30, 'players_batting',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(31, 'players_career_batting_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(32, 'players_career_fielding_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(33, 'players_career_pitching_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(34, 'players_contract',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(35, 'players_contract_extension',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(36, 'players_fielding',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(37, 'players_game_batting',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(38, 'players_game_pitching_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(39, 'players_individual_batting_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(40, 'players_league_leader',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(41, 'players_pitching',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(42, 'players_roster_status',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(43, 'players_streak',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(44, 'players_value',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(45, 'states',0,0,0,12,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(46, 'sub_leagues',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(47, 'teams',1,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(48, 'team_affiliations',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(49, 'team_batting_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(50, 'team_bullpen_pitching_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(51, 'team_fielding_stats_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(52, 'team_financials',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(53, 'team_history',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(54, 'team_history_batting_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(55, 'team_history_fielding_stats_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(56, 'team_history_financials',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(57, 'team_history_pitching_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(58, 'team_history_record',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(59, 'team_last_financials',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(60, 'team_pitching_stats',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(61, 'team_record',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(62, 'team_relations',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(63, 'team_roster',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(64, 'team_roster_staff',0,0,0,7,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(65, 'team_starting_pitching_stats',0,0,0,7,100)");

		// Team owners
        $this->dbforge->add_field('`id` int(11) NOT NULL AUTO_INCREMENT');
        $this->dbforge->add_field("`league_id` int(11) NOT NULL DEFAULT '100'");
        $this->dbforge->add_field("`team_id` int(11) NOT NULL DEFAULT '-1'");
        $this->dbforge->add_field("`user_id` int(11) NOT NULL DEFAULT '-1'");
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('teams_owners');

		// Default Settings
        $default_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES
			 ('ootp.league_id', 'ootp', '100'),
			 ('ootp.use_ootp_details', 'ootp', '1'),
			 ('ootp.game_version', 'ootp', '12'),
			 ('ootp.league_name', 'ootp', ''),
			 ('ootp.league_abbr', 'ootp', ''),
			 ('ootp.league_icon', 'ootp', ''),
			 ('ootp.league_txtcolor', 'ootp', ''),
			 ('ootp.league_bgcolor', 'ootp', ''),
			 ('ootp.league_file_path', 'ootp', ''),
			 ('ootp.asset_path', 'ootp', ''),
			 ('ootp.asset_url', 'ootp', ''),
			 ('ootp.header_img', 'ootp', ''),
			 ('ootp.twitter_string', 'ootp', 'ootpbaseball'),
			 ('ootp.tweet_count', 'ootp', '3'),
			 ('ootp.sql_path', 'ootp', ''),
			 ('ootp.auto_split', 'ootp', '-1'),
			 ('ootp.max_sql_size', 'ootp', ''),
			 ('ootp.limit_load', 'ootp', '1'),
			 ('ootp.sims_per_week', 'ootp', ''),
			 ('ootp.sims_occur_on', 'ootp', ''),
			 ('ootp.sims_details', 'ootp', ''),
			 ('ootp.league_file_date', 'ootp', ''),
			 ('ootp.next_sim', 'ootp', ''),
			 ('ootp.league_date', 'ootp', ''),
			 ('ootp.league_event', 'ootp', '');
		";
        $this->db->query($default_settings);
		
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'OOTPOL.Site.Manage'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the permission
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'OOTPOL.Site.Manage')");

		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'OOTPOL.SQL.Manage'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the permission
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'OOTPOL.SQL.Manage')");
		
		$this->dbforge->drop_table('sql_tables');
        $this->dbforge->drop_table('teams_owners');

        $this->db->query("DELETE FROM {$prefix}settings WHERE (module = 'ootp')");

	}
	
	//--------------------------------------------------------------------
	
}