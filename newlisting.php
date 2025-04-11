<?php
session_start();
require_once "inc/config.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriParts = explode('/', trim($uri, '/'));

if (isset($uriParts[1]) && $uriParts[1] === 'listing' && isset($uriParts[2]) && $uriParts[2] === 'create') {
    require __DIR__ . "/inc/bootstrap.php";
    require PROJECT_ROOT_PATH . "/Controller/Api/ListingController.php";

    $controller = new ListingController();
    $controller->createAction();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create new Diamond Real Estate listing">
    <title>Create new listing</title>

    <link rel="stylesheet" href="cssForIndex.css">
    <link rel="stylesheet" href="newlisting.css">
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

    <div id="site-content" class="site-content">
        <form id="listingForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div id="createbox" class="createbox">
                <h1>Create new listing</h1>
                <p>Put your amazing Minecraft house on the market!</p>

                <div>
                    <label for="listing_name">Listing name</label>
                    <br>
                    <input type="text" placeholder="Listing name" name="listing_name" maxlength="32" required>
                </div>

                <br>

                <div>
                    <label for="listing_desc">Listing description</label>
                    <br>
                    <textarea placeholder="Listing description" name="listing_desc" style="resize: none" maxlength="255" required></textarea>
                </div>

                <br>

                <div>
                    <label for="listing_price">Listing price</label>
                    <br>
                    <input type="number" placeholder="Listing price" name="listing_price" required>
                </div>

                <br>

                <div>
                    <label for="listing_image">Listing Image</label>
                    <br>
                    <input type="file" name="listing_image" accept="image/*" required>
                </div>

                <br>

                <span id="formError" style="color:red"><?php echo $form_error; ?></span>
                <button type="submit" name="create_listing">Create listing</button>
            </div>
        </form>
    </div>
</body>
</html>

<script>
    document.getElementById('listingForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const errorEl = document.getElementById('formError');
        errorEl.textContent = '';

        try {
            const formData = new FormData(form);
            const imageFile = formData.get('listing_image');
            
            // Convert image to base64
            const imageBase64 = await new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(imageFile);
            });

            const data = {
                listing_name: formData.get('listing_name'),
                listing_descript: formData.get('listing_desc'),
                price: formData.get('listing_price'),
                image: imageBase64
            };

            const response = await fetch('http://localhost/index.php/listing/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                window.location.href = 'listings.php';
            } else {
                errorEl.textContent = result.error || 'An error occurred while submitting the form';
            }
        } catch (error) {
            console.error('Error:', error);
            errorEl.textContent = 'An error occurred while submitting the form';
        }
    });
</script>



