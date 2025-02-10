# Post Management Application

A full-stack application for Passafund's software engineer internship assignment featuring user authentication and post CRUD operations.

## Features

- **User Authentication**
  - Registration with email verification
  - Login/Logout functionality
  - Password reset functionality
- **Post Management**
  - Create new posts with titles and content
  - Edit existing posts
  - Delete posts
  - Categorize posts (News/Update/Task)
- **User Interface**
  - Responsive design
  - Real-time post updates
  - Interactive forms with validation
  - Visual feedback for actions
- **Security**
  - CSRF protection
  - API authentication
  - Authorization checks

## API Endpoints

### Authentication
- `POST /api//auth/register` - User registration
- `POST /api//auth/login` - User login
- `POST /api//auth/logout` - User logout
- `GET /api/user` - Get authenticated user details

### Posts
- `GET /api/posts` - List all posts
- `POST /api/posts` - Create new post
- `PUT /api/posts/{id}` - Update post
- `DELETE /api/posts/{id}` - Delete post

## Directory Structure

# Post app for passafund software engineer internship asignment

Support User and post CRUD functionality

---

## **Requirements**
Make sure the following are installed on your machine:
- PHP >= 8.0
- Composer >= 2.0
- Node.js >= 14.x
- npm >= 6.x (or Yarn)
- MySQL (or another database, if applicable)
- Laravel >= 9.x
- Vue.js (or other frontend framework, if applicable)

---

## **Getting Started**

### 1. **Clone the Repository**
```bash
git clone https://github.com/your-repo-name.git
cd your-repo-name


---

 
## 2. **Create .env file **
```bash
Copy the .env.example file and rename it to .env
cp .env.example .env


Update the .env file with your local configurations:
Database configuration:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

Frontend URL for password reset (if applicable):
FRONTEND_URL=http://localhost:8081

Install php dependencies using composer: 
composer install


Install JavaScript dependencies using npm or Yarn:
npm install
# Or, if using Yarn:
yarn install

Generate Application Key
Run the following command to generate a new application key:

php artisan key:generate

Run Database Migrations
Run the following command to create the database tables:


php artisan migrate

If your app has seeded data, run:
php artisan db:seed

Start the Development Server
To start the Laravel backend:
php artisan serve

Frontend (Vue.js)
To start the Vue.js frontend:
npm run serve

Backend: http://localhost:8000
Frontend: http://localhost:8081 (or the port shown in the terminal after running npm run serve)
