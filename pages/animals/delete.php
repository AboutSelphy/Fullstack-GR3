<?php
require_once("./../../script/db_connection.php");

require_once("../../config.php");
include("./../../script/loginGate.php");

if ($role !== 'unset') {
    if ($role !== 'shelter') {
        header("Location: " . ROOT_PATH . "pages/login/login.php");
        exit();
    }
}

$id = $_GET["id"];
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
    header("Location: ../sh_dashboard.php");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // This will display the error message
}

// Close database connection
$db = NULL;
