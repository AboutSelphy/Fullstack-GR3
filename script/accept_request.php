<?php

function accept($parameter, $db, $change)
{
    $id = intval($parameter["accepted"]);
    try {
        $stmt = $db->prepare("UPDATE `animals` SET `status`='$change' WHERE id = $id");
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
