<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_OOTP14_tables extends Migration {

    public function up()
	{
		$prefix = $this->db->dbprefix;
		
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(66, 'messages',0,0,0,14,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(67, 'projected_starting_pitchers',0,0,0,14,100)");
		$this->db->query("INSERT INTO {$prefix}sql_tables VALUES(68, 'trade_history',0,0,0,14,100)");

	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;

		$this->db->query("DELETE FROM {$prefix}sql_tables where id IN (66, 67, 68)");

	}
	
	//--------------------------------------------------------------------
	
}