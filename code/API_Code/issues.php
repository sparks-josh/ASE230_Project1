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

function getBearerToken() {
    $headers = getallheaders();
    
    if (isset($headers['Authorization'])) {
        if (preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $matches)) {
            return trim($matches[1]);
        }
    }
    return null;
}

function isValidToken($token) {
    $validTokens = [
        'ase230' => 'ase230',
    ];
    return isset($validTokens[$token]) ? $validTokens[$token] : false;
}

function requireAuth() {
    $token = getBearerToken();
    if (!$token) {
           http_response_code(401);
           echo json_encode(['error' => 'Valid bearer token required']);
           exit;
        }
    
    $user = isValidToken($token);
    if (!$user) {
           http_response_code(401);
           echo json_encode(['error' => 'Valid bearer token required']);
           exit;
        }
    return $user;
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$segments = explode('/', $path);
$resource = $segments[1] ?? ''; 
$id = $segments[2] ?? null; 

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if ($id) { get_issue($conn, $id); } 
        else { get_all_issues($conn); }
        break;
    case 'POST':
        if ($id && isset($segments[2]) && $segments[2] === 'comments') {
            requireAuth();
            add_issue_comment($conn, $id);
        } else {
            requireAuth();
            create_issue($conn);
        }
        break;
    case 'PUT':
        requireAuth();
        if ($id) { update_issue($conn, $id); } 
        else {
            http_response_code(400);
            echo json_encode(['error' => 'Issue ID required']);
            exit;
        }
        break;
    case 'PATCH':
        requireAuth();
        if ($id && isset($segments[2]) && $segments[2] === 'status') { update_issue_status($conn, $id); } 
        else {
           http_response_code(400);
           echo json_encode(['error' => 'Issue ID required']);
           exit;
        }
        break;
    default:
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

function get_issue($conn, $id) {
    $sql = "SELECT * FROM issues WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Found issue: " . $row["id"] . " (Name: " . $row["name"] . ", Status: " . $row["status"] . ", Board: " . $row["board_id"] . ")<br>";
    } else {
        echo "No Issue found with ID $id<br>";
    }
    $stmt->close();
}

function get_all_issues($conn) {
    $sql = "SELECT * FROM issues";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
    echo "Found " . $result->num_rows . " issues:<br>";
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Name: " . $row["name"]. 
          " - Board: " . $row["board_id"]. 
          " - Status: " . $row["status"]. "<br>";
    }
    } else {
        echo "No Issues found<br>";
    }
    $stmt->close();
}


function create_issue($conn) {
    $name = $_POST['name'] ?? '';
    $board_id = $_POST['board_id'] ?? '';
    $status = $_POST['status'] ?? '';

    $sql = "INSERT INTO issues (name, board_id, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $name, $board_id, $status);

    if ($stmt->execute()) {
        echo "Issue '$name' added successfully<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
    $stmt->close(); 
}

function update_issue($conn, $id) {
    $raw_data = file_get_contents('php://input');
    $json_data = json_decode($raw_data, true);

    $new_name = $json_data['name'] ?? '';
    $new_board_id = $json_data['board_id'] ?? '';
    $new_status = $json_data['status'] ?? '';
    
    $sql = "UPDATE issues SET name = ?, board_id = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $new_name, $new_board_id, $new_status, $id);

    if ($stmt->execute()) {
        echo "Issue with ID $id updated successfully<br>";
    } else {
        echo "Error updating issue: " . $stmt->error . "<br>";
    }
    $stmt->close();
}

function update_issue_status($conn, $id) {
    $raw_data = file_get_contents('php://input');
    $json_data = json_decode($raw_data, true);
    $new_status = $json_data['status'] ?? '';
    
    $sql = "UPDATE issues SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $id);

    if ($stmt->execute()) {
        echo "Issue with ID $id status updated successfully<br>";
    } else {
        echo "Error updating issue: " . $stmt->error . "<br>";
    }
    $stmt->close();
}

function add_issue_comment($conn, $id) {
    $comment = $_POST['comment'] ?? '';

    $sql = "UPDATE issues SET comment = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $comment, $id);

    if ($stmt->execute()) {
        echo "Comment added to Issue $id successfully<br>";
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