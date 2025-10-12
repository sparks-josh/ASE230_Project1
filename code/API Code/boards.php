<?php
switch ($method) {
    case 'GET':
        if ($id) { get_board($id); } 
        else { get_all_boards(); }
        break;
    case 'POST':
        create_board();
        break;
        // create board needs to be secure api
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
function get_board($id) {
    $boards = load_boards();
    
    foreach ($boards as $board) {
        if ($board['id'] == $id) {
            echo json_encode([
                'success' => true,
                'data' => $board
            ]);
            return;
        }
    }
    
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'Board not found'
    ]);
}

function get_all_boards() {
    $boards = load_boards();
    echo json_encode([
        'success' => true,
        'data' => $boards,
        'count' => count($boards)
    ]);
}
function create_board() {
    $input = getRequestData();
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }

    $boards = load_boards();

    $new_id = get_next_id($boards);

    $new_board = new board();
    $new_board->setId($new_id);
    $new_board->setName($input['name'] ?? '');

    $boards[] = $new_board->toArray();
    
    save_boards($boards);
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Board created successfully',
        'data' => $new_board->toArray()
    ]);
}
// Retrieved from PowerPoint rest api build rest api server with php
?>