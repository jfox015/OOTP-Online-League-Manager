<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Data List Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Jeff Fox
 * @description	Various helpers for handling data lists
 * @version		1.0.1
 */
// ------------------------------------------------------------------------
/**
 * loadSimpleDataList.
 * Provides a simple method for retrieving the index and values of the 
 * many basic data lists used by the site.
 * @param	$list	The list id. See <b>usage for tips</b>
 * @param	$column	(OPTIONAL) Specifiy a column (other than id) to sort the list on
 * @param	$order	(OPTIONAL) Specify a SQL sort order other than ASC
 * @return			A key/value pair array of data items
 * @since			1.0
 */
if ( ! function_exists('loadSimpleDataList')) {
	function loadSimpleDataList($list,$column = '', $order = "ASC", $label = '') {
		$ci =& get_instance();
		$table = '';
		switch ($list) {
			case 'username':
				$table = USER_CORE_TABLE;
				break;
			case 'StateName':
				$table = 'states_us';
				break;	
			case 'cntryName':
				$table = 'countries';
				break;	
			case 'userLevel':
				$table = 'list_user_levels';
				break;
			case 'accessLevel':
				$table = 'list_access_levels';
				break;
			case 'userType':
				$table = 'list_user_types';
				break;
			case 'availableOOTPLeagues':
				$table = 'leagues';
				$list = "name";
				$label = "OOTP League";
				$identifier = 'league_id';
				break;
			case 'availableOOTPLeaguesAbbr':
				$table = 'leagues';
				$list = "abbr";
				$label = "OOTP League";
				$identifier = 'league_id';
				break;
			case 'accessType':
				$table = 'fantasy_leagues_access';
				break;
			case 'leagueStatus':
				$table = 'fantasy_leagues_status';
				break;
			case 'leagueType':
				$table = 'fantasy_leagues_types';
				break;	
			case 'bugCategory':
				$table = 'admin_list_bug_categories';
				break;
			case 'bugStatus':
				$table = 'admin_list_status';
				break;
			case 'priority':
				$table = 'admin_list_priorities';
				break;
			case 'severity':
				$table = 'admin_list_severities';
				break;
			case 'os':
				$table = 'qa_list_os';
				break;
			case 'browser':
				$table = 'qa_list_browsers';
				break;
			case 'project':
				$table = 'admin_projects';
				$list = 'name';
				break;
			case 'newsType':
				$table = 'fantasy_news_type';
				break;
			case 'tradeStatus':
				$table = 'fantasy_teams_trades_status';
				break;
			case 'tradeApprovalType':
				$table = 'fantasy_teams_trades_approvals';
				break;	
			case 'activationType':
				$table = 'users_activation_types';
				break;	
			default:
				break;
		} // END switch
		
		// ADD Default FIRST ITEM
		if (empty($identifier)) { $identifier = 'id'; }
		if (empty($column)) { $column = $identifier; }
		if (empty($label)) { $label = $list; }
		
		$datalist = array(''=>'Choose '.$label);
	
		// ADD LIST RESULTS
		if ($ci->db->table_exists($table) && !empty($table)) {
            $query = $ci->db->query('SELECT '.$identifier.', '.$list.' FROM '.$table.' ORDER BY '.$column .' '.$order);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $datalist = $datalist + array($row->$identifier=>$row->$list);
                } // END foreach
            } // END if
            $query->free_result();
		} else {
            redirect('media/nodb.php');
        }
		return $datalist;
	} // END function
} // END if

// ------------------------------------------------------------------------

if ( ! function_exists('getInjuryName')) {
	function getInjuryName($injuryId = false) {
	  	if ($injuryId === false) {
	        return false;
	    }
	    $ci =& get_instance();
	  	$injuryName = '';
		$query = $ci->db->query('SELECT injury_text FROM list_injuries WHERE id = '.$injuryId);
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$injuryName = $row->injury_text;
		} // END if
		$query->free_result();
		
	    return $injuryName;
	} // END function
} // END if
// ------------------------------------------------------------------------

if ( ! function_exists('getDaysInBetween')) {
	function getDaysInBetween($start, $end) {
		// Vars
		$day = 86400; // Day in seconds
		$format = 'Y-m-d'; // Output format (see PHP date funciton)
		$sTime = strtotime($start); // Start as time
		$eTime = strtotime($end); // End as time
		$numDays = round(($eTime - $sTime) / $day) + 1;
		$days = array();
		
		// Get days
		for ($d = 0; $d < $numDays; $d++) {
			$days[] = date($format, ($sTime + ($d * $day)));
		}
		
		// Return days
		return $days;
	}
} 
// ------------------------------------------------------------------------

if ( ! function_exists('getMonths')) {
	function getMonths() {
		return array('0'=>'Select Month',
			  '1'=>'January',
			  '2'=>'February',
			  '3'=>'March',
			  '4'=>'April',
			  '5'=>'May',
			  '6'=>'June',
			  '7'=>'July',
			  '8'=>'August',
			  '9'=>'September',
			  '10'=>'October',
			  '11'=>'Novemeber',
			  '12'=>'December');
	} // END function
} // END if

// ------------------------------------------------------------------------

if ( ! function_exists('getDays')) {
	function getDays() {
		$days = array('0'=>'Select Day');
		for ($i = 1; $i < 32; $i++) {
			$days = $days + array($i=>$i);
		} 
		return $days;
	} // END function
} // END if


// ------------------------------------------------------------------------

if ( ! function_exists('getYears')) {
	function getYears($startYear = false,$endYear = false) {
		if ($startYear === false)
			$startYear = date('Y');
		if ($endYear === false)
			$endYear = $startYear - 100;
		$years = array('0'=>'Select Year');
		for ($j = $startYear ; $j >= $endYear; $j--) {
			$years = $years + array($j=>$j);
		} 
		return $years;
	} // END function
} // END if

// ------------------------------------------------------------------------

if ( ! function_exists('getHours')) {
	function getHours() {
		$retArray = array();
		for ($c=1;$c<=12;$c++) {
			$x = str_pad( strval($c), 2, '0', STR_PAD_LEFT);
			$retArray = $retArray + array($x=>$x);
		}
		return $retArray;
	} // END function
} // END if

// ------------------------------------------------------------------------

if ( ! function_exists('getMinutes')) {
	function getMinutes($limit = false) {
		$retArray = array();
		if ($limit) {
			$retArray = $retArray + array('00'=>'00');
			$retArray = $retArray + array('15'=>'15');
			$retArray = $retArray + array('30'=>'30');
			$retArray = $retArray + array('45'=>'45');
		} else {
			for ($c=0;$c<=60;$c++) {
				$x = str_pad( strval($c), 2, '0', STR_PAD_LEFT);
				$retArray = $retArray + array($x=>$x);
			}
		}
		return $retArray;
	} // END function
} // END if

// ------------------------------------------------------------------------

if ( ! function_exists('getAMPM')) {
	function getAMPM() {
		return array('AM'=>'AM','PM'=>'PM');
	} // END function
} // END if

// ------------------------------------------------------------------------
/**
 * 	LOAD TIMEZONES.
 * 	Loads a list of timezone identifers for selection by the user.
 * 	
 * 	@param	$selectBox	(Boolean)	TRUE if the list will populate a select box, FALSE otherwise
 * 	@return				array		Array of timezones
 * 	@since				1.0.6
 */
if ( ! function_exists('loadTimezones')) {
	function loadTimezones($selectBox = true) {
		
		if ($selectBox) {
			$result = array(' '=>'Select Timezone');
		} else {
			$result = array();
		}
		$continent = '';
	    $timezone_identifiers = DateTimeZone::listIdentifiers();
	    foreach( $timezone_identifiers as $value ){
	        if ( preg_match( '/^(America|Antartica|Arctic|Asia|Atlantic|Europe|Indian|Pacific)\//', $value ) ){
	            $ex=explode("/",$value);//obtain continent,city   
	            //if ($continent!=$ex[0]){
	            //    $result = $result + array('X'=>$ex[0]);
	           // }
	            $city=$ex[1];
	            $continent=$ex[0];
	            $result = $result + array($value=>$continent."/".$city);             
	        }
	    }
		return $result;		
	}
}
// ------------------------------------------------------------------------
if ( ! function_exists('loadOOTPVersions')) {
	function loadOOTPVersions($selectBox = true) {

		return array("10"=>"OOTP 10",
					 "11"=>"OOTP 11",
					 "12"=>"OOTP 12",
					 "13"=>"OOTP 13"
					 //,"14"=>OOTP 14" -- Future support
					 //,"15"=>OOTP 15" -- Future support
					 //,"16"=>OOTP 16" -- Future support
					 );
	}
}
// ------------------------------------------------------------------------
if ( ! function_exists('getOOTPGameVersion')) {
	function getOOTPGameVersion($version = -1) {
		
		$outVer = -1;
		$versions = loadOOTPVersions();
		foreach ($versions as $ver => $label) {
			if ($ver == $version) {
				$outVer = $label;
				break;
			}
		}
		return $outVer;
	}
}
/* End of file dataList_helper.php */
/* Location: ./system/helpers/dataList_helper.php */