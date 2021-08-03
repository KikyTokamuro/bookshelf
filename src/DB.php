<?php

namespace Kikytokamuro\Bookshelf;

use Exception;

class DB
{    
    /**
     * @var mysqli
     */
    private $db;
    
    /**
     * @var bool
     */
    private $connect = true;
    
    /**
     * DB construct
     *
     * @param  string $addr
     * @param  string $login
     * @param  string $password
     * @param  string $dbname
     */
    public function __construct(
        string $addr, 
        string $login, 
        string $password, 
        string $dbname
    ) {
        $this->db = new \mysqli($addr, $login, $password, $dbname);
        
        if ($this->db->connect_error) {
            $this->connect = false;
        }
    }
        
    /**
     * DB destruct
     */
    public function __destruct()
    {
        $this->db->close();
    }

    /**
     * Get all books from db
     *
     * @return array|null
     */
    public function getBooks(): array
    {
        $sql = "select id, title, author, pub_date from books";
        $select = $this->db->query($sql);

        if (!$select) {
            throw new Exception("Error get items from DB");
        } else {
            return $select->fetch_all(MYSQLI_ASSOC);
        }
    }
        
    /**
     * Get book filename by id
     *
     * @param  string $id
     * @return string
     */
    public function getBookFilenameById(string $id): string
    {
        $id = preg_replace("/[^0-9]/", "", $id);

        $query = "select book_filename from books where id = $id";
        $select = $this->db->query($query);

        if (!$select) {
            throw new Exception("Error get items from DB");
        } else {
            $row = $select->fetch_assoc();
            return $row["book_filename"];
        }
    }
    
    /**
     * Delete book by id
     *
     * @param  string $id
     * @return void
     */
    public function deleteBookById(string $id): void
    {
        $id = preg_replace("/[^0-9]/", "", $id);

        $sql = "select book_filename from books where id = $id";
        $select = $this->db->query($sql);

        if (!$select) {
            throw new Exception("Error get items from DB");
        } else {
            while ($row = $select->fetch_assoc()) {
                $file = $row["book_filename"];
                unlink($file);
            }

            $sql = "delete from books where id = $id";

            if (!$this->db->query($sql)) {
                throw new Exception("Error delete item from DB");
            }
        }
    }
        
    /**
     * Add new book
     *
     * @param  string $title
     * @param  string $author
     * @param  string $tmpfile
     * @param  string $date
     * @return string
     */
    public function addNewBook(
        string $title, 
        string $author, 
        string $tmpfile, 
        string $date
    ): string {
        $title = htmlentities($title);
        $author = htmlentities($author);
        $filename = "../../books/" . basename($tmpfile);
        $date = preg_replace("/[^0-9]/", "", $date);

        $sql = "insert into books (title, author, pub_date, book_filename) values 
                ('$title', '$author', '$date', '$filename')";

        if (!$this->db->query($sql)) {
            throw new Exception("Error add new item to DB");
        }

        return $filename;
    }

    /**
     * DB is connected
     *
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->connect;
    }
}