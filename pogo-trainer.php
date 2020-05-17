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

function updateLocation()
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
	  CURLOPT_POSTFIELDS => "PDLat=-23.590979&PDLng=-46.503712",
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

try
{
	$logins = array(
		array('username', 'password')
	);
	
	$h = date('h');
	$x = $h % 3;
	
	getCookie();
	login($logins[$x]);
	updateLocation();
	
	$fp = fopen(dirname(__FILE__) . '/pogo-trainer.txt', 'a');
	fwrite($fp, '[' . date('Y-m-d H:i') . '] ' . $logins[$x][0] . "\n");  
	fclose($fp);  
}
catch(Exception $e)
{
	var_dump($e);
}

?>
