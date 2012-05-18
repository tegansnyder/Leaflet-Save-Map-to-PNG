<?php

	// Thanks to Jamund Ferguson
	// http://j-query.blogspot.com/2011/02/save-base64-encoded-canvas-image-to-png.html
	
	define('UPLOAD_DIR', '../savedMaps/');
	$savedMap = $_POST['savedMap'];
	$savedMap = str_replace('data:image/png;base64,', '', $savedMap);
	$savedMap = str_replace(' ', '+', $savedMap);
	$data = base64_decode($savedMap);
	$file = UPLOAD_DIR . uniqid() . '.png';
	$success = file_put_contents($file, $data);
	print $success ? $file : 'Unable to save the file.';
	
?>