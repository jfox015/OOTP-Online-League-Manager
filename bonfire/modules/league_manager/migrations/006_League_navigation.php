<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_League_navigation extends Migration {

    public function up()
	{
		$prefix = $this->db->dbprefix;
		
		$nav_group_id = 0;
		if ($this->db->table_exists('navigation_group')) 
		{
			$data = array('nav_group_id'=>0,
				'title'=>'header_nav',
				'abbr'=>'hn');
			$this->db->insert("{$prefix}navigation_group",$data);
			$nav_group_id = $this->db->insert_id();
		}
		
		if ($this->db->table_exists('navigation') && $nav_group_id != 0) 
		{
			$data = array('nav_id'=>0,
						  'title'=>'Home',
						  'url'=>'/',
						  'nav_group_id'=>$nav_group_id,
						  'position'=>1,
						  'parent_id'=>0,
						  'has_kids'=>0);
			$this->db->insert("{$prefix}navigation",$data);
			$data = array('nav_id'=>0,
						  'title'=>'Last Sim',
						  'url'=>'/lastsim',
						  'nav_group_id'=>$nav_group_id,
						  'position'=>2,
						  'parent_id'=>0,
						  'has_kids'=>0);
			$this->db->insert("{$prefix}navigation",$data);
		}
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;

		if ($this->db->table_exists('navigation')) 
		{
			$query = $this->db->query("SELECT nav_group_id FROM {$prefix}navigation_group WHERE title = 'header_nav';");
			if ($query->num_rows() > 0) 
			{
				$nav_group_id = $query->row()->nav_group_id;
				$this->db->query("DELETE FROM {$prefix}navigation WHERE nav_group_id={$nav_group_id};");
				$this->db->query("DELETE FROM {$prefix}navigation_group WHERE nav_group_id={$nav_group_id};");
			}
		}
	}
	
	//--------------------------------------------------------------------
	
}