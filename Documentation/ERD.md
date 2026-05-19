# Entity Relationship Diagram (ERD)

This document describes the database schema for the **CpE Registry — Contact Tracing App**.

```mermaid
erDiagram
    VISITORS ||--o{ VISIT_LOGS : "logs"
    
    VISITORS {
        INT id PK
        VARCHAR(20) id_number UK "Student/Employee ID"
        VARCHAR(60) first_name
        VARCHAR(60) last_name
        VARCHAR(80) barangay
        VARCHAR(80) city
        VARCHAR(80) province
        VARCHAR(20) contact_number
        VARCHAR(120) email
        TIMESTAMP created_at
    }
    
    VISIT_LOGS {
        INT log_id PK
        INT visitor_id FK
        TIMESTAMP sign_in
        TIMESTAMP sign_out "NULL = currently signed in"
    }
    
    ADMIN {
        INT admin_id PK
        VARCHAR(60) username UK
        VARCHAR(255) password "bcrypt hash"
        TIMESTAMP created_at
    }
```

### Table Details:
1. **`visitors`**: Stores the core registration details of each individual. The address is segmented into `barangay`, `city`, and `province` to allow precise filtering by the admin. `id_number` is unique to prevent duplicate registrations.
2. **`visit_logs`**: Tracks every visit instance. It links to `visitors` via `visitor_id`. A visit is active if `sign_out` is `NULL`.
3. **`admin`**: Stores the system administrators. The `password` must always be hashed.
