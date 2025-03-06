<!--
    Login page for HW1
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Log in to Diamond Real Estate!">
    <title>Login page</title>

    <link rel="stylesheet" href="cssForIndex.css">
    <link rel="stylesheet" href="login.css">
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
        <a class="active" href="#home">Login</a>
        <a href="signup.php">Signup</a>
    </div>
    
  <div id="site-content" class="site-content">
    <div id="logbox" class="logbox">
      <h1>Log in</h1>
      <p>For returning users!</p>

      <div>
        <label for="username"><b>Username</b></label>
        <br>
        <input type="text" placeholder="Enter Username" name="username" required>
      </div>

      <br>

      <div>
        <label for="pswd"><b>Password</b></label>
        <br>
        <input type="password" placeholder="Enter Password" name="pswd" required>
      </div>

      <br>

      <button type="submit">Login</button>

      <br>

      <label>
        <input type="checkbox" checked="checked" name="remember"> Remember Me
      </label>

      <div class="text-button">
        <p> Or |</p>
        <a href="signup.php">Signup</a>
      </div>
    </div>
  </div>
</body>
