<?php
switch ($method) {
    case 'GET':
        if ($id) { get_issue($id); } 
        else { get_all_issues(); }
        break;
    case 'POST':
        if ($id && isset($segments[2]) && $segments[2] === 'comments') {
            add_issue_comment($id);
        } else {
            create_issue();
            // This is POST /issues → create new issue
        }
        // create issue and add comment need to be secure api
        break;
    case 'PUT':
        if ($id) { update_issue($id); } 
        else {
            http_response_code(400);
            echo json_encode(['error' => 'Issue ID required']);
        }
        //update issue needs to be secure api
        break;
    case 'PATCH':
        if ($id && isset($segments[2]) && $segments[2] === 'status') { update_issue_status($id); } 
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
function get_issue($id) {
    $issues = load_issues();
    
    foreach ($issues as &$issue) {
        if ($issue['id'] == $id) {
            echo json_encode([
                'success' => true,
                'data' => $issue
            ]);
            return;
        }
    }
    
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'Issue not found'
    ]);
}

function get_all_issues() {
    $issues = load_issues();
    echo json_encode([
        'success' => true,
        'data' => $issues,
        'count' => count($issues)
    ]);
}
function create_issue() {
    $input = getRequestData();
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }

    $issues = load_issues();

    $new_id = get_next_id($issues);

    $new_issue = new issue();
    $new_issue->setId($new_id);
    $new_issue->setName($input['name'] ?? '');
    $new_issue->setStatus($input['status'] ?? '');

    $issues[] = $new_issue->toArray();
    
    save_issues($issues);
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Issue created successfully',
        'data' => $new_issue->toArray()
    ]);
}

function update_issue($id) {
    $input = getRequestData();
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }
    $issues = load_issues();
    foreach ($issues as &$issue) {
        if ($issue['id'] == $id) {
            if (isset($input['name'])) $issue['name'] = $input['name'];
            if (isset($input['status'])) $issue['status'] = $input['status'];
       
            save_issues($issues);
            echo json_encode([
                'success' => true,
                'message' => 'Issue updated successfully',
                'data' => $issue
            ]);
            return;
        }
    }
}
function update_issue_status($id) {
    $input = getRequestData();
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }
    $issues = load_issues();
    foreach ($issues as &$issue) {
        if ($issue['id'] == $id) {
            if (isset($input['status'])) $issue['status'] = $input['status'];
       
            save_issues($issues);
            echo json_encode([
                'success' => true,
                'message' => 'Issue updated successfully',
                'data' => $issue
            ]);
            return;
        }
    }
}
function add_issue_comment($id) {
    $input = getRequestData();
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }
    $issues = load_issues();
    foreach ($issues as &$issue) {
        if ($issue['id'] == $id) {
            if (isset($input['comment'])) $issue['comment'] = $input['comment'];
       
            save_issues($issues);
            echo json_encode([
                'success' => true,
                'message' => 'Issue updated successfully',
                'data' => $issue
            ]);
            return;
        }
    }
}
// Retrieved from PowerPoint rest api build rest api server with php
?>