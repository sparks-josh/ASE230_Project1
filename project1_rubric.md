---
marp: true
---

# Project 1 Grading and Submission

Total Points: 200

## Grading Policy & Rule

- No points may be given if students do not check the checklist and grade correctly.
- All the Marp slides should be converted to PDFs before submission.
- The professor will grade again to add or deduct points.
- Check Canvas or ask the professor for any questions or details.

---

## 🔹 REST API Implementation (130 pts)

- [40/40] Implemented at least 8 REST APIs in PHP/MySQL (5 points per 1 REST API).
  - List their names: get_board, get_all_boards, create_board, get_issue, get_all_issues, create_issue, update_issue, update_issue_status, add_issue_comment, get_user, get_all_users, create_user
- [20/20] Implemented at least 2 Bearer Token authentication REST APIs in PHP/MySQL (10 points per 1 Bearer Token REST API)
  - List their names: create_board, add_issue_comment, update_issue, update_issue_status, create_issue
- [20/20] Made cURL test code (should test all APIs, 2 points per 1 API).
  - List the file name: located in /code/cURL API Tests; boards_api_test.php, issues_api_tests.php, users_api_tests.php
- [ /20] Made HTML/JavaScript test code (should test all APIs, 2 points per 1 API).
  - List the file name: located in /code/HTML_JavaScript_Tests/;boards_api_tests.html, issues_api_tests.html, users_api_tests.html

---

- [10/10] Database integration working (CRUD) - Full points if MySQL works fine, no partial points
  - List your MySQL table names:
  mysql> show TABLES;
+---------------------+
| Tables_in_jira_lite |
+---------------------+
| boards              |
| comments            |
| issues              |
| users               |
+---------------------+
4 rows in set (0.003 sec)
- [ /20] Code pushed to **GitHub repository** - Full points if pushed to GitHub and provide a link, no partial points 
  - Write your GitHub repository link:
- [T/F] Copy included in `code/` directory  
  - List your zip file name in the code directory:
  - Earn 0 points in this section when you do not copy your code.

---

Total points earned in this section:

| Task                                | Points  | Earned  |
|-------------------------------------|---------|---------|
| ≥ 8 REST APIs (5 pts each)          | 40      | [40/40]  |
| ≥ 2 Bearer Token APIs (10 pts each) | 20      | [20/20]  |
| cURL test code (all APIs)           | 20      | [20/20]  |
| HTML/JS test code (all APIs)        | 20      | [ /20]  |
| DB integration (CRUD)               | 10      | [10/10]  |
| GitHub repository link              | 20      | [ /20]  |
| Copy in `code/` directory           | T/F     | [ T/F ] |
| **Total**                           | **130** | [ /130] |

---

## 🔹 Tutorial Slides (50 pts)

- [ /30] Created **Marp slides** explaining API usage (more than 20 pages (and converted into PDFs) - explanation/example for each API, 15 points if not, 0 points if none)
  - List Marp file name:
  - [T/F] Clear explanation of each endpoint: May lose points if not clearly explained
- [ /10] Includes examples of requests/responses - no partial points
- [ /10] Slides (and PDFs) uploaded to **GitHub** - no partial points
  - Convert the marp to PDF:
  - Write your GitHub repository marp link:
  - Write your GitHub repository PDF link:
- [T/F] Saved in `presentation/` directory  
  - Earn 0 points in this section when you do not copy your slides.

---

| Task                               | Points | Earned  |
|------------------------------------|--------|---------|
| Marp slides (>20 pages)            | 30     | [ /30]  |
| Clear explanation of endpoints     | T/F    | [ T/F ] |
| Examples of requests/responses     | 10     | [ /10]  |
| Slides uploaded to GitHub          | 10     | [ /10]  |
| Saved in `presentation/` directory | T/F    | [ T/F ] |
| **Total**                          | **50** | [ /50]  |

---

## 🔹 NGINX Deployment (20 pts)

- [ /10] REST API deployed with **NGINX**  - no partial points
- [ /10] Deployment steps/tutorial documented in Marp slide (and converted into PDFs)
  - full points if marp file is made with screen captures, no partial points
  - List Marp file name:
  - [T/F] A screen capture of your NGINX server working should be included (any screen capture is OK when it shows NGINX is working)
  - Don't need to push to GitHub (but it's OK if you do so).
- [T/F] Saved in `presentation/` directory  
  - Earn 0 points in this section when you do not copy your slides.

---

| Task                                       | Points | Earned  |
|--------------------------------------------|--------|---------|
| REST API deployed with NGINX               | 10     | [ /10]  |
| Deployment steps/tutorial in Marp          | 10     | [ /10]  |
| ↳ Includes screen capture of NGINX working | T/F    | [ T/F ] |
| Saved in `presentation/` directory         | T/F    | [ T/F ] |
| **Total**                                  | **20** | [ /20]  |

---

## 🏁 Final Checks

- [ ] I understand that I may deduct points if the results are of poor quality.
- [ ] I understand that I may be reported as plagiarism if I used other (including the professor's slides) work, but did not reference it.
- [ ] Copy files in correct directory: `code/`, `presentation/`, `plan/`  
- [ ] Pushed to GitHub + submitted zip copy  
- [ ] Project ready for **portfolio showcase**  

---

| Task                       | Points  | Earned  |
|----------------------------|---------|---------|
| 🔹 REST API Implementation | 130     | [ /130] |
| 🔹 Tutorial Slides         | 50      | [ /50]  |
| 🔹 NGINX Deployment        | 20      | [ /20]  |
| **Total**                  | **150** | [ /150] |