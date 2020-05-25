<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);

$cookie = '';

header('Content-Type: text/plain');

function getCookie()
{
	global $cookie;

	$s = curl_init();
	
	curl_setopt($s, CURLOPT_URL, 'https://pogotrainer.club/');
	curl_setopt($s, CURLOPT_HEADER, 1);
	curl_setopt($s, CURLOPT_RETURNTRANSFER, 1);
	
	$r = curl_exec($s); 
	
	preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $r, $matches);
	
	$cookies = array();

	foreach($matches[1] as $item) {
		$cookies[] = $item;
	}
	
	curl_close($s);
	
	$cookie = implode('; ', $cookies);
}

function login($login)
{
	global $cookie;

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://pogotrainer.club/login/run/",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "loginEmail={$login[0]}&loginPassword={$login[1]}&loginRedirect=",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/x-www-form-urlencoded",
		"Cookie: $cookie",
		"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:77.0) Gecko/20100101 Firefox/77.0",
	  ),
	));

	$response = curl_exec($curl);

	if(!curl_errno($curl))
	{
	  $info = curl_getinfo($curl);
	  
	  print_r($info);
	} 
	else 
	{
	  echo 'Curl error: ' . curl_error($curl);
	}

	curl_close($curl);
	
	echo "-------------------------------\n";
}

function updateLocation($location)
{
	global $cookie;

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://pogotrainer.club/pokemon/updateLocation/",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "PDLat=$location[0]&PDLng=$location[1]",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/x-www-form-urlencoded",
		"Cookie: $cookie"
	  ),
	));

	$response = curl_exec($curl);

	if(!curl_errno($curl))
	{
	  $info = curl_getinfo($curl);
	  
	  print_r($info);
	} 
	else 
	{
	  echo 'Curl error: ' . curl_error($curl);
	}

	curl_close($curl);
	
	echo '-------------------------------';
}

function random_float($min, $max) {
    return round(((mt_rand() / mt_getrandmax()) * ($max - $min)) + $min, 7);
}

try
{
	$logins = array(
		array('', ''), // 0 
		array('', ''), // 1
		array('', ''), // 2
		array('', ''), // 3
		array('', ''), // 4
	);
	
	$locations = array(
		array(-random_float(0, 22.7), -random_float(42.1, 56.0)), // Brazil
		array(random_float(32.8, 48.3), -random_float(80.1, 124.6)), // USA
		array(random_float(46.4, 50.9), random_float(1.8, 30.5)), // Europe
		array(random_float(24.5, 31.4), random_float(104.5, 118)), // China
		array(-23.590979, -46.503712), // SP
	);
	
	$h = date('g');
	$x = $h % count($logins);

	getCookie();
	login($logins[$x]);
	updateLocation($locations[$x]);
	
	$log = '[' . date('Y-m-d H:i') . "] {$logins[$x][0]} ({$locations[$x][0]}, {$locations[$x][1]})\n";
	
	print("\n$log");
	
	$fp = fopen(dirname(__FILE__) . '/pogo-trainer.txt', 'a');
	fwrite($fp, $log);
	fclose($fp);  
}
catch(Exception $e)
{
	var_dump($e);
}

?>
