<?php 
  require_once "inc/config.php" ;

  session_start() ;

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false){
    header("location: listings.php") ;
    exit ;
  }

  if(!isset($_GET["id"])){
    header("location: listings.php") ;
    exit ;
  }

  $list_id = $_GET["id"] ;
  $result_name = $result_descript = $result_price = $result_author = $result_id = "" ;
  $form_error = "" ;

  // Fetch listing details using REST API with file_get_contents
  $context = stream_context_create([
      'http' => [
          'method' => 'GET',
          'header' => 'Content-Type: application/json'
      ]
  ]);

  $response = file_get_contents('http://localhost/index.php/listing/get?id=' . urlencode($list_id), false, $context);

  if ($response === false) {
    header("location: listings.php");
    exit;
  }

  $listing = json_decode($response, true);
  if (!$listing || !isset($listing['id'])) {
    header("location: listings.php");
    exit;
  }

  if ($_SESSION["username"] !== $listing['username']) {
    header("location: listings.php");
    exit;
  }

  $result_id = $listing['id'];
  $result_author = $listing['username'];
  $result_name = $listing['listing_name'];
  $result_descript = $listing['listing_descript'];
  $result_price = $listing['price'];

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["delete"])){
      // Handle delete through REST API
      $context = stream_context_create([
          'http' => [
              'method' => 'DELETE',
              'header' => 'Content-Type: application/json',
              'content' => json_encode(['id' => $list_id])
          ]
      ]);

      $response = file_get_contents('http://localhost/index.php/listing/delete', false, $context);

      if ($response !== false) {
        $result = json_decode($response, true);
        if (isset($result['success']) && $result['success']) {
          header("location: listings.php");
          exit;
        }
      }
      $form_error = "Failed to delete listing";
    } else {
      // Handle update through REST API
      $data = [
        'id' => $list_id,
        'listing_name' => $_POST["listing_name"],
        'listing_descript' => $_POST["listing_desc"],
        'price' => $_POST["listing_price"]
      ];

      // Debug: Log the request data
      error_log("Update request data: " . print_r($data, true));

      $context = stream_context_create([
          'http' => [
              'method' => 'PUT',
              'header' => 'Content-Type: application/json',
              'content' => json_encode($data)
          ]
      ]);

      $response = file_get_contents('http://localhost/index.php/listing/update', false, $context);

      // Debug: Log the response
      error_log("Update response: " . $response);

      if ($response !== false) {
        $result = json_decode($response, true);
        if (isset($result['message']) && $result['message'] === 'Listing updated successfully') {
          header("location: listings.php");
          exit;
        } else if (isset($result['error'])) {
          $form_error = $result['error'];
        }
      }
      if (empty($form_error)) {
        $form_error = "Failed to update listing";
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
    <form id="editForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ; ?>?id=<?php echo htmlspecialchars($_GET["id"]) ; ?>" method="post">
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

        <span id="formError" style="color:red"><?php echo $form_error ; ?></span>
        <button type="submit" name="save">Save changes</button>
        <br>
        <br>
        <button type="button" id="deleteBtn">Delete listing</button>
      </div>
    </form>
  </div>

<script>
document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = e.target;
    const errorEl = document.getElementById('formError');
    errorEl.textContent = '';

    const data = {
        id: '<?php echo $list_id; ?>',
        listing_name: form.elements['listing_name'].value,
        listing_descript: form.elements['listing_desc'].value,
        price: form.elements['listing_price'].value
    };

    try {
        const response = await fetch('http://localhost/index.php/listing/update', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            window.location.href = 'listings.php';
        } else {
            errorEl.textContent = result.error || 'Failed to update listing';
        }
    } catch (error) {
        console.error('Error:', error);
        errorEl.textContent = 'An error occurred while updating the listing';
    }
});

document.getElementById('deleteBtn').addEventListener('click', async function() {
    if (confirm('Are you sure you want to delete this listing?')) {
        try {
            const response = await fetch('http://localhost/index.php/listing/delete', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'include',
                body: JSON.stringify({ id: '<?php echo $list_id; ?>' })
            });

            const result = await response.json();

            if (response.ok) {
                window.location.href = 'listings.php';
            } else {
                document.getElementById('formError').textContent = result.error || 'Failed to delete listing';
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('formError').textContent = 'An error occurred while deleting the listing';
        }
    }
});
</script>
</body>
