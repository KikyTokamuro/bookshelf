<?php

namespace Kikytokamuro\Bookshelf;

use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require  "../../vendor/autoload.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create json response
function createJsonResponse(array $json, Response $old, int $status): Response 
{
    $newResponse = $old->withHeader("Content-Type", "application/json; charset=utf-8")
        ->withStatus($status);
    $newResponse->getBody()->write(json_encode($json));

    return $newResponse;
}

// Read config
$jsonConfig = file_get_contents("../../config.json");
$config = json_decode($jsonConfig, true);

if (!$config && !empty($jsonConfig)) {
    echo "Error in config.json";
    die();
}

$bookDB = new DB(
    $config["addr"], 
    $config["login"], 
    $config["password"],
    $config["dbname"]
);

if (!$bookDB->isConnected()) {
    echo "Error connected to DB";
    die();
}

// Create app
$app = AppFactory::create();

// Setup twig
$twig = Twig::create("../templates", ["cache" => "../../cache"]);
$app->add(TwigMiddleware::create($app, $twig));

// Route index page
$app->get("/", function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);

    return $view->render($response, "index.twig", []);
});

// Route upload page
$app->get("/upload", function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);

    return $view->render($response, "upload.twig", []);
});

// Route upload new 
$app->post("/uploadNew", function (Request $request, Response $response, array $args) {
    global $bookDB;

    $params = $request->getParsedBody();

    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles["file"];

    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        try {
            $filename = $bookDB->addNewBook(
                $params["title"], 
                $params["author"], 
                $uploadedFile->getClientFilename(),
                $params["date"]
            );
        } catch (Exception $e) {
            $error = [
                "status" => "error",
                "message" => $e->getMessage()
            ];

            return createJsonResponse($error, $response, 400);
        }

        $uploadedFile->moveTo($filename);

        $redirect = $response->withStatus(200)
            ->withHeader("Location", "/");
        
        return $redirect; 
    }
});

// Route delete book
$app->get("/delete/{id}", function (Request $request, Response $response, array $args) {
    global $bookDB;

    $id = $args["id"];

    try {
        $bookDB->deleteBookById($id);
    } catch (Exception $e) {
        $error = [
            "status" => "error",
            "message" => $e->getMessage()
        ];

        return createJsonResponse($error, $response, 400);
    }

    $output = ["status" => "success"];
    
    return createJsonResponse($output, $response, 200);
});

// Route get all books
$app->get("/getAll", function (Request $request, Response $response) {
    global $bookDB;

    try {
        $books = $bookDB->getBooks();
    } catch (Exception $e) {
        $error = [
            "status" => "error",
            "message" => $e->getMessage()
        ];

        return createJsonResponse($error, $response, 400);
    }

    $output = [
        "status" => "success",
        "books" => $books
    ];

    return createJsonResponse($output, $response, 200);
});

// Route get book
$app->get("/get/{id}", function (Request $request, Response $response, array $args) {
    global $bookDB;

    $id = $args["id"];

    try {
        $book = $bookDB->getBookFilenameById($id);
    } catch (Exception $e) {
        $error = [
            "status" => "error",
            "message" => $e->getMessage()
        ];

        return createJsonResponse($error, $response, 400);
    }

    $newResponse = $response->withHeader("Content-Type", "application/octet-stream")
        ->withAddedHeader("Content-Disposition",  'attachment; filename="' . basename($book) . '"')
        ->withAddedHeader("Content-Length", filesize($book))
        ->withAddedHeader("Content-Description", "File Transfer");

    $newResponse->getBody()->write(file_get_contents($book));
    
    return $newResponse;
});

// Route static files
$app->get('/static/{file}', function (Request $request, Response $response, array $args) {
    $filePath = "./static/" . preg_replace("/\//", "", $args["file"]);

    if (!file_exists($filePath)) {
        return $response->withStatus(404, 'File Not Found');
    }

    switch (pathinfo($filePath, PATHINFO_EXTENSION)) {
        case 'css':
            $mimeType = 'text/css';
            break;

        case 'js':
            $mimeType = 'application/javascript';
            break;

        default:
            $mimeType = 'text/html';
    }

    $newResponse = $response->withHeader('Content-Type', $mimeType . '; charset=UTF-8');
    $newResponse->getBody()->write(file_get_contents($filePath));

    return $newResponse;
});

$app->run();