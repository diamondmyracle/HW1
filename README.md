# HW1
 Diamond, Lauren, and Austin's COMP333 HW1
 
 
 Title: Diamond Real Estate
 
 Purpose of the Code:
 - We created a website which is (hypothetically) a platform for Minecraft users to buy real estate to live in on the site OR for builders/sellers of houses to list Minecraft houses for sale 
 
 
 What's Contained in the Repo?
 - index.html : the html for our landing page
 - listings.html : the html for our listings page
 - login.html : the html for our login page
 - signup.html : the html for our signup page
 - timeline.html : the html for our update timeline page (accessed through the 2nd to last question in the FAQ through hyperlink)
 - ad.html : the html for the ad page which is linked through the iframe on index.html
 - cssForIndex.css : the css for our index and timeline.html and used throughout each html file (for the recurring navbar styling code)
 - listings.css : the css for our listings.html
 - signup.css : the css for our signup.html
 - login.css : the css for our login.html
 - script.js : the JavaScript file (used for the FAQ section on index.html) 
 - diamond.png : the image of the diamond icon used for listings.html
 - .gitignore
 - LICENSE : MIT license 
 - README.md
 
 How to Run the Code: 
 - can be run locally on your computer by cloning the code through Github
     OR
 - can copy the link to the website and paste it into a search browser
  (https://diamondmyracle.github.io/HW1/)
 
 
 Estimate of Teammate contribution: 
 Diamond/Lauren/Austin
 33/33/33
 equal contribution 
 
 
 
 
 
 ## HW2
 
 Lauren:
 <img width="1440" alt="Screenshot 2025-03-01 at 5 02 58 PM" src="https://github.com/user-attachments/assets/b82177bf-b6fa-477e-8330-8321de4978bb" />
 Diamond:
 ![Screenshot](https://github.com/user-attachments/assets/08766a9f-ae60-4cf8-84fa-285df6ab9fc4)
 Austin: 
 ![Screenshot(2)](https://github.com/user-attachments/assets/e2b2ac83-c1cc-4b2f-8188-fc18fd589b60)
 
 
 Link to InfinityFree: https://diamondrealestate.infy.uk/index.php
 - ^^link to the active website!!
 
 ## 1. Install and Configure XXAMP
 Install XAMPP from here: [https://sourceforge.net/projects/xampp/files/XAMPP%20Mac%20OSX/8.0.2/](https://sourceforge.net/projects/xampp/files/XAMPP%20Mac%20OSX/8.0.2/). After installation, locate the `xampp` folder in your Applications. Open the `xamppfiles` folder and run `manager-osx`. Start MySQL Database and Apache Web Server under the Manage Servers tab.
 
 ## 2. Set up SQL Database
 
 **Create `app-db` Database:**
 ```sql
     CREATE DATABASE `app-db`;
 ```
 
 **Create `users` Table:**
 ```sql
 CREATE TABLE `app-db`.`users` (
     `username` VARCHAR(255) NOT NULL,
     `password` VARCHAR(255) NOT NULL,
     PRIMARY KEY (`username`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 ```
add this line 
```
ALTER TABLE `users` ADD `acc_balance` INT(11) NOT NULL AFTER `password`,
ADD `user_descript` VARCHAR(255) NOT NULL AFTER `acc_balance`;
```
Whether you're having issues signing up after adding these two lines of code above^ or not, enter in these lines below:
```
ALTER TABLE users 
MODIFY acc_balance INT DEFAULT 1000,
MODIFY user_descript TEXT NOT NULL DEFAULT 'change your description';
```
 
 **Create `listings` Table:**
 ```sql
 CREATE TABLE `app-db`.`listings` (
     `id` VARCHAR(23) NOT NULL,
     `username` VARCHAR(255) NOT NULL,
     `listing_name` VARCHAR(255) NOT NULL,
     `listing_descript` VARCHAR(255) NOT NULL,
     `price` INT(11) NOT NULL,
     `image` LONGBLOB DEFAULT NULL,
     PRIMARY KEY (`id`),
     INDEX username_idx (`username`),
     FOREIGN KEY (`username`) REFERENCES users(`username`));
```
add these lines
```
     ALTER TABLE listings 
     ADD sold BOOLEAN NOT NULL DEFAULT FALSE AFTER image,
     MODIFY id INT NOT NULL AUTO_INCREMENT;
 ```
 **Foreign Key Relationship:**  
 The column `username` in `listings` references the `username` in the `users` table.

 **Create `comments` Table:**
```sql
CREATE TABLE `app-db`.`comments` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    comment TEXT NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE SET NULL,
    reactions JSON NOT NULL
);
```

**Create `favorites` Table**
```sql
CREATE TABLE  `app-db`.`favorites` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    listing_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE
);

```
 
 Pic of our local development environment!!
 
 ![PNG image](https://github.com/user-attachments/assets/f724b714-a4af-4745-9835-8459fa2ff580)
 
 
 
 How to Navigate the Website: 
 
 - Feel free to tap anything on the navbar, it all works!!
 - From home, you can choose to scroll farther through the home page or navigate to sign-up, log-in (if you've already made a log-in), the FAQ (on the home page), or to the listings page (although you won't be able to create or edit listings until you're logged in)
 - From Sign-up: enter your desired username and password (don't worry, you'll be notified if your username is taken already), and also re-enter your password for verification! Once you've signed up, you will be automatically logged into that same account to navigate the rest of the website as a logged-in user
 - From Log-In: log in with pre-existing username and password (our website will alert you if you enter in a wrong username/password or don't complete the fields necessary). Once you've logged in you can create listings or edit existing listings you've created under the same user!!
 - From Listings: if you're not logged in, you can view all listings but not edit or create any. Once you're logged in you may create as many listings as you like (just fill in all necessary fields) and also edit the listings you've created under this user
 - NOTE!!!!: if you're on Mac, before you upload a picture file for creating a listing you may need to run this command in your terminal to give make the uploads folder writeable to the browser:
 
 
 
   chmod 777 /Applications/XAMPP/xamppfiles/htdocs/uploads
 
 
 
 - Once logged in, feel free to click "Logout(yourUsername)" at any point and you'll need to sign-up or log-in again to access listing capabilities again!
 
 ...................................
 
 - Extra Note: We may need to create our release as v1.1.0 (instead of v1.0.0) because we already made a v1.0.1 release for hw1
 
 
 
 Notes of our AI usage:
 
 In general we got help from ChatGPT for: 
 - describing + fixing bugs in our code (for login.php and signup.php, such as issues with our navbar) 
 - why we couldn't style an input type (newlisting.php)
 - why $_GET wasn't working with a form action
 - how to implement photo uploads (for our listings)
 - more debugging with accessing uploads file 
 - help with implementing the while loop for listings.php
 - some help with running SQL queries (in the beginning when we were more unfamiliar with them) 
 
 With regard specifically to HW3:
 - Some general debugging
 - Understanding REST APIs
 - Implementation of a RESTful API
 - Fetch requests
 - React code
 
 
 ## HW3 - Frontend
 
 Postman Request Formation Tutorial (Professor Zimmeck + TA's should have access to the doc):
 https://docs.google.com/document/d/1GcqnH0rqUPqsn9haRuXQgcwP4XuIFbjTooJkHu-2u14/edit?usp=sharing 
 
 
 Postman Request Screenshots
 
 
 Lauren: 
 <img width="1440" alt="Screenshot 2025-04-11 at 4 21 40 AM" src="https://github.com/user-attachments/assets/31001a8d-85fd-4a3a-8aef-698852025987" />
 <img width="1440" alt="Screenshot 2025-04-11 at 4 21 48 AM" src="https://github.com/user-attachments/assets/50f18fe5-c2b1-49ca-9e92-fe90eca24aa7" />
 Diamond: 
 
 
 Diamond:
 <img width="1439" alt="diamondSC1" src="https://github.com/user-attachments/assets/255d3e05-6fcb-4910-b811-63b36ad4e067" />
 <img width="1439" alt="diamondSC2" src="https://github.com/user-attachments/assets/f583beb8-aa3f-49ed-8da5-90c7048acfde" />
 
 
 Austin:
 ![Screenshot_1908](https://github.com/user-attachments/assets/6fb40258-8b03-4d48-a841-9ded97bf2d5a)
 ![Screenshot_1909](https://github.com/user-attachments/assets/4f598986-61e0-44d5-b0cf-45f8feae92b3)
 

To Integrate Our Frontend and Backend: 
(Note: Don't forget to follow previous instructions in the README, especially those for creating the database)
1. Clone our repository 
2. Move only the non-React files into your htdocs (so everything in our repo besides "diamondrealestate-reactapp")
3. Open your terminal and (first navigate to whatever directory you want to create the app in then) run "npx create-expo-app@latest DiamondRealEstate" to create the expo app. This will create a folder called "DiamondRealEstate" that will have any starter code needed for a React app
4. Note: Have "diamondrealestate-reactapp" in a separate directory/path than htdocs
5. Next, in Finder/(whatever your files app is) navigate into the "diamondrealestate-reactapp" folder wherever you have it
6. Then, select and copy all the files in "diamondrealestate-reactapp" into "DiamondRealEstate" (this will replace any of those starter files that we've modified, but also because you created the start app yourself you'll have other assets like the node-module folder which we can't upload to Github because of its size) 
7. Next, open Android Studio and in the top-right corner, near "Clone Repository", press the button with "..." and select "Virtual Device Manager"
8. Once you're taken to the Virtual Device Manager Screen, select the "+" button and search for "Pixel 6a" and select that and then "Next" in the bottom-right. Then, select "Finish"
9. Now that your virtual device is created, press the play button for it to start it (the emulator should pop up on your screen, wait for it to completely boot up)
10. Now, in your terminal (in which you've navigated to "DiamondRealEstate", enter in "npx expo start"
11. After doing this, you may get an error. If you do, just run either of the commands the terminal suggest to move past and then rerun "npx expo start"
   <img width="798" alt="Screenshot 2025-04-16 at 3 16 02 AM" src="https://github.com/user-attachments/assets/ff488cad-4921-4e5a-9525-45151ff49532" />

12. Then, once it's started correctly (showing the QR code, available commands, expo url, etc.), type "a" (as in the given commands, without the quotes) and now Expo will install onto your emulator and our React App should be start running on the emulator after it finishes the bundling
13. You should see our default screen which will ask you to either navigate to listings ("GO TO LISTINGS") or create a new listing ("CREATE NEW LISTING"); select either as you wish
14. In the "DiamondRealEstate" folder, navigate to app>utils>api.ts and change the IP address in "const API_BASE = 'http://IP_ADDRESS';" to your own IP address (Zimmeck explained in the hw3 assignment pdf how to do so, but otherwise, just go to Wi-Fi>>Wi_Fi Settings...>>(the ... by whatever network you're connected to)>>Network Settings. There you will find you IP address, just copy and paste it in place of "IP_ADDRESS" (although in the actual code you may see a preexisting IP address as opposed to "IP_ADDRESS").
15. ^^Also, add the port your Apache WebServer is running on (can check this in XAMPP, but probably will be ":8080" or ":80") to the end of your IP address (so, for ex. "http://172.21.146.71:8080")
16. If the above doesn't work, repeat the following:
"Hail Mary, full of grace, the Lord is with thee. Blessed art thou among women, and blessed is the fruit of thy womb, Jesus. Holy Mary, mother of God, pray for us sinners now and at the hour of our death. Amen."

#MAKE SURE DiamondRealEstate file is NOT in your HTDOCS, THIS IS A SEPARATE FILE

Attached is app.zip and App.tsx. You should replace the app folder inside of DiamondRealEstate with the unzipped contents of app.zip. Then create file App.tsx and place it inside the DiamondRealEstate folder.
app.zip : [app.zip](https://github.com/user-attachments/files/19775446/app.zip)
App.tsx contents:
```javascript
import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import Navigation from './navigation/Navigation';

export default function App() {
  return (
    <NavigationContainer>
      <Navigation />
    </NavigationContainer>
  );
}
```
Here are examples of the app logging in and signing up:
<img width="1388" alt="Screenshot 2025-04-16 at 5 41 13 AM" src="https://github.com/user-attachments/assets/0e081aa5-0ea0-411b-a1e4-9f12685c8766" />
<img width="1388" alt="Screenshot 2025-04-16 at 5 33 53 AM" src="https://github.com/user-attachments/assets/50dc4bd8-0bd9-41fb-bf87-245fd7a3a64b" />
<img width="1388" alt="Screenshot 2025-04-16 at 5 34 20 AM" src="https://github.com/user-attachments/assets/ceef6e2c-aef7-4548-aeb4-95713721a53e" />

We equally contributed! 

We used ChatGPT to help start the foundation for how to begin the mobile frontend and being able to set up expo and react native. Used ChatGPT to help with debugging.


## Project (HW4)

## Setting Up The Unit Tests
Make sure you have the following downloaded in your terminal as per Zimmeck's tutorial:

1. If you do not have it yet, install [homebrew](https://brew.sh/) with:

   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```

2. Install PHPUnit with:

   ```bash
   brew install phpunit
   ```

3. Install Composer with:

   ```bash
   brew install composer
   ```

4. If necessary, you can install PHP with:

   ```bash
   brew install php
   ```
4.5 : Install guzzle:
      ```composer require guzzlehttp/guzzle```
5. In the root folder (so, htdocs which you've copied all our code into), of your diamondRealEstate backend code, which contains your
   `index.php`, create a new folder, and name it `test-project` 
   From inside `test-project` folder, start Composer by running:

   ```bash
   composer init
   ```

6. the packahge you will used is ```phpunit/phpunit```, skip following steps until version is asked.
7. Version used will be ```^10.4```, skip following steps until you need to confirm generation.
8. Type ```yes``` to confirm generation, skip installing dependencies.
   
When you do "composer init" inside the `test-project` folder, many other files will be added. In the repo, we have provided the `test-project` folder which contains the `tests` folder and `phpunit.umx` file. The `tests` folder contains the unit test file `UserAPITest.php`. At this point your `test-project` folder should look like the following:

<img width="940" alt="Screenshot 2025-04-20 at 2 41 32 PM" src="https://github.com/user-attachments/assets/3375d6c6-88b4-466d-8dee-01c0314acc83" />

To be able to test the unit test do this command:

```
./vendor/bin/phpunit
```

**NOTE**: 
We needed to change the HTTP response in the `UserController` and `ListingController` file from 200 to 201 depending on the test. So make sure to clone the repo for the most up to date version of the files.

**Additional Note**
For the unit test:
```
 public function testPost_LoginUser(): void {
        $data = json_encode([
            'username' => 'diamond', // use an existing user
            'psw' => '1234567890'
        ]);

        $ch = curl_init("{$this->baseUrl}/login.php/user/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(201, $status);
    }
```
If you already have an account, replace the `'username' = ` with username you have created and do the same for `psw`. Otherwise, if you don't want to update the code, signup with an account with the username `diamond` and password `1234567890`.

# Question 2 - Talks with ChatGPT

After successfully completing the unit tests for testGet_UserList, testPost_CreateUser, testPost_LoginUser, testPost_FailedLogin. I asked ChatGPT how we would be able to make a unit test to test for listing creation. I placed this request into chat:

```
ok given these correct unit test:

<?php

use PHPUnit\Framework\TestCase;

class UserApiTest extends TestCase
{
    private $baseUrl = "http://localhost"; 

    public function testGet_UserList(): void {
        $ch = curl_init("{$this->baseUrl}/signup.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(200, $status);
    }

    public function testPost_CreateUser(): void {
        $data = json_encode([
            'username' => 'testuser_' . rand(1000, 9999),
            'psw' => 'testpassword'
        ]);

        $ch = curl_init("{$this->baseUrl}/signup.php/user/create");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(201, $status);
    }

    public function testPost_LoginUser(): void {
        $data = json_encode([
            'username' => 'diamond', // use an existing user
            'psw' => '1234567890'
        ]);

        $ch = curl_init("{$this->baseUrl}/login.php/user/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(201, $status);
    }

    public function testPost_FailedLogin(): void {
        $data = json_encode([
            'username' => 'not_valid_user',
            'psw' => 'wrong_password'
        ]);

        $ch = curl_init("{$this->baseUrl}/login.php/user/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(201, $status);
    }

    public function testPost_CreateListing(): void {
        $data = json_encode([
            'listing_name' => 'Test Listing',
            'listing_descript' => 'This is a test listing.',
            'price' => 150,
        ]);

        $ch = curl_init("{$this->baseUrl}/listing.php/user/create");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->assertEquals(201, $status);
    }
}


create one for ListingCreate i suppose
```

To which I received the code:

```
public function testPost_CreateListing(): void {
    $data = json_encode([
        'listing_name' => 'Test Listing',
        'listing_descript' => 'This is a test listing.',
        'price' => 150,
        'username' => 'diamond' // add if your backend requires it
    ]);

    $ch = curl_init("{$this->baseUrl}/newlisting.php/listing/create");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $this->assertEquals(201, $status);
}
```
This code is correct, however, it doesn't account for out `ListingController.php` and the http response we received which was a 200 OK instead of 201. Going into that code and changing the response then gave a correct test result in the terminal.

# Question 3 - New Things On Diamond Real Estate

We have implemented 5 new features to the website, that being individual listing page viewing, a favoriting system, profile pages, commenting, and buying functionality.

**Individual Listing Page Viewing**:
With this feature, when you navigate to the listings page via the navbar, you can click on the listings created and you get more information of the listing. When you get to the page of the individual listing, you are able to buy the listing, favorite it and even comment on it. (make sure you are logged in!) You are also able to access your profile page and the other users profile pages by clicking on the author of the listing.
**(NOTE: the first 4 listings are hardcoded and we have a special attatchment to them to get rid of them)**

**Favoriting System**:
You can add a listing to your favorites and it increments each time someone favorites a listing and it also decrements when you remove the listing from your favorites. From your profile page, all your favorites are placed in one place. You are able to click on those favorites and it will bring you back to the individual listing page. 

**Commenting**:
You are able to comment on the individual lisitng pages. You can reply to comments and create a chain, edit, and delete your comment. There is also the ability to add emoji reaction to a comment and the comment holds the information for how many reactions it has recieved. There is also a timestamp that says how long ago the comment was posted.

**Profile Page**:
On the profile page which can be found either through the individual listing pages or at the navbar, you are able to see your profile picture (Steve), the listings you have posted, and your favorite listings. You are also able to see your account diamond balance for making future purchases as well as see your profile description that other players can also see. You are able to edit your profile description.

**Buying Functionality**:
 When you are logged into your account and you see a listing you like, you can buy it. When you click Buy you then lose some of your diamonds and the owner of the listing gains them (works like any transaction). When you signup with an account, you start with 1000 diamonds and your balance can once again be seen in your profile page. Only the logged in user can see their account balance and favorites.

**(NOTE: These functions are also implemented as rest API)**

We all contributed equally to this portion of the project. 

We used ChatGPT to debug and help implement different functionality we have not encountered.



