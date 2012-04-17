<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: SQL_model
	
	Provides a system to track tables required by various versions of OOTP 
	and provide validation and meta details for table loading and load tracking.
	
	Copyright (c) 2012 Jeff Fox.

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

class SQL_model extends BF_Model 
{

	protected $table		= 'sql_tables';
	protected $key			= 'id';
	protected $soft_deletes	= false;
	protected $date_format	= 'int';
	protected $set_created	= false;
	protected $set_modified = true;
	
	/*-----------------------------------------------
	/	
	/	PUBLIC FUNCTIONS
	/
	/----------------------------------------------*/
	/**
	 *	Get Tables.
	 *	Returns a list of all tables in the sql_tables list.
	 *	@param	$ootp_version	int		Specify a specific OOTP version to target
	 *	@return	array
	 */
	public function get_tables($ootp_version = 13)
	{
		$this->db->select('id, name');
		$this->db->where('version_min <= '.$ootp_version);
        $this->db->where('version_max >= '.$ootp_version);
        return $this->find_all();
	}
	/**
	 *	Get Tables Loaded.
	 *	Returns an array of all tables from the sql_table_list that are present int he DB 
	 *	(have been loaded) and they're last modified time.
	 *	@return	array
	 */
	public function get_tables_loaded() 
	{

        $sql_tables = '(';
        $query = $this->db->select('name')->get('sql_tables');
		if ($query->num_rows() > 0) 
		{
            foreach($query->result() as $row) 
			{
                if ($sql_tables != '(') 
				{ 
					$sql_tables .= ','; 
				}
                $sql_tables .= "'".$row->name."'";
            }
        }
		$query->free_result();
        $sql_tables .= ')';
        if ($sql_tables != '()') 
		{
            $this->db->where('name IN '.$sql_tables);
			$this->db->where('modified_on <> "0"');
			return $this->find_all();
        }
		else
		{
			return false;
		}
	}
	/**
	 *	Set Tables Loaded.
	 *	Updates the modified time field of all tables in the loaded list.
	 *	@return	array
	 */
	public function set_tables_loaded($fileList)
	{
		$modified = time();
		if (empty($fileList) || !is_array($fileList))
		{
			$this->error = $this->lang->line('bf_model_no_data');
			//$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->lang->line('bf_model_no_data'));
			return false;
		}
		foreach($fileList as $file => $loaded) {
            $path = explode("/",$file);
            $tableName = explode(".",$path[sizeof($path)-1]);
			$this->update_where('name',$tableName[0],array('modified_on'=>$modified));
		}
	}
	/**
	 *	Get Required Tables.
	 *	Returns an array of all tables marked required.
	 *	@return	array
	 */
	public function get_required_tables() 
	{
		return $this->find_all_by('required',1);
	}
	/**
	 *	Set Required Tables.
	 *	Updates the database table(s) passed with the value of $required.
	 *	@param	$tables		String or array of table names
	 *	@param	$required	TRUE or FALSE
	 *	@return	array
	 */
	public function set_required_tables($tables = false, $required = true) 
	{
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
		$required = ($required === true) ? 1 : 0;
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
	public function getMissingTables() 
	{
		$missingTables = array();
		$required_tables = $this->get_required_tables();
		if (sizeof($required_tables) > 0) 
		{
			foreach ($required_tables as $tableName) 
			{
				if (!$this->db->table_exists($tableName->name))
				{
					array_push($missingTables,$tableName->name);
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
	public function validate_loaded_files($fileList = array(), $extension = '.mysql.sql') 
	{
		$missingTables = array();
		$loadedTables = array();
		foreach ($fileList as $file) 
		{
			$ex = '';
			if (strpos($file,".")) 
			{
				$name = explode(".",$file);
				$ex = $name[0];
			} 
			else 
			{
				$ex = $file;
			}
			array_push($loadedTables,$ex);
		}
        $query = $this->db->select('name')
                 ->where('required',1)
                 ->get($this->table);
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {

                $found = false;
                if (!in_array($row->name,$loadedTables))
                {
                    array_push($missingTables,$row->name .$extension);
                }
            }
        }
        $query->free_result();
		return $missingTables;
	}
	public function get_latest_load_time() 
	{
		$time = '';
		$this->db->select('modified_on');
		$this->db->order_by('modified_on','desc');
		$this->db->limit(1);
		$query = $this->db->get($this->table);
		if ($query->num_rows > 0) 
		{
			$time = $query->row()
						  ->modified_on;
		}
		$query->free_result();
		return $time;
	}
}