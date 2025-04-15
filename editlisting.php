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

  // $sql = "SELECT id, username, listing_name, listing_descript, price FROM listings WHERE id = ?" ;

  // if(!($stmt = mysqli_prepare($db, $sql))){
  //   header("location: listings.php") ;
  //   exit ;
  // }

  // mysqli_stmt_bind_param($stmt, "s", $list_id) ;
  // if(!mysqli_stmt_execute($stmt)){
  //   header("location: listings.php") ;
  //   exit ;
  // } 
  
  // mysqli_stmt_store_result($stmt) ;
  // if(mysqli_stmt_num_rows($stmt) !== 1){
  //   header("location: listings.php") ;
  //   exit ;
  // }
        
  // mysqli_stmt_bind_result($stmt, $result_id, $result_author, $result_name, $result_descript, $result_price) ;
  // if(!mysqli_stmt_fetch($stmt)) {
  //   header("location: listings.php") ;
  //   exit ;
  // }
          
  // if($_SESSION["username"] !== $result_author){
  //   header("location: listings.php") ;
  //   exit ;
  // }

  // mysqli_stmt_close($stmt);

  // if($_SERVER["REQUEST_METHOD"] == "POST"){
  //   if(isset($_POST["delete"])){
  //     $sql = "DELETE FROM listings WHERE id = ?" ;

  //     if($stmt = mysqli_prepare($db, $sql)){
  //       mysqli_stmt_bind_param($stmt, "s", $list_id) ;

  //       if(mysqli_stmt_execute($stmt)){
  //         header("location: listings.php") ;
  //         exit ;
  //       } 
  //     }
  //   }

  //   if(isset($_POST["save"])){
  //     $param_listname = $_POST["listing_name"] ;
  //     $param_listdescript = $_POST["listing_desc"] ;
  //     $param_listprice = $_POST["listing_price"] ;
  //     $param_author = $_SESSION["username"] ;
  //     $param_id = uniqid("", true) ;

  //     if(empty($param_listname) || empty($param_listdescript) || empty($param_listprice)){
  //       $form_error = "Form fields cannot be left blank" ;
  //     }

  //     if(empty($form_error) && (($param_listprice > 2048) || ($param_listprice < 1))){
  //       $form_error = "Price must be between 1 and 2048" ;
  //     }
  
  //     if(empty($form_error)){
  //       $sql = "UPDATE listings SET listing_name=?, listing_descript=?, price=? WHERE id=?";
    
  //       if($stmt = mysqli_prepare($db, $sql)){
  //         mysqli_stmt_bind_param($stmt, "ssis", $param_listname, $param_listdescript, $param_listprice, $list_id) ;
    
  //         if(mysqli_stmt_execute($stmt)){
  //           header("location: listings.php") ;
  //         } else{
  //           echo "idk, it didn't work" ;
  //         }
  //       }

  //       mysqli_stmt_close($stmt);
  //       mysqli_close($db);
  //     }
  //   }
  // }
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
    reqBody["id"] = <?php echo $list_id ?> ;

    fetch("listings.php/listing/id", {
        method: "POST",
        header: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify(reqBody)
      })
    .then(response => response.json())
    .then(data => {
      const nameContainer = document.getElementById("listing-name") ;
      nameContainer.innerHTML = `
        <label for="listing_name">Listing name</label>
        <br>
        <input type="text" placeholder="Listing name" name="listing_name" maxlength="32" value="${escapeHTML((data[0]).listing_name)}">              
      ` ;

      const descContainer = document.getElementById("listing-desc") ;
      descContainer.innerHTML = `
        <label for="listing_desc">Listing description</label>
        <br>
        <textarea placeholder="Listing description" name="listing_descript" style="resize: none" maxlength="256">${escapeHTML((data[0]).listing_descript)}</textarea>
      ` ;

      const priceContainer = document.getElementById("listing-price") ;
      priceContainer.innerHTML = `
        <label for="listing_price">Listing price</label>
        <br>
        <input type="number" placeholder="Listing price" name="price" value="${escapeHTML((data[0]).price)}">
      `;
    })
    .catch(err =>{
        console.error("Failed to load listings, bitch:", err) ;
    })
  </script>
   
  <div id="site-content" class="site-content">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ; ?>?id=<?php echo htmlspecialchars($_GET["id"]) ; ?>" method="post" id="editForm">
      <div id="editbox" class="editbox">
        <h1>Edit listing</h1>
        <p>Edit the listing for your Minecraft house</p>

        <div id="listing-name">
          
        </div>

        <br>

        <div id="listing-desc">
          
        </div>

        <br>

        <div id="listing-price">
          
        </div>

        <br>

        <span style="color:red" id="error-msg"></span>
        <button type="submit" name="save" id="save-button">Save changes</button>
        <br>
        <br>
        <button type="submit" id="delete" name="delete">Delete listing</button>
      </div>
    </form>
  </div>
</body>

<script>
  let whichButton = null ;

  document.getElementById("save-button").addEventListener("click", () => {
    whichButton = "save" ;
  }) ;

  document.getElementById("delete").addEventListener("click", () => {
    whichButton = "delete" ;
  }) ;

  document.getElementById("editForm").addEventListener("submit", function(event) {
    event.preventDefault() ;

    if (whichButton == "save") {
      const form = event.target ;
      const formData = new FormData(form) ;

      const data = {} ;
      formData.forEach((value, key) => {
          data[key] = value ;
      }) ;

      data["id"] = <?php echo $list_id ?> ;

      const listing_name = data["listing_name"] ;
      const listing_descript = data["listing_descript"] ;
      const price = data["price"] ;

      const errorMsgBox = document.getElementById("error-msg") ;
      errorMsgBox.innerText = "" ;

      //Check if form information is valid
      if (listing_name === "" || listing_descript === "" || price === "") {
        errorMsgBox.innerText = "Please fill out all fields." ;
        return ;
      } else if (price < 1 || price > 2048) {
        errorMsgBox.innerText = "Price must be between 1 and 2048." ;
        return ;
      }

      //Save the edits
      whichButton = null ;
      saveEdits(data) ;
    } else if (whichButton == "delete") {
      const data = {} ;
      data["id"] = <?php echo $list_id ?> ;

      //delete the listing
      deleteListing(data) ;
    }

    whichButton = null ;
  }) ;

  async function deleteListing(data) {
    //delete the listing
    const deleted = await fetch("index.php/listing/delete", {
            method: "DELETE",
            header: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        }).catch(err => console.error("Fetch error:", err)) ;

        const deletedJson = await deleted.json() ;

        //Was it successful?
        if (deletedJson.success) {
            window.location.href = "/listings.php" ;
            return ;
        } else {
            alert(deletedJson.message || "Edit failed.") ;
        }
  }

  async function saveEdits(data) {
    //Save the edit
    const edit = await fetch("index.php/listing/update", {
        method: "PUT",
        header: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify(data)
    }).catch(err => console.error("Fetch error:", err)) ;

    const editJson = await edit.json() ;

    //Was it successful?
    if (editJson.success) {
        window.location.href = "/listings.php" ;
        return ;
    } else {
        alert(editJson.message || "Edit failed.") ;
    }
  }
</script>