<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: SQLLoader
	
	Provides a system to track tables required by various version of OOTP 
	and provide validation and meta details for table load tracking.
*/

class SQL_model extends BF_Model {

	protected $table		= 'sql_tables';
	protected $key			= 'id';
	protected $soft_deletes	= true;
	protected $date_format	= 'datetime';
	protected $set_created	= false;
	protected $set_modified = true;
	
	/*-----------------------------------------------
	/	
	/	PUBLIC FUNCTIONS
	/
	/----------------------------------------------*/
	
	public function set_tables_loaded($tableList) {
		
		$modified = time();
		if (empty($tableList) || !is_array($tableList))
		{
			$this->error = $this->lang->line('bf_model_no_data');
			//$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_no_data'));
			return false;
		}
		
		foreach($tableList as $table) {
			$this->update_where('name',$table,array('modified_on'=>$modified));
		}
	}
	
	public function get_tables_loaded() {
		
		$this->db->where('modified_on <> "0"');
		return $this->find_all();
	}
	
	public function get_required_tables() {
		
		return $this->find_all_by('required',1);
	}
	/**
	 *	Set Required Tables.
	 *	Updates the database table(s) passed with the value of $required.
	 *	@param	$tables		String or array of table names
	 *	@param	$required	TRUE or FALSE
	 *	@return	array
	 */
	public function set_required_tables($tables = false, $required = true) {
		
		$tbl_list = array();
		// Add a string
		if (is_string($tables))
		{
			array_push($tbl_list,$tables);
		}
		// Add an array
		else if (is_array($tables) && count($tables))
		{
			$tbl_list = $tables;
		}
		foreach($tbl_list as $table) 
		{
			$row = $this->find_by('name',$table);
			if ($row->required != $required) 
			{
				$this->update($row->id,array('required'=>$required));
			}
		}
		return true;
	}
	/**
	 *	Returns a list of required OOTP data tables that are not currently loaded.
	 *	@return	array
	 */
	public function getMissingTables() {
		$missingTables = array();
		$required_tables = $this->get_required_tables();
		if (sizeof($required_tables) > 0) {
			foreach ($required_tables as $tableName) {
				if (!$this->db->table_exists($tableName)) {
					array_push($missingTables,$tableName);
				}
			}
		}
		return $missingTables;
	}
	/**
	 *	VALIDATE LOADED FILES.
	 *	This function accepts an array of MySQL export files names and determines which if any of the
	 * 	required files are missing. Returns a list of required OOTP sql files that are not
	 *	cfound in the passed file list.
	 *	@param	$fileList	Array of SQL files
	 *	@param	$extention	Custom file extension (if different form OOTP export defualt)
	 *	@return	array
	 */
	public function validate_loaded_files($fileList = array(), $extension = '.mysql.sql') {
		$missingTables = array();
		$loadedTables = array();
		foreach ($fileList as $file) {
			$ex = '';
			if (strpos($file,".")) {
				$name = explode(".",$file);
				$ex = $name[0];
			} else {
				$ex = $file;
			}
			array_push($loadedTables,$ex);
		}
        $query = $this->db->select('name')
                 ->where('required',1)
                 ->get($this->table);
        if ($query->num_rows() > 0) {
            $required_tables = $query->result_array();
            if (isset($required_tables) && sizeof($required_tables) > 0) {
                foreach ($required_tables as $tableName) {
                    $found = false;
                    if (!in_array($tableName,$loadedTables)) {
                        array_push($missingTables,$tableName.$extension);
                    }
                }
            }
        }
		return $missingTables;
	}
	public function get_latest_load_time() {
		
		$time = '';
		$this->db->select('modified_on');
		$this->db->order_by('modified_on','desc');
		$this->db->limit(1);
		$query = $this->db->get($this->table);
		if ($query->num_rows > 0) {
			$time = $query->row()
						  ->modified_on;
		}
		$query->free_result();
		return $time;
	}
	public function get_tables($ootp_version = OOTP_CURRENT_VERSION) {
		
		$this->db->select('sql_tables.id, name');
        $this->db->join('list_tables_versions', 'sql_tables.id = list_tables_versions.table_id');
		
		$this->db->where('version_min <= '.$ootp_version);
        $this->db->where('version_max >= '.$ootp_version);
        return $this->find_all();
	}
	
	
}