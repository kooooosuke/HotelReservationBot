<?php
define('DIR' , '/var/www/html/bot/');

require_once('/var/www/html/bot/twitteroauth/twitteroauth.php');
require_once('/var/www/html/bot/config.php');

date_default_timezone_set('Asia/Tokyo');

$summer_date = array(
	'year'  => 2015,
	'month' => 8,
	'day'   => 13,
);

$winter_date = array(
	'year'  => 2014,
	'month' => 12,
	'day'   => 27,
);

$comike_dates = array($summer_date, $winter_date);

$urls = array();
$grep_words = array();
foreach($comike_dates as $key => $comike_date){
	for($i=0; $i<3; $i++){
		$date_str = $comike_date['year'].$comike_date['month'].($comike_date['day']+$i); 
		$urls += array('was_r_'.$date_str => 'http://hotel.travel.rakuten.co.jp/hotelinfo/plan/1177?f_hi1='.($comike_date['day']+$i).'\&f_tuki1='.$comike_date['month'].'\&f_nen1='.$comike_date['year'].'\&f_hi2='.($comike_date['day']+$i+1).'\&f_tuki2='.$comike_date['month'].'\&f_nen2='.$comike_date['year'].'\&f_heya_su=1\&f_otona_su=1');
		$urls += array('sun_r_'.$date_str => 'http://hotel.travel.rakuten.co.jp/hotelinfo/plan/76373?f_hi1='.($comike_date['day']+$i).'\&f_tuki1='.$comike_date['month'].'\&f_nen1='.$comike_date['year'].'\&f_hi2='.($comike_date['day']+$i+1).'\&f_tuki2='.$comike_date['month'].'\&f_nen2='.$comike_date['year'].'\&f_heya_su=1\&f_otona_su=1');
		$urls += array('tra_r_'.$date_str => 'http://hotel.travel.rakuten.co.jp/hotelinfo/plan/69354?f_hi1='.($comike_date['day']+$i).'\&f_tuki1='.$comike_date['month'].'\&f_nen1='.$comike_date['year'].'\&f_hi2='.($comike_date['day']+$i+1).'\&f_tuki2='.$comike_date['month'].'\&f_nen2='.$comike_date['year'].'\&f_heya_su=1\&f_otona_su=1');
		//$urls += array('bee_r_'.$date_str => 'http://hotel.travel.rakuten.co.jp/hotelinfo/plan/?f_hi1='.($comike_date['day']+$i).'\&f_tuki1='.$comike_date['month'].'\&f_nen1='.$comike_date['year'].'\&f_hi2='.($comike_date['day']+$i+1).'\&f_tuki2='.$comike_date['month'].'\&f_nen2='.$comike_date['year'].'\&f_heya_su=1\&f_otona_su=1');
		$urls += array('ooe_r_'.$date_str => 'http://hotel.travel.rakuten.co.jp/hotelinfo/plan/74664?f_hi1='.($comike_date['day']+$i).'\&f_tuki1='.$comike_date['month'].'\&f_nen1='.$comike_date['year'].'\&f_hi2='.($comike_date['day']+$i+1).'\&f_tuki2='.$comike_date['month'].'\&f_nen2='.$comike_date['year'].'\&f_heya_su=1\&f_otona_su=1');
		
		$urls += array('was_'.$date_str => 'https://www1.fujita-kanko.co.jp/fujita-kanko/stay_pc/rsv/grp_cal_index.aspx?mc=1\&lang=ja-JP\&pf=5\&dt='.$comike_date['year'].'%2f'.$comike_date['month'].'%2f'.($comike_date['day']+$i).'\&le=1l');
		$urls += array('sun_'.$date_str => 'https://asp.hotel-story.ne.jp/ver3d/planlist.asp?hcod1=40360\&hcod2=001\&LB01=server3\&mode=seek\&hidSELECTARRYMD='.$comike_date['year'].'/'.$comike_date['month'].'/'.($comike_date['day']+$i).'\&hidSELECTHAKSU=1\&hchannel=ISW\&clrmode=seek');
		$urls += array('tra_'.$date_str => 'https://asp.hotel-story.ne.jp/ver3d/planlist.asp?hcod1=RT010\&hcod2=001\&LB01=server7\&mode=seek\&hidSELECTARRYMD='.$comike_date['year'].'/'.$comike_date['month'].'/'.($comike_date['day']+$i).'\&hidSELECTHAKSU=1\&hchannel=ISW\&clrmode=seek');
		//'bee'   => '',
		$urls += array('ooe_'.$date_str => 'https://advance.reservation.jp/ooedoonsen/stay_pc/rsv/grp_cal_index.aspx?mc=1\&lang=ja-JP\&pf=3\&dt='.$comike_date['year'].'/'.$comike_date['month'].'/'.($comike_date['day']+$i).'\&le=1#202135');

		$urls += array('was_j_'.$date_str => 'http://www.jalan.net/yad343292/plan/?stayYear='.$comike_date['year'].'\&stayMonth='.$comike_date['month'].'\&stayDay='.($comike_date['day']+$i).'\&adultNum=1');

		$grep_words += array('was_r_'.$date_str => 'notFound');
		$grep_words += array('sun_r_'.$date_str => 'notFound');
		$grep_words += array('tra_r_'.$date_str => 'notFound');
		$grep_words += array('ooe_r_'.$date_str => 'notFound');
	        $grep_words += array('was_'.$date_str   => '<td id=\"calendar_RpCalendar_ctl15_td_day1\" class=\"g-cal-odd\">×</td>');
	        $grep_words += array('sun_'.$date_str   => '<div class =\"cap\">');
	        $grep_words += array('tra_'.$date_str   => '<div class =\"cap\">');
	        $grep_words += array('ooe_'.$date_str   => '<td id=\"calendar_RpCalendar_ctl01_td_day1\" class=\".*\">×</td>');
	        $grep_words += array('was_j_'.$date_str => '/common/image/icon02.gif');
	}	
}

foreach ($urls as $key => $url){
	exec('wget -A .html '.$urls[$key].' -O '.DIR.$key.'.html'); sleep(2);

	$output = array();
	$ret = null;
	exec('grep -c "'.$grep_words[$key].'" /var/www/html/bot/'.$key.'.html', $output, $ret);
	//print_r($output);

	if($output[0] == '0'){
		$conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
		$xml = bitLyShorten( str_replace("\&", "&", $urls[$key]) );
		$sLink = $xml->results->nodeKeyVal->shortUrl;
		$params = array(
			'status' => '@username '.date('Y/m/d H:i').$key.' '.$sLink
		);
		$result = $conn->post('statuses/update', $params);
		//var_dump($result);
	}
}

function bitLyShorten($link){
	$link = urlencode($link);
	$url = 'http://api.bit.ly/shorten?version=2.0.1&longUrl=' . $link . '&login=' . API_LOGIN_BIT_LY . '&apiKey='. API_KEY_BIT_LY .'&format=xml';
	$src = file_get_contents($url);
	return simplexml_load_string($src);
}

?>
