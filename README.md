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
     FOREIGN KEY (`username`) REFERENCES users(`username`)
     ALTER TABLE listings MODIFY id INT NOT NULL AUTO_INCREMENT;
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 ```
 **Foreign Key Relationship:**  
 The column `username` in `listings` references the `username` in the `users` table.
 
 
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




