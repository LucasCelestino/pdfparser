<?php

require("vendor/autoload.php");

// Parse PDF file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('cronograma-semestral.pdf');

$pattern = '/\d{2} - .+/';
$data = array();
$finalData = array();
$finalres = array();
$segundoSemestreMeses = ['julho','agosto','setembro','outubro','novembro','dezembro'];
$res = array();

foreach ($pdf->getPages() as $page) {

    $text = $page->getText();

    preg_match_all($pattern, $text, $matchs);

    $data = array_merge($data, $matchs[0]);
}

// tirando espaços duplicados
foreach ($data as $key => $value)
{
    $data[$key] =  str_replace('	', '', $value);
}


foreach ($data as $key => $value)
{
    $text = $value;

    preg_match_all($pattern, $text, $matchs);

    $finalData[$key] = $matchs[0];
}

foreach ($finalData as $string => $text) {
    $pattern = '/(?=\d{2} -)/';
    $res = preg_split($pattern, $text[0]);
    foreach ($res as $key => $value)
    {
        $finalres[] = $value;
    }

}

// Problema: 4=>09 - Dia da Revolução Constitucionalista 10 a
// --------  5=>31 - Recesso Escolar

// Jeito Correto: 4=>09 - Dia da Revolução Constitucionalista
// -------------  5=>10 a 31 - Recesso Escolar


foreach ($finalres as $key => $value)
{
    $standard = substr($value, -2);

    if($standard == "a ")
    {
        $finalres[$key] = substr($value, 0, -5);
    }
}


foreach ($finalres as $key => $value)
{
    echo $key.'=>'.$value;
    echo '<br>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
