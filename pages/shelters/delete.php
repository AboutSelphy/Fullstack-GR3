<?php
require_once("./../../script/db_connection.php");

// $id =$_GET["id"]; --> will get un-commented once there is an ID in the URL
$id = 10; // delete this line once everything established
$stmt = $db->prepare("SELECT * FROM shelters WHERE id = $id");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row = $result[0];

if ($row["image"] != "default.jpg") {
    unlink("../../resources/img/shelters/$row[image]");
}

$delete = "DELETE FROM shelters WHERE id = $id";

if (mysqli_query($connect, $delete)) {
    header("Location: ../user/dashboard.php");
} else {
    echo "<div class='alert alert-danger' role='alert'>Oh no, something must have gone wrong :(</div>";
    header("refresh: 3; url= /code-factory/codereview-4/BE20-CR4-SvenjaSchulmeister/animal/home.php");
}

$stmt = $db->prepare($delete);
$stmt->execute();

// Close database connection
$db = NULL;
