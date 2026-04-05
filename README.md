# 📸 InstaLaravel — Guia d'instal·lació completa

**Projecte:** DAW M613 B3 — Laravel 12 Instagram Clone  
**Stack:** Laravel 12 · Laravel Breeze · Blade · Tailwind CSS · SQLite/MySQL

---

## 🗂️ Estructura del projecte

```
installaravel/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/RegisteredUserController.php  ← Registre amb camps extra
│   │   ├── HomeController.php                 ← Llistat paginat d'imatges
│   │   ├── ImageController.php                ← CRUD imatges
│   │   ├── CommentController.php              ← CRUD comentaris
│   │   ├── LikeController.php                 ← Like/dislike AJAX
│   │   └── ProfileController.php              ← Edició perfil + avatar
│   ├── Models/
│   │   ├── User.php     ← role, name, surname, nick, image, phone_number
│   │   ├── Image.php    ← user_id, image_path, description
│   │   ├── Comment.php  ← user_id, image_id, content
│   │   └── Like.php     ← user_id, image_id (unique)
│   └── Providers/
│       └── AppServiceProvider.php  ← Paginació Tailwind
├── database/
│   ├── factories/        ← UserFactory, ImageFactory, CommentFactory, LikeFactory
│   ├── migrations/       ← 4 migracions
│   └── seeders/
│       └── DatabaseSeeder.php  ← Pobla la BD amb dades realistes
├── resources/views/
│   ├── layouts/app.blade.php       ← Layout principal amb navbar + avatar
│   ├── home/index.blade.php        ← Graella paginada d'imatges
│   ├── images/
│   │   ├── show.blade.php          ← Detall + comentaris + like reactiu
│   │   ├── create.blade.php        ← Formulari pujar imatge
│   │   ├── edit.blade.php          ← Formulari editar imatge
│   │   └── edit_comment.blade.php  ← Formulari editar comentari
│   ├── profile/edit.blade.php      ← Edició perfil + avatar
│   └── auth/
│       ├── login.blade.php         ← Login personalitzat
│       └── register.blade.php      ← Registre amb camps extra
└── routes/
    ├── web.php   ← Totes les rutes de l'aplicació
    └── auth.php  ← Rutes d'autenticació Breeze
```

---

## 🚀 Instal·lació pas a pas

### Prerequisits
- PHP >= 8.2
- Composer
- Node.js >= 18 + npm
- MySQL (o SQLite per a proves ràpides)

---

### Pas 1 — Crea un projecte Laravel 12 nou

```bash
composer create-project laravel/laravel nom-del-teu-projecte
cd nom-del-teu-projecte
```

---

### Pas 2 — Instal·la Laravel Breeze (autenticació + Tailwind)

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
```

---

### Pas 3 — Copia els arxius d'aquest ZIP

Copia tots els arxius/carpetes d'aquest ZIP **sobreescrivint** els originals del teu projecte Laravel:

```
app/Http/Controllers/Auth/RegisteredUserController.php
app/Http/Controllers/HomeController.php
app/Http/Controllers/ImageController.php
app/Http/Controllers/CommentController.php
app/Http/Controllers/LikeController.php
app/Http/Controllers/ProfileController.php
app/Models/User.php
app/Models/Image.php
app/Models/Comment.php
app/Models/Like.php
app/Providers/AppServiceProvider.php
database/factories/UserFactory.php
database/factories/ImageFactory.php
database/factories/CommentFactory.php
database/factories/LikeFactory.php
database/migrations/2025_01_01_000001_add_fields_to_users_table.php
database/migrations/2025_01_01_000002_create_images_table.php
database/migrations/2025_01_01_000003_create_comments_table.php
database/migrations/2025_01_01_000004_create_likes_table.php
database/seeders/DatabaseSeeder.php
resources/views/layouts/app.blade.php
resources/views/home/index.blade.php
resources/views/images/show.blade.php
resources/views/images/create.blade.php
resources/views/images/edit.blade.php
resources/views/images/edit_comment.blade.php
resources/views/profile/edit.blade.php
resources/views/auth/login.blade.php
resources/views/auth/register.blade.php
routes/web.php
routes/auth.php
```

---

### Pas 4 — Configura la base de dades

Edita el fitxer **`.env`** a l'arrel del projecte:

**Opció A — MySQL (recomanat per al projecte):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=installaravel
DB_USERNAME=root
DB_PASSWORD=
```

Crea la base de dades a MySQL:
```sql
CREATE DATABASE installaravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Opció B — SQLite (ràpid per a proves):**
```env
DB_CONNECTION=sqlite
```
```bash
touch database/database.sqlite
```

---

### Pas 5 — Executa les migracions

```bash
php artisan migrate
```

---

### Pas 6 — Crea l'enllaç simbòlic per a imatges

```bash
php artisan storage:link
```

Comprova que s'ha creat:
```bash
ls -la public/storage
# Ha de mostrar: storage -> ../storage/app/public
```

---

### Pas 7 — Pobla la base de dades (factories + seeders)

```bash
# Per a una BD neta des de zero (recomanat per al vídeo):
php artisan migrate:fresh --seed

# O si ja tens la BD migrada i vols afegir dades:
php artisan db:seed
```

**Sortida esperada:**
```
✅ Base de dades poblada correctament!
👤 Usuari de prova: test@example.com / password
📸 Total imatges: ~35-50
💬 Total comentaris: ~70-100
❤️  Total likes: ~80-150
```

> **Nota:** El seeder intenta descarregar imatges reals de https://picsum.photos.
> Si no hi ha connexió a internet, genera imatges de color sòlid automàticament.

---

### Pas 8 — Compila els assets CSS/JS

```bash
npm run dev
```

(En producció: `npm run build`)

---

### Pas 9 — Inicia el servidor de Laravel

**En un segon terminal** (mantenint `npm run dev` obert):

```bash
php artisan serve
```

---

### Pas 10 — Obre l'aplicació

Navega a: **http://localhost:8000**

---

## 🔑 Credencials de prova

| Camp     | Valor              |
|----------|--------------------|
| Email    | test@example.com   |
| Password | password           |

Tots els usuaris generats pels seeders també tenen contrasenya `password`.

---

## 📋 Funcionalitats implementades

| Funcionalitat | Ruta | Notes |
|---|---|---|
| Llistat paginat | `GET /` | 9 imatges per pàgina, eager loading |
| Detall imatge | `GET /images/{id}` | Comentaris ordenats ASC |
| Pujar imatge | `GET/POST /images/create` | Validació + previsualització |
| Editar imatge | `GET/PUT /images/{id}/edit` | Only owner |
| Eliminar imatge | `DELETE /images/{id}` | Only owner + cascade |
| Afegir comentari | `POST /images/{id}/comments` | Auth required |
| Editar comentari | `GET/PUT /comments/{id}/edit` | Only owner |
| Eliminar comentari | `DELETE /comments/{id}` | Only owner |
| Like/dislike | `POST /images/{id}/like` | AJAX reactiu (sense reload) |
| Editar perfil | `GET/PUT /profile` | Inclou avatar |
| Registre | `GET/POST /register` | name, surname, nick, phone |
| Login | `GET/POST /login` | |

---

## 🗃️ Estructura de la base de dades

```
USERS
├── id (PK)
├── role          → 'user' | 'admin'
├── name          → Nom
├── surname       → Cognom (nullable)
├── nick          → Nom d'usuari únic (nullable)
├── email         → Únic
├── password      → Encriptat
├── image         → Ruta de l'avatar (nullable)
├── phone_number  → Telèfon (nullable)
├── created_at
└── updated_at

IMAGES
├── id (PK)
├── user_id (FK → users)  → CASCADE DELETE
├── image_path             → Ruta a storage/app/public
├── description (nullable)
├── created_at
└── updated_at

COMMENTS
├── id (PK)
├── user_id (FK → users)   → CASCADE DELETE
├── image_id (FK → images) → CASCADE DELETE
├── content
├── created_at
└── updated_at

LIKES
├── id (PK)
├── user_id (FK → users)   → CASCADE DELETE
├── image_id (FK → images) → CASCADE DELETE
├── UNIQUE (user_id, image_id)
├── created_at
└── updated_at
```

---

## ⚠️ Possibles problemes i solucions

**Problema:** Les imatges no es veuen  
**Solució:** `php artisan storage:link`

**Problema:** Error 419 (CSRF) en fer like  
**Solució:** Assegura't que el layout té `<meta name="csrf-token" content="{{ csrf_token() }}">`

**Problema:** "Class not found" en algun model  
**Solució:** `composer dump-autoload`

**Problema:** Les migracions fallen (duplicate column)  
**Solució:** `php artisan migrate:fresh --seed` (esborra i recrea tot)

**Problema:** Imatges en blanc al seeder (sense internet)  
**Solució:** Normal — el seeder crea imatges de color sòlid com a fallback

---

## 🎬 Guia per al vídeo de presentació

**Ordre recomanat:**

1. `php artisan migrate:fresh --seed` — mostra la BD neta + seeding
2. Navega a http://localhost:8000 — pàgina principal amb imatges paginades
3. Registre d'un nou usuari (amb nom, cognom, nick, telèfon)
4. Login amb test@example.com / password
5. Editar perfil → canviar avatar → desar → veure l'avatar al navbar
6. Pujar una nova imatge → veure-la al llistat
7. Editar la imatge → canviar descripció → desar
8. Obrir una imatge → fer like (reactiu, sense reload) → fer dislike
9. Afegir un comentari a una imatge aliena
10. Editar i eliminar el teu propi comentari
11. Eliminar una imatge pròpia
12. Canvi de pàgina a la paginació

---

*Projecte realitzat per a DAW M613 B3 — Laravel 12*
