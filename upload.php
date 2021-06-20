<?php
    include "./config.php";

    if(isset($_POST["submit"])) {
        $db = new mysqli($addr, $login, $password, $dbname);
       
        if ($db->connect_error) {
            echo '<div class="terminal-alert terminal-alert-error">Connection failed: ' 
                . mysqli_connect_error() . '</div>';
        } else {
            $title = str_replace(['"',"'", "<", ">"], "", $_POST["title"]);
            $author = str_replace(['"',"'", "<", ">"], "", $_POST["author"]);
            $filename = "./books/" . basename($_FILES["file"]["name"]);
            $date = preg_replace("/[^0-9]/", "", $_POST["date"]);

            if (move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
                $query = "REPLACE into books (title, author, pub_date, book_filename) VALUES 
                          ('$title', '$author', '$date', '$filename')";
                
                $insert = $db->query($query);

                if ($insert) {
                    echo '<div class="terminal-alert terminal-alert-primary">Successful</div>';
                }
            } else {
                echo '<div class="terminal-alert terminal-alert-error">Error upload</div>';
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>BookShelf - Upload</title>
        <link rel="stylesheet" href="https://unpkg.com/terminal.css@0.7.1/dist/terminal.min.css" />
        <link href="https://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext" rel="stylesheet">
        <script src="./js/script.js"></script>
    </head>
    <body class="terminal">
        <div class="container">
            <div class="terminal-nav">
                <div class="terminal-logo">
                    <div class="logo terminal-prompt">
                        <a href="./index.php">BookShelf</a>
                    </div>
                </div>
                <nav class="terminal-menu">
                    <ul>
                        <li>
                            <a href="./index.php" class="menu-item">
                                <span>Books</span>
                            </a>
                        </li>
                        <li property="itemListElement" typeof="ListItem">
                            <a href="./upload.php" property="item" typeof="WebPage" class="menu-item active">
                                <span property="name">Upload</span>
                            </a>
                        </li>
                        <li property="itemListElement" typeof="ListItem">
                            <a href="./remove.php" property="item" typeof="WebPage" class="menu-item">
                                <span property="name">Remove</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="container">
            <section>
                <form action="./upload.php" method="POST" enctype="multipart/form-data">
                    <fieldset>
                        <legend>New book</legend>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input id="title" name="title" type="text" required placeholder="...">
                        </div>
                        <div class="form-group">
                            <label for="author">Author:</label>
                            <input id="author" name="author" type="text" required placeholder="...">
                        </div>
                        <div class="form-group">
                            <label for="date">Pulication year:</label>
                            <input id="date" name="date" type="text" required placeholder="...">
                        </div>
                        <div class="form-group">
                            <label for="file">File:</label>
                            <input id="file" name="file" type="file" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-default" type="submit" role="button" name="submit" id="submit">Upload</button>
                        </div>
                    </fieldset>
                </form>
            </section>
        </div>
    </body>
</html>