<?php

// TODO`s:
// 1. Move classes to separate files
// 2. PSR?
// 3. A 'prop' class?
// 4. Web-interface for 'usage' section
// 4. Web-interface for 'live' inventory editing
// 5. The 'brew it' function for ingr. auto-substraction from inventory

//<editor-fold desc="Alldata">
class alldata
{
    private static $dbfile = 'db.csv';
    private static $data;

    public static function init()
    {
        $data = file(self::$dbfile);
        $data = array_map(function ($e) {
            $res = str_getcsv($e, ';');
            return [
                'ingredient' => $res[0],
                'effect' => $res[1],
            ];
        }, $data);
        self::$data = $data;
    }

    public static function getAll()
    {
        return self::$data;
    }

    public static function getAllIngredients()
    {
        $ingredients = array_unique(array_column(self::$data, 'ingredient'));
        asort($ingredients);
        return $ingredients;
    }
    public static function getAllProps()
    {
        $props = array_unique(array_column(self::$data, 'effect'));
        asort($props);
        return $props;
    }

    public static function findByProp($prop)
    {
        $data = self::getAll();
        $data = array_filter($data, function ($e) use ($prop) {
            return $e['effect'] == $prop;
        });
        $res = [];
        $inv = inventory::getInventory();
        foreach ($data as $row) {
            $res[$row['ingredient']] = $inv[$row['ingredient']];
        }
        arsort($res);
        return $res;
    }

    public static function printByProp($prop)
    {
        $data = self::findByProp($prop);

        echo "=== [{$prop}] ===\n";
        foreach ($data as $ingr => $count) {
            echo "\t({$count}) {$ingr}\n";
        }
    }

    public static function printAllIngredients() {
        $data = self::getAllIngredients();
        foreach ($data as $row) {
            echo "{$row}\n";
        }
    }
    public static function printAllProps() {
        $data = self::getAllProps();
        foreach ($data as $row) {
            echo "{$row}\n";
        }
    }
}

alldata::init();
//</editor-fold>

//<editor-fold desc="Inventory">
class inventory
{
    private static $invfile = 'possessed.csv';
    private static $inventory;

    public static function init()
    {
        if (!file_exists(self::$invfile)) {
            self::generateDummy();
        }
        $tmp = file(self::$invfile);
        $inv = [];
        foreach ($tmp as $e) {
            $res = str_getcsv($e, ';');
            if ($res[1] > 0) {
                $inv[$res[0]] = $res[1];
            }
        };
        self::$inventory = $inv;
    }

    public static function getInventory()
    {
        return self::$inventory;
    }

    private static function generateDummy()
    {
        $res = '';
        $ingredients = alldata::getAllIngredients();
        foreach ($ingredients as $i) {
            $res .= "\"{$i}\";\"0\"\n";
        }
        file_put_contents(self::$invfile, $res);
        unset($res);
    }
}

inventory::init();

//</editor-fold>

//<editor-fold desc="Ingredient">
class ingredient
{
    private $name;
    private $props;
    private $count;

    public function __construct($name)
    {
        $this->name = $name;
        $this->props = $this->getProps($name);
        $this->count = inventory::getInventory()[$name];
    }

    private function getProps($name)
    {
        $data = alldata::getAll();
        $props = array_filter($data, function ($e) use ($name) {
            return $e['ingredient'] == $name;
        });
        $props = array_column($props, 'effect');
        return $props;
    }

    private function findMatch($prop = null)
    {
        $ingredient = $this->name;
        $posessed = inventory::getInventory();
        $data = alldata::getAll();
        $props = $this->props;
        if ($prop) {
            $props = [
                $prop
            ];
        }

        $res = [];
        $posessed_ingredients = array_keys($posessed);
        $data = array_filter($data, function ($e) use ($posessed_ingredients, $ingredient) {
            return in_array($e['ingredient'], $posessed_ingredients) && $e['ingredient'] != $ingredient;
        });
        $tmp = [];
        foreach ($data as $item) {
            $tmp[$item['ingredient']][] = $item['effect'];
        }
        foreach ($tmp as $ingr => $effects) {
            $count = array_intersect($effects, $props);
            if (count($count) > 0) {
                $res[$ingr] = $count;
            }
        }

        uksort($res, function ($a, $b) use ($res, $posessed) {
            return (count($res[$b]) * 1000 + $posessed[$b]) - (count($res[$a]) * 1000 + $posessed[$a]);
        });
        return $res;
    }

    public function printMatches($propNum = null)
    {
        $tmp_props = $this->props;
        if ($propNum) {
            $tmp_props[$propNum] = "[" . $tmp_props[$propNum] . "]";
        }
        $list = implode(',', $tmp_props);
        if ($propNum) {
            $matches = $this->findMatch($this->props[$propNum]);
        } else {
            $matches = $this->findMatch();
        }
        $inv = inventory::getInventory();

        echo "=== {$this->name} ({$list}) ===\n";
        foreach ($matches as $ingr => $props) {
            $count = $inv[$ingr];
            echo "\t({$count}) {$ingr}\n";
            foreach ($props as $prop) {
                echo "\t\t{$prop}\n";
            }
        }
    }
}

//</editor-fold>

//<editor-fold desc="The usage">

//(new ingredient('Пчелиное гнездо'))->printMatches();

//alldata::printByProp('Повышение навыка: Скрытность');

//alldata::printAllProps();
//alldata::printByProp('Повышение навыка: Зачарование');
//alldata::printByProp('Повышение навыка: Кузнечное дело');

//</editor-fold>
