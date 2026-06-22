# Hostel Management System - Docker Setup

## Overview
This Docker setup includes:
- **PHP 8.0** with Apache web server
- **MySQL 8.0** database
- **PHPMyAdmin** for database management

## Prerequisites
- Docker installed on your system
- Docker Compose installed

## Quick Start

### 1. Build and Run the Application
```bash
docker-compose up -d
```

### 2. Access the Application
- **Web Application**: http://localhost
- **PHPMyAdmin**: http://localhost:8080

### 3. Import Database
1. Open PHPMyAdmin: http://localhost:8080
2. Login credentials:
   - Username: `excelcb2_hostel`
   - Password: `EDWOLWN7}QM^ld@&`
3. Select the `excelcb2_hostel` database
4. Go to Import tab
5. Choose and upload SQL files from the `database/` directory:
   - `hostel_management_system_Application.sql`
   - `hostel_management_system_Hostel_Manager.sql`
   - `hostel_management_system_Hostel.sql`
   - `hostel_management_system_Message.sql`
   - `hostel_management_system_Room.sql`
   - `hostel_management_system_Student.sql`

### 4. Useful Commands

**View logs:**
```bash
docker-compose logs -f php
docker-compose logs -f mysql
```

**Stop services:**
```bash
docker-compose down
```

**Stop and remove volumes (clears database):**
```bash
docker-compose down -v
```

**Restart services:**
```bash
docker-compose restart
```

**Access MySQL CLI:**
```bash
docker-compose exec mysql mysql -u excelcb2_hostel -p excelcb2_hostel
```

## File Structure
- `Dockerfile` - PHP Apache configuration
- `docker-compose.yml` - Services orchestration
- `.dockerignore` - Files excluded from Docker build
- `includes/config.inc.php` - Updated database configuration for Docker

## Database Credentials
- **Host**: mysql (within Docker network), localhost:3306 (external)
- **Username**: excelcb2_hostel
- **Password**: EDWOLWN7}QM^ld@&
- **Database**: excelcb2_hostel

## PHPMyAdmin Access
- **URL**: http://localhost:8080
- **Server**: mysql
- **Username**: excelcb2_hostel
- **Password**: EDWOLWN7}QM^ld@&

## Port Mapping
- PHP/Apache: Port 80 (container) → Port 80 (host)
- MySQL: Port 3306 (container) → Port 3306 (host)
- PHPMyAdmin: Port 80 (container) → Port 8080 (host)

## Troubleshooting

**Permission issues:**
```bash
docker-compose exec php chown -R www-data:www-data /var/www/html
```

**Database connection issues:**
- Ensure MySQL service is running: `docker-compose ps`
- Check logs: `docker-compose logs mysql`
- Verify credentials in `includes/config.inc.php`

**PHPMyAdmin won't connect:**
- Wait 30 seconds after startup (MySQL needs to initialize)
- Refresh the page
- Check MySQL logs

## Notes
- All files in the application directory are volume-mounted, so code changes are reflected immediately
- Database data persists in the `mysql_data` volume between container restarts
- SQL files in the `database/` directory are automatically imported on first MySQL startup
