<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	Base OOTP DATA MODEL CLASS.
 *
 *	@author			Jeff Fox <jfox015 (at) gmail (dot) com>
 *  @copyright   	(c)2009-12 Jeff Fox/Aeolian Digital Studios
 *	@version		1.0
 *
 *	This model provides a basic set of function used by all models in 
 *	the league_manager module. To use:
 *	<ul>
 *		<li>Require this model before the class declaration:<br />
 *		<code>
 *      require_once(dirname(dirname(__FILE__)).'/models/base_ootp_model.php');
 *      </code></li>
 *      <.li>extend the class using Base_ootp_model</li>
 *  </ul>
 */
class Base_ootp_model extends BF_Model {

	//---------------------------------------------------------------
	protected $dbprefix = '';

	public function __construct()
	{
		parent::__construct();
        $this->dbprefix = $this->db->dbprefix;
	}

    /*--------------------------------------------------
     /
     /	PUBLIC FUNCTIONS
     /
     /-------------------------------------------------*/
    public function find($id) {
        $data = array();
		$this->db->dbprefix = '';
        if ($this->db->table_exists($this->table)) {
            $data = parent::find($id);
        }
        $this->db->dbprefix = $this->dbprefix;
        return $data;
    }

    public function find_all($id) {
        $this->db->dbprefix = '';
        if ($this->db->table_exists($this->table)) {
            $data = parent::find_all($id);
        }
        $this->db->dbprefix = $this->dbprefix;
        return $data;
    }
    public function find_by($field = '', $value='', $type='') {
        $this->db->dbprefix = '';
        if ($this->db->table_exists($this->table)) {
            $data = parent::find_by($field, $value, $type);
        }
        $this->db->dbprefix = $this->dbprefix;
        return $data;
    }
    public function find_all_by($field = '', $value='') {
        $this->db->dbprefix = '';
        if ($this->db->table_exists($this->table)) {
            $data = parent::find_all_by($field, $value);
        }
        $this->db->dbprefix = $this->dbprefix;
        return $data;
    }

}