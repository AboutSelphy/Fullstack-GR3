<?php
    //db connection

    try {
        $db = new PDO('mysql:host=localhost;dbname=localmagic', 'root', '');
        echo 'DB - Connected ✅🖥 ';

    } catch (PDOException $e) {
        // attempt to retry the connection after some timeout for example
        echo $e . 'DB - ERROR ✕ 🖥';

    }