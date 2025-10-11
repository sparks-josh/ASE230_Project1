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
}

// Retrieved from PowerPoint rest api build rest api server with php