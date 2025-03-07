<?php 
  require_once "config.php" ;

  session_start() ;
  
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
    header("location: listings.php");
    exit;
  }

  $param_listname = $param_listdescript = $param_listprice = $param_author = $param_id = "" ;

  if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
      header("location: listings.php");
      exit;
    }

    $param_listname = $_POST["listing_name"] ;
    $param_listdescript = $_POST["listing_desc"] ;
    $param_listprice = $_POST["listing_price"] ;
    $param_author = $_SESSION["username"] ;
    $param_id = uniqid("", true) ;

    $sql = "INSERT INTO listings (id, username, listing_name, listing_descript, price) VALUES (?, ?, ?, ?, ?)";

    if($stmt = mysqli_prepare($db, $sql)){
      mysqli_stmt_bind_param($stmt, "ssssi", $param_id, $param_author, $param_listname, $param_listdescript, $param_listprice) ;

      if(mysqli_stmt_execute($stmt)){
        header("location: listings.php") ;
      } else{
        echo "idk, it didn't work" ;
      }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
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
            <a class="active" href="#home">Home</a>
            <a href="listings.php">Listing</a>
            <a href="index.php#faq">FAQ</a>
      
            <?php if (!empty($username)): ?>
                 <a href="logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a>
            <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Signup</a>
            <?php endif; ?>
        </div>
    
  <div id="site-content" class="site-content">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
          <input type="number" placeholder="Listing price" name="listing_price" max="2048" min="1" required>
        </div>

        <br>

        <button type="submit" name="create_listing">Create listing</button>
      </div>
    </form>
  </div>
</body>
