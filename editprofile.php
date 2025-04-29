<?php 
  require_once "inc/config.php";

  session_start();

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: listings.php");
    exit;
  }

  if(!isset($_GET["username"]) || $_SESSION["username"] !== $_GET["username"]){
    header("location: listings.php");
    exit;
  }

  $username = $_GET["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Profile</title>

    <link rel="stylesheet" href="cssForIndex.css">
    <link rel="stylesheet" href="editlisting.css"> <!-- reusing your editlisting styles -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="listings.php">Listing</a>
        <a href="index.php#faq">FAQ</a>

        <?php if (isset($_SESSION['username'])): ?>
            <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php">Signup</a>
        <?php endif; ?>
    </div>

    <script>
    function escapeHTML(str) {
      return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;") ;
    }

    const reqBody = {} ;
    reqBody["username"] = <?php echo $user_name ?> ;

    fetch("index.php/profile/id", {
        method: "POST",
        header: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify(reqBody)
      })
    .then(response => response.json())
    .then(data => {
      const descContainer = document.getElementById("user-desc") ;
      descContainer.innerHTML = `
        <label for="user_desc">User description</label>
        <br>
        <textarea placeholder="User description" name="user_descript" style="resize: none" maxlength="256">${escapeHTML((data[0]).user_descript)}</textarea>
      `;
    })
    .catch(err =>{
        console.error("Failed to load profile:", err) ;
    })
    </script>

    <div id="site-content" class="site-content">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ; ?>?username=<?php echo htmlspecialchars($_GET["username"]) ; ?>" method="post" id="editProfileForm">
            <div id="editbox" class="editbox">
                <h1>Edit Profile</h1>
                <p>Update your profile description or password</p>

                <div id="user-desc">
                    
                </div>

                <br>

                <span style="color:red" id="error-msg"></span>

                <button type="submit" name="save" id="save-button">Save changes</button>

            </div>
        </form>
    </div>


<script>
    const username = <?php echo json_encode($username); ?>;
    let whichButton = null ;

    document.getElementById("save-button").addEventListener("click", () => {
        whichButton = "save" ;
    }) ;
    
    document.getElementById("editProfileForm").addEventListener("submit", function(event){
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    const user_descript = formData.get("user_descript");

    const errorMsgBox = document.getElementById("error-msg");
    errorMsgBox.innerText = "";

    if (user_descript.trim() === "") {
        errorMsgBox.innerText = "Profile description cannot be empty.";
        return;
    }

    const data = {  // <- you forgot this part
        username: username,
        user_descript: user_descript
    };

    saveProfile(data); // Now we call it properly
    });


    async function saveProfile(data){
        const updated = await fetch("index.php/profile/update", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        }).catch(err => console.error("Fetch error:", err));

        const updatedJson = await updated.json();

        if (updatedJson.success) {
            window.location.href = "viewprofile.php?username=" + encodeURIComponent(username);
            return;
        } else {
            alert(updatedJson.message || "Edit failed.");
        }
    }

    </script>
</body>
</html>

