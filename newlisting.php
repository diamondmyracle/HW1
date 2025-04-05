<?php 
require_once "inc/config.php";
require_once "image_upload.php";

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: listings.php");
    exit;
}

$param_listname = $param_listdescript = $param_listprice = $param_author = $param_id = $imageFilename = "";
$form_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $param_listname = $_POST["listing_name"];
    $param_listdescript = $_POST["listing_desc"];
    $param_listprice = $_POST["listing_price"];
    $param_author = $_SESSION["username"];
    $param_id = uniqid("", true);

    if (empty($param_listname) || empty($param_listdescript) || empty($param_listprice)) {
        $form_error = "Form fields cannot be left blank";
    }

    if (empty($form_error) && (($param_listprice > 2048) || ($param_listprice < 1))) {
        $form_error = "Price must be between 1 and 2048";
    }

    if (empty($form_error)) {
        // Handle the image upload (image_upload.php should have the handleImageUpload function)
        $imageFilename = handleImageUpload($param_id, $form_error);

        if (empty($form_error)) {
            // Proceed with inserting into the database
            $sql = "INSERT INTO listings (id, username, listing_name, listing_descript, price, image) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($db, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssssss", 
                    $param_id, 
                    $param_author, 
                    $param_listname, 
                    $param_listdescript, 
                    $param_listprice, 
                    $imageFilename
                );

                if (mysqli_stmt_execute($stmt)) {
                    header("location: listings.php");
                    exit;
                } else {
                    $form_error = "Database insert failed: " . mysqli_stmt_error($stmt);  // Show actual SQL error
                }
                mysqli_stmt_close($stmt);
            } else {
                $form_error = "Database prepare failed: " . mysqli_error($db);
            }
            mysqli_close($db);
        }
    }
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div id="createbox" class="createbox">
                <h1>Create new listing</h1>
                <p>Put your amazing Minecraft house on the market!</p>

                <div>
                    <label for="listing_name">Listing name</label>
                    <br>
                    <input type="text" placeholder="Listing name" name="listing_name" maxlength="32">
                </div>

                <br>

                <div>
                    <label for="listing_desc">Listing description</label>
                    <br>
                    <textarea placeholder="Listing description" name="listing_desc" style="resize: none" maxlength="255"></textarea>
                </div>

                <br>

                <div>
                    <label for="listing_price">Listing price</label>
                    <br>
                    <input type="number" placeholder="Listing price" name="listing_price">
                </div>

                <br>

                <div>
                    <label for="listing_image">Listing Image</label>
                    <br>
                    <input type="file" name="listing_image" accept="image/*">
                </div>

                <br>

                <span style="color:red"><?php echo $form_error; ?></span>
                <button type="submit" name="create_listing">Create listing</button>
            </div>
        </form>
    </div>
</body>
</html>

