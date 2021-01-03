<?php

// Шифруемая строка
$str = 'Lorem ipsum dolor sit amet consectetur adipisicing elit';

$table = get_w($str); // Частота вхождений
arsort($table); // Сортировка

// Построение кодового дерева и кодирование символов
$shenonСodes = shenonGetCodes($table);

// Выдача кодов символов
foreach ($shenonСodes as $key => $value) {
    echo $key . ': ' . $value . PHP_EOL;
}


/****************** functions ******************/

// Построение кодового дерева и кодирование символов
function shenonGetCodes($table, $value = '', &$codes = []) {
    if (count($table) != 1) {
        $divideTable = divideTable($table);
        $left = $divideTable['0'];
        $right = $divideTable['1'];
        shenonGetCodes($left, $value . '0', $codes);
        shenonGetCodes($right, $value . '1', $codes);
    } else {
        $key = end(array_keys($table));
        $codes[$key] .= $value;
        unset($table[$key]);
    }
    return $codes;
}

function divideTable($table) {
    $optimalDifference = array_sum(array_values($table));
    $optimalIndex = 0;
    $currentDifference = 0;
    $res = [];

    for ($i = 0; $i < count($table); $i++) {
        $left = array_slice($table, 0, $i);
        $right = array_slice($table, $i, -1);

        $leftSum = array_sum(array_values($left));
        $rightSum = array_sum(array_values($right));
        $currentDifference = abs($leftSum - $rightSum);

        if ($currentDifference <= $optimalDifference) {
            $optimalDifference = $currentDifference;
            $optimalIndex = $i;
        }
    }
    
    $j = 0;
    foreach ($table as $key => $value) {
        if ($j < $optimalIndex) $res['0'][$key] = $value;
        if ($j >= $optimalIndex) $res['1'][$key] = $value;
        $j++;
    }
    return $res;
}

// Частота вхождений
function get_w($str) {
    $w = [];
    foreach (str_split($str) as $s) {
        ++$w[$s];
    }
    return $w;
}
