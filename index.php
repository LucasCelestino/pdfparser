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

$segundoSemestreMeses = ['JULHO','AGOSTO','SETEMBRO','OUTUBRO','NOVEMBRO','DEZEMBRO'];
$auxMeses = 0;

foreach ($finalres as $key => $value)
{
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

// Verificando se alguma string acabou sendo cortada
foreach ($finalres as $key => $value)
{
    $primeiroCaractere = substr($value, 0, 1);

    if(ctype_alpha($primeiroCaractere))
    {
        // cula 16 - Compensação da carga horária de quinta-feira a fim de que se completem as 20 semanas letivas
        // 16 - Compensação da carga horária de quinta-feira a fim de que se completem as 20 semanas letivas
        // cortando o (cula) da string e reatribuindo o valor
        $finalres[$key] = substr($value, 5);

        // pegando o indice anterior para inserir a string no item correto
        $correctKey = $key-1;


        // pegando o item correto
        // 11 - Prazo máximo para Aplicação de Exames de Proficiência sem possibilidade de acomodação de matrí
        $stringForFix = $finalres[$correctKey];

        // pegando os 4 primeiros caracteres do item atual (cula) ^
        $firstDate = substr($value, 0, 4);

        // inserindo 
        // 11 - Prazo máximo para Aplicação de Exames de Proficiência sem possibilidade de acomodação de matrícula
        $finalres[$correctKey] = $stringForFix.$firstDate;

    }

}

foreach ($finalres as $key => $value)
{
    if($value == '')
    {
        $finalres[$key] = $segundoSemestreMeses[$auxMeses];
        $auxMeses += 1;
    }

}

foreach ($finalres as $key => $value)
{
    echo $key.'=>'.$value;
    echo '<br>';

}

?>