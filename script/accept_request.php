<?php

function accept($parameter, $db, $change,$table)
{
    $id = intval($parameter["accepted"]);
    try {
        $stmt = $db->prepare("UPDATE `" . $table . "` SET `status`='$change' WHERE id = $id");
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
