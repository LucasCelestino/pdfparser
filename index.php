<?php

require("vendor/autoload.php");

// Parse PDF file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('cronograma-semestral.pdf');

$text = $pdf->getText();

$explodedText = explode("- ", $text);

// 1 semestre

// 2 semestre

$serapatedData = [];
$finalData = [];
$meses = ["JULHO","AGOSTO","SETEMBRO","OUTUBRO","NOVEMBRO","DEZEMBRO"];

// removendo footer
array_pop($explodedText);

foreach ($explodedText as $key => $value)
{
    if(is_numeric(substr(trim($value), -1)))
    {
        foreach ($meses as $mes)
        {
            if(strpos($value, $mes))
            {
                $finalData[$mes] = [1,2,3,4];
            }
        }

        $serapatedData[] = $value;
    }
}

foreach ($serapatedData as $key => $value)
{
    
    echo $value;
    echo "<hr>";
}

var_dump($finalData);

