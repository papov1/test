<?php

include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/admin/vendor/autoload.php');

$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile('read.pdf');

$text = $pdf->getText();
$text = str_replace("\n", "", $text);
$text = str_replace("\r", "", $text);
$text = preg_replace('!\s+!', ' ', $text);

$words_array = explode(" ",strtolower($text));
$vals = array_count_values($words_array);
arsort($vals);

foreach ($vals as $key => $value) {
	if(!is_numeric($key) && (strlen($key) > 1)){

	}
	echo $key.' - '.$value.'<br>';
}