<!--HW1: Diamond, Lauren, Austin 
Index file for the landing page-->
<?php
    session_start();
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
    }
    else{
        $username = "";
    }

    require __DIR__ . "/inc/bootstrap.php";
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    if ((isset($uri[2]) && $uri[2] != 'user') || !isset($uri[3])) {
        header("HTTP/1.1 404 Not Found");
    } else {
    require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
    $objFeedController = new UserController();
    $strMethodName = $uri[3] . 'Action';
    $objFeedController->{$strMethodName}();
    }
?>

<!DOCTYPE html>
<HTML lang="en">
    <HEAD>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width">
        <meta name="description" content="Diamond Real Estate: Find your dream house in Minecraft!">
        <TITLE>Diamond Real Estate Home</TITLE>
        <link href="cssForIndex.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700&display=swap" rel="stylesheet">
    </HEAD>

    <BODY>
        <div class="navbar">
            <a class="active" href="#home">Home</a>
            <a href="listings.php">Listing</a>
            <a href="#faq">FAQ</a>
            <?php if (!empty($username)): ?>
                 <a href="logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a>
            <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Signup</a>
            <?php endif; ?>
        </div>

        <div id="site-content" class="site-content">
            <div id="header">
                <h1>Welcome to Diamond Real Estate</h1>
                <p>Find your dream home in Minecraft!</p>
            </div>

            <!-- This is the about us section. Has some info about us -->
            <div id="aboutus">
                <h2>About Us</h2>
                <p> Diamond Braddy, Lauren Eldarazi, and Austin Bosch are real estate agents who want to help you find your dream home in Minecraft.
                    We have a wide range of listings from different biomes and we are sure you will find the perfect place for you! Buy and sell property!
                    All buyers have access to an agent who shall make the buying process easier.
                </p>
                <!-- A little disclaimer -->
                <p id="comp333disclaimer">This site was designed and published as part of the COMP333 Software Engineering class at Wesleyan University. 
                    This is an exercise. </p>
            </div>

            <!-- This is for our fake ad -->
            <div id="ourIframe">
                <div id="IframeUnit">
                 <iframe title="fakead2"
                    src="ad.html" 
                    width="850" height="250"  
                    frameBorder="0">
                </iframe>
                </div>
            </div>
            
            <!-- Reviews of the site -->
            <div id="reviews">
                <h2>
                    Read what users have to say about Diamond Real Estate!
                </h2>
                    <p>
                        We've serviced thousands of clients and provided them with their dream home. <br> Here are some testimonies from frequent clients.
                    </p>
                <div class="container">
                    <!-- Review number 1 -->
                    <div class="card">
                        <img src ="photos/vill_1.webp" alt="user" 
                        width = "200"
                        height = "200">
                        <div class="card_discript">
                            <p> 
                                I got a good home. 10/10 recommend, hmm
                            </p>
                            <h3>
                                -Villager
                            </h3>
                        </div>
                    </div>

                    <!-- Review number 2 -->
                    <div class="card">
                        <img src ="photos/vill_2.webp" alt="user" 
                        width = "200"
                        height = "200">
                        <div class="card_discript">
                            <p> 
                                Amazing agents to match the amazing home!
                            </p>
                            <h3>
                                -Another Villager
                            </h3>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Here are the various subscription options -->
            <div id="tierlist">
                <h2>Subscription Tier List</h2>
                <table>
                    <tr>
                        <!-- The top layer of the table -->
                        <th>Features</th>
                        <th class="highlight">Land Wanter (Free)</th>
                        <th class="highlight">Eager Land Wanter (Paid)</th>
                        <th class="highlight">Seller (Free)</th>
                        <th class="highlight">Best Seller In Town (Paid)</th>
                    </tr>
                    <tr>
                        <td>Access to Listings</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Priority Support</td>
                        <td>*</td>
                        <td>Yes</td>
                        <td></td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Higher Boosted Visibility</td>
                        <td></td>
                        <td>Yes</td>
                        <td></td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Sell Listings</td>
                        <td></td>
                        <td></td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Lower Transaction Fees</td>
                        <td></td>
                        <td>Yes</td>
                        <td></td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Access to All Reviews</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                </table>
                <p>*Priority support is only available for one hour with an agent</p>
            </div>

            <!--The FAQ section of the landing page with some commonly asked questions-->
            <div id="faq" class="FAQ">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-item">
                    <button class="faq-question">
                        How do I buy a property?
                        <span class="arrow">&#9660;</span>
                    </button>
                    <div class="faq-answer">
                        <p>You can buy a property by clicking on the listing and contacting the agent. The agent will help you through the buying process.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        How do I sell a property?
                        <span class="arrow">&#9660;</span>
                    </button>
                    <div class="faq-answer">
                        <p>You can sell a property by clicking on the listing and contacting the agent. The agent will help you through the selling process.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Is there any protection against griefing?
                        <span class="arrow">&#9660;</span>
                    </button>
                    <div class="faq-answer">
                        <p>We can not guarantee protection against griefing. If you have any issues, please contact support with concerns.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Does this break the End User License Agreement (EULA)?
                        <span class="arrow">&#9660;</span>
                    </button>
                    <div class="faq-answer">
                        <p>Yes.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Are these legit listings?
                        <span class="arrow">&#9660;</span>
                    </button>
                    <div class="faq-answer">
                        <p>We are not liable for anything.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        What updates can we expect from Diamond Real Estate?
                        <span class="arrow">&#9660;</span>
                    </button>
                    <div class="faq-answer" style="text-align: left;">
                        <ul>
                            <li>For Premium Buyers: Video Tours of Each Listing<br></li>
                            <li>A Save Listings Button<br></li>
                            <li><a href="timeline.php">And More! See the Full List of Updates Coming Here!!</a></li>
                        </ul> 
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                       Where can we stay updated with Diamond Real Estate?
                        <span class="arrow">&#9660;</span>
                    </button>
                    <div class="faq-answer" style="text-align: left;">
                        <ul>
                            <li><a href="https://www.instagram.com/diamondrealestate333/" target="_blank">Follow us on Instagram</a><br>
                            </li>
                            <li><a href="https://twitter.com/diamondrestate" target="_blank">Follow us on X</a><br></li>
                            <li><a href="https://www.linkedin.com/in/lauren-eldarazi/" target="_blank">Connect with one of our agents on Linkedin!</a></li>
                        </ul> 
                    </div>
                </div>
                
            </div>
        </div>
        <script src="script.js"> </script>
    </BODY>
    </HTML>
