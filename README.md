# Installation and Setup Guide

This guide will walk you through the process of setting up the Project Management API on your local machine.

## Prerequisites

Before you begin, ensure you have the following installed on your system:
- PHP (>= 8)
- Composer
- MySQL
- Git

## Clone the Repository

```bash
git clone https://github.com/Matr1x01/Task-Manager.git
cd Task-Manager
```

# Install dependencies
```bash
composer install
```

# Set up environment file
```bash
cp .env.example .env
```

# Database setup
Create a new mysql database named `task_manager` and update the .env file with the database details.

```bash
php artisan migrate
```
# Generate application key
```bash
php artisan key:generate
```

# Run migrations
```bash
php artisan migrate
```

# Install and configure Passport
Run the following command to install Passport client:
```bash
php artisan passport:client --personal
```
and update the `PASSPORT_PERSONAL_ACCESS_CLIENT_ID` and `PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET` in the `.env` file.


# Start the development server
```bash
php artisan serve
```

## Features

- User authentication (login and registration)
- Project management (CRUD operations)
- Task management (CRUD operations)
- Subtask management (CRUD operations)
- Project reports
- Task upload functionality

# API Endpoints

## Authentication

- `POST /api/register`: Create a new user
    - Request Body:
      ```json
      {
        "name": "John Doe",
        "email": "john.doe@example.com",
        "password": "your_password"
      }
      ```

- `POST /api/login`: Authenticate user and generate token
    - Request Body:
      ```json
      {
        "email": "john.doe@example.com",
        "password": "your_password"
      }
      ```

- `POST /api/logout`: User logout

## Projects

- `GET /api/projects`: Get all projects for authenticated user
- `GET /api/projects/{project_id}`: Get details of a specific project
- `POST /api/projects`: Create a new project
    - Request Body:
      ```json
      {
        "name": "My Project",
        "description": "A sample project description"
      }
      ```
- `PUT /api/projects/{project_id}`: Update an existing project
    - Request Body:
      ```json
      {
        "name": "Updated Project Name",
        "description": "Updated project description"
      }
      ```
- `DELETE /api/projects/{project_id}`: Delete a project
- `GET /api/projects/{project_id}/report`: Get project report

## Tasks

- `GET /api/projects/{project_id}/tasks`: Get all tasks for a project
- `GET /api/projects/{project_id}/tasks/{task_id}`: Get details of a specific task
- `POST /api/projects/{project_id}/tasks`: Create a new task
    - Request Body:
      ```json
      {
        "title": "Task Title",
        "description": "Task description",
        "task_status": "pending"  // Options: pending, in_progress, completed, cancelled
      }
      ```
- `PUT /api/projects/{project_id}/tasks/{task_id}`: Update a task
    - Request Body:
      ```json
      {
        "title": "Updated Task Title",
        "description": "Updated task description",
        "task_status": "pending"  // Options: pending, in_progress, completed, cancelled
      }
      ```
- `DELETE /api/projects/{project_id}/tasks/{task_id}`: Delete a task
- `POST /api/projects/{project_id}/tasks/upload`: Upload tasks from a CSV file
    - The CSV file headers should contain `title`, `description`, `task_status`
    - A demo CSV file `csv_upload_test.csv` is available in the project for testing

## Subtasks

- `GET /api/projects/{project_id}/tasks/{task_id}/subtasks`: Get all subtasks for a task
- `GET /api/projects/{project_id}/tasks/{task_id}/subtasks/{subtask_id}`: Get a specific subtask
- `POST /api/projects/{project_id}/tasks/{task_id}/subtasks`: Create a new subtask
- - Request Body:
      ```json
      {
        "title": "Sub Task Title"
      }
      ```
    
- `PUT /api/projects/{project_id}/tasks/{task_id}/subtasks/{subtask_id}`: Update a subtask
- - Request Body:
      ```json
      {
        "title": "Updated Sub Task Title"
      }
      ```
- `DELETE /api/projects/{project_id}/tasks/{task_id}/subtasks/{subtask_id}`: Delete a subtask

## Rate Limiting

The API implements rate limiting through the `RateLimitMiddleware`.
Set the rate limit in the `.env` file using the `RATE_LIMITER_LIMIT` variable.
