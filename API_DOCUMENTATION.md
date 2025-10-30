# E-SPPD API Documentation

## Authentication

### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "admin@bantaeng.go.id",
    "password": "password"
}
```

### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

### Get Current User
```http
GET /api/me
Authorization: Bearer {token}
```

## Dashboard

### Get Dashboard Data
```http
GET /api/dashboard
Authorization: Bearer {token}
```

## SPT (Surat Perintah Tugas)

### Get All SPTs
```http
GET /api/spts
Authorization: Bearer {token}
```

### Create SPT
```http
POST /api/spts
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Perjalanan Dinas Jakarta",
    "purpose": "Menghadiri rapat koordinasi",
    "destination": "Jakarta",
    "start_date": "2025-11-01",
    "end_date": "2025-11-03",
    "notes": "Dinas dalam rangka koordinasi sistem"
}
```

### Get SPT Details
```http
GET /api/spts/{id}
Authorization: Bearer {token}
```

### Update SPT
```http
PUT /api/spts/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Updated Title",
    "purpose": "Updated purpose"
}
```

### Delete SPT
```http
DELETE /api/spts/{id}
Authorization: Bearer {token}
```

### Submit SPT
```http
POST /api/spts/{id}/submit
Authorization: Bearer {token}
```

## Estimated Costs

### Create Estimated Cost
```http
POST /api/estimated-costs
Authorization: Bearer {token}
Content-Type: application/json

{
    "spt_id": 1,
    "type": "transport",
    "amount": 1500000,
    "description": "Tiket pesawat",
    "notes": "Garuda Indonesia"
}
```

### Update Estimated Cost
```http
PUT /api/estimated-costs/{id}
Authorization: Bearer {token}
```

### Delete Estimated Cost
```http
DELETE /api/estimated-costs/{id}
Authorization: Bearer {token}
```

## SPPD (Surat Perjalanan Dinas)

### Get All SPPDs
```http
GET /api/sppds
Authorization: Bearer {token}
```

### Create SPPD
```http
POST /api/sppds
Authorization: Bearer {token}
Content-Type: application/json

{
    "spt_id": 1,
    "number": "SPPD/2025/001",
    "issue_date": "2025-10-28",
    "notes": "SPPD untuk perjalanan dinas"
}
```

### Generate SPPD PDF
```http
GET /api/sppds/{id}/pdf
Authorization: Bearer {token}
```

## Realizations

### Create Realization
```http
POST /api/realizations
Authorization: Bearer {token}
Content-Type: application/json

{
    "sppd_id": 1,
    "type": "transport",
    "amount": 1450000,
    "description": "Tiket pesawat actual",
    "file_path": "receipts/ticket.pdf",
    "notes": "Actual cost with receipt"
}
```

## Approvals (Supervisor, Finance, Verifikator only)

### Approve SPT
```http
POST /api/spts/{id}/approve
Authorization: Bearer {token}
Content-Type: application/json

{
    "stage": "supervisor",
    "status": "approved",
    "comment": "Disetujui untuk perjalanan dinas"
}
```

### Get Approvals
```http
GET /api/approvals
Authorization: Bearer {token}
```

## Users (Admin only)

### Get All Users
```http
GET /api/users
Authorization: Bearer {token}
```

### Create User
```http
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "New User",
    "email": "user@bantaeng.go.id",
    "password": "password",
    "nip": "USER001",
    "role": "employee",
    "jabatan": "Staf",
    "unit_kerja": "Dinas"
}
```

## Sample Users for Testing

All users have password: `password`

1. **Admin**
   - Email: admin@bantaeng.go.id
   - Role: admin

2. **Supervisor**
   - Email: supervisor@bantaeng.go.id
   - Role: supervisor

3. **Finance**
   - Email: finance@bantaeng.go.id
   - Role: finance

4. **Verifikator**
   - Email: verifikator@bantaeng.go.id
   - Role: verifikator

5. **Employee**
   - Email: rahmat@bantaeng.go.id
   - Role: employee

## Error Responses

All errors return JSON with `message` field:

```json
{
    "message": "Unauthorized action."
}
```

Common HTTP status codes:
- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error