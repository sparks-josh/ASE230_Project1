<?php
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    exit;
}

// Retrieved from PowerPoint slide access mysql using php simple_php_server_with_mysql
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$segments = explode('/', $path);
$resource = $segments[0] ?? ''; 
$id = $segments[1] ?? null; 

switch ($method) {
    case 'GET':
        if ($id) { get_issue($conn, $id); } 
        else { get_all_issues($conn); }
        break;
    case 'POST':
        if ($id && isset($segments[2]) && $segments[2] === 'comments') {
            add_issue_comment($conn, $id);
        } else {
            create_issue($conn);
            // This is POST /issues → create new issue
        }
        // create issue and add comment need to be secure api
        break;
    case 'PUT':
        if ($id) { update_issue($conn, $id); } 
        else {
            http_response_code(400);
            echo json_encode(['error' => 'Issue ID required']);
        }
        //update issue needs to be secure api
        break;
    case 'PATCH':
        if ($id && isset($segments[2]) && $segments[2] === 'status') { update_issue_status($conn, $id); } 
        else {
           http_response_code(400);
           echo json_encode(['error' => 'Issue ID required']);
        }
        break;
        // update issue status needs to be secure api
    default:
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

function get_issue($conn, $id) {
    $sql = "SELECT * FROM issues WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Found issue: " . $row["id"] . " (Name: " . $row["name"] . ", Status: " . $row["status"] . ", Board: " . $row["board"] . ")<br>";
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
          " - Board: " . $row["board"]. 
          " - Status: " . $row["status"]. "<br>";
    }
    } else {
        echo "No Issues found<br>";
    }
    $stmt->close();
}


function create_issue($conn) {
    $name = $_POST['name'] ?? '';
    $board = $_POST['board'] ?? '';
    $status = $_POST['status'] ?? '';

    $sql = "INSERT INTO issues (name, board, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $board, $status);

    if ($stmt->execute()) {
        echo "Issue '$name' added successfully<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
    $stmt->close(); 
}

function update_issue($conn, $id) {
    $new_name = $_PUT['name'] ?? '';
    $new_board = $_PUT['board'] ?? '';
    $new_status = $_PUT['status'] ?? '';
    
    $sql = "UPDATE issues SET name = ?, board = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $new_name, $new_board, $new_status, $id);

    if ($stmt->execute()) {
        echo "Issue with ID $id updated successfully<br>";
    } else {
        echo "Error updating issue: " . $stmt->error . "<br>";
    }
    $stmt->close();
}

function update_issue_status($conn, $id) {
    $new_status = $_PATCH['status'] ?? '';
    
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
// Retrieved from PowerPoint rest api build rest api server with php and crud_operatoin
?>