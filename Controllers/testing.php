<?php

$lista = array(
    array('aaa', 'bbb', 'ccc', 'dddd'),
    array('123', '456', '789'),
    array('"aaa"', '"bbb"')
);

$fp = fopen('fichero.txt', 'w');
$delimiter = " ";

foreach ($lista as $campos) {
    fputcsv($fp, $campos, $delimiter);
}

fclose($fp);
?>