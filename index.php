<?php

require("vendor/autoload.php");

// Parse PDF file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('cronograma-semestral.pdf');

$pattern = '/\d{2} - .+/';
$data = array();
$finalData = array();

foreach ($pdf->getPages() as $page) {

    $text = $page->getText();

    preg_match_all($pattern, $text, $matchs);

    $data = array_merge($data, $matchs[0]);
}

// tirando espaços duplicados
foreach ($data as $key => $value)
{
    $data[$key] =  str_replace('	', '', $value);;
}


foreach ($data as $key => $value)
{
    $text = $value;

    preg_match_all($pattern, $text, $matchs);

    $finalData[$key] = $matchs[0];
}


