<!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
        <title>BookShelf</title>
        <link rel="stylesheet" href="https://unpkg.com/terminal.css@0.7.1/dist/terminal.min.css" />
        <link href="https://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext" rel="stylesheet">
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
                        <tr>
                            <td>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</td>
                            <td>qqqqqqqq</td>
                            <td>2012</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>
    </body>
</html>
