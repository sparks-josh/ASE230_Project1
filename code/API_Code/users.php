<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jira_lite";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    exit;
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$segments = explode('/', $path);
$resource = $segments[1] ?? ''; 
$id = $segments[2] ?? null; 

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if ($id) { get_user($conn, $id); } 
        else { get_all_users($conn); }
        break;
    case 'POST':
        create_user($conn);
        break;
    default:
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

function get_user($conn, $id) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Found user: " . $row["id"] . " (Name: " . $row["name"] . ") <br>";
    } else {
        echo "No User with ID $id<br>";
    }
    $stmt->close();
}

function get_all_users($conn) {
    $sql = "SELECT * FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
    echo "Found " . $result->num_rows . " users:<br>";
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Name: " . $row["name"]. "<br>";
    }
    } else {
        echo "No users found<br>";
    }
    $stmt->close();
}


function create_user($conn) {
    $name = $_POST['name'] ?? '';
    $id = $_POST['id'] ?? '';

    $sql = "INSERT INTO users (name, id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $id);

    if ($stmt->execute()) {
        echo "User '$name' added successfully<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
    $stmt->close(); 
}

// References:
// Simple_PHP_Server_with_MYSQL.md, page number 9
// Building a REST API Server with PHP.md, page numbers 13, 14, 15, 19, 31, 32, 33, 36
// CRUD_operation.md, page numbers 1, 3, 5, 6, 8, 
// PUT and POST Requests.md, page numbers 32, 34, 35, 36, 38, 39
// Bearer Token Authenticatoin.md, page numbers 11, 12, 14
?>