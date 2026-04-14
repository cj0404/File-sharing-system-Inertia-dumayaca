# FileShare Development & Admin Guide

Complete guide for developers, administrators, and technical teams working with the FileShare System.

---

## 🏗️ Architecture Overview

### Technology Stack
- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Vue 3 + Inertia.js
- **Database:** SQLite (development), MySQL/PostgreSQL (production)
- **Styling:** Tailwind CSS
- **Build Tool:** Vite
- **Package Manager:** Composer (PHP), npm (JavaScript)

### Project Structure
```
FileShare/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/          # 5 API controllers
│   │   │   ├── Auth/         # Authentication
│   │   │   └── Profile/      # User management
│   │   ├── Middleware/       # Auth, CORS, etc.
│   │   └── Requests/         # Form validation
│   ├── Models/               # 4 models + relationships
│   ├── Policies/             # Authorization rules
│   └── Providers/            # Service providers
├── database/
│   ├── migrations/           # 9 migrations
│   ├── factories/            # Test data
│   └── seeders/              # Demo data
├── resources/
│   ├── js/
│   │   ├── Pages/            # 6 Vue pages
│   │   ├── Layouts/          # Layout components
│   │   ├── Components/       # Reusable components
│   │   ├── app.js            # Main entry point
│   │   └── bootstrap.js      # Bootstrap config
│   ├── css/                  # Tailwind CSS
│   └── views/                # Blade templates
├── routes/
│   ├── web.php               # Web routes (4 new)
│   └── api.php               # API routes (9 endpoints)
├── storage/                  # File uploads
├── tests/                    # Test files
└── public/
    └── build/                # Compiled assets (28 files)
```

---

## 🚀 Development Setup

### Prerequisites
- PHP 8.2 or higher
- Node.js 18 or higher
- Composer latest
- npm or yarn
- SQLite or MySQL/PostgreSQL

### Installation Steps

```bash
# 1. Clone repository
git clone <repository-url>
cd File-sharing-system-Inertia-dumayaca

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate Laravel key
php artisan key:generate

# 6. Create database and run migrations
php artisan migrate

# 7. Seed demo data
php artisan db:seed --class=FileSeeder

# 8. Build frontend assets
npm run build
# OR for development with live reload
npm run dev
```

### Development Servers

**Terminal 1: Laravel Development Server**
```bash
php artisan serve
# Runs on http://127.0.0.1:8000
```

**Terminal 2: Vite Development Server (hot reload)**
```bash
npm run dev
# Runs on http://127.0.0.1:5173
```

**Terminal 3: Queue Worker (if using jobs)**
```bash
php artisan queue:work
```

### Test Credentials
```
alice@demo.com / password
bob@demo.com / password
```

---

## 🗄️ Database Schema

### Tables Overview

#### users
```sql
id, name, email (unique), email_verified_at, 
password, remember_token, created_at, updated_at
```

#### files
```sql
id, user_id (fk), name, path, size, mime_type,
downloads, starred (boolean), visibility, 
created_at, updated_at
```

#### tags
```sql
id, user_id (fk), name, color, created_at, updated_at
UNIQUE(user_id, name)
```

#### file_tags (Pivot)
```sql
id, file_id (fk), tag_id (fk), created_at
UNIQUE(file_id, tag_id)
```

#### comments
```sql
id, file_id (fk), user_id (fk), content, 
created_at, updated_at
INDEX(file_id)
```

#### file_shares
```sql
id, file_id (fk), user_id (fk), permission,
created_at, updated_at
UNIQUE(file_id, user_id)
```

### Relationships
```
User 1 ─→ Many Files
User 1 ─→ Many Tags
User 1 ─→ Many Comments
User 1 ─→ Many Shares

File 1 ─→ Many Tags (Many-to-Many via file_tags)
File 1 ─→ Many Comments
File 1 ─→ Many Shares

Tag 1 ─→ Many Files (Many-to-Many via file_tags)

Comment ─→ File, User
Share ─→ File, User
```

---

## 🔌 API Documentation

### Base URL
```
http://127.0.0.1:8000/api/
```

### Authentication
- **Method:** Laravel session-based authentication
- **Middleware:** `auth:verified`
- **Header:** CSRF token in cookies (handled by Inertia)

### Response Format
```json
// Success
{
  "success": true,
  "data": { ... }
}

// Error
{
  "success": false,
  "message": "Error description",
  "errors": { ... }
}
```

### Endpoints

#### 1. GET /api/stats
**Get user file statistics**
```bash
curl http://127.0.0.1:8000/api/stats \
  -H "X-Requested-With: XMLHttpRequest"
```
**Response:**
```json
{
  "total_files": 12,
  "total_size": 5242880,
  "total_downloads": 45,
  "starred_count": 3,
  "private_count": 8,
  "public_count": 4,
  "tagged_files": 7,
  "commented_files": 5
}
```

#### 2. POST /api/{file}/star
**Toggle star status**
```bash
curl -X POST http://127.0.0.1:8000/api/1/star \
  -H "X-Requested-With: XMLHttpRequest"
```
**Response:**
```json
{
  "success": true,
  "starred": true,
  "message": "File starred"
}
```

#### 3. GET /api/tags
**List user tags**
```bash
curl http://127.0.0.1:8000/api/tags
```
**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Project Alpha",
      "color": "blue",
      "files_count": 3
    }
  ]
}
```

#### 4. POST /api/tags
**Create new tag**
```bash
curl -X POST http://127.0.0.1:8000/api/tags \
  -H "Content-Type: application/json" \
  -d '{
    "name": "New Project",
    "color": "green"
  }'
```

#### 5. POST /api/tags/{tag}/attach/{file}
**Attach tag to file**
```bash
curl -X POST http://127.0.0.1:8000/api/tags/1/attach/5
```

#### 6. DELETE /api/tags/{tag}/detach/{file}
**Detach tag from file**
```bash
curl -X DELETE http://127.0.0.1:8000/api/tags/1/detach/5
```

#### 7. GET /api/{file}/comments
**Get file comments**
```bash
curl http://127.0.0.1:8000/api/1/comments
```
**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "content": "Please review",
      "user": { "id": 1, "name": "Alice" },
      "created_at": "2026-04-14 10:00:00"
    }
  ]
}
```

#### 8. POST /api/{file}/comments
**Add comment to file**
```bash
curl -X POST http://127.0.0.1:8000/api/1/comments \
  -H "Content-Type: application/json" \
  -d '{
    "content": "New comment here"
  }'
```

#### 9. DELETE /api/{file}/comments/{comment}
**Delete comment**
```bash
curl -X DELETE http://127.0.0.1:8000/api/1/comments/5
```

---

## 🛡️ Security Considerations

### Authentication
- ✅ Session-based auth (secure, CSRF protected)
- ✅ Password hashing (bcrypt)
- ✅ Email verification available
- ⚠️ TODO: Implement 2FA for admin accounts

### Authorization
- ✅ Policy-based (FilePolicy.php)
- ✅ Middleware protection on routes
- ✅ User scoping on queries
- ⚠️ Verify user ownership before operations

### Data Protection
- ✅ SQL injection: Eloquent parameterized queries
- ✅ XSS: Vue template escaping + CSP headers
- ✅ CSRF: Laravel token middleware
- ⚠️ Implement rate limiting on API endpoints
- ⚠️ Add password strength validation

### File Security
- ✅ Files stored outside web root
- ✅ Private files require auth to access
- ⚠️ Implement file type validation (whitelist)
- ⚠️ Scan uploads for malware
- ⚠️ Implement virus scanning for production

### Production Checklist
```
SECURITY:
☐ Change APP_KEY to unique value
☐ Set APP_DEBUG=false in production
☐ Configure proper CORS headers
☐ Enable HTTPS/SSL
☐ Set secure cookie flags
☐ Implement rate limiting
☐ Add request validation
☐ Configure file upload limits
☐ Set proper file permissions (755/644)
☐ Keep dependencies updated
☐ Regular security audits
☐ Implement audit logging

DATABASE:
☐ Use backups in production (PostgreSQL recommended)
☐ Enable connection pool
☐ Configure replication if needed
☐ Set up monitoring
☐ Plan disaster recovery
```

---

## 🐛 Common Issues & Solutions

### Issue: Migrations Won't Run
```bash
# Check migration status
php artisan migrate:status

# Rollback one batch
php artisan migrate:rollback

# Reset all
php artisan migrate:refresh --seed

# Force fresh migration (use with caution!)
php artisan migrate:fresh --seed
```

### Issue: Assets Not Loading / 404 on CSS/JS
```bash
# Rebuild assets
npm run build

# Or in development
npm run dev

# Clear cache
php artisan cache:clear
php artisan view:clear

# Verify manifest exists
ls -la public/build/manifest.json
```

### Issue: Files Not Uploading
```bash
# Check storage permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Verify storage link
php artisan storage:link

# Check file upload limits in php.ini
# Look for upload_max_filesize and post_max_size
php -i | grep upload
```

### Issue: Database Connection Error
```bash
# Check .env database config
cat .env | grep DB_

# For SQLite, verify file exists
ls -la database/*.sqlite

# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Issue: 403 Unauthorized on API
```bash
# Clear session/cache
php artisan cache:clear

# Verify CSRF token is being sent
# In Vue components, Inertia handles this automatically

# Check if user is authenticated
php artisan tinker
>>> Auth::user()
```

### Issue: Comments/Tags Not Persisting
```bash
# Check migration status
php artisan migrate:status

# Verify tables exist
php artisan tinker
>>> Schema::hasTable('comments')
>>> Schema::hasTable('tags')
>>> Schema::hasTable('file_tags')

# Check foreign key constraints
mysql> SHOW CREATE TABLE comments\G
```

### Issue: Star Toggle Not Working
```bash
# Verify migration ran
php artisan migrate:status | grep starred

# Check column exists
php artisan tinker
>>> Schema::hasColumn('files', 'starred')

# Verify API route exists
php artisan route:list | grep star

# Test endpoint
curl -X POST http://127.0.0.1:8000/api/1/star -v
```

---

## 📊 Monitoring & Debugging

### Enable Debug Mode
```bash
# In .env
APP_DEBUG=true
APP_LOG_LEVEL=debug

# Restart server
php artisan serve
```

### Check Logs
```bash
# Real-time log viewing
tail -f storage/logs/laravel.log

# View specific date logs
ls -la storage/logs/

# Clear old logs
php artisan maintenance:enable  # gracefully stop requests
php artisan maintenance:disable
```

### Database Debugging
```bash
php artisan tinker

# Check user count
>>> \App\Models\User::count()

# Get all files with relationships
>>> \App\Models\File::with('user', 'tags', 'comments')->get()

# Count comments per file
>>> \App\Models\File::withCount('comments')->get()
```

### Query Logging
```php
// In routes or controllers
use Illuminate\Support\Facades\DB;

DB::listen(function ($query) {
    echo $query->sql . "\n";
    echo json_encode($query->bindings) . "\n";
});
```

### Performance Monitoring
```bash
# Check PHP-FPM status
php-fpm -s

# Monitor Laravel queue
php artisan queue:monitor

# Database performance
# Check slow queries
mysql> SET GLOBAL slow_query_log = 'ON';
mysql> SET GLOBAL long_query_time = 2;
```

---

## 🧪 Testing

### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test class
php artisan test tests/Feature/ProfileTest.php

# Run with coverage
php artisan test --coverage
```

### Create New Test
```bash
php artisan make:test FeatureName --feature
php artisan make:test ModelName --unit
```

### Test Database
```bash
# Tests use separate database (in-memory SQLite by default)
# Configure in phpunit.xml
```

---

## 📦 Deployment

### Build for Production
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev
npm ci
npm run build

# Generate key if new
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed data (if needed)
php artisan db:seed

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Environment Variables (Production .env)
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=fileshare_prod
DB_USERNAME=fileshare_user
DB_PASSWORD=strong-random-password

FILESYSTEM_DISK=public
QUEUE_CONNECTION=database

# Email configuration
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
```

### Database Migration (Production)
```bash
# Always backup first!
mysqldump -u user -p database > backup.sql

# Run migrations
php artisan migrate --force

# Verify
php artisan migrate:status
```

---

## 🔄 Maintenance & Updates

### Regular Maintenance Tasks
```bash
# Weekly
- Clear cached data: php artisan cache:clear
- Review logs for errors: tail storage/logs/laravel.log
- Verify backups completed

# Monthly
- Update dependencies: composer update, npm update
- Run security audit: composer audit, npm audit
- Check disk space usage
- Review and delete old uploads

# Quarterly
- Update to latest Laravel LTS version (if applicable)
- Security scan and penetration testing
- Database optimization: OPTIMIZE TABLE files; etc.
- Review and update deployment documentation
```

### Dependency Updates
```bash
# Check for outdated packages
composer outdated
npm outdated

# Update packages
composer update
npm update

# Update specific package
composer require laravel/framework:^12
npm install vue@latest

# Test after update
php artisan migrate
npm run build
php artisan test
```

---

## 📚 Development Resources

### Useful Commands
```bash
# Generate migrations
php artisan make:migration create_table_name

# Generate models
php artisan make:model ModelName -mfp

# Generate controllers
php artisan make:controller ControllerName -r

# Generate requests
php artisan make:request FormRequest

# Generate policies
php artisan make:policy ModelPolicy --model=Model

# Tinker shell
php artisan tinker

# Serve documentation
php artisan serve --port=8001
```

### Key Files to Customize
- **config/app.php** - Application settings
- **config/auth.php** - Authentication config
- **config/filesystems.php** - Storage config
- **routes/web.php** - Web routes
- **routes/api.php** - API routes
- **app/Http/Middleware/** - Custom middleware
- **app/Policies/** - Authorization policies

### Documentation Links
- Laravel: https://laravel.com/docs
- Vue 3: https://vuejs.org
- Inertia.js: https://inertiajs.com
- Tailwind CSS: https://tailwindcss.com

---

## 🆘 Support & Contact

### Getting Help
1. Check this guide first
2. Search GitHub issues: your-repo/issues
3. Check Laravel docs: laravel.com/docs
4. Stack Overflow: tag `laravel`, `vue.js`, `inertia.js`

### Reporting Bugs
```
Include:
- PHP version (php -v)
- Error message with stack trace
- Steps to reproduce
- Expected behavior
- Actual behavior
```

### Contributing
```
1. Fork repository
2. Create feature branch: git checkout -b feature-name
3. Make changes
4. Test thoroughly
5. Commit: git commit -am 'Add feature'
6. Push: git push origin feature-name
7. Create Pull Request
```

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2026-04-14 | Initial release with Star, Tags, Comments, Dashboard features |

---

## License

FileShare is released under the MIT License.

---

**Last Updated:** 2026-04-14
**Maintainer:** FileShare Development Team
