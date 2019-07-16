<?php

// Changes file mode for all files in current directory

$files = glob('*');

foreach($files as $file)
{
	if(chmod($file, 0644)) 
	{
		echo "file mode changed for: $file<br>";
	}
	else
	{
		echo "cannot change file mode for: $file<br>";
	}
}
