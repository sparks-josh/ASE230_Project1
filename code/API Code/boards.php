switch ($method) {
  case 'GET':
      if ($id) { get_board($id); } 
      else { get_all_board(); }
      break;
  case 'POST':
      create_board();
      break;
      // create board needs to be secure api
  default:
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

// Retrieved from PowerPoint rest api build rest api server with php