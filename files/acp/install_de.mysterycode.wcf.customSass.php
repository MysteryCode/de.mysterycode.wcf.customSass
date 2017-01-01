<?php

use wcf\util\FileUtil;

$fileName = FileUtil::getRealPath(WCF_DIR) . 'style/custom_mysterycode.less';
if (file_exists($fileName)) {
	$content = file_get_contents($fileName);
	$lineArray = explode("\n", $content);
	
	$output = "/*\n";
	foreach ($lineArray as $line) {
		$output .= "\t" . $line . "\n";
	}
	$output .= "*/\n";
	
	if (!empty($lineArray) && !empty($content)) {
		$file = FileUtil::getRealPath(WCF_DIR) . 'style/ui/customMysterycode.scss';
		file_put_contents($file, $output);
	}
}
