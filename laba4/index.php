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

function decodeSymbol($table, $code, $index = 0) {
    if (count($table) != 1) {
        $divideTable = divideTable($table);
        $left = $divideTable['0'];
        $right = $divideTable['1'];
        if ($code[$index] = '0') {
            return decodeSymbol($left, $code, $index + 1);
        } else {
            return decodeSymbol($right, $code, $index + 1);
        }
    } else {
        return array_pop($table)[0];
    }
}


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

function get_L($H, $n) {
    return round(1 - ($H / $n), 2);
}

function getEntropy($averageAmountOfInformation) {
    $entropySource = 0;
    foreach ($averageAmountOfInformation as $item) {
        $entropySource += $item * log($item, 2);
    }
    return round($entropySource * -1, 4);
}

function get_P($w, $m) {
    $P = [];
    foreach ($w as $key => $value) {
        $P[$key] = $value / $m;
    }
    return $P;
}

function get_w($str) {
    $w = [];
    foreach (str_split($str) as $s) {
        ++$w[$s];
    }
    return $w;
}

function get_N($str) {
    $Ntmp = [];
    foreach (str_split($str) as $s) {
        ++$Ntmp[$s];
    }
    return count($Ntmp);
}



