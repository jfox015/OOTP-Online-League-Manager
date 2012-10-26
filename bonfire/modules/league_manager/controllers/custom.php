<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Custom extends Admin_Controller {

	//--------------------------------------------------------------------

    protected $filename = null;

	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('League_Manager.Custom.View');

		$this->lang->load('manager');
        $this->lang->load('sqlload');

		if (!class_exists('sql_model'))
		{
			$this->load->model('sql_model');
		}
		Template::set_block('sub_nav', 'custom/_sub_nav');
	}
	
	//--------------------------------------------------------------------	

	public function index()
	{
		$settings = $this->settings_lib->find_all();
        $tables = false;
        $tables_loaded = 0;
        if (!isset($settings['osp.sql_path']) || empty($settings['osp.sql_path']))
        {
            Template::set('settings_incomplete', true);
        }
        else
        {
            Template::set('settings_incomplete', false);
            if (!isset($this->leagues_model))
            {
                $this->load->model('open_sports_toolkit/leagues_model');
            }
            if (!class_exists('teams_model'))
            {
                $this->load->model('open_sports_toolkit/teams_model');
            }
            $tables_loaded = $this->sql_model->count_tables_loaded();
            if (isset($settings['osp.league_id']) && !empty($settings['osp.league_id'])) {
                $league = $this->leagues_model->find($settings['osp.league_id']);
                if (isset($league) && isset($league->league_id) && $league->league_id != null)
                {
                    Template::set('user_count', $this->user_model->count_all(false));
                    Template::set('team_count', $this->teams_model->get_team_count($settings['osp.league_id']));
                    Template::set('owner_count', $this->teams_model->get_owner_count($settings['osp.league_id']));
                }
            }
            if (!function_exists('loadSQLFiles'))
            {
                $this->load->helper('sql');
            }
            $file_list = getSQLFileList($settings['osp.sql_path']);
            Template::set('missing_files', $this->sql_model->validate_loaded_files($file_list));
            Template::set('missing_tables', $this->sql_model->getMissingTables());
            $last_load = $this->sql_model->get_latest_load_time();
            Template::set('last_loaded', ($last_load != '0') ? date('M d, Y h:i:s A',$last_load): "- - - ");

            $latestTime = 0;
            if (isset($settings['osp.sql_path']) && !empty($settings['osp.sql_path'])) :
                $this->load->helper('file');
                $latestTime = 0;
                if ($dir = opendir($settings['osp.sql_path'])) {
                    $loadCnt = 0;
                    while (false !== ($file = readdir($dir)))	{
                        $fileTime=filemtime($settings['osp.sql_path'].DIRECTORY_SEPARATOR.$file);
                        if (strpos($file,".sql") === false || $fileTime < $latestTime) {continue;}
                        if ($fileTime > $latestTime) {
                            $latestTime = $fileTime;
                        }
                    }
                }

            endif;
            Template::set('last_file_time', ($latestTime != '0') ? date('M d, Y h:i:s A',$latestTime) : '- - -');
        }
        Template::set('settings', $settings);
        Template::set('tables_loaded', $tables_loaded);
        Template::set('required_table_count', $this->sql_model->count_required_tables());
        Template::set('toolbar_title', lang('lm_settings_title'));
        Template::set_view('league_manager/custom/index');
        Template::render();
	}

    //--------------------------------------------------------------------

    public function about() {

        Template::set('toolbar_title', lang('lm_about_title'));
        Template::render();
    }

    //--------------------------------------------------------------------

    public function license() {

        Template::set('toolbar_title', lang('lm_license_title'));
        Template::render();
    }
	//--------------------------------------------------------------------

	function map_users_to_teams() 
	{

        $settings = $this->settings_lib->find_all();
		if (!isset($this->author_model)) 
		{
			$this->load->model('news/author_model');
		}
		if (!isset($this->leagues_model))
		{
			$this->load->model('open_sports_toolkit/leagues_model');
		}
		if (!isset($this->teams_owners_model))
		{
			$this->load->model('league_manager/teams_owners_model');
		}
        $league = $this->leagues_model->find($settings['osp.league_id']);
		if (isset($league) && $league->league_id != null) 
		{
			$teams_owners = $this->teams_owners_model->get_team_owner_list($settings['osp.league_id']);
			if ($this->input->post('submit')) {
				$duped_ids = $this->save_team_owners($teams_owners, $settings['osp.league_id']);
                if (sizeof($duped_ids) == 0 && empty($this->teams_owners_model->error)) {
					Template::set_message('Team owner settings saved.','success');
				}
				else
				{
                    $errorStr = '';
                    if (sizeof($duped_ids) > 0)
                    {
                        $errorStr .= '<br />The following user id&#039;s were selected more than once. Obly the first selection for each user is saved.:<ul>';

                        if (sizeof($duped_ids) > 1)
                        {
                            foreach ($duped_ids as $id)
                            {
                                $errorStr .= '<li>'.$this->author_model->find_author($id).'</li>';
                            }
                        }
                        else
                        {
                            $errorStr .= '<li>'.$this->author_model->find_author($duped_ids[0]).'</li>';
                        }
                        $errorStr .= '</ul>';
                    }
                    if (!empty($this->teams_owners_model->error))
                    {
                        $errorStr .= $this->teams_owners_model->error;
                    }
                    Template::set_message('Error saving owner information. Errors:'.$errorStr,'error');
				}
			}
            $this->load->helper('open_sports_toolkit/general');
            // ASSURE PATH COMPLIANCE TO OOPT VERSION
            $settings = get_asset_path($settings);
            Template::set('settings',$settings);
			$this->lang->load('news/news');
            Template::set('users',$this->author_model->get_users_select(true));
            $teams_owners = $this->teams_owners_model->get_team_owner_list($settings['osp.league_id']);
            Template::set('team_owners',$teams_owners);
		}
		else
		{
			Template::set_message('The league ID specified in the OOTP League Manager settings was not found.', 'error');
		}
        Template::set('leagues', $this->leagues_model->find_all());
		Template::set('toolbar_title', lang('lm_owners_to_users'));
		Template::set_view('league_manager/custom/map_users_to_teams');
		Template::render();
	}	
	//--------------------------------------------------------------------	

	function create_members_from_game() 
	{
        $settings = $this->settings_lib->find_all();
		if (!isset($this->human_managers_model))
		{
			$this->load->model('open_sports_toolkit/human_managers_model');
		}
		if (!isset($this->leagues_model))
		{
			$this->load->model('open_sports_toolkit/leagues_model');
		}
		 if (!isset($this->teams_owners_model))
		{
			$this->load->model('league_manager/teams_owners_model');
		}
        $league = $this->leagues_model->find($settings['osp.league_id']);
		if (isset($league) && $league->league_id != null) 
		{
			$use_usernames = $this->settings_lib->item('auth.use_own_names');
			if ($this->input->post('submit')) 
			{
                if ($this->create_team_owners($settings['osp.league_id']))
				{
					Template::set_message('New members were created and assigned to teams successfully.','success');
				}
				else
				{
					Template::set_message('Error saving owner information.','error');
				}
			}
			Template::set('settings',$settings);
			$arrays = $this->human_managers_model->get_owner_user_matches($settings['osp.league_id']);
			Template::set('owner_matches',$arrays[0]);
			Template::set('non_matches',$arrays[1]);
			Template::set('use_usernames',$use_usernames);
		}
		else
		{
			Template::set_message('The league ID specified in the OOTP League Manager settings was not found.', 'error');
		}
		Template::set('leagues', $this->leagues_model->find_all());
		Template::set('toolbar_title', lang('lm_owners_to_users'));
		Template::set_view('league_manager/custom/create_members_from_game');
		Template::render();
	}
	
	//--------------------------------------------------------------------	

	function sim_details() 
	{

        $this->auth->restrict('League_Manager.Sim.Manage');

        if ($this->input->post('submit')) {
		
			$this->form_validation->set_rules('auto_sim_length', lang('sim_ettings_autocalc'), 'number|xss_clean');
			$this->form_validation->set_rules('calc_length', lang('sim_settings_calclen'), 'number|xss_clean');
			$this->form_validation->set_rules('sim_length', lang('sim_setting_simlen'), 'number|xss_clean');
			$this->form_validation->set_rules('sims_per_week', lang('sim_setting_perweek'), 'required|number|xss_clean');
			$this->form_validation->set_rules('sims_occur_on', lang('sim_setting_occuron'), 'required|xss_clean');
			$this->form_validation->set_rules('sim_details', lang('lm_settings_useootp'), 'number|xss_clean');
			$this->form_validation->set_rules('league_file_date', lang('sim_setting_league_file_date'), 'trim|xss_clean');
			$this->form_validation->set_rules('next_sim', lang('sim_setting_next_sim'), 'trim|xss_clean');
			$this->form_validation->set_rules('league_date', lang('sim_setting_league_date'), 'trim|xss_clean');
			$this->form_validation->set_rules('league_event', lang('sim_setting_league_event'), 'number|xss_clean');

			if ($this->form_validation->run() !== FALSE) {

				$this->load->helper('date');
				//$dates = text_date_to_int(array('next_sim'=>'','league_file_date'=>'','league_date'=>''),$this->input);
				$data = array(
                    array('name' => 'osp.auto_sim_length', 'value' => ($this->input->post('auto_sim_length')) ? 1 : -1),
                    array('name' => 'osp.calc_length', 'value' => $this->input->post('calc_length')),
                    array('name' => 'osp.sim_length', 'value' => $this->input->post('sim_length')),
                    array('name' => 'osp.sims_per_week', 'value' => $this->input->post('sims_per_week')),
                    array('name' => 'osp.sims_occur_on', 'value' => serialize($this->input->post('sims_occur_on'))),
                    array('name' => 'osp.sim_details', 'value' => ($this->input->post('sim_details')) ? 1 : -1),
                    array('name' => 'osp.next_sim', 'value' => ($this->input->post('next_sim') ? $this->format_dates($this->input->post('next_sim')):'')),
					array('name' => 'osp.league_file_date', 'value' => ($this->input->post('league_file_date') ? $this->format_dates($this->input->post('league_file_date')):'')),
					array('name' => 'osp.league_date', 'value' => ($this->input->post('league_date') ? $this->format_dates($this->input->post('league_date')):'')),
                    array('name' => 'osp.league_event', 'value' => $this->input->post('league_event')),
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
                //$this->activity_model->log_activity($this->auth->user_id(), lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'ootp');

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

        $league_id = ((isset($settings['osp.league_id']))?$settings['osp.league_id']:100);

        if (!isset($this->leagues_model)) {
            $this->load->model('open_sports_toolkit/leagues_model');
        }
        $league = $this->leagues_model->find($league_id);
		$league_date = ((isset($league->current_date)) ? $league->current_date : date('Y-m-d'));
        if (!isset($this->leagues_events_model)) {
			$this->load->model('open_sports_toolkit/leagues_events_model');
		}
        Assets::add_css(css_path() . 'bootstrap-datepicker.css');
        Assets::add_js( js_path() . 'bootstrap-datepicker.js');
        Assets::add_js($this->load->view('custom/sim_details_js.php',null,true),'inline');
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

    //--------------------------------------------------------------------

    /**
	 *	LOAD SQL DATA TABLE(S)
	 */
	function load_sql() 
	{
		//$this->getURIData();
        $this->auth->restrict('League_Manager.SQL.Manage');

        if (!function_exists('loadSQLFiles')) 
		{
            $this->load->helper('sql');
        }
        if (!function_exists('return_bytes')) 
		{
            $this->load->helper('open_sports_toolkit/general');
        }
        $settings = $this->settings_lib->find_all();
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
            else if (isset($settings['osp.limit_load']) && $settings['osp.limit_load'] == 1)
            {
                $fileList = $required_tables;
            }
            else
            {
                $fileList = getSQLFileList($settings['osp.sql_path'],$latest_load);
            }
        }
        else if (isset($this->filename) && !empty($this->filename))
        {
            $fileList = array($this->filename);
        }
        if ($fileList !== false && is_array($fileList) && sizeof($fileList) > 0) {
			$mess = loadSQLFiles($settings['osp.sql_path'],$latest_load, $fileList);
			if (!is_array($mess) || (is_array($mess) && sizeof($mess) == 0))
			{
				if (is_array($mess))
				{
					$status = "Errors occured processing the SQL files:<br />";
                    foreach ($mess as $error) {
                        $status .= $mess."<br />";
                    }
				}
				else
				{
					$status = "error: ".$mess;
				}
                Template::set_message('$mess', 'error');
			}
			else
			{
				if (is_array($mess))
				{;
                    $files_loaded = $mess;
				}
				Template::set_message("All tables were updated sucessfully.", 'success');
				$this->sql_model->set_tables_loaded($files_loaded);
			}
		}

		$file_list = getSQLFileList($settings['osp.sql_path']);

		Assets::add_module_css('league_manager','style.css');
		//Assets::add_js(module_path('league_manager').'/views/custom/file_list_js.js','external');
		//Assets::add_js($this->load->view('custom/file_list_js',null,true),'inline');
		Template::set('file_list', $file_list);
		Template::set('missing_files', $this->sql_model->validate_loaded_files($file_list));
		Template::set('load_times', $this->sql_model->get_tables_loaded());
		Template::set('files_loaded', $files_loaded);
		Template::set('required_tables', $required_tables);
		Template::set('toolbar_title', lang('sql_settings_title'));
        Template::set_view('league_manager/custom/file_list');
        Template::render();
	}

    //--------------------------------------------------------------------

    /**
	 *	LOAD ALL SQL DATA
	 */
	public function load_all_sql() {

        $settings = $this->settings_lib->find_all();
        $latest_load = $this->sql_model->get_latest_load_time();
        if (!function_exists('loadSQLFiles'))
        {
            $this->load->helper('sql');
        }
        if (!function_exists('return_bytes'))
        {
            $this->load->helper('open_sports_toolkit/general');
        }
        if (isset($settings['osp.limit_load']) && $settings['osp.limit_load'] == 1) {
			$fileList = $this->sql_model->get_required_tables();
		} else {
			$fileList = getSQLFileList($settings['osp.sql_path'],$latest_load);
		}
		
		$files_loaded = array();
        $mess = loadSQLFiles($settings['osp.sql_path'],$latest_load, $fileList);
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
	 *	Required Table List.
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
        $settings = $this->settings_lib->find_all();
        Template::set('ootp_version', $settings['osp.source_version']);

        Template::set('toolbar_title', lang('lm_required_title'));
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
			$settings = $this->settings_lib->find_all();
			$mess = splitFiles($settings['osp.sql_path'],$filename, $delete, $settings['osp.max_sql_size']);
		}
		if ($mess != "OK") {
			Template::set_message("error:".$mess,'error');
		} else {
            Template::set_message("File successfully split",'success');
		}
        $this->load_sql();
	}
	
	//--------------------------------------------------------------------
	
	private function save_team_owners($teams_owners = false, $league_id = false)
	{
        $duped_ids = array();
        if (is_array($teams_owners) && count($teams_owners) > 0)
		{
            $used_ids = array();
            foreach ($teams_owners as $team)
			{
                $success = false;
                $owner_id = ($this->input->post($team->team_id)) ? $this->input->post($team->team_id) : -999;
				if (isset($owner_id) && $owner_id != -999)
				{
					if (!in_array($owner_id, $used_ids)) {
                        $success = $this->teams_owners_model->set_team_owner($team->team_id, $owner_id, $league_id);
                        array_push($used_ids,$owner_id);
                    }
                    else
                    {
                        if (!in_array($owner_id, $duped_ids)) {
                            array_push($duped_ids,$owner_id);
                        }
                        $success = true;
                    }
				}
				else
				{
                    $success = $this->teams_owners_model->delete_team_owner($team->team_id, $league_id);
				}
                if (!$success) {
                    return false;
                }
			}
		}
        return $duped_ids;
	}

    //--------------------------------------------------------------------

    private function create_team_owners($league_id = false, $use_usernames = true)
	{
		// GET LIST OF HUMAN MANAGER IDS FOR UNOWNED TEAMS
		$managers = $this->human_managers_model->get_unowned_team_managers($league_id);
		
		if (isset($managers) && is_array($managers) && count($managers))
		{
			foreach($managers as $manager)
			{
				if ($use_usernames)
				{
					$this->form_validation->set_rules($manager->human_manager_id.'_username', lang('bf_username'), 'required|trim|strip_tags|max_length[30]|unique['.$this->db->dbprefix.'users.username,'.$this->db->dbprefix.'users.id]|xss_clean');
				}
				$this->form_validation->set_rules($manager->human_manager_id.'_display_name', lang('bf__display_name'), 'trim|max_length[255]|strip_tags|xss_clean');
				$this->form_validation->set_rules($manager->human_manager_id.'_email', lang('bf_email'), 'trim|unique[bf_users.email]|valid_email|max_length[120]|xss_clean');
			}
		}
		if ($this->form_validation->run() === false)
		{
			return false;
		}
		$this->lang->load('users/users');
		foreach ($managers as $manager) 
		{
			$display_name = $this->input->post($manager->human_manager_id."_display_name");
			$username = ($use_usernames) ? $this->input->post($manager->human_manager_id."_username") : '';
			$email = $this->input->post($manager->human_manager_id."_email");
			$activation = $this->input->post($manager->human_manager_id."_activate");
			
			$data = $this->teams_owners_model->create_user($email, $activation, $display_name, $username);
			if ($data !== false && count($data) > 0)
			{
				$this->teams_owners_model->set_team_owner($manager->team_id, $data['user_id'], $league_id);
				$subject 		= str_replace('[SITE_TITLE]',$this->settings_lib->item('site.title'),lang('lm_site_account_created'));
				$email_mess 	= $this->load->view('league_manager/_emails/user_created', array('title'=>$this->settings_lib->item('site.title'),'link' => site_url(), 'password'=>$data['password'], 'login'=>($use_usernames ? $username: $email)), true);
			}
		}
		return true;
	}
    private function format_dates ( $date = '', $text = true )
    {
        if ( $date == '' )
        {
            return time();
        }

        return strtotime($date);

    }
}