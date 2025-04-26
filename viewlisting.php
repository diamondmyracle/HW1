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

// // Handle comment submission
// if (isset($_POST['submit_comment'])) {
//     $comment = $_POST['comment'];
//     $username = $_SESSION['username'] ?? 'Anonymous';
//     $parent_id = $_POST['parent_id'] ?? null;
//     $parent_id = ($parent_id === '') ? null : (int)$parent_id;

//     if ($parent_id === null) {
//         $stmt = $db->prepare("INSERT INTO comments (listing_id, username, comment) VALUES (?, ?, ?)");
//         $stmt->bind_param("iss", $list_id, $username, $comment);
//     } else {
//         $stmt = $db->prepare("INSERT INTO comments (listing_id, username, comment, parent_id) VALUES (?, ?, ?, ?)");
//         $stmt->bind_param("issi", $list_id, $username, $comment, $parent_id);
//     }
//     $stmt->execute();
// }

// // Handle comment deletion
// if (isset($_POST['delete_comment']) && isset($_POST['comment_id'])) {
//     $comment_id = $_POST['comment_id'];
//     $stmt = $db->prepare("DELETE FROM comments WHERE id = ? AND username = ?");
//     $stmt->bind_param("is", $comment_id, $_SESSION['username']);
//     $stmt->execute();
// }

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
            //echo "<form method='post' action=''>";
            echo "<input type='hidden' name='comment_id' value='{$row['id']}'>";
            echo "<button class='delete-comment' name='delete_comment'>Delete</button>";
            //echo "</form>";
        }

        //echo "<form method='post' action=''>";
        echo "<input type='hidden' name='parent_id' value='{$row['id']}'>";
        echo "<textarea name='comment' placeholder='Reply to this comment'></textarea>";
        echo "<button class='submit-comment' name='submit_comment'>Reply</button>";
        //echo "</form>";

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

        <div class="comment-section" id="comment-section">
        <h2>Comments</h2>
        <!-- <form method="post" action=""> -->
            <div class="comment">
                <textarea name="comment" placeholder="Write your comment!"></textarea>
                <input type="hidden" name="parent_id" value="">
                <button class="submit-comment" name="submit_comment">Post Comment</button>
            </div>

            <div class="comments-list" id="comments-list">
                <?php displayComments($db, $list_id); ?>
            </div>
        <!-- </form> -->
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
</body>
</html>

<script>
    renderComments() ;

    async function renderComments() {
        //Prepare the body of the request
        const commentData = {
            list_id: <?php echo $list_id ?>
        } ;

        const commentResponse = await fetch('upload.php/comment/list', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(commentData)
        }).catch(err => console.error("Fetch error:", err)) ;

        //Get resulting list and check to see if it was successful
        const commentResult = await commentResponse.json();
        if (commentResult.status != "success") {
            return ;
        }

        const commentList = JSON.parse(commentResult[0].data) ;
        const commentTree = buildCommentTree(commentList) ;

        console.log(commentTree) ;
        console.log(commentTree[0]) ;

        //Get the comment section div
        const commentSection = document.getElementById("comments-list") ;
        commentSection.innerHTML = "" ;

        if (commentTree.length > 0) {
            commentTree.forEach(comment => {
                commentSection.appendChild(printComment(comment)) ;
            }) ;
        }
    }

    function printComment(comment) {
        const commentDiv = document.createElement("div") ;
        commentDiv.classList.add("comment") ;

        const usernameP = document.createElement("p") ;
        const usernameText = document.createElement("strong") ;

        usernameText.textContent = comment.username ;
        usernameP.appendChild(usernameText) ;
        commentDiv.appendChild(usernameP) ;

        const commentText = document.createElement("p") ;
        commentText.textContent = comment.comment ;
        commentDiv.appendChild(commentText) ;

        const commentId = document.createElement("input") ;
        commentId.setAttribute("type", "hidden") ;
        commentId.setAttribute("name", "comment_id") ;
        commentId.setAttribute("value", comment.id) ;
        commentDiv.appendChild(commentId) ;

        const deleteButton = document.createElement("button") ;
        deleteButton.setAttribute("class", "delete-comment") ;
        deleteButton.setAttribute("name", "delete_comment") ;
        deleteButton.textContent = "Delete" ;
        commentDiv.appendChild(deleteButton) ;

        const parentId = document.createElement("input") ;
        parentId.setAttribute("type", "hidden") ;
        parentId.setAttribute("name", "parent_id") ;
        parentId.setAttribute("value", comment.parent_id) ;
        commentDiv.appendChild(parentId) ;

        const textArea = document.createElement("textarea") ;
        textArea.setAttribute("name", "comment") ;
        textArea.setAttribute("placeholder", "Reply to this comment") ;
        commentDiv.appendChild(textArea) ;

        const submitButton = document.createElement("button") ;
        submitButton.setAttribute("class", "submit-comment") ;
        submitButton.setAttribute("name", "submit_comment") ;
        submitButton.textContent = "Reply" ;
        commentDiv.appendChild(submitButton) ;

        if (comment.children && comment.children.length > 0) {
            comment.children.forEach(child => {
                const childDiv = printComment(child) ;
                commentDiv.appendChild(childDiv) ;
            }) ;
        }

        return commentDiv ;

        // parentDiv.innerHTML += `
        // <div class="comment">
        // <p><strong>USERNAME</strong></p>
        // <p>COMMENT</p>
        // <input type="hidden" name="comment_id" value="">
        // <button class="delete-comment" name="delete_comment">Delete</button>
        // <input type="hidden" name="parent_id" value="">
        // <textarea name="comment" placeholder="Reply to this comment"></textarea>
        // <button class="submit-comment" name="submit_comment">Reply</button>
        // </div>` ;
    }

    //Should make a tree of the comments and return the roots
    function buildCommentTree(commentList) {
        const commentMap = {} ;

        commentList.forEach(comment => {
            comment.children = [] ;
            commentMap[comment.id] = comment ;
        }) ;

        const commentTree = [] ;

        commentList.forEach(comment => {
            if (!comment.parent_id) {
                commentTree.push(comment) ;
            } else {
                commentMap[comment.parent_id].children.push(comment) ;
            }
        }) ;

        return commentTree ;
    }

    document.getElementById("comment-section").addEventListener("click", async (event) => {
        if (event.target.matches('.submit-comment')) {
            //Submission of a comment
            const button = event.target ;
            const commentDiv = button.closest('div.comment') ;
            const textarea = commentDiv.querySelector('textarea') ;
            const text = textarea.value.trim() ;
            const parentId = commentDiv.querySelector('input[type="hidden"][name="parent_id"]') ;

            if (!text) return ;

            const commentData = {
                list_id: <?php echo $list_id ?>,
                username: "<?php echo htmlspecialchars($_SESSION["username"]) ?>",
                comment: text,
                parent_id: parentId.value
            } ;

            const commentResponse = await fetch('upload.php/comment/post', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(commentData)
            }).catch(err => console.error("Fetch error:", err)) ;

            const commentResult = await commentResponse.json();

            if (commentResult.status === "success") {
                textarea.value = "" ;
            } else {
                return ;
            }
        } else if (event.target.matches('.delete-comment')) {
            //Deletion of a comment
            const button = event.target ;
            const commentDiv = button.closest('div.comment') ;
            const commentId = commentDiv.querySelector('input[type="hidden"][name="comment_id"]') ;

            const commentData = {
                comment_id: commentId.value
            } ;

            const commentResponse = await fetch('upload.php/comment/delete', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(commentData)
            }).catch(err => console.error("Fetch error:", err)) ;

            const commentResult = await commentResponse.json();

            if (commentResult.status === "success") {
                return ;
            } else {
                return ;
            }
        }
    }) ;
</script>