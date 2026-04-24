# Project Management System

A full-stack **Project Management System (PMS)** — a single-page application for managing projects, tasks, team members, and role-based access control.

## Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | Laravel 12 (PHP ^8.2) |
| **Frontend** | Vue 3 (Composition API, `<script setup>`) |
| **SPA Layer** | Inertia.js v2 |
| **CSS** | Tailwind CSS v4 |
| **Build** | Vite 7 |
| **State** | Pinia 3 |
| **Database** | MySQL |

## Features

- **Project Management** — CRUD with status workflow (planning → active → completed / on-hold), date tracking
- **Task Management** — CRUD with priority/status, user assignment, one-click status toggle
- **Member Management** — User CRUD with role assignment, profile pictures
- **Dashboard** — Overview with project listing, task data, charts (ApexCharts)
- **RBAC** — Two-tier permission system (global roles + project-scoped roles) with 6 roles
- **Dark Mode** — Full dark mode via Tailwind `dark:` variants
- **Notifications** — Events for task assignment and status changes
- **Calendar** — FullCalendar integration (daygrid, timegrid, list views)
- **Data Tables** — Reusable sortable/paginated tables with inline actions

## Quick Start

```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build & serve
php artisan serve          # Backend at localhost:8000
npm run dev                # Vite dev server (hot reload)
```

**Requirements:** PHP 8.2+, Composer, Node.js 18+, MySQL

## Authorization (RBAC)

Two-tier permission system:

1. **Global roles** (`owner`, `operation_manager`) — permissions checked without project context
2. **Project-scoped roles** (`project_lead`, `developer`, `qa`, `client`) — permissions resolved within specific project membership

Authorization flow: `Policy → User::hasPermission() → ProjectAccessService` (checks global first, then project pivot).

### Roles & Permissions

| Role | Scope | Key Permissions |
|---|---|---|
| **Owner** | Global | All permissions |
| **Operation Manager** | Global | Create/update/view projects and tasks, manage members |
| **Project Lead** | Project | Full CRUD within assigned projects |
| **Developer** | Project | View project, view/update own assigned tasks |
| **QA** | Project | View project, view/update tasks |
| **Client** | Project | View project and tasks (read-only) |

## Testing

```bash
# Run full test suite
php artisan test

# Run specific test file
php artisan test --filter=ProjectAccessServiceTest
```

**54 tests** across 5 test files:

| Test File | Tests | Coverage |
|---|---|---|
| `ProjectAccessServiceTest` | 15 | RBAC permission resolution (all 6 roles + edge cases) |
| `ProjectPolicyTest` | 12 | HTTP-level project CRUD authorization |
| `TaskPolicyTest` | 11 | HTTP-level task CRUD + status toggle + assignee access |
| `ProjectServiceTest` | 7 | Project service CRUD + member management |
| `TaskServiceTest` | 8 | Task service CRUD + event assertions |

## Project Structure

```
app/
├── Http/
│   ├── Controllers/     # Dashboard, Project, Task, Member
│   └── Requests/        # Form validation + authorization
├── Models/               # User, Role, Permission, Project, Task
├── Policies/             # ProjectPolicy, TaskPolicy
├── Services/             # ProjectAccessService, ProjectService, TaskService, ProjectMemberService
└── Events/               # TaskAssigned, TaskStatusUpdated
resources/js/
├── Pages/                # SignIn, SignUp, Dashboard, Projects, Tasks, Members
├── Composables/          # useResourceManager, useProjectManager, useTaskManager, useMemberManager
├── Components/           # DataTable, Modal, Button, form inputs
└── Layouts/              # AdminLayout, FullScreenLayout
routes/
├── web.php               # Authenticated routes
└── auth.php              # Auth routes
```
