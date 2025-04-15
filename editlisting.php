<?php 
  require_once "inc/config.php" ;

  session_start() ;

  $result_name = $result_descript = $result_price = $result_author = $result_id = "" ;
  $list_id = "" ;
  $form_error = "" ;
  
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
    header("location: listings.php") ;
    exit ;
  }

  if(isset($_GET["id"])){
    $list_id = $_GET["id"] ;
  } 
  else{
    header("location: listings.php") ;
    exit ;
  }

  $sql = "SELECT id, username, listing_name, listing_descript, price FROM listings WHERE id = ?" ;

  if(!($stmt = mysqli_prepare($db, $sql))){
    header("location: listings.php") ;
    exit ;
  }

  mysqli_stmt_bind_param($stmt, "s", $list_id) ;
  if(!mysqli_stmt_execute($stmt)){
    header("location: listings.php") ;
    exit ;
  } 
  
  mysqli_stmt_store_result($stmt) ;
  if(mysqli_stmt_num_rows($stmt) !== 1){
    header("location: listings.php") ;
    exit ;
  }
        
  mysqli_stmt_bind_result($stmt, $result_id, $result_author, $result_name, $result_descript, $result_price) ;
  if(!mysqli_stmt_fetch($stmt)) {
    header("location: listings.php") ;
    exit ;
  }
          
  if($_SESSION["username"] !== $result_author){
    header("location: listings.php") ;
    exit ;
  }

  mysqli_stmt_close($stmt);

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["delete"])){
      $sql = "DELETE FROM listings WHERE id = ?" ;

      if($stmt = mysqli_prepare($db, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $list_id) ;

        if(mysqli_stmt_execute($stmt)){
          header("location: listings.php") ;
          exit ;
        } 
      }
    }

    if(isset($_POST["save"])){
      $param_listname = $_POST["listing_name"] ;
      $param_listdescript = $_POST["listing_desc"] ;
      $param_listprice = $_POST["listing_price"] ;
      $param_author = $_SESSION["username"] ;
      $param_id = uniqid("", true) ;

      if(empty($param_listname) || empty($param_listdescript) || empty($param_listprice)){
        $form_error = "Form fields cannot be left blank" ;
      }

      if(empty($form_error) && (($param_listprice > 2048) || ($param_listprice < 1))){
        $form_error = "Price must be between 1 and 2048" ;
      }
  
      if(empty($form_error)){
        $sql = "UPDATE listings SET listing_name=?, listing_descript=?, price=? WHERE id=?";
    
        if($stmt = mysqli_prepare($db, $sql)){
          mysqli_stmt_bind_param($stmt, "ssis", $param_listname, $param_listdescript, $param_listprice, $list_id) ;
    
          if(mysqli_stmt_execute($stmt)){
            header("location: listings.php") ;
          } else{
            echo "idk, it didn't work" ;
          }
        }

        mysqli_stmt_close($stmt);
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
    <meta name="description" content="Edit a Diamond Real Estate listing">
    <title>Create new listing</title>

    <link rel="stylesheet" href="cssForIndex.css">
    <link rel="stylesheet" href="editlisting.css">
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ; ?>?id=<?php echo htmlspecialchars($_GET["id"]) ; ?>" method="post">
      <div id="editbox" class="editbox">
        <h1>Edit listing</h1>
        <p>Edit the listing for your Minecraft house</p>

        <div>
          <label for="listing_name">Listing name</label>
          <br>
          <input type="text" placeholder="Listing name" name="listing_name" maxlength="32" value="<?php echo $result_name ; ?>">
        </div>

        <br>

        <div>
          <label for="listing_desc">Listing description</label>
          <br>
          <textarea placeholder="Listing description" name="listing_desc" style="resize: none" maxlength="256"><?php echo $result_descript ; ?></textarea>
        </div>

        <br>

        <div>
          <label for="listing_price">Listing price</label>
          <br>
          <input type="number" placeholder="Listing price" name="listing_price" value="<?php echo $result_price ; ?>">
        </div>

        <br>

        <span style="color:red"><?php echo $form_error ; ?></span>
        <button type="submit" name="save">Save changes</button>
        <br>
        <br>
        <button type="submit" id="delete" name="delete">Delete listing</button>
      </div>
    </form>
  </div>
</body>
