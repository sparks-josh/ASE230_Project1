---
marp: true
paginate: true
---

# Project 1 NGINX Deployment Slides
Joshua Sparks, Northern Kentucky University

Prepared for: Prof. Samuel Cho, ASE 230, Fall 2025

---

## Overview:

The goal of this part of the project was to deploy the REST APIs with **NGINX** in accordance to the following constraints: 
- Deployment steps/tutorial documented in Marp slide (and converted into PDFs)
- full points if marp file is made with screen captures, no partial points
- A screen capture of your NGINX server working should be included 
- Saved in `presentation/` directory  

---

## Steps 
1. Locate the nginx.conf file, this can be done by executing `brew info nginx`. Example location: 
   
        /opt/homebrew/etc/nginx

2. CD to the correct file path. Example command:
   
        cd /opt/homebrew/etc/nginx

3. Perform `nano nginx.conf` to edit the file. Example command:

       nano nginx.conf

---

### Screen Captures:

1. <img src="Screen Captures/Step 1.png" width="700">
2. <img src="Screen Captures/Step 2.png" width="700">
3. <img src="Screen Captures/Step 3.png" width="700">

---



4. Replace the server block with the following:

        server {
        listen 8080;
        server_name localhost;
        root "/Users/joshsparks/Desktop/Fall 2025 Schoolwork/ASE 230 Server-Side Programming/project1-4/ASE230_Project1/code"; # Adjust path for your system
        index index.php index.html index.htm;
        
        location / {
            try_files $uri $uri/ =404;
        }
            
        location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;  # PHP-FPM address
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        }


---

### Screen Capture:

4. <img src="Screen Captures/Step 4.png" width="800"> 

---

5. Restart NGINX with the following command: 
   
        nginx -s reload


6. If php is not running, run the following command:
   
         brew services start php

7. To test NGINX is working properly, go to the following address on your web browser: 
   
        http://localhost:8080/your_file_path/file_you_want_to_test

---

### Screen Captures:

5. <img src="Screen Captures/Step 5.png" width="800">
6. <img src="Screen Captures/Step 6.png" width="900">


---

## My first web address test: 
http://localhost:8080/HTML_JavaScript_Tests/issues_api_tests.html

## Screen Capture: 
<img src="Screen Captures/Issues_PHP_Test.png" width="500">


---

## My second web address test: 
http://localhost:8080/HTML_JavaScript_Tests/users_api_tests.html

## Screen Capture:
<img src="Screen Captures/Users_PHP_Test.png" width="800">

---

## My third web address test: 
http://localhost:8080/HTML_JavaScript_Tests/boards_api_tests.html

## Screen Capture:
<img src="Screen Captures/Boards_PHP_Test.png" width="800">

---


## References: 
Setting up NGINX For PHP.md, pages 15, 16, 21, 25, 26
