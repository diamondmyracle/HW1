<?php
session_start();
require_once 'inc/config.php';

 $error = "";  // Store error message to show above the form
    $username = "" ;

    if (isset($_SESSION['validationError'])) {
        $error = $_SESSION['validationError'] ;
        unset($_SESSION['validationError']) ;
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require __DIR__ . "/inc/bootstrap.php" ;
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ;
            $uri = explode('/', $uri) ;
            if ((isset($uri[2]) && $uri[2] != 'user') || ($uri[3] != 'createUser')) {
                header("HTTP/1.1 404 Not Found");
                exit();
            }

            require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php" ;
            $objFeedController = new UserController() ;
            $strMethodName = $uri[3] ;

            $username = trim($_POST['username']);
            $password = trim($_POST['psw']);
            $passwordRepeat = trim($_POST['psw-repeat']);

            if (empty($username) || empty($password) || empty($passwordRepeat)) {
                $error = "Please fill out all fields.";
                $_SESSION['validationError'] = $error ;
                header("Location: /signup.php") ;
                exit ;
            } elseif (strlen($password) < 10) {
                $error = "Password must be at least 10 characters long.";
                $_SESSION['validationError'] = $error ;
                header("Location: /signup.php") ;
                exit ;
            } elseif ($password !== $passwordRepeat) {
                $error = "Passwords do not match.";
                $_SESSION['validationError'] = $error ;
                header("Location: /signup.php") ;
                exit ;
            } else {
                $result = $objFeedController->userExists() ;
                if (count(json_decode($result)) > 0) {
                    $error = "Username is already taken. Please choose a different one." ;
                    $_SESSION['validationError'] = $error ;
                    header("Location: /signup.php") ;
                    exit ;
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    /* $result = */$objFeedController->{$strMethodName}() ;
                    //if ($result) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $username;
                        header("Location: /index.php");
                        exit;
                    //} else {
                    //    $error = "Database error: " . $result ;
                    //}
                }
            }
        }
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

            <form method="POST" action="signup.php/user/createUser">
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
