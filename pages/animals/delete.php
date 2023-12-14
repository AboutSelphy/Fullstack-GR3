<?php
require_once("./../../script/db_connection.php");

// $id =$_GET["id"]; --> will get un-commented once there is an ID in the URL
$id = 23; // delete this line once everything established
$stmt = $db->prepare("SELECT * FROM animals WHERE id = $id");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row = $result[0];

if ($row["image"] != "default.jpg") {
    unlink("../../resources/img/shelters/$row[image]");
}

try {
    $delete = "DELETE FROM animals WHERE id = $id";
    $stmt = $db->prepare($delete);
    $stmt->execute();
    header("Location: ./animals.php");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // This will display the error message
}

// Close database connection
$db = NULL;
