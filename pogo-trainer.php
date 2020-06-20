<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);

$cookie = '';

header('Content-Type: text/plain');

function getCookie()
{
	global $cookie;

	$curl = curl_init();
	
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://pogotrainer.club/',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_HEADER => 1,
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/x-www-form-urlencoded",
		"Cookie: $cookie",
		"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:77.0) Gecko/20100101 Firefox/77.0",
	  )
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

	preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
	
	$cookies = array();

	foreach($matches[1] as $item) {
		$cookies[] = $item;
	}
	
	curl_close($curl);
	
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

	if (is_array($location[0]))
	{
		$x = mt_rand(0, count($location) - 1);
		$location = $location[$x];
	}

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
	
	return $location;
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
		// 0: Brazil (São Paulo, Rio de Janeiro, Recife, Curitiba, Manaus)
		array(array(-23.5882183,-46.6565463), array(-22.9687034,-43.1978738), array(-8.1157493,-34.9093704), array(-25.4350492,-49.244312), array(-3.0444884,-60.0371439)), 
		// 1: USA (New York, Chicago, Miami, Los Angeles, Seattle)
		array(array(40.7482156,-73.9872887), array(41.8395421,-87.7217484), array(25.7990002,-80.1322639), array(33.9886552,-118.3008758), array(47.6206961,-122.3509972)), 
		// 2: Europe (Paris, Bruxelas, Berlim, Londres, Madri)
		array(array(48.8667993,2.3209658), array(50.8510756,4.3578794), array(52.5069704,13.2846503), array(51.5305873,-0.1740457), array(40.4149204,-3.7117179)), 
		// 3: Asia (Seul, Hong Kong, Tokio, Kuala Lumpur)
		array(array(37.552048,126.9744137), array(31.2246325,121.1965699), array(22.3065456,114.1565307), array(35.6684415,139.6007845), array(3.1631903,101.7063753)), 
		// 4: SP
		array(-23.590979, -46.503712), 
	);
	
	$hours = array(
		0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4,
		5 => 0, 6 => 1, 7 => 2, 8 => 3, 9 => 4,
		9 => null, 10 => null, 11 => null, 12 => null, 13 => 4,
		14 => 0, 15 => 1, 16 => 2, 17 => 3, 18 => 4,		
		19 => null, 20 => null, 21 => null, 22 => null, 23 => 4,
	);
	
	$h = date('G');
	$x = $hours[$h];
	
	if ($x === null)
	{
		$log = '[' . date('Y-m-d H:i') . "] nenhum login (h=$h, x=$x)";
	}
	else
	{
		getCookie();
		
		if (empty($cookie))
		{
			$log = '[' . date('Y-m-d H:i') . "] {$logins[$x][0]} - NÃO FOI POSSIVEL PEGAR O COOKIE\n";
		}
		else
		{
			login($logins[$x]);
			$location = updateLocation($locations[$x]);
		
			$log = '[' . date('Y-m-d H:i') . "] {$logins[$x][0]} - https://www.latlong.net/c/?lat={$location[0]}&long={$location[1]}\n";
		}
	}
	
	print("\n$log");
	
	$fp = fopen(dirname(__FILE__) . '/pogo-trainer.txt', 'a');
	fwrite($fp, $log);
	fclose($fp);  
}
catch(Exception $e)
{
	var_dump($e);
	
	$fp = fopen(dirname(__FILE__) . '/pogo-trainer.error.log', 'a');
	fwrite($fp, '[' . date('Y-m-d H:i') . ']');
	fwrite($fp, print_r($e, true));
	fclose($fp);  
}

?>
