<?php
// When calling fileUpload $source should be either "user" / "animal" / "shelter" depending on CRUD
function fileUpload($picture, $source)
{
    if ($picture["error"] == 4) {
        // There is a different default.jpg in every subfolder of resources/img/...
        $pictureName = "default.jpg";
        // The $message can get used for alerts!
        $message = "No picture has been chosen, but you can upload an image later :)";
    } else {
        $checkIfImage = getimagesize($picture["tmp_name"]);
        $message = $checkIfImage ? "Nice image" : "This is not an image";
    }

    if ($message == "Nice image") {
        $ext = strtolower(pathinfo($picture["name"], PATHINFO_EXTENSION));
        $pictureName = uniqid("") . "." . $ext;

        // Accesing the correct folder for image saving ($destiny) depending on the $source
        if ($source == "user") {
            $destination = "../../resources/img/users/$pictureName";
        } elseif ($source == "animal") {
            $destination = "../../resources/img/animals/$pictureName";
        } else {
            $destination = "../../resources/img/shelters/$pictureName";
        }

        move_uploaded_file($picture["tmp_name"], $destination);
    }

    return [$pictureName, $message];
}
