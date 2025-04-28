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
                        <div style="margin-top: 10px; text-align: center;">
                            <button id="favoriteButton" style="background: none; border: none; cursor: pointer; 
                            display: flex; align-items: center; gap: 8px;">
                            <img id="favoriteIcon" src="minecraft-black-heart.png" alt="Favorite" width="32" height="32" style="image-rendering: pixelated ;">
                            <span id="favoriteText" style="font-size: 1em; color: black;">Add to Favorites</span>
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="comment-section" id="comment-section">
        <h2>Comments</h2>
            <div class="comment">
                <textarea name="comment" placeholder="Write your comment!"></textarea>
                <input type="hidden" name="comment_id" value="">
                <button class="submit-comment" name="submit_comment">Post Comment</button>
            </div>
            <div class="comments-list" id="comments-list"></div>
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
        commentSection.classList.add("loading") ;
        commentSection.innerHTML = "" ;

        if (commentTree.length > 0) {
            commentTree.forEach(comment => {
                commentSection.appendChild(printComment(comment)) ;
            }) ;
        }

        commentSection.classList.remove("loading") ;
    }

    function printComment(comment) {
        //Create the comment div
        const commentDiv = document.createElement("div") ;
        commentDiv.classList.add("comment") ;

        //Add the username
        const usernameP = document.createElement("p") ;
        const usernameText = document.createElement("strong") ;
        usernameText.textContent = comment.username ;
        usernameP.appendChild(usernameText) ;
        commentDiv.appendChild(usernameP) ;

        //Add the comment text
        const commentText = document.createElement("p") ;
        commentText.textContent = comment.comment ;
        commentDiv.appendChild(commentText) ;

        //Add the hidden comment id
        const commentId = document.createElement("input") ;
        commentId.setAttribute("type", "hidden") ;
        commentId.setAttribute("name", "comment_id") ;
        commentId.setAttribute("value", comment.id) ;
        commentDiv.appendChild(commentId) ;

        //Get reaction counts
        const reacts = JSON.parse(comment.reactions) ;
        const numLikes = reacts.like.length ;
        const numLoves = reacts.love.length ;
        const numLaughs = reacts.laugh.length ;
        const numMad = reacts.mad.length ;
        const numReactions = numLikes + numLoves + numLaughs + numMad ;

        //Get the user reaction data
        <?php
            //If the user isn't logged in, redirect them to login
            if (isset($_SESSION["username"])) {
                echo "const username = '" . $_SESSION["username"] . "' ;" ;
            } else {
                echo "const username = '' ;" ;
            }
        ?>
        const userLike = reacts.like.includes(username) ;
        const userLove = reacts.love.includes(username) ;
        const userLaugh = reacts.laugh.includes(username) ;
        const userMad = reacts.mad.includes(username) ;

        //Create the react button container
        const reactContainer = document.createElement("div") ;
        reactContainer.setAttribute("class", "react-container") ;
        //Add the basic react button
        const reactButton = document.createElement("button") ;
        reactButton.setAttribute("class", "react-comment") ;
        reactButton.setAttribute("name", "react_comment") ;
        if (userLike) {
            reactButton.innerHTML = "<img src='/like.png' alt='like' class='react-button-img'>" ;
            reactButton.setAttribute("data-type", "none") ;
        } else if (userLove) {
            reactButton.innerHTML = "<img src='/love.png' alt='love' class='react-button-img'>" ;
            reactButton.setAttribute("data-type", "none") ;
        } else if (userLaugh) {
            reactButton.innerHTML = "<img src='/laugh.png' alt='laugh' class='react-button-img'>" ;
            reactButton.setAttribute("data-type", "none") ;
        } else if (userMad) {
            reactButton.innerHTML = "<img src='/mad.png' alt='mad' class='react-button-img'>" ;
            reactButton.setAttribute("data-type", "none") ;
        } else {
            reactButton.textContent = "Like" ;
            reactButton.setAttribute("data-type", "like") ;
        }
        reactButton.setAttribute("data-id", comment.id) ;
        reactButton.addEventListener("click", () => {
            updateReaction(reactButton) ;
        }) ;
        reactContainer.appendChild(reactButton) ;

        //Create the options container
        const reactOptions = document.createElement("div") ;
        reactOptions.setAttribute("class", "react-options hidden") ;
        //Add the like button
        const likeButton = document.createElement("button") ;
        likeButton.setAttribute("class", "like-comment") ;
        likeButton.setAttribute("name", "like_comment") ;
        if (userLike) {
            likeButton.setAttribute("data-type", "none") ;
        } else {
            likeButton.setAttribute("data-type", "like") ;
        }
        likeButton.setAttribute("data-id", comment.id) ;
        likeButton.innerHTML = "<img src='/like.png' alt='like' class='react-img'>" ;
        likeButton.addEventListener("click", () => {
            updateReaction(likeButton) ;
        }) ;
        reactOptions.appendChild(likeButton) ;
        //Add the love button
        const loveButton = document.createElement("button") ;
        loveButton.setAttribute("class", "love-comment") ;
        loveButton.setAttribute("name", "love_comment") ;
        if (userLove) {
            loveButton.setAttribute("data-type", "none") ;
        } else {
            loveButton.setAttribute("data-type", "love") ;
        }
        loveButton.setAttribute("data-id", comment.id) ;
        loveButton.innerHTML = "<img src='/love.png' alt='love' class='react-img'>" ;
        loveButton.addEventListener("click", () => {
            updateReaction(loveButton) ;
        }) ;
        reactOptions.appendChild(loveButton) ;
        //Add the laugh button
        const laughButton = document.createElement("button") ;
        laughButton.setAttribute("class", "laugh-comment") ;
        laughButton.setAttribute("name", "laugh_comment") ;
        if (userLaugh) {
            laughButton.setAttribute("data-type", "none") ;
        } else {
            laughButton.setAttribute("data-type", "laugh") ;
        }
        laughButton.setAttribute("data-id", comment.id) ;
        laughButton.innerHTML = "<img src='/laugh.png' alt='laugh' class='react-img'>" ;
        laughButton.addEventListener("click", () => {
            updateReaction(laughButton) ;
        }) ;
        reactOptions.appendChild(laughButton) ;
        //Add the hate button
        const hateButton = document.createElement("button") ;
        hateButton.setAttribute("class", "mad-comment") ;
        hateButton.setAttribute("name", "mad_comment") ;
        if (userMad) {
            hateButton.setAttribute("data-type", "none") ;
        } else {
            hateButton.setAttribute("data-type", "mad") ;
        }
        hateButton.setAttribute("data-id", comment.id) ;
        hateButton.innerHTML = "<img src='/mad.png' alt='mad' class='react-img'>" ;
        hateButton.addEventListener("click", () => {
            updateReaction(hateButton) ;
        }) ;
        reactOptions.appendChild(hateButton) ;
        //Add this to the comment div
        reactContainer.appendChild(reactOptions) ;
        commentDiv.appendChild(reactContainer) ;


        //Add the reply button
        const replyButton = document.createElement("button") ;
        replyButton.setAttribute("class", "reply") ;
        replyButton.setAttribute("name", "reply") ;
        replyButton.textContent = "Reply" ;

        replyButton.addEventListener("click", () => {
            addTextboxToComment(commentDiv) ;
        }) ;

        commentDiv.appendChild(replyButton) ;

        //Add the delete button
        const deleteButton = document.createElement("button") ;
        deleteButton.setAttribute("class", "delete-comment") ;
        deleteButton.setAttribute("name", "delete_comment") ;
        deleteButton.textContent = "Delete" ;
        commentDiv.appendChild(deleteButton) ;

        //Add reaction counter if there are reactions
        if (numReactions > 0) {
            const reactListContainer = document.createElement("div") ;
            reactListContainer.setAttribute("class", "react-list-container") ;

            const reactCounter = document.createElement("p") ;
            reactCounter.setAttribute("class", "react-p") ;
            switch (numReactions) {
                case 1:
                    reactCounter.textContent = numReactions + " reaction" ;
                    break ;
                default:
                    reactCounter.textContent = numReactions + " reactions" ;
                    break ;
            }
            reactListContainer.appendChild(reactCounter) ;

            //Create the reaction list container
            const reactList = document.createElement("div") ;
            reactList.setAttribute("class", "react-list hidden") ;

            const reactLikeCount = document.createElement("p") ;
            reactLikeCount.setAttribute("class", "react-p") ;
            switch (numLikes) {
                case 1:
                    reactLikeCount.textContent = numLikes + " like" ;
                    break ;
                default:
                    reactLikeCount.textContent = numLikes + " likes" ;
                    break ;
            }
            reactList.appendChild(reactLikeCount) ;

            const reactLoveCount = document.createElement("p") ;
            reactLoveCount.setAttribute("class", "react-p") ;
            switch (numLoves) {
                case 1:
                    reactLoveCount.textContent = numLoves + " love" ;
                    break ;
                default:
                    reactLoveCount.textContent = numLoves + " loves" ;
                    break ;
            }
            reactList.appendChild(reactLoveCount) ;

            const reactLaughCount = document.createElement("p") ;
            reactLaughCount.setAttribute("class", "react-p") ;
            switch (numLaughs) {
                case 1:
                    reactLaughCount.textContent = numLaughs + " laugh" ;
                    break ;
                default:
                    reactLaughCount.textContent = numLaughs + " laughs" ;
                    break ;
            }
            reactList.appendChild(reactLaughCount) ;

            const reactMadCount = document.createElement("p") ;
            reactMadCount.setAttribute("class", "react-p") ;
            switch (numMad) {
                case 1:
                    reactMadCount.textContent = numMad + " mad" ;
                    break ;
                default:
                    reactMadCount.textContent = numMad + " mads" ;
                    break ;
            }
            reactList.appendChild(reactMadCount) ;

            reactCounter.appendChild(reactList) ;
            commentDiv.appendChild(reactListContainer) ;
        }

        //Add the reply text thingy
        const textDiv = document.createElement("div") ;
        textDiv.setAttribute("class", "reply_box") ;
        commentDiv.appendChild(textDiv) ;

        //Do recursion on children
        if (comment.children && comment.children.length > 0) {
            comment.children.forEach(child => {
                const childDiv = printComment(child) ;
                commentDiv.appendChild(childDiv) ;
            }) ;
        }

        return commentDiv ;
    }

    async function updateReaction(button) {
        <?php
            //If the user isn't logged in, redirect them to login
            if (!isset($_SESSION["username"])) {
                echo "window.location.href = '/login.php' ;" ;
                echo "return ;" ;
            }
        ?>

        const reactionType = button.getAttribute("data-type") ;
        const commentId = button.getAttribute("data-id") ;

        const reactData = {
            comment_id: commentId,
            reactType: reactionType,
            <?php 
                if (isset($_SESSION["username"])) {
                    echo "username: " . "'" . htmlspecialchars($_SESSION["username"]) . "'," ;
                } else {
                    echo "username: null," ;
                }
            ?>
        } ;

        const reactResponse = await fetch('upload.php/comment/react', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(reactData)
        }).catch(err => console.error("Fetch error:", err)) ;

        const reactResult = await reactResponse.json();

        if (reactResult.status === "success") {
            renderComments() ;
        } else {
            return ;
        }

    }

    function addTextboxToComment(commentDiv) {
        //Return if there's already a text box
        if (commentDiv.querySelector(".reply_textbox")) {
            return ;
        }

        const textDiv = commentDiv.querySelector(".reply_box") ;

        //Add the reply text area
        const textArea = document.createElement("textarea") ;
        textArea.setAttribute("name", "comment") ;
        textArea.setAttribute("placeholder", "Reply to this comment") ;
        textArea.setAttribute("class", "reply_textbox")
        textDiv.appendChild(textArea) ;

        //Add the post button
        const submitButton = document.createElement("button") ;
        submitButton.setAttribute("class", "submit-comment") ;
        submitButton.setAttribute("name", "submit_comment") ;
        submitButton.textContent = "Post comment" ;
        textDiv.appendChild(submitButton) ;

        //Add the cancel button
        const cancelButton = document.createElement("button") ;
        cancelButton.setAttribute("class", "cancel-comment") ;
        cancelButton.setAttribute("name", "cancel_comment") ;
        cancelButton.textContent = "Cancel" ;

        cancelButton.addEventListener("click", () => {
            textArea.remove() ;
            submitButton.remove() ;
            cancelButton.remove() ;
        }) ;

        textDiv.appendChild(cancelButton) ;
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
            <?php
                //If the user isn't logged in, redirect them to login
                if (!isset($_SESSION["username"])) {
                    echo "window.location.href = '/login.php' ;" ;
                    echo "return ;" ;
                }
            ?>

            //Submission of a comment
            const button = event.target ;
            const commentDiv = button.closest('div.comment') ;
            const textarea = commentDiv.querySelector('textarea') ;
            const text = textarea.value.trim() ;
            const parentId = commentDiv.querySelector('input[type="hidden"][name="comment_id"]') ;

            if (!text) return ;

            const commentData = {
                list_id: <?php echo $list_id ?>,
                <?php 
                    if (isset($_SESSION["username"])) {
                        echo "username: " . "'" . htmlspecialchars($_SESSION["username"]) . "'," ;
                    } else {
                        echo "username: null," ;
                    }
                ?>
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
                renderComments() ;
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
                renderComments() ;
                return ;
            } else {
                return ;
            }
        }
    }) ;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const favoriteButton = document.getElementById('favoriteButton');
    const favoriteIcon = document.getElementById('favoriteIcon');
    const favoriteText = document.getElementById('favoriteText');
    const listingId = <?php echo json_encode($list_id); ?>;
    const loggedInUsername = <?php echo json_encode($_SESSION['username'] ?? ''); ?>;

    if (!loggedInUsername) {
        favoriteButton.addEventListener('click', function() {
            window.location.href = '/login.php'; // Redirect if not logged in
        });
        return;
    }

    // Check if already favorited
    fetch('favorite_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            listing_id: listingId,
            favorite_action: 'check'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.favorited) {
            favoriteIcon.src = 'minecraft-red-heart.png';
            favoriteText.textContent = 'Remove from Favorites';
        } else {
            favoriteIcon.src = 'minecraft-black-heart.png';
            favoriteText.textContent = 'Add to Favorites';
        }
    });

    favoriteButton.addEventListener('click', function() {
        const isFavorited = favoriteIcon.src.includes('red-heart');

        fetch('favorite_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                listing_id: listingId,
                favorite_action: isFavorited ? 'unfavorite' : 'favorite'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (isFavorited) {
                    favoriteIcon.src = 'minecraft-black-heart.png';
                    favoriteText.textContent = 'Add to Favorites';
                } else {
                    favoriteIcon.src = 'minecraft-red-heart.png';
                    favoriteText.textContent = 'Added to Favorites';
                }
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred.');
        });
    });
});
</script>
