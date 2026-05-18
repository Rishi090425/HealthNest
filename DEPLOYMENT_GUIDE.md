# 🚀 Health Nest Deployment Guide

You mentioned you are getting a "could not find driver" error and failing to deploy. This usually happens on cloud platforms for two reasons:
1. **Wrong web server:** Using `php artisan serve` instead of a real web server like Apache.
2. **Missing PHP Extensions:** Standard containers/builders do not always include database drivers (like `sqlite` or `pgsql`) by default.

We have fully upgraded your `Dockerfile` to use **Apache (Production Ready)**, compile all Vite frontend assets automatically during building, and install SQLite, MySQL, and PostgreSQL drivers.

We have also created a **Render Blueprint (`render.yaml`)** to let you spin up the entire application (Web Service + production-ready PostgreSQL database) automatically with zero-config database linking!

---

## 🌐 Option 1: Deploying to Render (Recommended & Highly Secure)

Using our Render Blueprint configuration, you can deploy both your Laravel app and a persistent PostgreSQL database.

### Step 1: Push Changes to GitHub
Make sure all updated files (`Dockerfile`, `render.yaml`, and `000-default.conf`) are committed and pushed to your GitHub repository.

### Step 2: Create a Blueprint Instance on Render
1. Log into your [Render Dashboard](https://dashboard.render.com).
2. Click **New** (top right) -> **Blueprint**.
3. Connect your GitHub repository containing the project.
4. Render will read the `render.yaml` file automatically and list the services it will create:
   - **`health-nest-web`** (Web Service using Docker)
   - **`health-nest-db`** (PostgreSQL Database)
5. Under **Blueprint Instance Name**, enter a friendly name (e.g. `health-nest-production`).
6. Click **Apply**. 
7. Render will automatically provision the database, build the Docker container (compiling Vite assets automatically), link the two services together, run migrations, and launch your application!

### Step 3: Set a Static APP_KEY (Crucial for Production)
The `Dockerfile` is smart enough to generate a safe `APP_KEY` if none is set. However, on Render's free tier, the server container restarts or goes to sleep after inactivity. Generating a new key on every boot will log out your active users and render any encrypted database entries un-decryptable.

To make the key persistent:
1. Copy the `APP_KEY` from your local `.env` file (or generate a new one using `php artisan key:generate --show`).
2. In your Render Dashboard, click on your new **`health-nest-web`** Web Service.
3. Navigate to the **Environment** tab.
4. Click **Add Environment Variable**:
   - **Key:** `APP_KEY`
   - **Value:** `base64:YOUR_GENERATED_KEY_HERE`
5. Click **Save Changes**. The web service will securely redeploy using your permanent key!

---

## 🚂 Option 2: Deploying to Railway

If you prefer to deploy to Railway:

1. **Commit and Push:** Push your latest code (including `Dockerfile` and `000-default.conf`) to your GitHub repo.
2. **Create Project:** On Railway, click **New Project** -> **Deploy from GitHub repo**.
3. **Change Builder to Dockerfile:**
   - Click on your new service.
   - Go to **Settings** -> **Build** -> **Builder**.
   - Change it from "Nixpacks" to **Dockerfile**.
4. **Set Environment Variables:**
   - Go to the **Variables** tab.
   - Add the following variables:
     - `PORT`: `8080`
     - `APP_KEY`: `base64:YOUR_KEY_HERE`
     - `APP_ENV`: `production`
     - `APP_DEBUG`: `false`
     - `DB_CONNECTION`: `sqlite`
5. **Generate Domain:** Go to the **Settings** tab -> **Networking** -> click **Generate Domain**.

---

### ⚠️ WARNING: SQLite Data Loss Warning
If you choose to use SQLite instead of PostgreSQL:
- Platforms like Railway and Render use **ephemeral file systems** for their default containers. This means every time you deploy an update or the server restarts, **your SQLite database will be completely wiped!**
- To prevent this with SQLite, you must provision a **Persistent Volume** in your host's dashboard (e.g. mount a volume to `/app/data` and change the environment variable to `DB_DATABASE=/app/data/database.sqlite`). Using **PostgreSQL** via Render Blueprint (Option 1) is much simpler and highly recommended!

