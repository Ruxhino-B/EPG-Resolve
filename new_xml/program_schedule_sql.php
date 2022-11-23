<?php

	date_default_timezone_set('Europe/Rome');
	$mychannels = simplexml_load_file('allchannels.xml');
	$channelID = simplexml_load_file('/root/epg/channelID.xml');
	
	$myfile = fopen("Program.dat", "w") or die("Unable to open file!");
	
	for($i=0; $i<count($mychannels->children()); $i++)
		for($k=0; $k<count($channelID->children()); $k++){

			if( strcmp($mychannels->channel[$i]['name'], $channelID->channel[$k]['name'])== 0){

				for ($j=0; $j<count($mychannels->channel[$i]); $j++){
					$date=$mychannels->channel[$i]->event[$j]['start_time'];
					$d=strtotime($date);
					$date = date("YmdHis",$d);
					// $date->sub(new DateInterval('P0Y0M0DT2H0M0S'));
					$txt = $channelID->channel[$k]['id']."-".$date."|".$mychannels->channel[$i]->event[$j]->short_event_descriptor['name']."|||".$mychannels->channel[$i]->event[$j]->short_event_descriptor." ".$mychannels->channel[$i]->event[$j]->extended_event_descriptor->text."||||||||||||||||||||||||||||||||||||||\n";
					$txt = preg_replace('/\s+/', ' ', $txt)."\n";
					fwrite($myfile, $txt);
				}
			
				break;
			}
		}

	fclose($myfile);
	$myfile = fopen("Schedule.dat", "w") or die("Unable to open file!");
        for($i=0; $i<count($mychannels->children()); $i++)
		for($k=0; $k<count($channelID->children()); $k++){
                        if( strcmp($mychannels->channel[$i]['name'], $channelID->channel[$k]['name'])== 0){

				for ($j=0; $j<count($mychannels->channel[$i]); $j++){
					$duration_min=$mychannels->channel[$i]->event[$j]['duration']/60;
					$date=$mychannels->channel[$i]->event[$j]['start_time'];
					$d=strtotime($date);
                                        $date = date("YmdHis",$d);
					$date1=date("Ymd",$d);
					$date2=date("Hi",$d);
                                        // $date->sub(new DateInterval('P0Y0M0DT2H0M0S'));
					$hr = floor($duration_min / 60);
					if($hr < 10 ){
						 $hr = "0".$hr;
					}
					$min = $duration_min % 60;
					if($min < 10){
						$min = "0".$min;
					}
                        		$txt = 
$channelID->channel[$k]['id']."|".$channelID->channel[$k]['id']."-".$date."|".$date1."|".$date2."|".$hr.$min."|||||||||||||||||||||\n";
                        		$txt = preg_replace('/\s+/', ' ', $txt)."\n";
					fwrite($myfile, $txt);
                		}
				break;
			}
		}
        fclose($myfile); 
/*
$link = mysql_connect('localhost:3306','root', '', false, 128);

if (!$link) {
        die('Cannot Connect to Database');
}
$sql = "LOAD DATA LOCAL INFILE '/root/epg/Program.dat'
        INTO TABLE epg.program
        FIELDS TERMINATED BY '|'
        LINES TERMINATED BY '\n'
        (EventID, ShortDesc, ext, ext1, LongDesc, ext2, ext3, ext4, ext5, ext6, ext7, ext8, ext9, ext10, ext11, ext12, ext13, ext14, ext15, ext16, ext17, ext18, ext19, ext20, ext21, ext22, ext23, ext24, ext25, ext26, ext27, ext28, ext29, ext30, ext31, ext32, ext33, ext34, ext35, ext36, ext37, ext38, ext39)";
mysql_query($sql, $link) or die(mysql_error());

$sql = "LOAD DATA LOCAL INFILE '/root/epg/Schedule.dat'
        INTO TABLE epg.schedule
        FIELDS TERMINATED BY '|'
        LINES TERMINATED BY '\n'
        (ChID, EventID, Date, StartTime, Duration, ext, ext1, ext2, ext3, ext4, ext5, ext6, ext7, ext8, ext9, ext10, ext11, ext12, ext13, ext14, ext15, ext16, ext17, ext18, ext19, ext20)";
mysql_query($sql, $link) or die(mysql_error());

mysql_close($link);
*/

?>
