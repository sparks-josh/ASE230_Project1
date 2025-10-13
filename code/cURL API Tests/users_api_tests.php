<?php

//get_all_boards test:
curl http://localhost:8000/boards.php

//My output: Found 1 boards:<br>ID: 1 - Name: Test<br>%  




//get_board test:
curl http://localhost:8000/boards.php/1

//My output: Found board: 1 (Name: Test) <br>%  




//create_board test:
curl -s -X POST -H "Content-Type: application/json" \ -d '{"name": "Test2","id": 1}' \ http://localhost:8000/boards.php

//My output: joshsparks@MacBookAir API Code % 




//References:
//Testing REST API Server.md, pages 25, 28, 29, 31, 33, 36
?>