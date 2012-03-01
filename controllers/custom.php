<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Custom extends Admin_Controller {

	//--------------------------------------------------------------------

    protected $filename = null;

	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('OOTPOL.Site.Manage');
		$this->auth->restrict('OOTPOL.SQL.Manage');

		$this->lang->load('manager');
        $this->lang->load('sqlload');

		if (!class_exists('sql_model'))
		{
			$this->load->model('sql_model');
		}
	}
	
	//--------------------------------------------------------------------	

	public function index()
	{
		$settings = $this->settings_lib->find_all_by('module','ootp');
        $tables = array();
        if (!isset($settings['ootp.sql_path']) || empty($settings['ootp.sql_path']))
        {
            Template::set('settings_incomplete', true);
        }
        else
        {
            Template::set('settings_incomplete', false);
            if (!class_exists('teams_model'))
            {
                $this->load->model('teams_model');
            }
            $tables = $this->sql_model->get_tables_loaded();
            if (isset($settings['ootp.league_id']) && !empty($settings['ootp.league_id'])) {
                Template::set('owner_count', $this->teams_model->get_owner_count($settings['ootp.league_id']));
            }

            if (!function_exists('loadSQLFiles'))
            {
                $this->load->helper('sql');
            }
            $file_list = getSQLFileList($settings['ootp.sql_path']);
            Template::set('missing_files', $this->sql_model->validate_loaded_files($file_list));
            Template::set('last_loaded', date('M d, Y h:i:s A',$this->sql_model->get_latest_load_time()));

            $latestTime = 0;
            if (isset($settings['ootp.sql_path']) && !empty($settings['ootp.sql_path'])) :
                $this->load->helper('file');
                $latestTime = 0;
                if ($dir = opendir($settings['ootp.sql_path'])) {
                    $loadCnt = 0;
                    while (false !== ($file = readdir($dir)))	{
                        $fileTime=filemtime($settings['ootp.sql_path']."/".$file);
                        if ($fileTime<$latestTime) {continue;}
                        if ($fileTime<$latestTime) {
                            $latestTime = $fileTime;
                        }
                    }
                }

            endif;
            Template::set('last_file_time', date('M d, Y h:i:s A',$latestTime));
        }
        Template::set('tables_loaded', sizeof($tables));
         Assets::add_css(array(Template::theme_url('css/bootstrap-responsive.min.css'),
			Template::theme_url('css/bootstrap.min.css')));
        Assets::add_js(Template::theme_url('js/bootstrap.min.js'));
		Template::set('toolbar_title', lang('dbrd_settings_title'));
        Template::set_view('league_manager/custom/index');
        Template::render();
	}
	
	//--------------------------------------------------------------------	

	function import_team_owners() 
	{

        $settings = $this->settings_lib->find_all_by('module','ootp');
		if (!isset($this->user_model)) {
			$this->load->model('user_model');
		}
        if (!isset($this->leagues_model)) {
            $this->load->model('leagues_model');
        }
        if (!isset($this->teams_model)) {
            $this->load->model('teams_model');
        }
		$league = $this->leagues_model->find($settings['ootp.league_id']);
		if (isset($league) && $league->league_id != NULL) {
			Template::set('teams',$this->teams_model->get_teams_array($settings['ootp.league_id']));
			Template::set('users',$this->user_model->find_all(false));
		}	
		if ($this->input->post('submit')) {
		
		}
		$this->index();
		
	}
	
	//--------------------------------------------------------------------	

	function sim_details() 
	{
	
		if ($this->input->post('submit')) {
		
			$this->form_validation->set_rules('auto_sim_length', lang('dbrd_settings_autocalc'), 'number|xss_clean');
			$this->form_validation->set_rules('calc_length', lang('dbrd_settings_calclen'), 'number|xss_clean');
			$this->form_validation->set_rules('sim_length', lang('sim_setting_simlen'), 'number|xss_clean');
			$this->form_validation->set_rules('sims_per_week', lang('sim_setting_perweek'), 'required|number|xss_clean');
			$this->form_validation->set_rules('sims_occur_on', lang('sim_setting_occuron'), 'required|xss_clean');
			$this->form_validation->set_rules('sim_details', lang('sim_setting_details'), 'number|xss_clean');
			$this->form_validation->set_rules('league_file_date', lang('sim_setting_league_file_date'), 'trim|xss_clean');
			$this->form_validation->set_rules('next_sim', lang('sim_setting_next_sim'), 'trim|xss_clean');
			$this->form_validation->set_rules('league_date', lang('sim_setting_league_date'), 'trim|xss_clean');
			$this->form_validation->set_rules('league_event', lang('sim_setting_league_event'), 'number|xss_clean');

			if ($this->form_validation->run() !== FALSE) {

				$this->load->helper('date');
				$dates = textDateToInt(array('next_sim'=>'','league_file_date'=>'','league_date'=>''),$this->input);
				$data = array(
                    array('name' => 'ootp.auto_sim_length', 'value' => ($this->input->post('auto_sim_length')) ? 1 : -1),
                    array('name' => 'ootp.calc_length', 'value' => $this->input->post('calc_length')),
                    array('name' => 'ootp.sim_length', 'value' => $this->input->post('sim_length')),
                    array('name' => 'ootp.sims_per_week', 'value' => $this->input->post('sims_per_week')),
                    array('name' => 'ootp.sims_occur_on', 'value' => serialize($this->input->post('sims_occur_on'))),
                    array('name' => 'ootp.sim_details', 'value' => ($this->input->post('sim_details')) ? 1 : -1),
                    array('name' => 'ootp.next_sim', 'value' => $dates['next_sim']),
					array('name' => 'ootp.league_file_date', 'value' => $dates['league_file_date']),
					array('name' => 'ootp.league_date', 'value' => $dates['league_date']),
                    array('name' => 'ootp.league_event', 'value' => $this->input->post('league_event')),
				);
				
                //destroy the saved update message in case they changed update preferences.
                if ($this->cache->get('update_message'))
                {
                    if (!is_writeable(FCPATH.APPPATH.'cache/'))
                    {
                        $this->cache->delete('update_message');
                    }
                }
                // Log the activity
                $this->activity_model->log_activity($this->auth->user_id(), lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'ootp');

                // save the settings to the DB
				if ($this->settings_model->update_batch($data, 'name')) {
				// Success, so reload the page, so they can see their settings
					Template::set_message('Sim settings successfully saved.', 'success');
					redirect(SITE_AREA .'/custom/league_manager');
				}
				else
				{
					Template::set_message('There was an error saving sim settings.', 'error');
				}
            }
		}
        $settings = $this->settings_lib->find_all();
        Template::set('settings', $settings);

        $league_id = ((isset($settings['ootp.league_id']))?$settings['ootp.league_id']:100);

        if (!isset($this->leagues_model)) {
            $this->load->model('leagues_model');
        }
        $league = $this->leagues_model->find($league_id);
		$league_date = ((isset($league->current_date)) ? $league->current_date : date('Y-m-d'));
        if (!isset($this->leagues_events_model)) {
			$this->load->model('leagues_events_model');
		}
		Template::set('events',$this->leagues_events_model->get_events($league_id,$league_date,10));
        Template::set('toolbar_title', lang('sim_setting_title'));
        Template::set_view('league_manager/custom/sim_details');
        Template::render();
	}
	//--------------------------------------------------------------------

    function load_sql_file($filename = '') 
	{

        $filename= $this->uri->segment(5);
        if (isset($filename) && !empty($filename)) {
            $this->filename = urldecode($filename);
            $this->load_sql();
        }

    }
	/**
	 *	LOAD SQL DATA TABLE(S)
	 */
	function load_sql() 
	{
		//$this->getURIData();

        if (!function_exists('loadSQLFiles')) 
		{
            $this->load->helper('sql');
        }
        if (!function_exists('return_bytes')) 
		{
            $this->load->helper('general');
        }
        $settings = $this->settings_lib->find_all_by('module','ootp');
        Template::set('settings', $settings);

        $files_loaded = array();
		$required_tables = $this->sql_model->get_required_tables();
		
        $file = $this->uri->segment(5);
		if ($this->filename == null && isset($file) && !empty($file))
		{
			$this->filename = $file;
			//print ("Filename: ".$this->filename."<br />");
		}
        $latest_load = $this->sql_model->get_latest_load_time();
        $fileList = false;
        if ($this->input->post('submit'))
		{

			if (isset($_POST['loadList']) && sizeof($_POST['loadList']) > 0)
			{
				$fileList = $_POST['loadList'];
			}
        }
			else if (isset($this->filename) && !empty($this->filename))
			{
				$fileList = array($this->filename);
			}
			else if (isset($settings['ootp.limit_load']) && $settings['ootp.limit_load'] == 1)
			{
				$fileList = $required_tables;
			}
			else
			{
				$fileList = getSQLFileList($settings['ootp.sql_path'],$latest_load);
			}
        if ($fileList !== false) {
			$mess = loadSQLFiles($settings['ootp.sql_path'],$latest_load, $fileList);
			if (!is_array($mess) || (is_array($mess) && sizeof($mess) == 0))
			{
				if (is_array($mess))
				{
					$status = "An error occured processing the SQL files.";
				}
				else
				{
					$status = "error: ".$mess;
				}
			}
			else
			{
				if (is_array($mess))
				{
					$files_loaded = $mess;
				}
				Template::set_message('All tables were updated sucessfully.', 'success');
				$this->sql_model->set_tables_loaded($files_loaded);
			}
		}

		$file_list = getSQLFileList($settings['ootp.sql_path']);

		Template::set('file_list', $file_list);
		Template::set('missing_files', $this->sql_model->validate_loaded_files($file_list));
		Template::set('load_times', $this->sql_model->get_tables_loaded());
		Template::set('files_loaded', $files_loaded);
		Template::set('required_tables', $required_tables);
		Template::set('toolbar_title', lang('sql_settings_title'));
        Template::set_view('league_manager/custom/file_list');
        Template::render();
	}

	public function load_all_sql() {

        $settings = $this->settings_lib->find_all_by('module','ootp');
        $latest_load = $this->sql_model->get_latest_load_time();
        if (isset($settings['ootp.limit_load']) && $settings['ootp.limit_load'] == 1) {
			$fileList = $this->sql_model->get_required_tables();
		} else {
			$fileList = getSQLFileList($settings['ootp.sql_path'],$latest_load);
		}
		
		$files_loaded = array();
        $mess = loadSQLFiles($settings['ootp.sql_path'],$latest_load, $fileList);
		if (!is_array($mess) || (is_array($mess) && sizeof($mess) == 0)) {
			if (is_array($mess)) {
				$status = "An error occured processing the SQL files.";
			} else {
				$status = "error: ".$mess;
			}
			Template::set_message($status, 'error');
		} else {
			if (is_array($mess)) {
				$files_loaded = $mess;
			}
			Template::set_message('All tables were updated sucessfully.', 'success');
			$this->sql_model->set_tables_loaded($files_loaded);
		}
		$this->index();
		
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	SPLIT SQL DATA FILE.
	 */
	public function table_list() {
		
		if ($this->input->post('submit'))
		{
            $tables = false;
            if (is_array(($tables = $this->input->post('required_tables')))) {

				if ($this->sql_model->set_required_tables($tables)) {
					// Success, so reload the page, so they can see their settings
					Template::set_message('Required table update successfully saved.', 'success');
				}
				else
				{
					Template::set_message('There was an error saving the update.', 'error');
				}
			}
		}
		
		Template::set('table_list', $this->sql_model->get_tables());
		Template::set('required_tables', $this->sql_model->get_required_tables());
        $settings = $this->settings_lib->find_all_by('module','ootp');
        Template::set('ootp_version', $settings['ootp.game_version']);
		
		Template::set_view('league_manager/custom/table_list');
        Template::render();
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	SPLIT SQL DATA FILE.
	 */
	function splitSQLFile() {
		if (!function_exists('splitFiles')) {
			$this->load->helper('sql');
		}
		$mess =  "No filename provided.";
		$filename = $this->uri->segment(5);
        $delete = $this->uri->segment(6);
		$delete = ((isset($delete) && $delete == 1) ? true : false);
		
        if (isset($filename) && !empty($filename)) {
			$settings = $this->settings_lib->find_all_by('module','ootp');
			$mess = splitFiles($settings['ootp.sql_path'],$filename, $delete, $settings['ootp.max_sql_size']);
		}
		if ($mess != "OK") {
			Template::set_message("error:".$mess,'error');
			//$status = "error:".$mess;
		} else {
            Template::set_message("File successfully split",'success');
            //$status = "OK";
		}
        $this->load_sql();
		/*$code = 200;
		$result = '{"result":"'.$mess.'","code":"'.$code.'","status":"'.$status.'"}';
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output($result);*/
	}
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
}