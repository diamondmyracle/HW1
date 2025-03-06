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
        <a class="active" href="listings.php">Listing</a> <!-- keeping this as listings.html as sometime people like to reload that way -->
        <a href="index.php#faq">FAQ</a>
        <a href="login.php">Login</a>
        <a href="signup.php">Signup</a>
    </div>

    <div id="site-content" class="site-content">
        <div id="listings-group" class="listings-group">
            <h1>Listings</h1>
            
            <ul>
                <li>
                    <div class="listing">
                        <img src="photos/listing1.webp" alt="Listing 1 photo">
                        <h2>St. Valentine's Cathedral</h2>
                        <p>
                            Author
                            <br>
                            Some BS
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
                            Author
                            <br>
                            Some BS
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
                            Author
                            <br>
                            Some BS
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
                            Author
                            <br>
                            Some BS
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
