<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Settings.View');
		//$this->auth->restrict('OOTP.Manager.View');
		
		$this->lang->load('manager');

	}
	
	//--------------------------------------------------------------------

	public function _remap($method) 
	{ 
		if (method_exists($this, $method))
		{
			$this->$method();
		}
	}
	
	//--------------------------------------------------------------------
	public function index()
	{
		$this->load->helper('datalist');
		$this->load->library('form_validation');

		if ($this->input->post('submit'))
		{
            $this->form_validation->set_rules('ootp_version', lang('dbrd_settings_gamever'), 'required|trim|xss_clean');
            $this->form_validation->set_rules('league_id', lang('dbrd_settings_leagueid'), 'required|number|trim|xss_clean');
			$this->form_validation->set_rules('league_name', lang('dbrd_settings_lgname'), 'trim|xss_clean');
            $this->form_validation->set_rules('league_abbr', lang('dbrd_settings_lgabbr'), 'trim|xss_clean');
			$this->form_validation->set_rules('league_icon', lang('dbrd_settings_lgicon'), 'trim|xss_clean');
			$this->form_validation->set_rules('league_txtcolor', lang('dbrd_settings_txtcolor'), 'trim|xss_clean');
			$this->form_validation->set_rules('league_bgcolor', lang('dbrd_settings_bgcolor'), 'trim|xss_clean');
			
			$this->form_validation->set_rules('league_file_path', lang('dbrd_settings_lgdfile'), 'number|xss_clean');
			$this->form_validation->set_rules('asset_path', lang('dbrd_settings_assetpath'), 'required|trim|xss_clean');
			$this->form_validation->set_rules('asset_url', lang('dbrd_settings_asseturl'), 'required|trim|xss_clean');
			
			$this->form_validation->set_rules('header_img', lang('home_settings_header'), 'trim|xss_clean');
            $this->form_validation->set_rules('twitter_string', lang('home_settings_twitter'), 'trim|xss_clean');
            $this->form_validation->set_rules('tweet_count', lang('home_settings_tweets'), 'trim|xss_clean');
           
			$this->form_validation->set_rules('sql_path', lang('sql_settings_mysqlpath'), 'required|trim|xss_clean');
            $this->form_validation->set_rules('max_sql_size', lang('sql_settings_max'), 'number|xss_clean');
            $this->form_validation->set_rules('auto_split', lang('sql_settings_autosplit'), 'number|xss_clean');
            $this->form_validation->set_rules('limit_load', lang('sql_settings_auto_load'), 'required|number|xss_clean');

			if ($this->form_validation->run() !== FALSE)
			{
				$data = array(
					'ootp_version'			=> $this->input->post('ootp_version'),
					'league_id'				=> $this->input->post('league_id'),
					'use_ootp_details'		=> ($this->input->post('use_ootp_details')) ? 1 : -1,
					'league_name'			=> $this->input->post('league_name'),
					'league_abbr'			=> $this->input->post('league_abbr'),
					'league_icon'			=> $this->input->post('league_icon'),
					'league_txtcolor'		=> $this->input->post('league_txtcolor'),
					'league_bgcolor'		=> $this->input->post('league_bgcolor'),
					
					'league_file_path'		=> $this->input->post('league_file_path'),
					'asset_path'			=> $this->input->post('asset_path'),
					'asset_url'				=> $this->input->post('asset_url'),
					
					'header_img'			=> $this->input->post('header_img'),
					'twitter_string'		=> $this->input->post('twitter_string'),
					'tweet_count'			=> $this->input->post('tweet_count'),
					
					'sql_path'				=> $this->input->post('sql_path'),
					'auto_split'			=> $this->input->post('auto_split'),
					'max_sql_size'			=> $this->input->post('max_sql_size'),
					'limit_load'			=> $this->input->post('limit_load'),
					
				);

				if (write_config('league_manager', $data)) {
					// Success, so reload the page, so they can see their settings
					Template::set_message('League settings successfully saved.', 'success');
					redirect(SITE_AREA .'/settings/league_manager');
				}
				else
				{
					Template::set_message('There was an error saving the file: config/league_manager.', 'error');
				}
			}
		}

		// Load our current settings
		Template::set(read_config('league_manager'));

		Template::set('toolbar_title', lang('dbrd_settings_title'));
        Template::set_view('league_manager/settings/index');
		Template::render();
	}
	
}

// End User Admin class