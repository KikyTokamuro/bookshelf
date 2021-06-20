<!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
        <title>BookShelf</title>
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
                            <a href="./index.php" class="menu-item active">
                                <span>Books</span>
                            </a>
                        </li>
                        <li property="itemListElement" typeof="ListItem">
                            <a href="./upload.php" property="item" typeof="WebPage" class="menu-item">
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
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Publication date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        include "./config.php";

                        $db = new mysqli($addr, $login, $password, $dbname);

                        if ($db->connect_error) {
                            echo '<div class="terminal-alert terminal-alert-error">Failed connecting to DB</div>';
                        } else {
                            $query = "select id, title, author, pub_date from books";
                            $select = $db->query($query);

                            if (!$select) {
                                echo '<div class="terminal-alert terminal-alert-error">Error get items from DB</div>';
                            } else {
                                while ($row = $select->fetch_assoc()) {
                                    $tr = sprintf(
                                        "<tr><td><a href='./get.php?id=%s'>%s</a></td><td>%s</td><td>%s</td></tr>",
                                        $row["id"], $row["title"], $row["author"], $row["pub_date"]
                                    );

                                    echo $tr;
                                }
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </section>
        </div>
    </body>
</html>
