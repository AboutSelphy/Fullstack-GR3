<?php

// Grammar correction depending on number
function grammarCheck($data, $word)
{
    if ($data == 1) {
        $word;
    } else {
        $word = $word . "s";
    }
    return $word;
}

// Example
// $example = grammarCheck(5, 'year'); --> $word = "years"
