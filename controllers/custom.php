<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Custom extends Admin_Controller {

	//--------------------------------------------------------------------

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
		$settings = read_config('league_manager');
		$tables = $this->sql_model->get_tables_loaded();
		if (isset($settings['league_id']) && !empty($settings['league_id'])) {
			Template::set('owner_count', $this->teams_model->get_owner_count($settings['league_id']));
		}
		Template::set('tables_loaded', sizeof($tables));
		Template::set('missing_tables', $this->sql_model->validate_loaded_files());
		Template::set('last_loaded', date('M d, Y h:i:s A',$this->sql_model->get_latest_load_time()));
		$settings = read_db_config('sql_Loader');

        $latestTime = 0;
		if (isset($settings['sql_path']) && !empty($settings['sql_path'])) :
			$this->load->helper('file');
			$latestTime = 0;
			if ($dir = opendir($settings['sql_path'])) {
				$loadCnt = 0;
				while (false !== ($file = readdir($dir)))	{
					$fileTime=filemtime($settings['sql_path']."/".$file);
					if ($fileTime<$latestTime) {continue;}
					if ($fileTime<$latestTime) {
						$latestTime = $fileTime;
					}
				}
			}
			
        endif;
        Template::set('last_file_time', date('M d, Y h:i:s A',$latestTime));
		Assets::add_css(array(Template::theme_url('css/bootstrap-responsive.min.css'),
			Template::theme_url('css/bootstrap.min.css')));
        Assets::add_js(Template::theme_url('js/bootstrap.min.js'));
		Template::set('toolbar_title', lang('dbrd_settings_title'));
        Template::set_view('league_manager/custom/index');
        Template::render();

	}
	
	//--------------------------------------------------------------------	

	function import_team_owners() {
		
		$settings = read_config('league_manager');
		if (!isset($this->user_model)) {
			$this->load->model('user_model');
		}
        if (!isset($this->leagues_model)) {
            $this->load->model('leagues_model');
        }
        if (!isset($this->teams_model)) {
            $this->load->model('teams_model');
        }
		$league = $this->leagues_model->find($settings['league_id']);
		if (isset($league) && $league->league_id != NULL) {
			Template::set('teams',$this->teams_model->get_teams_array($settings['league_id']));
			Template::set('users',$this->user_model->find_all(false));
		}	
		if ($this->input->post('submit')) {
		
		}
		Template::set('toolbar_title', lang('dbrd_import_owners'));
        Template::set_view('league_manager/custom/import_owners');
        Template::render();
		
	}
	
	//--------------------------------------------------------------------	

	function sim_details() {
	
		if ($this->input->post('submit')) {
		
			$this->form_validation->set_rules('sims_per_week', lang('sim_setting_perweek'), 'required|number|xss_clean');
			$this->form_validation->set_rules('sims_occur_on', lang('sim_setting_occuron'), 'required|xss_clean');
			$this->form_validation->set_rules('sims_details', lang('sim_setting_details'), 'number|xss_clean');
			$this->form_validation->set_rules('league_file_date', lang('sim_setting_league_file_date'), 'trim|xss_clean');
			$this->form_validation->set_rules('next_sim', lang('sim_setting_next_sim'), 'trim|xss_clean');
			$this->form_validation->set_rules('league_date', lang('sim_setting_league_date'), 'trim|xss_clean');
			$this->form_validation->set_rules('league_event', lang('sim_setting_league_event'), 'number|xss_clean');

			if ($this->form_validation->run() !== FALSE)
			
				$data = array(
					'sims_per_week'			=> $this->input->post('sims_per_week'),
					'sims_occur_on'			=> implode(",",$this->input->post('sims_occur_on')),
					'sims_details'			=> ($this->input->post('sims_details')) ? 1 : -1,
					'league_file_date'		=> $this->input->post('league_file_date'),
					'next_sim'				=> $this->input->post('next_sim'),
					'league_date'			=> $this->input->post('league_date'),
					'league_event'			=> $this->input->post('league_event'),

				);

				if (write_config('sims', $data)) {
				// Success, so reload the page, so they can see their settings
					Template::set_message('Sim settings successfully saved.', 'success');
					redirect(SITE_AREA .'/custom/league_manager');
				}
				else
				{
					Template::set_message('There was an error saving the file: config/sims.', 'error');
				}
		}
		
		$lg_configs = read_config('league_manager');
        $league_id = ((isset($lg_configs['league_id']))?$lg_configs['league_id']:100);
		Template::set(read_config('sims'));
        if (!isset($this->leagues_model)) {
            $this->load->model('leagues_model');
        }
        $league = $this->leagues_model->find($league_id);
		$league_date = ((isset($league->current_date)) ? strtotime($league->current_date) : time());
        if (!isset($this->leagues_events_model)) {
			$this->load->model('leagues_events_model');
		}
		Template::set('events',$this->leagues_events_model->get_events($league_id,$league_date,10));
        Template::set('toolbar_title', lang('sim_setting_title'));
        Template::set_view('league_manager/custom/sim_details');
        Template::render();
	}
	//--------------------------------------------------------------------
	
	/**
	 *	LOAD SQL DATA TABLE(S)
	 */
	function load_sql() {
		//$this->getURIData();

        if (!function_exists('loadSQLFiles')) {
            $this->load->helper('sql');
        }
        if (!function_exists('return_bytes')) {
            $this->load->helper('general');
        }
        $cfgs_arr = read_config('league_manager');
        $files_loaded = array();
        if ($this->input->post('submit')) {

			$this->uriVars = $this->uri->uri_to_assoc(3);
			
			$latest_load = $this->sql_model->get_latest_load_time();
			$required_tables = $this->sql_model->get_required_tables();
			if (isset($this->uriVars['loadList']) && sizeof($this->uriVars['loadList']) > 0) {
				$fileList = $this->uriVars['loadList'];
			} else if (isset($this->uriVars['filename']) && !empty($this->uriVars['filename'])) {
				$fileList = array($this->uriVars['filename']);
			} else if (isset($cfgs_arr['limit_load']) && $cfgs_arr['limit_load'] == 1) {
				$fileList = $required_tables;
			} else {
				$fileList = getSQLFileList($cfgs_arr['sql_path'],$latest_load);
			}
			
			$mess = loadSQLFiles($cfgs_arr['sql_path'],$latest_load, $fileList);
			if (!is_array($mess) || (is_array($mess) && sizeof($mess) == 0)) {
				if (is_array($mess)) {
					$status = "An error occured processing the SQL files.";
				} else {
					$status = "error: ".$mess;
				}
			} else {
				if (is_array($mess)) {
					$files_loaded = $mess;
				}
				Template::set_message('All tables were updated sucessfully.', 'success');
				$this->sql_model->set_tables_loaded($files_loaded);
			}
		}
		$file_list = getSQLFileList($cfgs_arr['sql_path']);
		
		Template::set('config', $cfgs_arr);
		Template::set('file_list', $file_list);
		Template::set('missing_files', $this->sql_model->validate_loaded_files($file_list));
		Template::set('files_loaded', $files_loaded);
		Template::set('required_tables', $this->sql_model->get_required_tables());
		Template::set('toolbar_title', lang('sql_settings_title'));
        Template::set_view('league_manager/custom/file_list');
        Template::render();
	}
	
	public function load_all_sql() {

        $cfgs_arr = read_config('league_manager');
        $latest_load = $this->sql_model->get_latest_load_time();
        if (isset($this->config['limit_load']) && $cfgs_arr['limit_load'] == 1) {
			$fileList = $this->sql_model->get_required_tables();
		} else {
			$fileList = getSQLFileList($cfgs_arr['sql_path'],$latest_load);
		}
		
		$files_loaded = array();
        $mess = loadSQLFiles($cfgs_arr['sql_path'],$latest_load, $fileList);
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
            $this->form_validation->set_rules('required_tables', lang('sql_required_tables'), 'required|trim|xss_clean');
			
			if ($this->form_validation->run() !== FALSE)
			{
				
				if ($this->sql_model->set_required_tables($this->input->post('required_tables'))) {
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
        $cfgs_arr = read_config('league_manager');
        Template::set('ootp_version', $cfgs_arr['ootp_version']);
		
		Template::set_view('league_manager/custom/table_list');
        Template::render('blank');
	}
	
	//--------------------------------------------------------------------
	
	/**
	 *	SPLIT SQL DATA FILE.
	 */
	function splitSQLFile() {
		if (!function_exists('splitFiles')) {
			$this->load->helper('sql_loader/sql');
		}
		$this->uriVars = $this->uri->uri_to_assoc(3);
        $cfgs_arr = read_config('league_manager');
        $mess = splitFiles($cfgs_arr['sql_path'],$this->uriVars['filename'], $cfgs_arr['max_sql_size']);
		if ($mess != "OK") {
			$status = "error:".$mess;
		} else {
			$status = "OK";
		}
		$code = 200;
		$result = '{"result":"'.$mess.'","code":"'.$code.'","status":"'.$status.'"}';
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output($result);
	}
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------
	
	
	
	//--------------------------------------------------------------------
}