# 🚀 Health Nest Deployment Guide

You mentioned you are getting a "could not find driver" error and failing to deploy. This usually happens on cloud platforms (like Railway, Render, or Heroku) for two reasons:
1. **Wrong web server:** Using `php artisan serve` instead of a real web server like Apache or Nginx.
2. **Missing PHP Extensions:** The default builders (like Nixpacks) don't always install the `sqlite3` PHP extension correctly.

I have updated your `Dockerfile` and added a `000-default.conf` file to fix these issues. Your app is now using **Apache (Production Ready)** and explicitly installs the SQLite drivers!

## Deploying to Railway (Recommended)

1. **Commit and Push:** Push your latest code (including the new `Dockerfile` and `000-default.conf`) to your GitHub repository.
2. **Create Project:** On Railway, create a new project and select **Deploy from GitHub repo**.
3. **Change Builder to Dockerfile:**
   - Click on your new service.
   - Go to **Settings** -> **Build** -> **Builder**.
   - Change it from "Nixpacks" to **Dockerfile**.
4. **Set Environment Variables:**
   - Go to the **Variables** tab.
   - Add the following variables:
     - `PORT`: `8080`
     - `APP_KEY`: `base64:qzCK5xiZyRoKTeIIhiR4PAPLAjJ0b8lFmVF8V4wX3fA=` (Or generate a new one)
     - `APP_ENV`: `production`
     - `APP_DEBUG`: `false`
     - `DB_CONNECTION`: `sqlite`
5. **Generate Domain:** Go to the **Settings** tab -> **Networking** -> click **Generate Domain**.

---

### ⚠️ IMPORTANT: SQLite on Cloud Platforms (Data Loss Warning)

Platforms like Railway and Render use **ephemeral file systems**. This means every time you deploy an update or the server restarts, **your SQLite database will be WIPED completely!** 

To fix this for a production app, you have two options:

**Option 1: Add a Persistent Volume (Keep using SQLite)**
- On Railway, go to your service's **Volumes** tab and create a new volume.
- Mount the volume to a new folder, e.g., `/app/data`.
- In your **Variables**, change the database path: `DB_DATABASE=/app/data/database.sqlite`.

**Option 2: Use PostgreSQL or MySQL (Best for Production)**
- On Railway, right-click the empty space on the canvas and click **New -> Database -> Add PostgreSQL**.
- Wait for it to deploy, then click your Laravel service -> **Variables**.
- Railway will let you **Reference** variables from the Postgres service. Add a `DATABASE_URL` variable and point it to the Postgres service's `DATABASE_URL`.
- Change `DB_CONNECTION` to `pgsql`.
- Your app will now securely connect to a real database that never deletes your data!
