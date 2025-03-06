<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create new Diamond Real Estate listing">
    <title>Create new listing</title>

    <link rel="stylesheet" href="cssForIndex.css">
    <link rel="stylesheet" href="editlisting.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar">
    <a href="index.php">Home</a>
    <a href="listings.php">Listing</a>
    <a href="#faq">FAQ</a>

    <?php if (isset($_SESSION['username'])): ?>
        <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="signup.php">Signup</a>
    <?php endif; ?>
    
  <div id="site-content" class="site-content">
    <div id="editbox" class="editbox">
      <h1>Edit listing</h1>
      <p>Edit the listing for your Minecraft house</p>

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

      <button type="submit">Save changes</button>
      <br>
      <br>
      <button type="submit" id="delete">Delete listing</button>
    </div>
  </div>
</body>
