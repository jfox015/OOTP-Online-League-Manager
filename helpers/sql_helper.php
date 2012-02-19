<?php

function getSQLFileList($sqlLoadPath, $loadTime = false, $timeout = 120, $logPath = false, $max_file_size = false) {
	
	$fileList = array();
	if ($loadTime == false) $loadTime = '1970-01-01';
	if ($dir = opendir($sqlLoadPath)) {
   		$loadCnt = 0;
		$now=time();    
   		while (false !== ($file = readdir($dir)))	{
	
			$ex = explode(".",$file);
      		$last = count($ex)-1;
      		$fileTime=filemtime($sqlLoadPath."/".$file);
      		$fileSize=filesize($sqlLoadPath."/".$file);
      
      		if (($fileTime<$loadTime)||(($max_file_size!=false)&&($fileSize>$max_file_size))) {continue;}

      		if (($ex[$last]=="sql") && ($file!=".") && ($file!="..")) {
         		$fileList[$loadCnt]=$file;
	 			$loadCnt++;
       		}
		}
	}
	return $fileList;
}

function loadSQLFiles($sqlLoadPath, $loadTime, $fileList = false, $timeout = 120, $logPath = false, $max_file_size = 500000000) {
	// Load SQL Files #####
	$errors = "";
	include($sqlLoadPath."/ootpfl_db.php");
	$loadCnt=sizeof($fileList);
	$filesLoaded = array();
	if ($logPath === false) $logPath = $sqlLoadPath;
	if (file_exists($logPath."/sqlloadlog.txt")) {
		unlink($logPath."/sqlloadlog.txt");
	}
	if ($dir = opendir($sqlLoadPath)) {
		$now=time();    
		//echo("File load count = ".$loadCnt."<br />");
		if ($loadCnt>0) {
			$ci =& get_instance();
			asort($fileList);
			foreach ($fileList as $key => $file) {
				$ex = explode(".",$file);
				set_time_limit($timeout);
				$fnow=time();
				
				$f = fopen($logPath."/sqlloadlog.txt","a");
				fwrite($f,"LOADING: ".$file." ... ");
				fclose($f);
				//echo("LOADING: ".$file);
				/*$pFile=fopen("./sqlprocess.txt","w");
				
				fclose($pFile);*/
				$tableName=$ex[0];
				$query="CREATE TABLE IF NOT EXISTS `$tableName';";
				mysql_query($query,$db);
				
				## Import data
				$file=$sqlLoadPath."/".$file;
				//echo("File to load = ".$file."<br />");
				$fr = fopen($file,"r");
				//echo("File resource = ".$fr."<br />");
				$errCnt=0;
				if (isset($errors)) { 
					unset($errors);
					unset($queries);
				}
				while (!feof($fr)) {
					$query=fgets($fr);
					if ($query=="") {continue;}
					$query=str_replace(", , );",",1,1);",$query);
					//$query=preg_replace("/([\xC2\xC3])([\x80-\xBF])/e","chr(ord('\\1')<<6&0xC0|ord('\\2')&0x3F)",$query);
					$query=str_replace(", ,",",'',",$query);
					$query=str_replace("#IND",0,$query);
					if (($tableName=='players_career_batting_stats')||($tableName=='players_career_pitching_stats')) {
						$query=str_replace("insert into","insert ignore into",$query);
					}
					$result=mysql_query($query,$db);
					$err=mysql_error($db);
					if (($err!="") && ($query!="")) {
					$errors[$errCnt]=$err;
					$queries[$errCnt]=$query;
					$errCnt++;
					if (!isset($_SESSION['sqlloaderr'])) {$_SESSION['sqlloaderr']=1;}}
					if ((substr_count($query,"CREATE ")>0)&&(($tableName=='players_career_batting_stats')||($tableName=='players_career_pitching_stats'))) {
						$query="ALTER TABLE $tableName ADD PRIMARY KEY (player_id,year,team_id,league_id,split_id);";
						$result=mysql_query($query,$db);
					}
				}
				fclose($fr);
				$f = fopen($logPath."/sqlloadlog.txt","a");
				$fend=time();
				 if ($errCnt==0) {
					fwrite($f,"SUCCESSFUL! Processing took ".($fend-$fnow)." seconds\n");
					$filesLoaded[$file]=1;
				}
				fclose($f);
				if ($errCnt>0) break;
			}
 		}
     	$end=time();
    } else {
		$errors="ERROR: Unable to read directory ".$sqlLoadPath;
	}
	if (empty($errors)) $errors = $filesLoaded; else  $errors = $errors;
	return $errors;
}
function splitFiles($sqlLoadPath,$filename = false, $delete = false, $max_file_size = false, $timeout = 120 ) {
	
	$errors = '';
	//echo("File name = ".$sqlLoadPath."/".$filename."<br />");
	if ($filename!="ALL") {
		$file=$sqlLoadPath."/".$filename;
	
		if (file_exists($file) && $delete == 1) {
			unlink($file);
			return "OK";
		} // END if
		if ($filename=='DELSPLITS') {
			if ($dir = opendir($sqlLoadPath)) {
				while (false !== ($file = readdir($dir))) {
					$ex = explode(".",$file);
					$last = count($ex)-1;
					$filename=$sqlLoadPath."/".$file;
					$isSplit=substr_count($file,".mysql_");
					
					#echo "$file :: $filename :: $isSplit<br/>\n";
					if (($ex[$last]=="sql") && ($file!=".") && ($file!="..") && ($isSplit>0)) {unlink($filename);}
				} // END while
			} // END if
			return "OK";
		} // END if
	
		if (($timeout<30) || ($timeout=="")) {$timeout=120;} // END if
	
		if (file_exists($file)) {
			$e=explode(".",$filename);
			$last = count($e)-1;
	
			$f = fopen($file,"r");
			$cnt=0;
			while (!feof($f)) {
				$line=fgets($f);
				if ($line=="") {continue;} // END if
				$line=str_replace(", , );",",1,1);",$line); 
				//$line=preg_replace("/([\xC2\xC3])([\x80-\xBF])/e","chr(ord('\\1')<<6&0xC0|ord('\\2')&0x3F)",$line);
				$line=str_replace(", ,",",'',",$line);
				$line=str_replace("#IND","0",$line);
				$queries[$cnt]=$line;
				$cnt++;
			} // END while
			fclose($f);
		} else {
			return "$file not found";
		} // END if
	
		## Loop through queries, split to 5 files
		$nlines=ceil($cnt/5);
		$fcnt=1;
		for ($i=0;$i<$cnt;$i++) {
			if ((($i%$nlines)==0)||($i==0)) {
				if ($i!=0) {fclose($f);} // END if
				$newFileNm=$e[0];
				for ($j=1;$j<count($e);$j++) {
					$newFileNm.=".".$e[$j];
					if ($j==($last-1)) {$newFileNm.="_".$fcnt;} // END if
				} // END for
				$newFile=$sqlLoadPath."/".$newFileNm;
				$fcnt++;
		
				#echo $newFile."<br/>\n";
				$f=fopen($newFile,"w");
			} // END if
			fwrite($f,$queries[$i]);
		} // END for
		fclose($f);
	} else {
		if (($dir = opendir($sqlLoadPath))&&($max_file_size!=false)) {
			while (false !== ($file = readdir($dir))) {
				$ex = explode(".",$file);
				$last = count($ex)-1;
				$filename=$sqlLoadPath."/".$file;
				$fileTime=filemtime($filename);
				$fileSize=filesize($filename);
				
				#echo "$file :: $fileTime : $loadTime<br/>\n";
				if ($fileSize<$max_file_size) {continue;} // END if
	
				$numSplits=ceil($fileSize/$max_file_size)+1;
				unset($queries);
				if (($ex[$last]=="sql") && ($file!=".") && ($file!="..")) {
					$e=explode(".",$file);
					$last = count($e)-1;
	
					#echo "Splitting $filename <br/>\n";
					
					$f = fopen($filename,"r");
					$cnt=0;
					while (!feof($f)) {
						$line=fgets($f);
						if ($line=="") {continue;}
						$line=str_replace(", , );",",1,1);",$line); 
						//$line=preg_replace("/([\xC2\xC3])([\x80-\xBF])/e","chr(ord('\\1')<<6&0xC0|ord('\\2')&0x3F)",$line);
						$line=str_replace(", ,",",'',",$line);
						$line=str_replace("#IND","0",$line);
						$queries[$cnt]=$line;
						$cnt++;
					} // END whille
					fclose($f);
				} // END if
				## Loop through queries, split to 5 files
				$nlines=ceil($cnt/$numSplits);
				$fcnt=1;
				for ($i=0;$i<$cnt;$i++) {
					if ((($i%$nlines)==0)||($i==0)) {
						if ($i!=0) {fclose($f);} // END if
						$newFileNm=$e[0];
						for ($j=1;$j<count($e);$j++) {
							$newFileNm.=".".$e[$j];
							if ($j==($last-1)) {$newFileNm.="_".$fcnt;} // END if
						} // END for
						$newFile=$sqlLoadPath."/".$newFileNm;
						$fcnt++;
		
						#echo $newFile."<br/>\n";
						$f=fopen($newFile,"w");
					} // END if
					fwrite($f,$queries[$i]);
				} // END for
				fclose($f);
			} // END while
		} // END if
	} // END if
	if (empty($errors)) $errors = "OK"; else  $errors = $errors;
	return $errors;
} // END function
?>
