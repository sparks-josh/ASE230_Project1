<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jira_lite";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieved from PowerPoint slide access mysql using php simple_php_server_with_mysql

?>