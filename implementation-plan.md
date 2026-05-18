# Contact Tracing App — Implementation Plan

**Project:** Simple Contact Tracing Application for the Department of Computer Engineering  
**Stack:** PHP, MySQL, HTML, CSS, JavaScript (XAMPP)  
**Deliverables:** Working app + Use Case Diagram + Wireframe/Prototype + ERD

---

## Phase 1 — Repository Setup ✅
**Status:** Done  
Create the GitHub repo, add a `.gitignore` for PHP projects, and push the initial commit.

---

## Phase 2 — Project File Structure ✅
**Status:** Done  
**Goal:** Scaffold the folder and file layout before writing any logic.  
**Output:** Empty but organized directory tree, committed to GitHub.

Key folders to establish:
- `config/` — database connection
- `includes/` — reusable PHP partials (header, footer, db)
- `assets/css`, `assets/js`, `assets/img` — frontend files
- `admin/` — all admin-side pages
- `database/` — the exported `.sql` file

> Prompt Claude to generate the tree + a `setup.sh` scaffold script.

---

## Phase 3 — Database Design (ERD + SQL Schema) ✅
**Status:** Done  
**Goal:** Design the tables before touching PHP. Everything downstream depends on this.  
**Output:** ERD diagram (for submission) + `database/schema.sql` ready to import in phpMyAdmin.

Tables to plan:
- `visitors` — stores registration data (ID number, name, address, contact, email)
- `visit_logs` — stores each sign-in and sign-out event with timestamps
- `admin` — single row for hardcoded or registered admin credentials

> Prompt Claude to generate the ERD description and the full SQL `CREATE TABLE` statements.

---

## Phase 4 — UI Wireframes and Prototype ✅
**Status:** Done  
**Goal:** Design the pages before coding them, so you know exactly what to build.  
**Output:** Wireframes in Figma or a stitching tool (required for submission).

Pages to wireframe:
- Visitor landing page (ID lookup vs. first-time registration toggle)
- Registration form
- Sign-in confirmation screen
- Sign-out screen
- Admin login page
- Admin dashboard with search and results table

> Use Figma. Prompt Claude to generate a UI layout description you can translate into Figma frames.

---

## Phase 5 — Core Visitor Flow (PHP + MySQL) ✅
**Status:** Done  
**Goal:** Build the working visitor-facing side of the app.  
**Output:** Functional registration, sign-in, and sign-out pages connected to the database.

Build in this order:
1. `config/db.php` — database connection
2. `index.php` — ID lookup logic (returning vs. new visitor routing)
3. `register.php` — first-time registration form and INSERT
4. `signin.php` — verify info, record sign-in timestamp in `visit_logs`
5. `signout.php` — record sign-out timestamp for the active visit

> Prompt Claude one page at a time, providing the schema from Phase 3 as context each time.

---

## Phase 6 — Admin Panel ✅
**Status:** Done  
**Goal:** Build the admin-side interface with login and search functionality.  
**Output:** Working admin login + dashboard with all six required search filters.

Build in this order:
1. `admin/login.php` — hardcoded credentials check + session start
2. `admin/dashboard.php` — search form UI
3. `admin/search.php` — backend query handler for all six filters:
   - City, Barangay, Province, ID Number, Last/First Name, Time and Day

> Prompt Claude with the schema and the exact filter list from the instructions.

---

## Phase 7 — Session Handling and Security
**Goal:** Lock admin pages behind a session check and sanitize all inputs.  
**Output:** `includes/auth_check.php` included at the top of every admin page; sanitized queries.

Tasks:
- Add `session_start()` and redirect-if-not-logged-in guard to all admin pages
- Use prepared statements (PDO or mysqli) on every query that takes user input
- Add a logout button that destroys the session

> Prompt Claude to audit all PHP files from Phases 5 and 6 for SQL injection and session gaps.

---

## Phase 8 — Use Case Diagram
**Goal:** Produce the required UML Use Case Diagram for submission.  
**Output:** Diagram image exported and included in the final ZIP.

Actors: Visitor, Returning Visitor, Administrator  
Use cases to cover: Register, Sign In, Sign Out, Look Up by ID, Admin Login, Search Visitors, View Visit Logs

> Prompt Claude to generate PlantUML or draw.io markup you can render and export.

---

## Phase 9 — Styling and UI Polish
**Goal:** Make the app look user-friendly, not just functional.  
**Output:** Consistent CSS across all pages.

Keep it simple — this is a utility app, not a portfolio piece:
- Mobile-friendly layout (flexbox or basic grid)
- Clear form labels and validation feedback
- Distinct visual states for sign-in vs. sign-out

> Do this last so styling changes don't interrupt logic development.

---

## Phase 10 — Testing and Final Packaging
**Goal:** Verify all flows work end to end, then package for submission.  
**Output:** ZIP file containing all PHP files, `database/schema.sql`, and documentation assets.

Test checklist:
- [ ] New visitor can register and is auto-signed in
- [ ] Returning visitor lookup by ID works and pre-fills data
- [ ] Sign-out records the correct timestamp
- [ ] Admin login rejects wrong credentials
- [ ] All six search filters return correct results
- [ ] Session guard blocks unauthenticated access to admin pages
- [ ] SQL file imports cleanly in a fresh phpMyAdmin instance

Package:
- All PHP and asset files
- `database/schema.sql`
- Use Case Diagram image
- Wireframe exports or Figma link
- ERD image

---

## Phase Summary

| Phase | Task | Depends On |
|-------|------|------------|
| 1 | GitHub repo | — |
| 2 | File structure scaffold | 1 |
| 3 | Database design + ERD | 2 |
| 4 | Wireframes | 3 |
| 5 | Visitor flow (PHP) | 3 |
| 6 | Admin panel | 5 |
| 7 | Sessions + security | 5, 6 |
| 8 | Use Case Diagram | Full feature knowledge |
| 9 | UI polish | 5, 6 |
| 10 | Testing + packaging | All |
