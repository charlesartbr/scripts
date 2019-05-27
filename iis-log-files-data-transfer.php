<?php

ini_set ('memory_limit', 400000000);

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');   

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

for($m = 1; $m <= 5; $m++)
{
	$transfer = 0;
	$mm = $m < 10 ? "0$m" : $m;
	
	$files = glob("W3SVC52/u_ex19$mm*");

	foreach($files as $file)
	{
		$rows = explode("\n", file_get_contents($file));
		
		foreach($rows as $row)
		{
			$data = explode(" ", $row);
			
			if (is_numeric($data[19]))
			{
				$transfer += intval($data[19]);
			}
		}
	}
	
	echo "Transfer ($m): " . formatBytes($transfer) . "\n";
}
