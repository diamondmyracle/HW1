<?php 
    require_once "inc/config.php";
    session_start();

    $list_id = "";



    if (isset($_GET["id"])) {
        $list_id = $_GET["id"];
    } else {
        header("location: listings.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View a Diamond Real Estate listing">
    <title>View Listing</title>

    <link rel="stylesheet" href="cssForIndex.css">
    <link rel="stylesheet" href="viewlisting.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a class="active" href="listings.php">Listing</a>
        <a href="index.php#faq">FAQ</a>

        <?php if (isset($_SESSION['username'])): ?>
            <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php">Signup</a>
        <?php endif; ?>
    </div>

    <div class="site-content">
        <div id="listing" class="listing">
            <ul>
                <li class="li-img">
                    <div id="listing-image" class="listing-image">
                        <!-- Image will be injected here -->
                    </div>
                </li>

                <li class="li-info">
                    <div id="listing-info">
                        <h1 id="listing-name">Loading...</h1>
                        <p id="listing-author">by <b><span id="seller" class="seller">Loading...</span></b></p>
                        <br>
                        <p id="listing-descript">Loading...</p>
                        <!-- Edit button gets injected here -->
                    </div>
                </li>

                <li class="li-sell-info">
                    <div class="listing-sell-info">
                        <div class="listing-price">
                            <img src="diamond.png" alt="diamond">
                            <p><b><span id="cost" class="cost">0</span></b> diamonds</p>
                        </div>
                        <div class="watch-info">
                            <ul>
                                <li>1 left! <img src="fire.gif" alt="fire"></li>
                                <li>Greater than or equal to 1 watching!</li>
                                <li><span id="num-favourited">0</span> people favourited!</li>
                            </ul>
                        </div>
                        <button type="button" class="buy">Buy now!</button>
                    </div>
                </li>
            </ul>
        </div>
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

        function changeTitle(str) {
            document.title = str;
        }

        const reqBody = { id: <?php echo json_encode($list_id); ?> };
        const loggedInUsername = <?php echo json_encode($_SESSION['username'] ?? ''); ?>;

        fetch("listings.php/listing/id", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(reqBody)
        })
        .then(response => response.json())
        .then(data => {
            const item = data[0];

            document.getElementById("listing-image").innerHTML = `
                <img src="/uploads/${escapeHTML(item.image)}" alt="Listing photo">
            `;
            document.getElementById("listing-name").innerText = item.listing_name;
            document.getElementById("seller").innerText = item.username;
            document.getElementById("listing-descript").innerText = item.listing_descript;
            document.getElementById("cost").innerText = item.price;

            changeTitle(escapeHTML(item.listing_name) + " - Diamond Real Estate");

            if (loggedInUsername === item.username) {
                const editButton = document.createElement("div");
                editButton.innerHTML = `
                    <a href="editlisting.php?id=${item.id}"
                       style="display: inline-block; border-radius: .5em; text-decoration: none; margin: 15px 0; padding: 8px 12px; border: 2px solid rgb(30, 209, 30); background-color: rgb(30, 209, 30); color: white;">
                       ✏️ Edit Listing
                    </a>
                `;
                document.getElementById("listing-info").appendChild(editButton);
            }
        })
        .catch(err => {
            console.error("Failed to load listing:", err);
            window.location.href = "/listings.php";
        });
    </script>
</body>
</html>


