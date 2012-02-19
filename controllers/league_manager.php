<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class League_Manager extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		// Load our current settings
		Template::set(read_config('league_manager'));
		if (!isset($this->leagues_model)) {
			$this->load->model('leagues_model');
		}
	}

	//--------------------------------------------------------------------
	
	public function index()
	{
		Template::set_block('home_news_list','league_manager/empty',modules::run('news/get_articles',0,2));
		Template::set_block('sim_details','league_manager/sim_details',$this->sim_details());
		Template::set_block('tweets','league_manager/tweets',$this->get_tweets());
		
		Template::render();
	}
	
	public function get_tweets() {
		
		$mng_configs = read_config('league_manager');

        if (isset($mng_configs['twitter_string'])) {
		
            $tweets = json_decode(@file_get_contents('http://twitter.com/statuses/user_timeline/' . $mng_configs['twitter_string'] . '.json'));

            // If no number provided, just get 5
            empty($mng_configs['tweet_count']) AND $mng_configs['tweet_count'] = 5;

            $tweets = is_array($tweets) ? array_slice($tweets, 0, $mng_configs['tweet_count']) : array();

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
                $tweet->text	= str_replace($mng_configs['twitter_string'] . ': ', '', $tweet->text);
                $tweet->text	= preg_replace(array_keys($patterns), $patterns, $tweet->text);
            }

            // Store the feed items
            return array(
                'username'	=> $mng_configs['twitter_string'],
                'tweets'	=> $tweets
            );
        } else {
	        return false;
        }
	}
	
	//--------------------------------------------------------------------
	
	public function sim_details()
	{
		
		$cfgs_lg = read_config('league_manager');
		$cfgs_sim = read_config('sims');
		
		if (!isset($this->leagues_events_model)) {
			$this->load->model('leagues_events_model');
		}
		$league = $this->leagues_model->find_by('league_id',$cfgs_lg['league_id']);
		
		$league_file_date = -1;
		$league_date = -1;
		$next_sim = -1;
		$league_event = '';
		
		if (isset($cfgs_sim) && is_array($cfgs_sim) && $cfgs_sim['sims_details'] == -1) {
			// GET League File 
			if (isset($cfgs_lg['league_file_path']) && 
				!empty($cfgs_lg['league_file_path']) && 
				file_exists($cfgs_lg['league_file_path'])) {
				$league_file_date = filemtime($cfgs_lg['league_file_path']);
			}
			// Determine Next Sim Date
			$day_count = 0;
			$update_day = date('N',$league_file_date);
			$sim_days = unserlialize($cfgs_sim['sims_occur_on']);
			if (sizeof($sim_days) == 1) {
				$day_count = 7;
			} else if (sizeof($sim_days) > 1) {
				// remove current sim from list
				$idx = 0;
				foreach($sim_days as $day) {
					if ($day == $update_day) {
						$sim_days = array_splice($sim_days,$idx);
						break;
					}
					$idx++;
				}
				// Determine next sim D.O.w
				foreach($sim_days as $day) {
					if ($update_day < $day) {
						$day_count = $day - $update_day;
					} else {
						$day_count = ($update_day - 7) + $day;
					}
					break;
				}
			}
			$next_sim = $league_file_date + ((60*60*24)*$day_count);
			$league_date = strtotime($league->current_date);
			$evt = $this->leagues_events_model->get_events($league->league_id,$league->current_date,1);
            $league_event = ((isset($evt) && isset($evt->name))? $evt->name: "");
		} else {
			$league_file_date = $cfgs_sim['league_file_date'];
			$next_sim = $cfgs_sim['next_sim'];
			$league_date = $cfgs_sim['league_date'];
			$league_event = $cfgs_sim['league_event'];
		}
		
		return array('league_file_date'=>(($league_file_date != -1) ? date('m/d',$league_file_date) : lang('sim_date_na')),
		'next_sim'=>(($next_sim != -1) ? date('m/d',$next_sim) : lang('sim_date_na'))
		,'league_date'=>(($league_date != -1) ? date('m/d\Y',$league_date) : lang('sim_date_na')),
        'league_event'=>$league_event);
	}
}

// End User League_Manager class