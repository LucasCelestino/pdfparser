<?php

require("vendor/autoload.php");

// Parse PDF file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('cronograma-semestral.pdf');

$text = $pdf->getText();

$explodedText = explode("- ", $text);

$finalData = [];

// removendo header
unset($explodedText[0]);
unset($explodedText[1]);

array_pop($explodedText);

foreach ($explodedText as $key => $value)
{
    if(is_numeric(substr(trim($value), -1)))
    {
        $finalData[] = $value;
    }
}

foreach ($finalData as $key => $value)
{
    echo $value;
    echo "<hr>";
}