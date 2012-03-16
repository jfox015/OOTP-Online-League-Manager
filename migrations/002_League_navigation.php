<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_League_navigation extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		if ($this->db->table_exists('navigation')) {
			$data = array('nav_id'=>1,
						  'title'=>'Home',
						  'url'=>'/',
						  'nav_group_id'=>1,
						  'position'=>1,
						  'parent_id'=>0,
						  'has_kids'=>0);
			$this->db->insert("{$prefix}navigation",$data);
			$data = array('nav_id'=>2,
						  'title'=>'Last Sim',
						  'url'=>'/lastsim',
						  'nav_group_id'=>1,
						  'position'=>2,
						  'parent_id'=>0,
						  'has_kids'=>0);
			$this->db->insert("{$prefix}navigation",$data);
		}
		if ($this->db->table_exists('navigation_group')) {
			$data = array('nav_group_id'=>1,
				'title'=>'header_nav',
				'abbr'=>'hn');
			$this->db->insert("{$prefix}navigation_group",$data);
		}
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;

		$this->db->query("DELETE FROM {$prefix}navigation WHERE nav_group_id=1;");
		$this->db->query("DELETE FROM {$prefix}navigation_group WHERE nav_group_id=1;");
	}
	
	//--------------------------------------------------------------------
	
}