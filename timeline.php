
<?php
    session_start();
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
    }
    else{
        $username = "";
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" /> 
        <meta name="viewport" content="width=device-width" />
        <meta name="description" content="View the timeline for Diamond Real Estate!">
        <title>Minecraft Real Estate Timeline</title>
        <link href="cssForIndex.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="listings.php">Listing</a>
            <a href="index.php#faq">FAQ</a>
            <?php if (!empty($username)): ?>
                 <a href="logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a>
                 <a href="viewprofile.php?username=<?php echo $_SESSION['username']?>">MyProfile</a>
            <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Signup</a>
            <?php endif; ?>
        </div>

        <div id="timelineContent">
            <h1>
                What's to Come!
            </h1>
            <br>
            <ul>
                <li>Premium Buyers: Video Tours of Each Listing</li>
                <br>
                <li>Save Listings Button (heart icon)</li>
                <br>
                <li>Alert Listings Button (bell icon)</li>
                <br>
                <li>Buyers: Comment Section on Listings</li>
                <br>
                <li>Make Your Own Listings!</li>
              </ul>
        </div>
    </body>
</html>
