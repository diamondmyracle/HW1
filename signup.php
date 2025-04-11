<?php
session_start();
    require_once 'inc/config.php';

    $username = "" ;

    $reqMethod = $_SERVER["REQUEST_METHOD"] ;
    if ($reqMethod == "POST") //if the method is POST, then check for user/create endpoint
    {
        require __DIR__ . "/inc/bootstrap.php" ;
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ;
        $uri = explode('/', $uri) ;
        if ((isset($uri[2]) && $uri[2] != "user") || (isset($uri[3]) && $uri[3] != "create")) {
            if ((isset($uri[2]) && $uri[2] != "user") || (isset($uri[3]) && $uri[3] != "exists")) {
                header("HTTP/1.1 404 Not Found") ;
                exit() ;
            }
            require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php" ;
            $objFeedController = new UserController() ;
            $objFeedController->userExists() ;
            exit() ;
        }

        require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php" ;
        $objFeedController = new UserController() ;
        $objFeedController->createUser() ;
        exit ;
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

            <form method="POST" action="signup.php/user/createUser" id="signupForm">
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
       
              <p style="color: red; font-weight: bold;" id="error-msg"></p>

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

<script>
    document.getElementById("signupForm").addEventListener("submit", function(event) {
        event.preventDefault() ;

        const form = event.target ;
        const formData = new FormData(form) ;

        const data = {} ;
        formData.forEach((value, key) => {
            data[key] = value ;
        }) ;

        const username = data["username"] ;
        const psw = data["psw"] ;
        const psw_repeat = data["psw-repeat"] ;

        const errorMsgBox = document.getElementById("error-msg") ;

        //Check if form information is valid
        if (username === "" || psw === "" || psw_repeat === "") {
            errorMsgBox.innerText = "Please fill out all fields." ;
            return ;
        } else if (psw.length < 10) {
            errorMsgBox.innerText = "Password must be at least 10 characters." ;
            return ;
        } else if (psw != psw_repeat) {
            errorMsgBox.innerText = "Passwords must match." ;
            return ;
        }

        //Sign up the user with the data
        signupUser(data) ;
    }) ;

    async function signupUser(data) {
        const errorMsgBox = document.getElementById("error-msg") ;

        //Test to see if the username is taken
        const exists = await fetch("signup.php/user/exists", {
            method: "POST",
            header: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        }).catch(err => console.error("Fetch error:", err)) ;

        const existsJson = await exists.json() ;

        //Is it taken?
        if (existsJson.status === "success") {
            if (existsJson.data.length > 0) {
                errorMsgBox.innerText = "Username already taken." ;
                return ;                    
            }
        } else {
            alert(existsJson.message || "User GET failed") ;
            errorMsgBox.innerText = "Database error." ;
            return ;
        }

        //Create the user
        const signup = await fetch("signup.php/user/create", {
            method: "POST",
            header: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        }).catch(err => console.error("Fetch error:", err)) ;

        const signupJson = await signup.json() ;

        //Was it successful?
        if (signupJson.status === "success") {
            //should probably fetch something like login.php/login
            window.location.href = "/index.php" ;
            return ;
        } else {
            alert(signupJson.message || "Signup failed.") ;
        }
    }
</script>
