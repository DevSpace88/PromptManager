# Railway Deployment Troubleshooting Guide

## Common Issues and Solutions

### 1. Database Connection Issues

**Error:** `Connection refused` or `SQLSTATE[HY000] [2002] Connection refused`

**Solutions:**

- Ensure you have a MySQL database service added to your Railway project
- Check that the `DATABASE_URL` environment variable is properly set
- The migration script now has enhanced retry logic with longer timeouts
- Wait for the database service to be fully ready (can take 1-2 minutes)

### 2. Testing Database Connection

Run the connection test script to verify your database setup:

```bash
php database/test-connection.php
```

This will show you:

- Environment variables
- Database configuration
- Connection test results

### 3. Environment Variables

Make sure these environment variables are set in Railway:

**Required:**

- `DATABASE_URL` - Provided by Railway MySQL service
- `APP_KEY` - Generated automatically during deployment
- `APP_ENV=production`
- `APP_DEBUG=false`

**Optional but recommended:**

- `APP_NAME` - Your application name
- `APP_URL` - Your Railway app URL

### 4. Deployment Process

The deployment process now includes:

1. **Composer install** - Install PHP dependencies
2. **NPM build** - Build frontend assets
3. **Generate app key** - Create Laravel application key
4. **Clear config cache** - Ensure fresh configuration
5. **Wait 45 seconds** - Allow database service to be ready
6. **Run migration helper** - Enhanced migration with retry logic
7. **Cache configurations** - Optimize for production

### 5. Migration Helper Features

The enhanced migration helper (`database/migration-helper.php`) includes:

- **15 retry attempts** with exponential backoff
- **10-minute total timeout** for database operations
- **PDO exception handling** for connection issues
- **Configuration cache clearing** before each attempt
- **Detailed logging** of each attempt
- **Graceful error handling** with helpful messages

### 6. Manual Database Migration

If automatic migration fails, you can manually run:

```bash
# Test connection first
php database/test-connection.php

# Run migrations manually
php artisan migrate --force

# Or use the enhanced helper
php database/migration-helper.php
```

### 7. Railway-Specific Configuration

The `AppServiceProvider` automatically:

- Parses the `DATABASE_URL` from Railway
- Configures MySQL connection settings
- Sets up HTTPS for production
- Handles Railway's proxy headers

### 8. Debugging Steps

1. **Check Railway logs** for detailed error messages
2. **Verify database service** is running and accessible
3. **Test connection** using the test script
4. **Check environment variables** in Railway dashboard
5. **Review migration logs** for specific error details

### 9. Common Railway Commands

```bash
# View deployment logs
railway logs

# Check environment variables
railway variables

# Connect to database (if needed)
railway connect

# Redeploy with fresh start
railway up --detach
```

### 10. Performance Optimization

After successful deployment:

- All Laravel caches are automatically generated
- Assets are optimized for production
- Database connections are configured for Railway's environment

### 11. Monitoring

Monitor your deployment:

- Check Railway dashboard for service status
- Review application logs for errors
- Verify database connectivity
- Test application functionality

## Quick Fix Checklist

- [ ] MySQL database service added to Railway project
- [ ] `DATABASE_URL` environment variable is set
- [ ] `APP_ENV=production` is set
- [ ] `APP_DEBUG=false` is set
- [ ] All required environment variables are configured
- [ ] Database service is running and accessible
- [ ] Application can connect to database
- [ ] Migrations run successfully
- [ ] Application serves correctly

## Support

If you continue to experience issues:

1. Run the connection test script
2. Check Railway logs for specific error messages
3. Verify all environment variables are set correctly
4. Ensure database service is properly configured
5. Try redeploying with a fresh start
