
<?php
    session_start();


    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
    }
    else{
        $username = "";
    }

    // includes database connection
    require_once "config.php"; 

    // querying to fetch all listings within the listings table of our db 
    $sql = "SELECT id, username, listing_name, listing_descript, price FROM listings";
    $result = mysqli_query($db, $sql);

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
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="signup.php">Signup</a>
    <?php endif; ?>
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
            
            <ul>
                <li>
                    <div class="listing">
                        <img src="photos/listing1.webp" alt="Listing 1 photo">
                        <h2>St. Valentine's Cathedral</h2>
                        <p>
                            Author
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
                            Author
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
                            Author
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
                            Author
                            <br>
                            Past tenants said the wall-shaking ding-dongs of the clock every hour were an integral part of the charm that everyone should experience!
                        </p>
                        <div class="listing-price">
                            <img src="diamond.png" alt="diamond">
                            <p><b>10 diamonds</b></p>
                        </div>
                    </div>
                </li>

                <?php
            
                if (mysqli_num_rows($result) > 0) {
                    // loop through each row in the listings table to create a listing object/display
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<li>';
                        echo '<div class="listing">';
                        //echo '<img src="' . htmlspecialchars($row['image']) . '" alt="alttext" >';
                        echo '<img src="photos/listing1.webp" alt="Example listing photo">' ; 
                        if($username == $row['username']) {
                            echo '<a href="editlisting.php?id=' . htmlspecialchars($row['id']). '" style="text-decoration: none"><h2 style="color: rgb(7, 138, 138);">' .htmlspecialchars($row['listing_name']) . '</h2></a>';  
                        } else {
                            echo '<h2>' .htmlspecialchars($row['listing_name']) . '</h2>'; 
                        }
                        //echo '<h2>' .htmlspecialchars($row['listing_name']) . '</h2>'; 
                        echo '<p>' . htmlspecialchars($row['username']) . '<br>' . htmlspecialchars($row['listing_descript']) . '</p>';
                        echo '<div class="listing-price">';
                        echo '<img src="diamond.png" alt = "diamond">' ; 
                        echo '<p><b>' . $row['price'] . ' diamonds' . '</b><p>' ;
                        // echo '<a href="link.php?id=' . $row['id'] . '" class="view-details">View Details</a>'>; 
                        echo '</div>' ; 
                        echo '</div>' ; 
                        echo '</li>' ; 
                    }
                } else {
                    echo "<p>No listings found. We guess our listings have been getting snatched up tooooo fast!</p>" ; 
                }

                // Close connection 
                mysqli_close($db);
                ?>
            </ul>
        </div>
    </div>
</body>

