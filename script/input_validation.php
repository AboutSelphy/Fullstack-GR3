<?php
function clean($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = strip_tags($data);
    return $data;
}

function validate($data, $dataError, $length)
{
    if (empty($data)) {
        $error = true;
        $dataError = "Please insert some data here!";
    } elseif (strlen($data) > $length) {
        $error = true;
        $dataError = "Your input must not have more than $length characters!";
    } else {
        $error = false;
        $dataError = "";
    }

    $validation = [$error, $dataError];
    return $validation;
}
