<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Sport_abstraction extends Migration {

    private $update_settings = array(
		'ootp.league_id'=>'osp.league_id',
		'ootp.use_ootp_details'=>'osp.use_game_details',
		'ootp.game_version'=>'osp.source_version',
		'ootp.asset_path'=>'osp.asset_path',
		'ootp.asset_url'=>'osp.asset_url',
		'ootp.header_img'=>'osp.header_img',
		'ootp.twitter_string'=>'osp.twitter_string',
		'ootp.tweet_count'=>'osp.tweet_count',
		'ootp.sql_path'=>'osp.sql_path',
		'ootp.use_db_prefix'=>'osp.use_db_prefix',
		'ootp.auto_split'=>'osp.auto_split',
		'ootp.max_sql_size'=>'osp.max_sql_size',
		'ootp.limit_load'=>'osp.limit_load',
		'ootp.sims_per_week'=>'osp.sims_per_week',
		'ootp.auto_sim_length'=>'osp.auto_sim_length',
		'ootp.sim_length'=>'osp.sim_length',
		'ootp.calc_length'=>'osp.calc_length',
		'ootp.sims_occur_on'=>'osp.sims_occur_on',
		'ootp.sims_details'=>'osp.sims_details',
		'ootp.league_file_path'=>'osp.league_file_path',
		'ootp.league_file_date'=>'osp.league_file_date',
		'ootp.next_sim'=>'osp.next_sim',
		'ootp.league_date'=>'osp.league_date',
		'ootp.league_event'=>'osp.league_event'
	);
	private $new_settings = array(
		'osp.game_sport'=>'0',
		'osp.game_source'=>'ootp',
        'osp.sql_timeout'=>'120'
	);
	private $ignore_fields = "
		'ootp.league_name',
		'ootp.league_abbr',
		'ootp.league_icon',
		'ootp.league_txtcolor',
		'ootp.league_bgcolor'
	";
	public function up()
	{
		$prefix = $this->db->dbprefix;
		
		foreach ($this->update_settings as $old => $new) {
			$this->db->query("UPDATE `{$prefix}settings` SET `name` = '".$new."' WHERE `name` = '".$old."'");
		}
		$this->db->query("UPDATE `{$prefix}settings` SET `module` = 'osp' WHERE `module` = 'ootp' AND `name` NOT IN (".$this->ignore_fields.")");
		$newStr = '';
		foreach ($this->new_settings as $key => $value) {
			if (!empty($newStr)) { $newStr .= ", "; }
			$newStr .= "('".$key."', 'osp', '".$value."')";
		}
        $this->db->query("INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES ".$newStr);
	}

	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;

		foreach ($this->update_settings as $old => $new) {
			$this->db->query("UPDATE `{$prefix}settings` SET `name` = '".$old."' WHERE `name` = '".$new."'");
		}
		$this->db->query("UPDATE `{$prefix}settings` SET `module` = 'ootp' WHERE `module` = 'osp' AND `name` NOT IN (".$this->ignore_fields.")");
		$newStr = "";
		foreach ($this->new_settings as $key => $value) {
			if (!empty($newStr)) { $newStr .= " OR "; }
			$newStr .= "`name` = '".$key."'";
		}
        $newStr .= "";

        $this->db->query("DELETE FROM `{$prefix}settings` WHERE (".$newStr.")");
	}
	
	//--------------------------------------------------------------------
	
}