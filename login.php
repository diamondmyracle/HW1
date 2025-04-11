<?php
    session_start();
    require_once "inc/config.php";

    // Initialize variables
    $username = $password = "";
    $error = "";  // Single error message

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require __DIR__ . "/inc/bootstrap.php" ;
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ;
        $uri = explode('/', $uri) ;
        if ((isset($uri[2]) && $uri[2] != "user") || (isset($uri[3]) && $uri[3] != "login")) {
            header("HTTP/1.1 404 Not Found") ;
            exit() ;
        }

        $json = file_get_contents("php://input") ;
        $data = json_decode($json, true) ;

        $username = trim($data["username"]) ;
        $password = trim($data["psw"]) ;

        require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php" ;
        $objFeedController = new UserController() ;
        $objFeedController->loginUser() ;

        // // Check for empty fields
        // if (empty($username) || empty($password)) {
        //     $error = "Please fill in all fields.";
        // } else {

        // }
    } else {
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            header("location: index.php");
            exit;
        }
    }
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


            <form method="POST" action="login.php" id="loginForm">
                <div>
                    <label for="username"><b>Username</b></label>
                    <br>
                    <input type="text" placeholder="Enter Username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                </div>

                <br>

                <div>
                    <label for="psw"><b>Password</b></label>
                    <br>
                    <input type="password" placeholder="Enter Password" name="psw">
                </div>

         <br>
            <!-- Single error message shown above the form -->
                <p style="color: red; font-weight: bold;" id="error-msg"></p>

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

<script>
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault() ;

        const form = event.target ;
        const formData = new FormData(form) ;

        const data = {} ;
        formData.forEach((value, key) => {
            data[key] = value ;
        }) ;

        const username = data["username"] ;
        const psw = data["psw"] ;

        const errorMsgBox = document.getElementById("error-msg") ;

        //Check if form information is valid
        if (username === "" || psw === "") {
            errorMsgBox.innerText = "Please fill out all fields." ;
            return ;
        }

        //Sign up the user with the data
        loginUser(data) ;
    }) ;

    async function loginUser(data) {
        const errorMsgBox = document.getElementById("error-msg") ;

        //Create the user
        const login = await fetch("login.php/user/login", {
            method: "POST",
            header: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        }).catch(err => console.error("Fetch error:", err)) ;

        const loginJson = await login.json() ;

        //Was it successful?
        if (loginJson.status === "success") {
            window.location.href = "/index.php" ;
            return ;
        } else if (loginJson.status === "failure") {
            errorMsgBox.innerText = "Invalid username or password." ;
        } else {
            alert(loginJson.message || "Login failed.") ;
        }
    }
</script>
