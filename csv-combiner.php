<?php

$headers = false;
$output = fopen("php://stdout",'w');
ini_set("auto_detect_line_endings", true);
foreach ($argv as $index => $file) {
	// we skip the first argument, as that is the script we are running
	if($index == 0)
		continue;
	// validate file exists before trying to do anything
	if(file_exists($file)){
		// handle file
		$handler = fopen($file, 'r');
		// get headers from the file
		$header = fgetcsv($handler,0,",");
		if(!$headers){
			// set headers to output
			$h = array_merge($header,['filename']);
			fputcsv($output, $h);
			// flag that headers have been set, so we don't try and add them
			// when we read the next file
			$headers = true;
		}
		// iterate through the rest of the rows in file
		while ($row = fgetcsv($handler,0,",")){
			$r = array_merge($row,[basename($file)]);
			fputcsv($output, $r);
		}
		fclose($handler);	
	}
}
fclose($output);
?>