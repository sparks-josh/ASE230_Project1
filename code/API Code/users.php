<?php
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
$segments = explode('/', $path);
$resource = $segments[0] ?? ''; 
$id = $segments[1] ?? null; 
switch ($method) {
    case 'GET':
        if ($id) { get_user($id); } 
        else { get_all_users(); }
        break;
    case 'POST':
        create_user();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
function get_user($id) {
    $users = load_users();
    
    foreach ($users as $user) {
        if ($user['id'] == $id) {
            echo json_encode([
                'success' => true,
                'data' => $user
            ]);
            return;
        }
    }
    
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'User not found'
    ]);
}

function get_all_users() {
    $users = load_users();
    echo json_encode([
        'success' => true,
        'data' => $users,
        'count' => count($users)
    ]);
}
function create_user() {
    $input = getRequestData();
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }

    $users = load_users();

    $new_id = get_next_id($users);

    $new_user = new user();
    $new_user->setId($new_id);
    $new_user->setName($input['name'] ?? '');

    $users[] = $new_user->toArray();
    
    save_users($users);
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'User created successfully',
        'data' => $new_user->toArray()
    ]);
}
// Retrieved from PowerPoint rest api build rest api server with php
?>