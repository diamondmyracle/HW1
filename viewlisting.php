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
        <link rel="stylesheet" href="viewlisting.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a class="active" href="listings.php">Listing</a>
            <a href="index.php#faq">FAQ</a>

            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php">Signup</a>
            <?php endif; ?>
        </div>

        <div class="site-content">
            <div id="listing" class="listing">
                <ul>
                    <li id="li-img" class="li-img">
                        <div id="listing-image" class="listing-image">
                            <img src="photos/listing1.webp" alt="Listing 1 photo">
                        </div>
                    </li>

                    <li id="li-info" class="li-info">
                        <div id="listing-info">
                            <h1 id="listing-name">Listing Name</h1>
                            <p id="listing-author">by <span id="seller" class="seller">Listing Author</span></p>
                            <br>
                            <p id="listing-descript">
                                Perfect for an eternal date-night...
                            </p>
                        </div>
                    </li>

                    <li id="li-sell-info" class="li-sell-info">
                        <div id="listing-sell-info" class="listing-sell-info">
                            <div class="listing-price">
                                    <img src="diamond.png" alt="diamond">
                                    <p>
                                        <span id="cost" class="cost"><b>10</b></span> diamonds
                                    </p>
                            </div>
                            <ul>
                                <li>
                                    1 left!
                                </li>
                                <li>
                                    Greater than or equal to 1 watching!
                                </li>
                                <li>
                                    <span id="num-favourited">0<span> people favourited!
                                </li>
                            </ul>

                            <button type="button" class="buy">Buy now!</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </body>
</html>