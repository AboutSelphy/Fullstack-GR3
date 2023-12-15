<?php

function findProjectFolder() {
    // echo __DIR__ ;
    $directories = glob(__DIR__ . '/**/Fullstack-GR3', GLOB_ONLYDIR);
    var_dump($directories);
    if (!empty($directories)) {
        return $directories[0]; // Returns the first occurrence of Fullstack-GR3 folder
    } else {
        return null; // Fullstack-GR3 folder not found
    }
}

// Get the path to Fullstack-GR3 folder
$fullStackPath = findProjectFolder();

if ($fullStackPath) {
    echo "Path to Fullstack-GR3 folder: " . $fullStackPath;
} else {
    echo "Fullstack-GR3 folder not found.";
}