<?php

if(isset($_GET["id"])) {
    include "./config.php";

    $db = new mysqli($addr, $login, $password, $dbname);

    if ($db->connect_error) {
        http_response_code(400);
        echo "Failed connecting to DB";
    } else {
        $id = preg_replace("/[^0-9]/", "", $_GET["id"]);

        $query = "select book_filename from books where id = $id";
        $select = $db->query($query);

        if (!$select) {
            http_response_code(400);
            echo "Error get items from DB";
        } else {
            while ($row = $select->fetch_assoc()) {
                $file = $row["book_filename"];

                if(file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                    header('Content-Length: ' . filesize($file));
                    flush();
                    readfile($file);
                } else {
                    http_response_code(404);
                    echo "Error, file not exists";
                }
            }
        }
    }
}
