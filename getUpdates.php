<?php
	function deleteDir($dirPath)
	{
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}

	
	$fileUrl = 'http://server.example.com/updates/v1.zip';
	$fileName = md5(uniqid());
	$savedFilePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $fileName . '.zip';
	$raw = @file_get_contents($fileUrl);
	if ($raw && file_put_contents($savedFilePath, $raw)) {
		$zip = new ZipArchive;
		if ($zip->open($savedFilePath) === TRUE) {
			$zip->extractTo($_SERVER['DOCUMENT_ROOT']);
			$zip->close();
			unlink($savedFilePath);
			deleteDir($_SERVER['DOCUMENT_ROOT'] . '/__MACOSX');
			return 'OK';
		}
	}
	return 'File Not Opened';
?>