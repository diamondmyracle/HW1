<?php 
  require_once "inc/config.php";
  session_start();

  $username_ = "";
  $form_error = "";

  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: listings.php");
    exit;
  }

  $username_ = $_SESSION["username"];
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
        .replace(/'/g, "&#039;");
    }

    const reqBody = {
        username: "<?php echo htmlspecialchars($_SESSION['username']); ?>"
    };

    fetch("index.php/user/update", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify(reqBody)
    })
    .then(response => response.json())
    .then(data => {
        const descContainer = document.getElementById("user-desc");
        descContainer.innerHTML = `
            <label for="user_descript">User description</label>
            <br>
            <textarea id="user_descript" placeholder="User description" name="user_descript" style="resize: none" maxlength="256">${escapeHTML(data[0].user_descript)}</textarea>
        `;
    })
    .catch(err => {
        console.error("Failed to load profile:", err);
    });
    </script>

    <div id="site-content" class="site-content">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="editProfileForm">
            <div id="editbox" class="editbox">
                <h1>Edit Profile</h1>
                <p>Update your profile description or password</p>

                <div id="user-desc"></div>
                <br>
                <span style="color:red" id="error-msg"></span>
                <button type="submit" name="save" id="save-button">Save changes</button>
            </div>
        </form>
    </div>

<script>
    let whichButton = null;

    document.getElementById("save-button").addEventListener("click", () => {
        whichButton = "save";
    });

    document.getElementById("editProfileForm").addEventListener("submit", function(event) {
        event.preventDefault();
        if (whichButton === "save") {
            const form = event.target;
            const formData = new FormData(form);
            const data = {};

            formData.forEach((value, key) => {
                data[key] = value;
            });

            data["user"] = "<?php echo htmlspecialchars($username_); ?>";
            const user_descript = data["user_descript"];
            const errorMsgBox = document.getElementById("error-msg");
            errorMsgBox.innerText = "";

            if (user_descript === "") {
                errorMsgBox.innerText = "Please fill out all fields.";
                return;
            }

            whichButton = null;
            saveProfile(data);
        } 
        whichButton = null;
    });

    async function saveProfile(data) {
        try {
            const updated = await fetch("index.php/user/update", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(data)
            });

            const updatedJson = await updated.json();

            if (updatedJson.success) {
                window.location.href = "/viewprofile.php?username=" + data["user"];
            } else {
                alert(updatedJson.message || "Edit failed.");
            }
        } catch (err) {
            console.error("Fetch error:", err);
        }
    }
</script>
</body>
</html>

