<?php

session_start();
require_once __DIR__ . "/inc/bootstrap.php"; 
$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "POST") {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);

    if (isset($uri[2])) {
        switch ($uri[2]) {
            case "favorites":
                if (isset($uri[3])) {
                    switch ($uri[3]) {
                        case "add":
                            require_once PROJECT_ROOT_PATH . "/Controller/Api/FavoritesController.php";
                            $controller = new FavoritesController();
                            $controller->addFavorite();
                            exit();
                            break;
                        case "count":
                            require_once PROJECT_ROOT_PATH . "/Controller/Api/FavoritesController.php";
                            $controller = new FavoritesController();
                            $controller->getFavoriteCount();
                            exit();
                            break;
                        case "isFavorited":
                            require_once PROJECT_ROOT_PATH . "/Controller/Api/FavoritesController.php";
                            $controller = new FavoritesController();
                            $controller->isFavorited();
                            exit();
                            break;
                        case "userFavorites":
                            require_once PROJECT_ROOT_PATH . "/Controller/Api/FavoritesController.php";
                            $controller = new FavoritesController();
                            $controller->getUserFavorites();
                            exit();
                    }
                }
                break;
            default:
            header("HTTP/1.1 404 Not Found") ;
            exit() ;
        }
    } else {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    
}
if ($requestMethod == "DELETE"){
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);

    if (isset($uri[2])) {
        switch ($uri[2]) {
            case "favorites":
                if (isset($uri[3])) {
                    switch ($uri[3]) {
                        case "remove":
                            require_once PROJECT_ROOT_PATH . "/Controller/Api/FavoritesController.php";
                            $controller = new FavoritesController();
                            $controller->deleteFavorite();
                            exit();
                    }
                }
                break;
            default:
            header("HTTP/1.1 404 Not Found") ;
            exit() ;
            }
        } else {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
    }
?>
