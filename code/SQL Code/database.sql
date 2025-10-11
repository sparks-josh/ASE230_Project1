CREATE TABLE users (id INT PRIMARY KEY, name VARCHAR(20));

CREATE TABLE boards (id INT PRIMARY KEY, name VARCHAR(20));

CREATE TABLE issues (id INT PRIMARY KEY, name VARCHAR(20), board_id INT REFERENCES boards(id), status VARCHAR(20));

CREATE TABLE comments (id INT PRIMARY KEY, issue_id INT REFERENCES issues(id), comment VARCHAR(100)); 