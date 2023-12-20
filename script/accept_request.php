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

// function acceptShelter($parameter, $db, $shelterID,$table)
// {
//     $id = intval($parameter["accepted"]);
//     try {
//         $stmt = $db->prepare("UPDATE `" . $table . "` SET `fk_shelter`= $shelterID WHERE id = $id");
//         $stmt->execute();
//     } catch (PDOException $e) {
//         echo "Error: " . $e->getMessage();
//     }
// }
