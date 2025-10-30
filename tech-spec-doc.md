
**Title**:
Build a Laravel-based E-SPPD (Electronic Travel Warrant) System for the Regional Government of Bantaeng.

---

### 🧩 Stack Specifications:

* **Backend**: Laravel 10+
* **Frontend**: TailwindCSS (via Laravel Mix or Vite)
* **UI Framework**: FlyonUI (AlpineJS-based)
* **Database**: PostgreSQL 14+
* **ORM**: Eloquent (Laravel default)
* **Auth**: Laravel Breeze / Sanctum
* **API**: RESTful (with optional OpenAPI spec)
* **Testing**: PHPUnit + Laravel Dusk (for UI)

---

### 📚 Description of the System:

Design and build a **web-based travel order management system** (E-SPPD) for government employees of **Pemkab Bantaeng**. The system allows employees to request travel permits, get them approved by supervisors and financial officers, manage estimated and actual expenses, and generate printable travel warrant documents (SPT/SPPD).

The system must include:

1. Role-based access (admin, employee, supervisor, financial officer, verifikator)
2. Travel request creation (SPT)
3. Cost estimation input
4. Multistage approval flow (supervisor → finance → verifikator)
5. Travel realization form (actual expenses with receipt uploads)
6. Report and rekap dashboard
7. PDF document generation (SPPD + reports)
8. Audit logging

---

### 🗂️ Models and Relationships (ERD logic):

Create the following models with Eloquent relationships:

* `User`

  * has roles: employee, supervisor, finance, etc.
  * fields: name, NIP, email, password, role, jabatan, unit_kerja

* `SPT` (travel request)

  * belongs to user
  * has many estimated_costs
  * has one SPPD
  * has many approvals
  * status: draft, submitted, approved, rejected

* `EstimatedCost`

  * belongs to SPT
  * fields: type (transport, daily, accommodation), amount, notes

* `SPPD`

  * belongs to SPT
  * has many `Realization`
  * fields: number, issue_date, status

* `Realization`

  * belongs to SPPD
  * fields: type, amount, file_path (receipt), notes

* `Approval`

  * belongs to SPT
  * fields: stage (supervisor, finance, verifikator), status, comment, approved_by

* `ActivityLog`

  * belongs to user
  * fields: action, table_name, record_id, timestamp

---

### 🔐 Auth & Permissions

Use Laravel Breeze or Sanctum. Implement middleware to restrict access:

* Employee: create and view own requests
* Supervisor: can view and approve SPT of subordinates
* Finance: view cost estimations, approve if valid
* Verifikator: verify realization expenses
* Admin: CRUD all

---

### 🧾 RESTful API Endpoints (with sample):

#### 🔐 Auth

```http
POST /api/login
GET /api/me
POST /api/logout
```

#### 👤 Users

```http
GET /api/users
GET /api/users/{id}
POST /api/users
PUT /api/users/{id}
DELETE /api/users/{id}
```

#### 📄 SPT (Travel Request)

```http
GET /api/spt
POST /api/spt
GET /api/spt/{id}
PUT /api/spt/{id}
DELETE /api/spt/{id}
POST /api/spt/{id}/submit
```

#### 💸 Estimated Costs

```http
POST /api/spt/{id}/estimations
GET /api/spt/{id}/estimations
DELETE /api/estimations/{id}
```

#### ✅ Approvals

```http
POST /api/spt/{id}/approve
Payload: { stage: "supervisor", status: "approved" | "rejected", comment: "..." }
```

#### 📄 SPPD (Generated Document)

```http
POST /api/spt/{id}/generate-sppd
GET /api/sppd/{id}
GET /api/sppd/{id}/pdf
```

#### 📥 Realization

```http
POST /api/sppd/{id}/realizations
GET /api/sppd/{id}/realizations
```

#### 📊 Dashboard & Logs

```http
GET /api/dashboard
GET /api/logs
```

---



### 🧱 FlyonUI Component Plan (Front-End)

Build the following screens using **TailwindCSS + FlyonUI**:

* Login & Register
* Dashboard (stats: total SPT, approved, in process)
* SPT Form Page (Create/Edit)
* Estimated Cost Entry Form (modal or inline)
* Approval Screens (supervisor & finance roles)
* Realization Form + File Upload
* SPPD Preview + PDF download
* User management (Admin only)
* Notification alerts (approved, rejected, etc.)

Use **FlyonUI components**:

* FlyonCard
* FlyonButton
* FlyonModal
* FlyonTable
* FlyonToast
* FlyonUpload

---

### 🧪 Testing Instructions

* Use **PHPUnit** to test:

  * SPT creation and flow logic
  * Role-based access enforcement
  * Approval and rejection paths
* Use **Laravel Dusk** to test:

  * UI form submissions
  * Approval flows

---

### ⚙️ Laravel Commands

```bash
php artisan make:model SPT -m
php artisan make:controller Api/SPTController --api
php artisan make:request StoreSPTRequest
php artisan make:middleware RoleMiddleware
php artisan migrate
php artisan db:seed
```

---


