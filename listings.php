
<?php
    session_start();


    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
    }
    else{
        $username = "";
    }

    // includes database connection
    require_once "inc/config.php";

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uriParts = explode('/', trim($uri, '/'));

    $reqMethod = $_SERVER["REQUEST_METHOD"] ;
    if ($reqMethod == "GET") //if the method is POST, then check for user/create endpoint
    {
        if (isset($uriParts[1]) && $uriParts[1] === 'listing' && isset($uriParts[2]) && $uriParts[2] === 'list') {
            require __DIR__ . "/inc/bootstrap.php" ;
            require PROJECT_ROOT_PATH . "/Controller/Api/ListingController.php" ;
    
            $controller = new ListingController() ;
            $controller->listAction() ;
            exit() ;
        }
    } 
    elseif ($reqMethod == "POST")
    {
        if (isset($uriParts[1]) && $uriParts[1] === 'listing' && isset($uriParts[2]) && $uriParts[2] === 'id') {
            require __DIR__ . "/inc/bootstrap.php" ;
            require PROJECT_ROOT_PATH . "/Controller/Api/ListingController.php" ;
    
            $controller = new ListingController() ;
            $controller->listingByID() ;
            exit() ;
        }
    }

?>

<!--
    Listings page for HW1
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View the newest Minecraft listings on Diamond Real Estate!">
    <title>Listings on Diamond Real Estate</title>

    <link rel="stylesheet" href="cssForIndex.css">
    <link rel="stylesheet" href="listings.css">
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
        <a href="viewprofile.php?username=<?php echo $_SESSION['username']?>">MyProfile</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="signup.php">Signup</a>
    <?php endif; ?>

    <script>
        function escapeHTML(str) {
            return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;")    ;
        }

        fetch('/listings.php/listing/list')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById("listing-list") ;

            data.forEach(item => {
                const listItem = document.createElement("li") ;
                
                if ("<?php echo $username ?>" == item.username) {
                    listItem.innerHTML = `
                    <div class="listing">
                            <img src="uploads/${escapeHTML(item.image)}" alt="Listing 1 photo">
                            <a href="viewlisting.php?id=${item.id}" style="text-decoration: none">
                                <h2 style="color: rgb(7, 138, 138);">${escapeHTML(item.listing_name)}</h2>
                            </a>
                            <p>
                                ${escapeHTML(item.username)}
                                <br>
                                ${escapeHTML(item.listing_descript)}
                            </p>
                            <div class="listing-price">
                                <img src="diamond.png" alt="diamond">
                                <p><b>${escapeHTML(item.price)}</b></p>
                            </div>
                        </div>` ;
                } else {
                    listItem.innerHTML = `
                    <div class="listing">
                            <img src="uploads/${escapeHTML(item.image)}" alt="Listing 1 photo">
                            <a href="viewlisting.php?id=${item.id}" style="text-decoration: none">
                                <h2 style="color: #000000" >${escapeHTML(item.listing_name)}</h2>
                            </a>
                            <p>
                                ${escapeHTML(item.username)}
                                <br>
                                ${escapeHTML(item.listing_descript)}
                            </p>
                            <div class="listing-price">
                                <img src="diamond.png" alt="diamond">
                                <p><b>${escapeHTML(item.price)}</b></p>
                            </div>
                        </div>` ;
                }

                container.appendChild(listItem) ;
            }) ;
        })
        .catch(err =>{
            console.error("Failed to load listings:", err) ;
        })
    </script>
</div>

    <div id="site-content" class="site-content">
        <div id="listings-group" class="listings-group">
            <h1>Listings</h1>

            <?php
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                        echo '<a href="newlisting.php" style="border-radius: .5em ; text-decoration: none ; margin: 10px ; margin-bottom: 15px ; padding: 2px ; border: 2px solid rgb(30, 209, 30) ; background-color: rgb(30, 209, 30) ; color: white ;">Create new listing</a>' ;
                        echo '<br>' ;
                }
            ?>
            
            <ul id="listing-list">
                <li>
                <div class="listing">
                        <img src="photos/listing1.webp" alt="Listing 1 photo">
                        <h2>St. Valentine's Cathedral</h2>
                        <p>
                            Saint Val
                            <br>
                            Perfect for an eternal date-night...
                        </p>
                        <div class="listing-price">
                            <img src="diamond.png" alt="diamond">
                            <p><b>10 diamonds</b></p>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="listing">
                        <img src="photos/listing2.webp" alt="Listing 2 photo">
                        <h2>2-Story 1 bedroom house</h2>
                        <p>
                            Steve
                            <br>
                            Get cozy!
                        </p>
                        <div class="listing-price">
                            <img src="diamond.png" alt="diamond">
                            <p><b>10 diamonds</b></p>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="listing">
                        <img src="photos/listing3.webp" alt="Listing 3 photo">
                        <h2>Barn</h2>
                        <p>
                            Farmer Alex
                            <br>
                            Moooooo
                        </p>
                        <div class="listing-price">
                            <img src="diamond.png" alt="diamond">
                            <p><b>10 diamonds</b></p>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="listing">
                        <img src="photos/listing4.webp" alt="Listing 4 photo">
                        <h2>Neo-classical clocktower</h2>
                        <p>
                            Hugo Cabret 
                            <br>
                            Past tenants said the wall-shaking ding-dongs of the clock every hour were an integral part of the charm that everyone should experience!
                        </p>
                        <div class="listing-price">
                            <img src="diamond.png" alt="diamond">
                            <p><b>10 diamonds</b></p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</body>


</body>


