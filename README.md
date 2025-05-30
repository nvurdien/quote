https://github.com/user-attachments/assets/b714b45b-debe-41f1-8d7f-ee2e208b50eb

# Quote Generator App

This is a full-stack quote generation application built with **Angular** (frontend) and **Laravel** (backend). The app allows users to input basic life insurance information and receive premium quotes via a third-party API.

## Stack

- **Frontend**: Angular (port `4200`)
- **Backend**: Laravel (port `8000`)
- **External API**: Quote data from `https://plumlife-api-lab.azurewebsites.net/api/quote`

---

## Features

- Dynamic form for user input (DOB, state, smoker status, etc.)
- Validates and submits form data to Laravel backend
- Backend fetches quotes from external API
- Filters and returns a matching or closest coverage quote
- Handles off-value coverage requests gracefully (e.g. `500343` → `500000`)

---

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/nvurdien/quote.git
cd quote
```

### 2. Setup Laravel (Backend)
```bash
cd insurance-quote-backend
composer install
cp .env.example .env
php artisan key:generate
php artisan serve --port=8000
```

### 3. Setup Angular (Frontend)
```bash
cd frontend  # or wherever your Angular app is
npm install
ng serve --port 4200
```

### 4. API Route (Laravel)
The backend exposes a single API route:

```bash
POST /api/generate-quote
```

#### Payload:
```json
{
  "dob": "1999-01-01",
  "state": "TX",
  "smoker": true,
  "gender": "M",
  "term": 20,
  "coverage_amount": 500000
}
```

#### Response (Example):
```json
{
  "matched_coverage_amount": 500000,
  "monthly_premium": "69.52",
  "quarterly_premium": "216.92",
  "semi_annual_premium": "425.49",
  "annual_premium": "834.30"
}
```
