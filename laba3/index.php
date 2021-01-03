<?php

$characters = 400;

$averageAmountOfInformation = [0.7, 0.2, 0.1]; // a1, a2, a3

$channelMatrix = [
    [0.98, 0.01, 0.01],
    [0.1, 0.75, 0.15],
    [0.2, 0.3, 0.5]
];

// Энтропия источника
$entropySource = getEntropySource($averageAmountOfInformation);
echo $entropySource . PHP_EOL;

// Общая условная энтропия
$generalConditionalEntropy = getGeneralConditionalEntropy($averageAmountOfInformation, $channelMatrix);
echo $characters * $generalConditionalEntropy . PHP_EOL;

// Энтропия приемника
$receiverEntropyBySymbol = getReceiverEntropy($channelMatrix, $averageAmountOfInformation);
echo $characters * ($receiverEntropyBySymbol - $generalConditionalEntropy) . PHP_EOL;


/****************** functions ******************/

// Энтропия источника
function getEntropySource($averageAmountOfInformation) {
    $entropySource = 0;
    foreach ($averageAmountOfInformation as $item) {
        $entropySource += $item * log($item, 2);
    }
    return round($entropySource * -1, 4);
}

// Общая условная энтропия
function getGeneralConditionalEntropy($averageAmountOfInformation, $linkMatrix) {
    $generalConditionalEntropy = 0;
    foreach ($averageAmountOfInformation as $i => $item) {
        foreach ($linkMatrix[$i] as $link) {
            $generalConditionalEntropy += $item * ($link * log($link, 2));
        }
    }
    return round($generalConditionalEntropy * -1, 4);
}

// Энтропия приемника
function getReceiverEntropy($matrix, $averageAmountOfInformation) {
    $tmp = [];
    $TchannelMatrix = getTransposeMatrix($matrix);

    for ($i = 0; $i < count($matrix); $i++) {
        foreach ($TchannelMatrix[$i] as $j => $m) {
            $tmp[$i] += $averageAmountOfInformation[$j] * $m;
        }
    }

    $receiverEntropy = 0;
    foreach ($tmp as $item) {
        $receiverEntropy += $item * log($item, 2);
    }

    return round($receiverEntropy * -1, 4);
}

// Транспонирование матрицы
function getTransposeMatrix($matrix) {
    array_unshift($matrix, null);
    return call_user_func_array('array_map', $matrix);
}
