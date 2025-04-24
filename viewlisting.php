<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "inc/config.php";
session_start();

$list_id = "";

if (isset($_GET["id"])) {
    $list_id = $_GET["id"];
} else {
    header("location: listings.php");
    exit;
}

// Handle comment submission
if (isset($_POST['submit_comment'])) {
    $comment = $_POST['comment'];
    $username = $_SESSION['username'] ?? 'Anonymous';
    $parent_id = $_POST['parent_id'] ?? null;
    $parent_id = ($parent_id === '') ? null : (int)$parent_id;

    if ($parent_id === null) {
        $stmt = $db->prepare("INSERT INTO comments (listing_id, username, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $list_id, $username, $comment);
    } else {
        $stmt = $db->prepare("INSERT INTO comments (listing_id, username, comment, parent_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $list_id, $username, $comment, $parent_id);
    }
    $stmt->execute();
}

// Handle comment deletion
if (isset($_POST['delete_comment']) && isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];
    $stmt = $db->prepare("DELETE FROM comments WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $comment_id, $_SESSION['username']);
    $stmt->execute();
}

// Recursive comment renderer
function displayComments($db, $list_id, $parent_id = null)
{
    $query = "SELECT id, username, comment FROM comments WHERE listing_id = ? AND parent_id ";
    $query .= is_null($parent_id) ? "IS NULL" : "= ?";
    $query .= " ORDER BY id DESC";

    $stmt = $db->prepare($query);
    if (is_null($parent_id)) {
        $stmt->bind_param("i", $list_id);
    } else {
        $stmt->bind_param("ii", $list_id, $parent_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<div class='comment'>";
        echo "<p><strong>" . htmlspecialchars($row['username']) . "</strong></p>";
        echo "<p>" . nl2br(htmlspecialchars($row['comment'])) . "</p>";

        if (isset($_SESSION['username']) && $_SESSION['username'] === $row['username']) {
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='comment_id' value='{$row['id']}'>";
            echo "<button type='submit' name='delete_comment'>Delete</button>";
            echo "</form>";
        }

        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='parent_id' value='{$row['id']}'>";
        echo "<textarea name='comment' required placeholder='Reply to this comment'></textarea>";
        echo "<button type='submit' name='submit_comment'>Reply</button>";
        echo "</form>";

        displayComments($db, $list_id, $row['id']);
        echo "</div>";
    }
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
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
                <li class="li-img"><div id="listing-image" class="listing-image"></div></li>
                <li class="li-info">
                    <div id="listing-info">
                        <h1 id="listing-name">Loading...</h1>
                        <p id="listing-author">by <b><span id="seller" class="seller">Loading...</span></b></p>
                        <p id="listing-descript">Loading...</p>
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
            document.getElementById("listing-image").innerHTML = `<img src="/uploads/${item.image}" alt="Listing photo">`;
            document.getElementById("listing-name").innerText = item.listing_name;
            document.getElementById("seller").innerText = item.username;
            document.getElementById("listing-descript").innerText = item.listing_descript;
            document.getElementById("cost").innerText = item.price;

            if (loggedInUsername === item.username) {
                const editButton = document.createElement("div");
                editButton.innerHTML = `<a href="editlisting.php?id=${item.id}" style="margin:15px 0; padding:8px 12px; background:green; color:white; border-radius:5px;">✏️ Edit Listing</a>`;
                document.getElementById("listing-info").appendChild(editButton);
            }
        })
        .catch(err => {
            console.error("Failed to load listing:", err);
            window.location.href = "/listings.php";
        });
    </script>

    <div class="comment-section">
        <h2>Comments</h2>
        <form method="post" action="">
            <textarea name="comment" placeholder="Write your comment!" required></textarea>
            <input type="hidden" name="parent_id" value="">
            <button type="submit" name="submit_comment">Post Comment</button>
        </form>

        <div class="comments-list">
            <?php displayComments($db, $list_id); ?>
        </div>
    </div>
</body>
</html>

