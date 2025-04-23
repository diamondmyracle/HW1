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
                            <img src="uploads/50819.jpg" alt="Listing 1 photo">
                        </div>
                    </li>

                    <li id="li-info" class="li-info">
                        <div id="listing-info">
                            <h1 id="listing-name">Listing Name</h1>
                            <p id="listing-author">by <b><span id="seller" class="seller">Listing Author</span></b></p>
                            <br>
                            <p id="listing-descript">
                                Description
                            </p>
                        </div>
                    </li>

                    <li id="li-sell-info" class="li-sell-info">
                        <div id="listing-sell-info" class="listing-sell-info">
                            <div class="listing-price">
                                    <img src="diamond.png" alt="diamond">
                                    <p>
                                        <b><span id="cost" class="cost">0</span></b> diamonds
                                    </p>
                            </div>
                            <div id="watch-info" class="watch-info">
                                <ul>
                                    <li>
                                        1 left!
                                        <img src="fire.gif" alt="fire">
                                    </li>
                                    <li>
                                        Greater than or equal to 1 watching!
                                    </li>
                                    <li>
                                        <span id="num-favourited">0<span> people favourited!
                                    </li>
                                </ul>
                            </div>

                            <button type="button" class="buy">Buy now!</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </body>
</html>

<script>
    function changeTitle(str) {
        document.title = str
    }
</script>

<script>
    function escapeHTML(str) {
    return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;") ;
    }

    const reqBody = {} ;
    reqBody["id"] = <?php echo $list_id ?> ;

    fetch("listings.php/listing/id", {
        method: "POST",
        header: {
        "Content-Type": "application/json",
        "Accept": "application/json"
        },
        body: JSON.stringify(reqBody)
    })
    .then(response => response.json())
    .then(data => {
    const imageContainer = document.getElementById("listing-image") ;
    imageContainer.innerHTML = `
        <img src="/uploads/${escapeHTML((data[0]).image)}" alt="Listing 1 photo">
    ` ;

    const nameContainer = document.getElementById("listing-name") ;
    nameContainer.innerHTML = `
        ${escapeHTML((data[0]).listing_name)}
    ` ;

    changeTitle(escapeHTML((data[0]).listing_name) + " - Diamond Real Estate") ;

    const authorContainer = document.getElementById("seller") ;
    authorContainer.innerHTML = `
        ${escapeHTML((data[0]).username)}
    ` ;

    const descContainer = document.getElementById("listing-descript") ;
    descContainer.innerHTML = `
        ${escapeHTML((data[0]).listing_descript)}
    ` ;

    const priceContainer = document.getElementById("cost") ;
    priceContainer.innerHTML = `
        ${escapeHTML((data[0]).price)}
    `;
    })
    .catch(err =>{
        console.error("Failed to load listings, bitch:", err) ;
    })
</script>