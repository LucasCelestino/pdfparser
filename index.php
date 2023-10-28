<?php

require("vendor/autoload.php");

// Parse PDF file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('cronograma-semestral.pdf');

$pattern = '/\d{2} - .+/';
$data = array();

foreach ($pdf->getPages() as $page) {

    $text = $page->getText();

    preg_match_all($pattern, $text, $matchs);

    $data = array_merge($data, $matchs[0]);
}

foreach ($data as $key => $value)
{
    echo $key;
    echo "<hr>";
}

// // 2 semestre
// $serapatedData = [];
// $finalData = [];
// $meses = ["JULHO","AGOSTO","SETEMBRO","OUTUBRO","NOVEMBRO","DEZEMBRO"];
// $formatedData = [];

// // removendo footer
// array_pop($explodedText);

// foreach ($explodedText as $key => $value)
// {
//     // pegando apenas os itens que contenham um valor int no final
//     if(is_numeric(substr(trim($value), -1)))
//     {

//         $actualDay = substr(trim($value), strlen($value) - 3, 2);

//         // if($key == 1)
//         // {
//         //     continue;
//         // }

//         echo $actualDay." - ".$value;
//         echo "<hr>";


//         // foreach ($meses as $mes)
//         // {
//         //     if(strpos($value, $mes))
//         //     {
//         //         $finalData[$mes] = [1,2,3,4];
//         //     }
//         // }

//         $serapatedData[] = $value;
//     }
// }

// // var_dump($formatedData);exit;

// foreach ($formatedData as $key => $value)
// {
    
//     echo $value;
//     echo "<hr>";
// }


