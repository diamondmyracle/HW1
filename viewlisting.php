<?php 
    require_once "inc/config.php" ;

    session_start() ;

    $result_name = $result_descript = $result_price = $result_author = $result_id = "" ;
    $list_id = "" ;
    $form_error = "" ;

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
    header("location: listings.php") ;
    exit ;
    }

    if(isset($_GET["id"])){
    $list_id = $_GET["id"] ;
    } 
    else{
    header("location: listings.php") ;
    exit ;
    }
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Edit a Diamond Real Estate listing">
        <title>Create new listing</title>

        <link rel="stylesheet" href="cssForIndex.css">
        <link rel="stylesheet" href="editlisting.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700&display=swap" rel="stylesheet">
    </head>

    <body>
        <p>Paragraph</p>
    </body>
</html>