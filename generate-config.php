<?php

$configMap = [];

$envs = [];
while ($line = trim(fgets(STDIN))) {
    $equalsPos = strpos($line, "=");

    if ($equalsPos !== -1 && $equalsPos > 0) {
        $envs[substr($line, 0, $equalsPos)] = substr($line, $equalsPos + 1);
    }
}

foreach ($envs as $name => $value) {
    if (strpos($name, "PS_") !== 0) {
        continue;
    }

    $value = is_numeric($value)
        ? $value
        : (in_array($value, ['true', 'false'], true)
        ? $value
        : '"' . addcslashes($value, '"') . '"');

    $name = substr($name, 3);

    $pieces = explode("__", $name);

    if (count($pieces) === 1) {
        $configMap[strtolower($pieces[0])] = $value;

    } elseif (count($pieces) === 3 && is_numeric($pieces[1])) {
        $configMap[strtolower($pieces[0])][(int)$pieces[1]][strtolower($pieces[2])] = $value;

    } elseif (count($pieces) === 2) {
        $configMap[strtolower($pieces[0])][strtolower($pieces[1])] = $value;

    } else {
        fwrite(STDERR, "Ignoring key PS_$name because it has more than 2 components.");
    }
}

function printObject($value, $level) {
    foreach ($value as $subKey => $subValue) {
        echo str_repeat("    ", $level), $subKey, "=", $subValue, PHP_EOL;
    }
}

foreach ($configMap as $key => $value) {
    if (is_array($value) && !is_int(array_keys($value)[0])) {
        echo PHP_EOL, $key, "=", PHP_EOL, "{", PHP_EOL;
        printObject($value, 1);
        echo "}", PHP_EOL;

    } elseif (is_array($value) && is_int(array_keys($value)[0])) {

        echo PHP_EOL, $key, "=", PHP_EOL, "(", PHP_EOL;
        $i = 1;
        foreach ($value as $object) {
            echo "    {", PHP_EOL;
            printObject($object, 2);
            echo "    }", ($i < count($value) ? "," : ""), PHP_EOL;
            $i++;
        }
        echo ")", PHP_EOL;

    } else {
        echo $key, "=", $value, PHP_EOL;
    }
}
