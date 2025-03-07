<?php
session_start();
require_once 'config.php';

$error = "";  // Store error message to show above the form

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['psw']);
    $passwordRepeat = trim($_POST['psw-repeat']);

    if (empty($username) || empty($password) || empty($passwordRepeat)) {
        $error = "Please fill out all fields.";
    } elseif (strlen($password) < 10) {
        $error = "Password must be at least 10 characters long.";
    } elseif ($password !== $passwordRepeat) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $db->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Username is already taken. Please choose a different one.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashedPassword);
            if ($stmt->execute()) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit;
            } else {
                $error = "Database error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sign-up for Diamond Real Estate!">
    <title>Sign-Up page</title>

    <link rel="stylesheet" href="cssForIndex.css">
    <link rel="stylesheet" href="signup.css">
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
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                 <a href="logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a>
            <?php else: ?>
                    <a href="login.php">Login</a>
                    <a class="active" href="signup.php">Signup</a>
            <?php endif; ?>
        </div>
    <div id="site-content" class="site-content">
        <img src="photos/fake_ad.webp" alt="Fake ad">
        <div id="logbox" class="logbox">
            <h1>Sign Up</h1>

            <p>Create an account here!</p>

            <form method="POST" action="signup.php">
                <div>
                    <label for="username"><b>Username</b></label>
                    <br>
                    <input type="text" placeholder="Enter Username" name="username">
                </div>

                <br>

                <div>
                    <label for="psw"><b>Password</b></label>
                    <br>
                    <input type="password" placeholder="Enter Password" name="psw">
                </div>

                <br>

                <div>
                    <label for="psw-repeat"><b>Reenter Password</b></label>
                    <br>
                    <input type="password" placeholder="Repeat Password" name="psw-repeat">
                </div>

            <br>
       
            <!-- Show error message if any -->
            <?php if (!empty($error)): ?>
              <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

                <button type="submit">Create account</button>
            </form>

            <div class="text-button">
                <p> Or |</p>
                <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</body>
</html>
