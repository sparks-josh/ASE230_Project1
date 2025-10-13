<?php

//get_all_users test:
curl http://localhost:8000/users.php

//My output: Found 1 users:<br>ID: 2 - Name: Test<br>%




//get_user test:
curl http://localhost:8000/users.php/2

//My output: Found user: 2 (Name: Test) <br>%   




//create_user test:
curl -s -X POST -H "Content-Type: application/json" \ -d '{"name": "Test2","id": 1}' \ http://localhost:8000/users.php

//My output: joshsparks@MacBookAir API Code % 




//References:
//Testing REST API Server.md, pages 25, 28, 29, 31, 33, 36
?>