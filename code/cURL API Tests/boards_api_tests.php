<?php

//get_all_issues test:
curl http://localhost:8000/issues.php

//My output: Found 1 issues:<br>ID: 3 - Name: TestIssue - Board: 1 - Status: Open<br>%  




//get_issue test:
curl http://localhost:8000/issues.php/3

//My output: Found issue: 3 (Name: TestIssue, Status: Open, Board: 1)<br>%           




//create_issue test:
curl -s -X POST -H "Content-Type: application/json" \ -d '{"name": "TestIssue2","board_id": 2,"status": "Closed"}' \ http://localhost:8000/issues.php

//My output: joshsparks@MacBookAir API Code % 




//update_issue test: 
curl -s -X PATCH -H "Content-Type: application/json" \ -d '{"name":"TestIssue"}' \ http://localhost:8000/issues.php/3/name 

//My output: {"error":"Valid bearer token required"}% 




//update_issue_status test:
curl -s -X PATCH -H "Content-Type: application/json" \ -d '{"status":"Open"}' \ http://localhost:8000/issues.php/3/status

//My output: {"error":"Valid bearer token required"}%   





//add_issue_comment
curl -s -X POST -H "Content-Type: application/json" \ -d '{"comment": "Test comment"}' \ http://localhost:8000/issues.php/3/comments

//My output: joshsparks@MacBookAir API Code % 




//References:
//Testing REST API Server.md, pages 25, 28, 29, 31, 33, 36
?>