# 🚀 FileShare - Quick Reference Guide

## Start the Application

### Option 1: Development Mode
```bash
# Terminal 1
php artisan serve

# Terminal 2 (optional for hot reload)
npm run dev

# Browser: http://localhost:8000
```

### Option 2: Production Mode
```bash
php artisan serve
# (Assets already built)
```

## Demo Credentials
```
Email:    alice@demo.com
Password: password

Or

Email:    bob@demo.com  
Password: password
```

## Key URLs

| URL | Purpose |
|-----|---------|
| http://localhost:8000 | Landing page (public) |
| http://localhost:8000/login | Login page |
| http://localhost:8000/files | File dashboard (auth) |
| http://localhost:8000/files/create | Upload page (auth) |
| http://localhost:8000/share/{token} | Public share link |
| /api/files | Public API |

## Common Commands

```bash
# Database
php artisan migrate                # Run migrations
php artisan migrate:rollback       # Rollback migrations
php artisan migrate --seed         # Run with seeders
php artisan tinker                 # Interactive shell

# Cache & Optimization
php artisan config:cache           # Cache config
php artisan view:clear             # Clear views
php artisan cache:clear            # Clear cache

# Routes & Database
php artisan route:list             # List all routes
php artisan db:show                # Database info

# Frontend
npm run dev                         # Watch for changes
npm run build                       # Build for production
```

## Project Files

| File | Purpose |
|------|---------|
| README.md | Quick start guide |
| PROJECT_SUMMARY.md | Detailed rubric compliance |
| DEMO.md | 3-minute presentation script |
| .env | Environment config |
| routes/web.php | Web routes |
| routes/api.php | API routes |
| app/Http/Controllers/FileController.php | Main CRUD logic |
| resources/js/Pages/Files/ | Vue components |
| database/database.sqlite | SQLite database |

## Troubleshooting

**Port 8000 in use?**
```bash
php artisan serve --port=8001
```

**Database permission denied?**
```bash
chmod -R 775 storage bootstrap/cache database
```

**Assets not loading?**
```bash
npm install --legacy-peer-deps
npm run build
```

**Need fresh database?**
```bash
rm database/database.sqlite
php artisan migrate --seed
```

## File Structure

```
FileShare/
├── app/                    # Application code
├── bootstrap/              # Framework bootstrap
├── config/                 # Configuration files
├── database/
│   ├── database.sqlite    # SQLite database
│   ├── migrations/        # Database migrations
│   └── seeders/           # Data seeders
├── public/
│   └── build/             # Compiled assets
├── resources/
│   ├── css/               # Stylesheets
│   └── js/
│       ├── app.js         # Inertia setup
│       └── Pages/         # Vue pages
├── routes/                # Route definitions
├── storage/               # File storage
├── tests/                 # Test files
├── .env                   # Environment variables
├── README.md              # This project description
├── DEMO.md                # Demo script
└── PROJECT_SUMMARY.md     # Detailed notes
```

## For the Demo

1. **Start server**: `php artisan serve`
2. **Open browser**: http://localhost:8000
3. **Follow DEMO.md** for 3-minute walkthrough
4. **Key features to show:**
   - Landing page with public file API
   - Login/authentication
   - File upload (CREATE)
   - File listing (READ)
   - File edit (UPDATE)
   - File deletion (DELETE)
   - Share link
   - Authorization/policies

## Important Notes

- ✅ All dependencies installed and configured
- ✅ Database created and seeded
- ✅ Frontend assets compiled
- ✅ All routes defined and working
- ✅ Authorization policies active
- ✅ Middleware protection in place
- ✅ SEO tags included
- ✅ API endpoints functional

## Support Docs

- **Laravel**: https://laravel.com/docs
- **Vue**: https://vuejs.org
- **Inertia**: https://inertiajs.com
- **Tailwind**: https://tailwindcss.com

---

**Status**: ✅ Ready for Demo (April 14, 2026)
