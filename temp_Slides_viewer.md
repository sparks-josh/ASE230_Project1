---
marp: true
theme: default
class: lead
paginate: true
backgroundColor: #fff
---

<!-- _class: frontpage -->
<!-- _paginate: skip -->

# Frontend with JavaScript

Making Requests from HTML Pages to PHP APIs

---

<!-- TOC -->
- [How a Web Page Loads](#how-a-web-page-loads)
  - [Frontend \& Backend Interaction](#frontend--backend-interaction)
  - [What We're Building for Web Applications](#what-were-building-for-web-applications)
- [Example: Communication between Frontend and Backend](#example-communication-between-frontend-and-backend)
  - [Backend Code: index.php](#backend-code-indexphp)
  - [Frontend Code: test.html](#frontend-code-testhtml)
  - [Different Types of Requests](#different-types-of-requests)
  - [Error Handling Best Practices](#error-handling-best-practices)
  - [Updating the DOM with Results](#updating-the-dom-with-results)
- [Key Takeaways](#key-takeaways)
  - [Best Practices](#best-practices)
<!-- /TOC -->

---

## How a Web Page Loads

```txt
[HTML + JavaScript] ⟷ HTTP Requests ⟷ [PHP Server]
     (Frontend)                          (Backend)
```

- User requests a page in a **browser**  
  - We can use `cURL` to make the request.
- **Web server** (Apache/Nginx) routes `request` to **PHP**  
  - In our examples, we haven't used web servers, but we have used `PHP -S` to the local web server.
- In the server, PHP processes and `responses` (returns) **HTML, CSS, JS, or JSON**  
- **Browser** renders the page  
- After load, user interacts with **front end (JavaScript)**

---

### Frontend & Backend Interaction

- The **frontend (JavaScript)** can send extra requests  
  (AJAX, Fetch API) for APIs or dynamic content.  
- The same **PHP backend** or other API endpoints handle these requests.

---

**Web Application Frontend Tools We'll Use**:

- HTML for structure
- CSS for styling  
- JavaScript for API communication
- Fetch API for HTTP requests

---

### What We're Building for Web Applications

Frontend (Server)

1. **HTML Page**: User interface with buttons and forms
2. **JavaScript**: Makes HTTP requests to your PHP API

Backend (Server)

3. **PHP API**: Processes requests and responses (returns JSON).

---

## Example: Communication between Frontend and Backend

**Frontend** (`test.html`) ⟷ **Backend** (`index.php`)

---

How to run this example:

1. Start the PHP server

```bash
php -S localhost:8000
```

2. Open a web browser, and access the server.

<http://localhost:8000/test.html>

- The test.html is downloaded to the local (client side) web browser.
- The web browser parses the HTML file and runs the JavaScript code.

---

3. JavaScript has the code to make requests to server

```javascript
fetch('/index.php/api')
```

- The JavaScript code makes requests (GET /index.php/api) to the server where it is downloaded from (<http://localhost:8000>).

4. We can directly make GET request to the server by accessing the endpoint (index.php/api) with a query string (a=b&c=d) without using HTML/JavaScript.

<http://localhost:8000/index.php/api?a=b&c=d>

We can use a web browser, or we can use cURL to make the request.

```bash
curl http://localhost:8000/index.php/api
```

---

### Backend Code: index.php

Server/Backend (PHP) extracts the API information (api) from the GET request.

```php
// Get the request path and clean it up
$path = $_SERVER['REQUEST_URI']; // '/index.php/api?a=b&c=d'
// remove ?a=b&c=d
$path = parse_url($path, PHP_URL_PATH); // '/index.php/api'
$path = trim($path, '/'); // 'index.php/api'

// Remove index.php from path if present
if (strpos($path, 'index.php') === 0) { // true as path has index.php
    $path = substr($path, 9); // '/api'
    $path = trim($path, '/'); // 'api'
}
```

---

- The parsing code is complicated because there is `index.php` in the URI string.
- We need to use `strpos` PHP function to find the `index.php` (return 0 means find the string).
- Then remove 9 characters (the length of `index.php`).
- Finally, remove '/' to get the final endpoint.

---

#### Making a Response

- In this example, the PHP server returns JSON as a response.
- We need the header, code, and JSON body.

```php
function sendJson(array $payload, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}
```

- By default, json_encode() in PHP will escape forward slashes (/) into \/.
- It’s valid JSON either way, but can look strange in URLs.
- The JSON_UNESCAPED_SLASHES prevents it

---

The sendResponse uses the sendJson function to make response.

```php
function sendResponse($data, string $message = 'Success', int $code = 200): void {
    $count = is_countable($data) ? count($data) : 1;
    sendJson([
        'success' => true,
        'message' => $message,
        'data'    => $data,
        'count'   => $count,
    ], $code);
}
```

---

#### Making an Error Response (400)

- Following the web protocol, we send a 400 error when an error occurs.

```php
function sendError(string $message, int $code = 400): void {
    sendJson([
        'success' => false,
        'message' => $message,
        'data'    => null,
    ], $code);
}
```

---

#### Routing the $path to handle the API

- In this example, we handle only the `api` API.

```php
switch ($path) {
    case 'api':
        // API information endpoint
        $info = [
            'name' => 'Simple Student Management API',
            'version' => '1.0',
            'description' => 'A minimal API for learning PHP basics with student data',
            'endpoints' => [
                'GET /api' => 'Show this API information',
            ]
        ];
        sendResponse($info, 'Welcome to Simple Student Management API');
        break;
    default:
        sendError('Endpoint not found', 404);
        break;
}
?>
```

---

### Frontend Code: test.html

- To access the API, we need to make a request.
  - Run PHP server with <PHP -S localhost:8000>
  - <http://localhost:8000/index.php/api>
- This is a response from the server.

```json
{
    "success": true,
    "message": "Welcome to Simple Student Management API",
    "data": {
        "name": "Simple Student Management API",
        "version": "1.0",
        "description": "A minimal API for learning PHP basics with student data",
        "endpoints": {
            "GET \/api": "Show this API information"
        }
    },
    "count": 4
}
```

---

#### HTML/JavaScript

- We need to get the response using HTML/JavaScript.

```html
<!DOCTYPE html>
<head> ... </head>
<body>
    <div class="container"> 
        <button onclick="getApiInfo()">Get API Information</button>
    </div>
    <script>
        const API_BASE = '/index.php';
        // Function to make API calls
        async function apiCall(endpoint) {
            ...
          }
        function getApiInfo() {
          apiCall('/api');
        }
    </script>
</body>
</html>
```

---

#### JavaScript: The Communication Layer

- We use the fetch JavaScript API to make a request.

```javascript
// Simple GET request
fetch('/index.php/api')
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
```

---

- response has the HTTP-level object (status, headers, body stream).

```txt
HTTP/1.1 200 OK
Content-Type: application/json
Content-Length: 234

{"message":"...","data":{"name": ...,"endpoints":{"GET /api":"Show this API information"}}}
```

- data = actual parsed JSON object from the body.

```json
{"message":"...","data":{"name": ...,"endpoints":{"GET /api":"Show this API information"}}}
```

---

- We can use async/await to get the same results (preferred)

```javascript
async function getApiInfo() {
    try {
        const response = await fetch('/index.php/api');
        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error('Error:', error);
    }
}
```

**Why async/await?** Cleaner, easier to read, better error handling

---

#### Displaying the response from servers

```html
<div id="result" class="result">
  Click any button above to test the API...
</div>
```

We can use JavaScript to update the information in the \<div> block using id.

```javascript
document.getElementById('result').cextContent = 
   JSON.stringify(data, null, 2);
```

---

We get the JSON content from `response.json()` and then we update the HTML.

```javascript
// Base URL for our PHP API
const API_BASE = '/index.php';

// Function to make API calls
async function apiCall(endpoint) {
    try {
        // Make the request
        const response = await fetch(API_BASE + endpoint);
        const data = await response.json();
        
        // Display the result
        document.getElementById('result').textContent = 
            JSON.stringify(data, null, 2);
            
    } catch (error) {
        console.error('Error:', error);
    }
}
```

---

- We make the api GET request using this `getApiInfo()` JavaScript function.

```javascript

function getApiInfo() {
   apiCall('/api');  // Calls our API at /index.php/
}
```

---

#### Button Click Handlers

- HTML Button

```html
<button onclick="getApiInfo()">Get API Information</button>
```

- JavaScript Handler

```javascript
function getApiInfo() {
    apiCall('/api');  // Calls our API at /index.php/
}
```

---

#### What Happens

1. User clicks the button
2. `getApiInfo()` function runs
3. `apiCall('/api')` makes an HTTP request to `/index.php/api`
4. PHP processes the request and returns JSON
5. JavaScript receives the response and updates the page

---

### Different Types of Requests

- We can make other types of requests using JavaScript.
  - We should make corresponding API handlers on the PHP side.

---

- GET Request (Default)

```javascript
const response = await fetch('/api/users');
```

---

- POST Request (JSON)

```javascript
const response = await fetch('/api/users', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        name: 'John Doe',
        email: 'john@example.com'
    })
});
```

---

- PUT Request (JSON)

```javascript
const response = await fetch('/api/users/123', {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        name: 'John Smith'
    })
});
```

---

### Error Handling Best Practices

- Check Response Status

```javascript
async function apiCall(endpoint) {
    try {
        const response = await fetch(API_BASE + endpoint);
        
        // Check if request was successful
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return data;
        
    } catch (error) {
        console.error('API call failed:', error);
        throw error; // Re-throw for caller to handle
    }
}
```

---

#### Handle Different Error Types

```javascript
try {
    const data = await apiCall('/api/users');
    // Success handling
} catch (error) {
    if (error.name === 'TypeError') {
        // Network error
        showError('Network error. Please check your connection.');
    } else {
        // Server error
        showError(`Server error: ${error.message}`);
    }
}
```

---

### Updating the DOM with Results

- We already discussed that we can access an HTML block with `id` using `document.getElementById.
- We can update the content by modifying the `textContent`.

```javascript
document.getElementById('result').textContent = 'Hello World';
```

---

- CSS/HTML

```css
<style>
  #result {
    background: #f5f5f5; padding: 1em; border: 1px solid #ccc;
    font-family: monospace; white-space: pre-wrap; word-break: break-word;
  }
</style>
```

```html
<div id="result" class="result">
  Click any button above to test the API...
</div>
```

---

- HTML Update from the PHP server

```javascript
const response = await fetch(API_BASE + endpoint);
const data = await response.json();
// Display the result
document.getElementById('result').textContent =
  JSON.stringify(data, null, 2);
```

---

## Key Takeaways

- Frontend-Backend Communication:

1. **HTML** provides the user interface structure
2. **JavaScript** handles API communication and DOM updates
3. **Fetch API** makes modern HTTP requests easy
4. **async/await** provides clean asynchronous code
5. **Error handling** is crucial for a good user experience

---

### Best Practices

- Always handle errors gracefully
- Provide user feedback (loading states)
- Use browser developer tools for debugging
- Keep API calls focused and straightforward