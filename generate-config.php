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

    $value = is_numeric($value) ? $value : '"' . addcslashes($value, '"') . '"';

    $name = substr($name, 3);

    $pieces = explode("__", $name);

    if (count($pieces) === 1) {
        $configMap[strtolower($pieces[0])] = $value;

    } elseif (count($pieces) === 2) {
        $configMap[strtolower($pieces[0])][strtolower($pieces[1])] = $value;

    } else {
        fwrite(STDERR, "Ignoring key PS_$name because it has more than 2 components.");
    }
}

foreach ($configMap as $key => $value) {
    if (is_array($value)) {
        echo PHP_EOL, $key, "=", PHP_EOL, "{", PHP_EOL;

        foreach ($value as $subKey => $subValue) {
            echo "    ", $subKey, "=", $subValue, PHP_EOL;
        }

        echo "}", PHP_EOL;

    } else {
        echo $key, "=", $value, PHP_EOL;
    }
}
