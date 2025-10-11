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

// Retrieved from PowerPoint rest api build rest api server with php