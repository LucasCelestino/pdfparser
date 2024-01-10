<?php

require("vendor/autoload.php");

// Parse PDF file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('cronograma-semestral-2.pdf');

$pattern = '/\d{2} - .+/';
$data = array();
$finalData = array();
$finalres = array();
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

$segundoSemestreMeses = ['julho','agosto','setembro','outubro','novembro','dezembro'];
$primeiroSemestreMeses = ['janeiro', 'fevereiro','março','abril','maio','junho','julho'];
$auxMeses = 0;

foreach ($finalres as $key => $value)
{

    if($value == '')
    {
        $finalres[$key] = ' '.$primeiroSemestreMeses[$auxMeses];
        $auxMeses += 1;
    }

    $standard = substr($value, -2);

    if($standard == "a ")
    {
        // modificando o item que tem um valor por ex: (Dia da Revolução Constitucionalista 10 a) para: (Dia da Revolução Constitucionalista)
        $finalres[$key] = substr($value, 0, -5);

        // pegando esse valor, por ex: 10 a
        $firstDate = substr($value, -5);

        // pegando a key do item atual e somando + 1, pq o proximo item seria: 31 - Recesso Escolar
        $correctKey = $key+1;

        // juntando o valor que foi cortado (10 a) e juntando com o proximo item do array para ficar: 10 a 31 - Recesso Escolar
        $finalres[$correctKey] = $firstDate.''.$finalres[$correctKey];
    }
    else
    {
        $padrao_data = "/\b\d{2}\/\d{2}\/\d{2}\b/";

        // procurando padrão xx/xx/xx em um item
        if (preg_match($padrao_data, $value)) {
            // // removendo o xx/xx/xx do item 
            $finalres[$key] = substr($value, 0,-8);

            $date = substr($value, -8,8);

            // pegando a key do item atual e somando + 1, pq o proximo item seria: 26 - Matrícula da 2ª Lista de convocação de ingressantes e solicitação de Aproveitamento de Estudos - Vestibular 
            $correctKey = $key+1;

            $stringForFix = $finalres[$correctKey];
            $finalres[$correctKey] = $date.''.$stringForFix;
        }
    }
}

foreach ($finalres as $key => $value)
{
    echo $key.'=>'.$value;
    echo '<br>';
}

?>