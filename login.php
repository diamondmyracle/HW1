<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();  // Start output buffering
session_start();

// Check if already logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

require_once "inc/config.php";

// Initialize variables
$username = $password = "";
$error = "";  // Single error message

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');

    // Check for empty fields
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Proceed with database check
        $sql = "SELECT username, password FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Start session and redirect
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;
                            header("location: index.php");
                            exit;
                        } else {
                            $error = "Invalid username or password.";
                        }
                    }
                } else {
                    $error = "Invalid username or password.";
                }
            } else {
                $error = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($db);
}
ob_end_flush();
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


            <form method="POST" action="login.php">
                <div>
                    <label for="username"><b>Username</b></label>
                    <br>
                    <input type="text" placeholder="Enter Username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                </div>

                <br>

                <div>
                    <label for="password"><b>Password</b></label>
                    <br>
                    <input type="password" placeholder="Enter Password" name="password">
                </div>

         <br>
            <!-- Single error message shown above the form -->
             <?php if (!empty($error)): ?>
                <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

                <button type="submit">Login</button>
            </form>

            <div class="text-button">
                <p> Or |</p>
                <a href="signup.php">Signup</a>
            </div>
        </div>
    </div>
</body>
</html>
