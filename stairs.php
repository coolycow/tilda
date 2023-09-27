<?php

    $i = 0;
    $row = 1;
    $max = 100;

    while ($i < $max) {
        $j = 0;
        $out = [];

        while ($j < $row && $i < $max) {
            $j++;
            $out[] = ++$i;
        }

        print implode(' ', $out) . "\n";

        $row++;
    }

?>