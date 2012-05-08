<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class League_Manager extends Front_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		// Load our current settings
		if (!isset($this->leagues_model)) 
		{
			$this->load->model('ootp_web_toolkit/leagues_model');
		}
        $this->lang->load('manager');

	}

	//--------------------------------------------------------------------
	
	public function index()
	{
		$settings = $this->settings_lib->find_all();

		// acessing our userdata cookie
		$cookie = unserialize($this->input->cookie($this->config->item('sess_cookie_name')));
		$logged_in = isset ($cookie['logged_in']);
		unset ($cookie);
		
		$username = ''; // SITE username
		$user_name = ''; // FULL NAME
		if (!isset($this->auth)) 
		{
			// Auth setup
			$this->load->model('users/User_model', 'user_model');
			$this->load->library('users/auth');
		}
		if ($logged_in) 
		{
			$username = $this->current_user->username;
			$user_name = "(".$this->current_user->display_name.")";
		}
		Template::set('logged_in', $logged_in);
		Template::set('username', $username);
		Template::set('user_name', $user_name);
		
        Template::set('home_news_block',modules::run('news/get_articles',2));
		$data['articles'] = modules::run('news/get_article_list',5,2);
		Template::set('home_news_list',$this->load->view('news/news_list',$data, true));
		Template::set('sim_details',$this->load->view('league_manager/sim_details',$this->sim_details(), true));
		Template::set('tweets',$this->load->view('league_manager/tweets',$this->get_tweets(), true));
		Template::set('settings', $settings);

        Assets::add_css( Template::theme_url() .'css/bootstrap-responsive.min.css','screen');
        if (!isset($loggedIn) || !$loggedIn) 
		{
            if (!function_exists('form_open')) 
			{
                $this->load->helper('form');
            }
        }
		$this->load->helper('navigation/navigation');
        Template::set_view('league_manager/index');
        Template::set_theme('ootpol');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function get_tweets() {
		
		$settings = $this->settings_lib->find_all_by('module','ootp');
        
        if (isset($settings['ootp.twitter_string'])) 
		{
		
            $tweets = json_decode(@file_get_contents('http://twitter.com/statuses/user_timeline/' . $settings['ootp.twitter_string'] . '.json'));

            // If no number provided, just get 5
            $settings['ootp.tweet_count'] = 3;

            $tweets = is_array($tweets) ? array_slice($tweets, 0, $settings['ootp.tweet_count']) : array();

            $patterns = array(
                // Detect URL's
                '((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)' => '<a href="$0" target="_blank">$0</a>',
                // Detect Email
                '|[a-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,6}|i' => '<a href="mailto:$0">$0</a>',
                // Detect Twitter @usernames
                '|@([a-z0-9-_]+)|i' => '<a href="http://twitter.com/$1" target="_blank">$0</a>',
                // Detect Twitter #tags
                '|#([a-z0-9-_]+)|i' => '<a href="http://twitter.com/search?q=%23$1" target="_blank">$0</a>'
            );

            foreach ($tweets as &$tweet)
            {
                $tweet->id		= sprintf('%.0f', $tweet->id);
                $tweet->text	= str_replace($settings['ootp.twitter_string'] . ': ', '', $tweet->text);
                $tweet->text	= preg_replace(array_keys($patterns), $patterns, $tweet->text);
            }

            // Store the feed items
            return array(
                'username'	=> $settings['ootp.twitter_string'],
                'tweets'	=> $tweets
            );
        } 
		else 
		{
	        return false;
        }
	}
	
	//--------------------------------------------------------------------
	
	public function sim_details()
	{
		
		$settings = $this->settings_lib->find_all_by('module','ootp');

		if (!isset($this->leagues_events_model)) 
		{
			$this->load->model('ootp_web_toolkit/leagues_events_model');
		}
		$league = $this->leagues_model->find_by('league_id',$settings['ootp.league_id']);
		
		$league_file_date = false;
		$league_date = false;
		$next_sim = false;
		$league_event = '';
		
		if (isset($league) && $league !== false && count($league) &&
		(!isset($settings['ootp.sims_details']) || 
		(isset($settings['ootp.sims_details']) && empty($settings['ootp.sims_details'])) || 
		(isset($settings['ootp.sims_details']) && $settings['ootp.sims_details'] == -1))) 
		{
			// GET League File 
			if (isset($settings['ootp.league_file_path']) && 
				!empty($settings['ootp.league_file_path']) && 
				file_exists($settings['ootp.league_file_path'])) 
			{
				$league_file_date = filemtime($settings['ootp.league_file_path']);
			}
			// Determine Next Sim Date
			$day_count = 0;
			$update_day = date('N',$league_file_date);
			$sim_days = unserialize($settings['ootp.sims_occur_on']);
			if (sizeof($sim_days) == 1) 
			{
				$day_count = 7;
			} 
			else if (sizeof($sim_days) > 1) 
			{
				// remove current sim from list
				$idx = 0;
				foreach($sim_days as $day) 
				{
					if ($day == $update_day) 
					{
						$sim_days = array_splice($sim_days,$idx);
						break;
					}
					$idx++;
				}
				// Determine next sim D.O.w
				foreach($sim_days as $day) 
				{
					if ($update_day < $day) 
					{
						$day_count = $day - $update_day;
					} 
					else 
					{
						$day_count = ($update_day - 7) + $day;
					}
					break;
				}
			}
			$next_sim = $league_file_date + ((60*60*24)*$day_count);
			$league_date = strtotime($league->current_date);
			$evt = $this->leagues_events_model->get_events($league->league_id,$league->current_date,1);
            $league_event = ((isset($evt) && sizeof($evt) > 0)? $evt[0]['name']: "");
		}
		else 
		{
			$league_file_date = strtotime($settings['ootp.league_file_date']);
			$next_sim = strtotime($settings['ootp.next_sim']);
			$league_date = strtotime($settings['ootp.league_date']);
			$league_event = $settings['ootp.league_event'];
		}
		
		return array('league_file_date'=>(($league_file_date !== false) ? date('m/d',$league_file_date) : lang('sim_date_na')),
		'next_sim'=>(($next_sim !== false) ? date('m/d',$next_sim) : lang('sim_date_na'))
		,'league_date'=>(($league_date !== false) ? date('m/d/Y',$league_date) : lang('sim_date_na')),
        'league_event'=>$league_event);
	}
}

// End League_Manager controller class