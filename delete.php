<?php

// Delete books from DB

if(isset($_GET["id"])) {
    include "./config.php";

    $db = new mysqli($addr, $login, $password, $dbname);

    if ($db->connect_error) {
        http_response_code(400);
        echo "Failed connecting to DB";
    } else {
        $id = preg_replace("/[^0-9]/", "", $_GET["id"]);

        $sql = "select book_filename from books where id = $id";
        $select = $db->query($sql);

        if (!$select) {
            http_response_code(400);
            echo "Error get items from DB";
        } else {
            while ($row = $select->fetch_assoc()) {
                $file = $row["book_filename"];
                unlink($file);
            }

            $sql = "delete from books where id = $id";

            if ($db->query($sql)) {
                echo "Successful";
            } else {
                http_response_code(400);
                echo "Error delete file from DB";
            }
        }

        $db->close();
    }
}
