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
		
		// change the roles which don't have any specific login_destination set
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1, ".$permission_id.")");
		
		// News Articles
        $this->dbforge->add_field('`id` int(11) NOT NULL AUTO_INCREMENT');
        $this->dbforge->add_field('`name` varchar(255) NOT NULL');
        $this->dbforge->add_field("`required` tinyint(1) NOT NULL DEFAULT '0'");
        $this->dbforge->add_field("`modified_on` int(11) NOT NULL DEFAULT '0'");
        $this->dbforge->add_field("`modified_by` int(11) NOT NULL DEFAULT '-1'");

        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('sql_tables');

        $this->db->query("INSERT INTO {$prefix}sql_tables VALUES(1, 'cities',0,0,0)");
        $this->db->query("INSERT INTO {$prefix}sql_tables VALUES(2, 'continents',0,0,0)");
        $this->db->query("INSERT INTO {$prefix}sql_tables VALUES(3, 'games',0,0,0)");

        // News Articles
        $this->dbforge->add_field('`id` int(11) NOT NULL AUTO_INCREMENT');
        $this->dbforge->add_field("`table_id` tinyint(1) NOT NULL DEFAULT '0'");
        $this->dbforge->add_field("`version_min` int(11) NOT NULL DEFAULT '0'");
        $this->dbforge->add_field("`version_max` int(11) NOT NULL DEFAULT '0'");

        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('list_tables_versions');

        $this->db->query("INSERT INTO {$prefix}list_tables_versions VALUES(1, 1, 10, 100)");
        $this->db->query("INSERT INTO {$prefix}list_tables_versions VALUES(2, 2, 12, 100)");
        $this->db->query("INSERT INTO {$prefix}list_tables_versions VALUES(3, 3, 10, 100)");

        $data = array(
			'name'        => 'OOTPOL.SQL.Manage' ,
			'description' => 'Manage SQL Loading and Settings' 
		);
		$this->db->insert("{$prefix}permissions", $data);
		
		$permission_id = $this->db->insert_id();
		
		// change the roles which don't have any specific login_destination set
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1, ".$permission_id.")");
		
		// Team owners
        $this->dbforge->add_field('`id` int(11) NOT NULL AUTO_INCREMENT');
        $this->dbforge->add_field("`league_id` int(11) NOT NULL DEFAULT '100'");
        $this->dbforge->add_field("`team_id` int(11) NOT NULL DEFAULT '-1'");
        $this->dbforge->add_field("`user_id` int(11) NOT NULL DEFAULT '-1'");
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('teams_owners');


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
		//delete the role
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'OOTPOL.Site.Manage')");

		$this->dbforge->drop_table('sql_tables');
        $this->dbforge->drop_table('list_tables_versions');
        $this->dbforge->drop_table('teams_owners');

        $query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'OOTPOL.SQL.Manage'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the role
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'OOTPOL.SQL.Manage')");
		$this->db->query("DELETE FROM {$prefix}settings WHERE (module = 'ootp')");

	}
	
	//--------------------------------------------------------------------
	
}