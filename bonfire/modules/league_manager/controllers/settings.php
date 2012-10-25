<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();

        $this->auth->restrict('League_Manager.Settings.View');

        if (!class_exists('Activity_model'))
        {
            $this->load->model('activities/Activity_model', 'activity_model', true);
        }
		$this->lang->load('manager');

	}
	
	//--------------------------------------------------------------------

	public function _remap($method) 
	{ 
		if (method_exists($this, $method))
		{
			$this->$method();
		}
		else
		{
			show_404();
		}
	}
    //--------------------------------------------------------------------

    public function index()
    {
        if ($this->input->post('submit'))
        {
            $this->auth->restrict('League_Manager.Settings.Manage');
            if ($this->save_settings())
            {
                Template::set_message('League settings successfully saved.', 'success');
                redirect(SITE_AREA .'/settings/league_manager');
            } else
            {
                Template::set_message('There was an error saving your settings.', 'error');
            }
        }
        // Read our current settings
        $this->load->helper('open_sports_toolkit/general');
        $this->load->helper('open_sports_toolkit/datalist');
        $settings = $this->settings_lib->find_all();
        Template::set('settings', $settings);
		Assets::add_js($this->load->view('settings/settings_js.php',null,true),'inline');

        Template::set('toolbar_title', lang('lm_settings_title'));
        Template::set_view('league_manager/settings/index');
        Template::render();
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------

    private function save_settings()
    {

		$this->load->library('form_validation');

        $this->form_validation->set_rules('game_sport', lang('lm_settings_sport'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('game_source', lang('lm_settings_source'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('source_version', lang('lm_settings_source_version'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('league_id', lang('lm_settings_leagueid'), 'required|numeric|trim|xss_clean');
        $this->form_validation->set_rules('league_name', lang('lm_settings_lgname'), 'trim|xss_clean');
        $this->form_validation->set_rules('league_abbr', lang('lm_settings_lgabbr'), 'trim|xss_clean');
        $this->form_validation->set_rules('league_icon', lang('lm_settings_lgicon'), 'trim|xss_clean');
        $this->form_validation->set_rules('league_txtcolor', lang('lm_settings_txtcolor'), 'trim|xss_clean');
        $this->form_validation->set_rules('league_bgcolor', lang('lm_settings_bgcolor'), 'trim|xss_clean');

        $this->form_validation->set_rules('league_file_path', lang('lm_settings_lgdfile'), 'trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('asset_path', lang('lm_settings_assetpath'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('asset_url', lang('lm_settings_asseturl'), 'required|trim|xss_clean');

        $this->form_validation->set_rules('header_img', lang('home_settings_header'), 'trim|xss_clean');
        $this->form_validation->set_rules('twitter_string', lang('home_settings_twitter'), 'trim|xss_clean');
        $this->form_validation->set_rules('tweet_count', lang('home_settings_tweets'), 'trim|xss_clean');

        $this->form_validation->set_rules('sql_path', lang('sql_settings_mysqlpath'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('max_sql_size', lang('sql_settings_max'), 'numeric|xss_clean');
        $this->form_validation->set_rules('auto_split', lang('sql_settings_autosplit'), 'numeric|xss_clean');
        $this->form_validation->set_rules('use_db_prefix', lang('sql_use_db_prefix'), 'numeric|xss_clean');
        $this->form_validation->set_rules('limit_load', lang('sql_settings_auto_load'), 'required|numeric|xss_clean');

        if ($this->form_validation->run() === false)
        {
            return false;
        }

		$data = array(
            array('name' => 'ootp.game_sport', 'value' => $this->input->post('game_sport')),
            array('name' => 'ootp.game_source', 'value' => $this->input->post('game_source')),
            array('name' => 'ootp.source_version', 'value' => $this->input->post('source_version')),
            array('name' => 'ootp.league_id', 'value' => $this->input->post('league_id')),
            array('name' => 'ootp.use_ootp_details', 'value' => ($this->input->post('use_ootp_details')) ? 1 : -1),
            array('name' => 'ootp.league_name', 'value' => $this->input->post('league_name')),
            array('name' => 'ootp.league_abbr', 'value' => $this->input->post('league_abbr')),
            array('name' => 'ootp.league_icon', 'value' => $this->input->post('league_icon')),
            array('name' => 'ootp.league_txtcolor', 'value' => $this->input->post('league_txtcolor')),
            array('name' => 'ootp.league_bgcolor', 'value' => $this->input->post('league_bgcolor')),
            array('name' => 'ootp.league_file_path', 'value' => $this->input->post('league_file_path')),
            array('name' => 'ootp.asset_path', 'value' => $this->input->post('asset_path')),
            array('name' => 'ootp.asset_url', 'value' => $this->input->post('asset_url')),
            array('name' => 'ootp.header_img', 'value' => $this->input->post('header_img')),
            array('name' => 'ootp.twitter_string', 'value' => $this->input->post('twitter_string')),
            array('name' => 'ootp.tweet_count', 'value' => $this->input->post('tweet_count')),
            array('name' => 'ootp.sql_path', 'value' => $this->input->post('sql_path')),
            array('name' => 'ootp.auto_split', 'value' => ($this->input->post('auto_split')?1:0)),
            array('name' => 'ootp.use_db_prefix', 'value' => ($this->input->post('use_db_prefix')?1:0)),
            array('name' => 'ootp.max_sql_size', 'value' => $this->input->post('max_sql_size')),
            array('name' => 'ootp.limit_load', 'value' => $this->input->post('limit_load')),

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
        $this->activity_model->log_activity($this->auth->user_id(), lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'core');

        // save the settings to the DB
        $updated = $this->settings_model->update_batch($data, 'name');

        return $updated;

	}
	
}

// End User Admin class