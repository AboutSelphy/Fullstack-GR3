# Cookie-Based Loginsystem


## create user / register.php
-   registerForm with file upload enabled
    -   input Fields
        -   first name text
        -   last name   text
        -   email   mail
        -   password    pwd
        -   status(enum) selection (option:user,option:admin,option:shelter)
            -   if shelter add additonal entry in shelter table
        -   adress text text
        -   zip (options by ajax)
        -   shelter (shelter)
        -   image file Upload (script/fileUpload.php)
    -   input Val (script/inputsVal.php)
    - create user. check if email already exists


## login user / index.php
<!-- -   create Session -->
<!-- -   if cookies allowed und no cookies -> setCookie Long lived, store sessionID of current Session into it. (function) -->
<!-- -   store sessionID in DB (function) -->


## logout user / logout.php
<!-- -   destroy session / delete entry in login table -->
<!-- -   return Markup-> after 3000ms redirect to landingpage (root/index.php) -->


## create GLOBALS
<!-- -   isUSER, isADMIN, isSHELTER -->

## SECURITY redirects when user is logged in or not
-   loginGate.php (function with fileName and Switch)
    <!-- -   check is cookie is set and get found in database -->
    -   isLoggedIn
        <!-- -   loginpage->dashboard -->
        -   register->dashboard
    -   !isLoggedIn
        -   createAninaml/editAnimal/deleteAnimal->login/index.php
        -   user/admin/dashboard->login/index.php
        -   animalList->login/index.php
        -   logout.php->index.php

