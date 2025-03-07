


<?php



error_reporting(E_ALL);
ini_set('display_errors', 1);


//Initialize the session 
ob_start();  // Start output buffering
session_start();

//Check if the user is already logged, 
//      if yes, then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty 
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty 
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";
    
        if ($stmt = mysqli_prepare($db, $sql)) {
            // Set parameters
            $param_username = $username;
    
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
    
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);
    
                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;
    
                            // Redirect user to index/home page
                            header("location: index.php");
                            exit;
                        }
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later!";
            }
    
            // Close statement
            mysqli_stmt_close($stmt);
        }
    
        // ❗ Always set a generic error message when login fails (fix #4)
        $login_err = "Invalid username or password.";
    }
    
    // Close connection
    mysqli_close($db);

}

ob_end_flush();  // Send the output and end buffering
?>




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

    <?php if (isset($_SESSION['username'])): ?>
        <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
    <?php else: ?>
        <a class="active" href="login.php">Login</a>
        <a href="signup.php">Signup</a>
    <?php endif; ?>
</div>
    
  <div id="site-content" class="site-content">
    <div id="logbox" class="logbox">
      <h1>Log in</h1>
      <p>For returning users!</p>



      <form method = "POST" action="login.php">
        <?php 
        if(!empty($login_err)){
            echo '<div class="error" style="color: red;">' . $login_err . '</div>';
        }
        ?>
      <div>
        <label for="username"><b>Username</b></label>
        <br>
        <input type="text" placeholder="Enter Username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        <span style="color: red;"><?php echo $username_err; ?></span>
      </div>

      <br>

      <div>
        <label for="pswd"><b>Password</b></label>
        <br>
        <input type="password" placeholder="Enter Password" name="password" required>
        <span style="color: red;"><?php echo $password_err; ?></span>
      </div>

      <br>

      <button type="submit">Login</button>

</form>


      <div class="text-button">
        <p> Or |</p>
        <a href="signup.php">Signup</a>
      </div>
    </div>
  </div>
</body>




