<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "inc/config.php";
session_start();

if (!isset($_GET["username"])) {
    header("location: listings.php");
    exit;
}

$username = $_GET["username"];
$loggedInUser = $_SESSION['username'] ?? null;

// Get user profile
$stmt = $db->prepare("SELECT username, acc_balance, user_descript FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit;
}

// Get user's own listings
$stmt = $db->prepare("SELECT id, listing_name, image, price FROM listings WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$listings_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Profile</title>
    <link rel="stylesheet" href="cssForIndex.css">
    <link rel="stylesheet" href="viewprofile.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="listings.php">Listing</a>
    <a href="index.php#faq">FAQ</a>
    <?php if ($loggedInUser): ?>
        <a href="logout.php">Logout (<?php echo htmlspecialchars($loggedInUser); ?>)</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="signup.php">Signup</a>
    <?php endif; ?>
</div>

<div class="site-content">
    <div class="profile-container">
        <ul>
            <li class="li-img">
                <div id="profile-image" class="listing-image">
                    <img src="profileicon.png" alt="Profile icon">
                </div>
            </li>
            <li class="li-info">
                <div id="profile-info">
                    <h1 id="user-name"><?php echo htmlspecialchars($user['username']); ?></h1>
                    <p id="user-descript"><?php echo nl2br(htmlspecialchars($user['user_descript'])); ?></p>

                    <?php if ($loggedInUser === $username): ?>
                        <div class="account-balance">
                            <img src="diamond.png" alt="diamond">
                            <p><strong>Balance:</strong> <b><span id="user-balance"><?php echo (int)$user['acc_balance']; ?></span></b> diamonds</p>
                        </div>

                        <div style="margin-top: 15px;">
                            <a href="editprofile.php?username=<?php echo urlencode($username); ?>" style="margin:15px 0; padding:8px 12px; background:green; color:white; border-radius:5px; display:inline-block; text-decoration:none; font-size: 75%">✏️ Edit Profile</a>
                        </div>
                    <?php endif; ?>
                </div>
            </li>
        </ul>
    </div>

    <!-- User Listings -->
    <div id="user-listings" class="listing-sell-info">
        <h2>Listings by <?php echo htmlspecialchars($user['username']); ?></h2>
        <?php if ($listings_result->num_rows > 0): ?>
            <ul>
                <?php while ($listing = $listings_result->fetch_assoc()): ?>
                    <li class="li-sell-info">
                        <div class="listing-sell-info">
                            <div class="listing-price">
                                <img src="/uploads/<?php echo htmlspecialchars($listing['image']); ?>" alt="Listing Image" style="width:100px;">
                                <p id="listing-title">
                                    <b><a href="viewlisting.php?id=<?php echo $listing['id']; ?>" class="listing-title">
                                        <?php echo htmlspecialchars($listing['listing_name']); ?>
                                    </a></b>
                                </p>
                                <p><b><?php echo (int)$listing['price']; ?></b> diamonds</p>
                            </div>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No listings yet.</p>
        <?php endif; ?>
    </div>

    <!-- Favorites Section -->
    <?php if ($loggedInUser === $username): ?>
    <div id="user-favorites" class="listing-sell-info">
        <h2>Favorites of <?php echo htmlspecialchars($username); ?></h2>
        <ul id="favorites-list">
        </ul>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const username = <?php echo json_encode($username); ?>;
    const loggedInUser = <?php echo json_encode($loggedInUser); ?>;

    if (username !== loggedInUser) return;

    fetch("favorites_api.php/favorites/userFavorites", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            const favorites = data.favorites;
            const favoritesList = document.getElementById("favorites-list");

            if (favorites.length === 0) {
                favoritesList.innerHTML = "<p>No favorites yet.</p>";
                return;
            }

            favorites.forEach(item => {
                const li = document.createElement("li");
                li.classList.add("li-sell-info");
                li.innerHTML = `
                    <div class="listing-sell-info">
                        <div class="listing-price">
                            <img src="/uploads/${item.image}" alt="Listing Image" style="width:100px;">
                            <p id="listing-title">
                                <b><a href="viewlisting.php?id=${item.id}" class="listing-title">
                                    ${item.listing_name}
                                </a></b>
                            </p>
                            <p><b>${item.price}</b> diamonds</p>
                        </div>
                    </div>
                `;
                favoritesList.appendChild(li);
            });
        } else {
            console.error("Failed to load favorites:", data.message);
        }
    })
    .catch(err => {
        console.error("Error loading favorites:", err);
    });
});
</script>
</body>
</html>
