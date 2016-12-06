<?php

//<editor-fold desc="Get an ingredient-effects data">
$data = file('db.csv');
$data = array_map(function ($e) {
    $res = str_getcsv($e, ';');
    return [
        'ingredient' => $res[0],
        'effect' => $res[1],
    ];
}, $data);
//</editor-fold>
/** @var $data array All the ingredients and effects map (ingredient=>effect) */

$ingredients = array_unique(array_column($data, 'ingredient'));
/** @var $ingredients array List of al lthe ingredients */

//<editor-fold desc="Generate ingredients map, if none">
if (!file_exists('possessed.csv')) {
    $res = '';
    foreach ($ingredients as $i) {
        $res .= "\"{$i}\";\"0\"\n";
    }
    file_put_contents('possessed.csv', $res);
    unset($res);
}
//</editor-fold>

//<editor-fold desc="Get current inventory">
$tmp = file('possessed.csv');
$inv = [];
foreach ($tmp as $e) {
    $res = str_getcsv($e, ';');
    if ($res[1] > 0) {
        $inv[$res[0]] = $res[1];
    }
};
unset($res);
unset($e);
unset($tmp);
//</editor-fold>
/** @var $inv array Current inventory (ingr. => count) */

function findMatch($ingredient, $data, $posessed)
{
    $res = [];
    $props = array_filter($data, function ($e) use ($ingredient) {
        return $e['ingredient'] == $ingredient;
    });
    $props = array_column($props, 'effect');
    $posessed_ingredients = array_keys($posessed);
    $data = array_filter($data, function ($e) use ($posessed_ingredients, $ingredient) {
        return in_array($e['ingredient'], $posessed_ingredients) && $e['ingredient']!=$ingredient;
    });
    $tmp=[];
    foreach ($data as $item) {
        $tmp[$item['ingredient']][] = $item['effect'];
    }
    foreach ($tmp as $ingr => $effects) {
        $count = array_intersect($effects,$props);
        if(count($count) > 0) {
            $res[$ingr] = count($count);
        }
    }

    asort($res);
    return $res;
}

$res = findMatch('Абесинский окунь', $data, $inv);

$done = true;   //purely for breakpoints