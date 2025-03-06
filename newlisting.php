<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create new Diamond Real Estate listing">
    <title>Create new listing</title>

    <link rel="stylesheet" href="cssForIndex.css">
    <link rel="stylesheet" href="newlisting.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar">
            <a class="active" href="#home">Home</a>
            <a href="listings.php">Listing</a>
            <a href="index.php#faq">FAQ</a>
            <?php if (!empty($username)): ?>
                 <a href="logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a>
            <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Signup</a>
            <?php endif; ?>
        </div>
    
  <div id="site-content" class="site-content">
    <div id="createbox" class="createbox">
      <h1>Create new listing</h1>
      <p>Put your amazing Minecraft house on the market!</p>

      <div>
        <label for="listing name">Listing name</label>
        <br>
        <input type="text" placeholder="Listing name" name="listing name" maxlength="32" required>
      </div>

      <br>

      <div>
        <label for="description">Listing description</label>
        <br>
        <textarea placeholder="Listing description" name="description" style="resize: none" maxlength="256" required></textarea>
      </div>

      <br>

      <div>
        <label for="listing price">Listing price</label>
        <br>
        <input type="number" placeholder="Listing price" name="listing price" max="2048" required>
      </div>

      <br>

      <button type="submit">Create listing</button>
    </div>
  </div>
</body>
