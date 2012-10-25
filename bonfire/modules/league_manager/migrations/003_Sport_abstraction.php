<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Sport_abstraction extends Migration {

    public function up()
	{
		$prefix = $this->db->dbprefix;
		
		$this->db->query("UPDATE `{$prefix}settings` SET `name` = 'osp.source_version' WHERE `name` = 'ootp.game_version'");
		$this->db->query("UPDATE `{$prefix}settings` SET `name` = 'osp.league_id' WHERE `name` = 'ootp.league_id'");
		$this->db->query("UPDATE `{$prefix}settings` SET `name` = 'osp.use_game_details' WHERE `name` = 'ootp.use_ootp_details'");
		$this->db->query("UPDATE `{$prefix}settings` SET `value` = 'jfox015' WHERE `name` = 'ootp.twitter_string'");

		$new_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES ('osp.game_sport', 'osp', '0'), ('osp.game_source', 'osp', 'ootp');";
        $this->db->query($new_settings);
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;

		$this->db->query("UPDATE `{$prefix}settings` SET `name` = 'ootp.game_version' WHERE `name` = 'osp.game_source'");
		$this->db->query("UPDATE `{$prefix}settings` SET `name` = 'ootp.league_id' WHERE `name` = 'osp.league_id'");
		$this->db->query("UPDATE `{$prefix}settings` SET `name` = 'ootp.use_ootp_details' WHERE `name` = 'osp.use_game_details'");
        $this->db->query("UPDATE `{$prefix}settings` SET `value` = 'ootpbaseball' WHERE `name` = 'ootp.twitter_string'");

        $this->db->query("DELETE FROM `{$prefix}settings` WHERE (`name` = 'osp.game_sport' OR `name` = 'osp.game_source')");
	}
	
	//--------------------------------------------------------------------
	
}