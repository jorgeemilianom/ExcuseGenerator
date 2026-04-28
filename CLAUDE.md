# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

ExcuseGenerator is a freemium SPA that generates personalized excuses using AI. The stack is:
- **Frontend**: React 19 + Vite (in `/frontend`)
- **Backend**: PHP 8.2 sin framework (in `/backend`)
- **AI**: OpenAI API (`gpt-4o-mini`)
- **Infrastructure**: Docker (backend only via `docker-compose.yml`)

## Commands

### Frontend

```bash
cd frontend
npm install          # install dependencies
npm run dev          # dev server (http://localhost:5173)
npm run build        # production build
npm run lint         # ESLint
npm run preview      # preview production build
```

### Backend

```bash
# Run with Docker (recommended)
docker-compose up --build

# Run locally (requires PHP 8.2+)
cd backend
php -S 0.0.0.0:8000 -t public
```

Backend runs on port `8000`. Configure `backend/.env` from `backend/.env.example` before starting.

## Architecture

### Backend

Entry point is `backend/public/index.php`, which:
1. Loads `.env` manually (no Composer/dotenv library)
2. Sets CORS headers globally
3. Registers routes on a custom `Router` class and calls `dispatch()`

The layer structure is:
- `src/Router.php` — maps HTTP method + URI to controller actions
- `src/Controllers/GenerateController.php` — validates input, enforces freemium limits, delegates to service
- `src/Services/GenerateExcuseService.php` — builds the OpenAI prompt, calls the API via cURL, parses JSON response

**Freemium limit**: tracked in `backend/storage/usage.json` keyed by IP (`ip_<normalized_ip>`). Free users get 3 generations/day; resets automatically by comparing stored date with today's date. The file must be writable — Docker ensures this via `chmod 777 /app/storage`.

**Currently implemented endpoint**: `POST /api/generate` only. Auth endpoints (`/api/auth/register`, `/api/auth/login`) and `/api/usage` are specced but not yet built.

### Frontend

Minimal React SPA at `frontend/src/main.jsx`. The planned structure (from spec) is:
- `src/pages/` — page-level components (Generator, Result, Auth, Landing, Paywall)
- `src/components/` — reusable UI components
- `src/services/` — API calls using Fetch
- `src/hooks/` — custom hooks
- `src/store/` — state (useState for MVP, Zustand optional)

API calls go to `/api/*`. In dev, configure a Vite proxy to `http://localhost:8000` to avoid CORS issues.

### OpenAI Integration

`GenerateExcuseService` uses `gpt-4o-mini` with temperature 0.85. The prompt is written in Argentine Spanish and instructs the model to return a strict JSON with keys: `excuse`, `short_version`, `recommendation`. The `parseResponse` method strips markdown fences and extracts the JSON via regex.

## Environment

`backend/.env`:
```
OPENAI_API_KEY=your_openai_api_key_here
```

The `.env` file is loaded by `index.php` into `$_ENV` — no third-party library involved.
