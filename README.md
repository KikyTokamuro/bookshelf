# Bookshelf
Simple service for storing books. Was implemented in order to understand the PHP programming language.

## Install
- Install dependencys:
```bash
composer install
```
- Create "books" directory
```bash
mkdir books
```
- Create a database for storing books:
```bash
mysql -uroot -proot books < books.sql # Put your password config.json
```
- Start server:
```bash
composer start
```
- Open http://localhost:8080/