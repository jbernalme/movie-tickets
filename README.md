# Movie Tickets 🎬

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)](https://laravel.com)
[![React](https://img.shields.io/badge/React-19-61DAFB?logo=react)](https://react.dev)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)](https://php.net)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?logo=typescript)](https://typescriptlang.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2.0-9553E9?logo=inertia)](https://inertiajs.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?logo=tailwindcss)](https://tailwindcss.com)
[![MercadoPago](https://img.shields.io/badge/MercadoPago-Integrated-009EE3?logo=mercadopago)](https://mercadopago.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

> **Full-stack movie ticket booking system** — Modern Laravel + React architecture with Inertia.js, TMDB integration, and MercadoPago payments.
>
> **Sistema completo de reserva de entradas de cine** — Arquitectura moderna Laravel + React con Inertia.js, integracion TMDB y pagos con MercadoPago.

---

## Demo / Demo en Vivo
<!-- Add your deployed URL here -->
> *Coming soon / Proximamente*

---

## Screenshots & GIFs / Capturas y GIFs
<details>
<summary><strong>Click to expand / Clic para expandir</strong></summary>

| Home / Inicio | Movie Detail / Detalle Pelicula |
|:---:|:---:|
| ![home](docs/screenshots/home.png) | ![movie-detail](docs/screenshots/movie-detail.png) |

| Seat Selection / Seleccion Asientos | Checkout & Payment / Checkout y Pago |
|:---:|:---:|
| ![seat-selection](docs/screenshots/seat-selection.gif) | ![checkout](docs/screenshots/checkout.gif) |

| Reservations / Reservas | Mobile Responsive / Responsivo Movil |
|:---:|:---:|
| ![reservations](docs/screenshots/reservations.png) | ![mobile](docs/screenshots/mobile.gif) |

</details>

---

## Tech Stack / Stack Tecnologico
<details>
<summary><strong>View Stack / Ver Stack</strong></summary>

| Category / Categoría | Technologies / Tecnologias |
|---|---|
| **Backend** | Laravel 12, PHP 8.2+, SQLite/PostgreSQL, Laravel Fortify (Auth + 2FA) |
| **Frontend** | React 19, TypeScript, Inertia.js, Tailwind CSS v4, Radix UI, Lucide Icons |
| **State & Data** | Spatie Laravel Data (DTOs), Ziggy (Route helpers), React Hooks |
| **Payments** | MercadoPago SDK (DX PHP) |
| **External APIs** | TMDB (The Movie Database) — Trending, Now Playing, Upcoming, Genres |
| **Testing** | Pest PHP, Laravel Pint (Code Style) |
| **Build & Tools** | Vite 7, ESLint 9, Prettier, Concurrently, Laravel Wayfinder |

</details>

---

## Key Features / Caracteristicas Principales
<details>
<summary><strong>View Features / Ver Caracteristicas</strong></summary>

### Movie Discovery / Descubrimiento de Peliculas
- **Trending / Populares** — TMDB trending movies weekly/daily
- **Now Playing / En Cartelera** — Current theater releases
- **Upcoming / Proximos Estrenos** — Future releases with dates
- **Genres / Generos** — Filter by genre with dynamic mapping
- **Movie Detail / Detalle** — Synopsis, cast, ratings, trailers, screenings

### Booking Flow / Flujo de Reserva
- **Visual Seat Map / Mapa Visual de Asientos** — Hall layout with seat types (Standard, VIP, Accessible)
- **Real-time Availability / Disponibilidad en Tiempo Real** — Seat locking during selection
- **Shopping Cart / Carrito** — Multi-screening cart with persistent state
- **Checkout** — Guest or authenticated flow

### Payments / Pagos
- **MercadoPago Integration** — Checkout Pro / Custom flow
- **Payment States** — Approval, Pending, Cancelled
- **Webhook Ready** — IPN/Notification handling structure

### Authentication / Autenticacion
- **Laravel Fortify** — Register, Login, Email Verification, Password Reset
- **Two-Factor Auth (2FA)** — TOTP with recovery codes
- **Session Management** — Database sessions, remember me

### UX & Polish / UX y Pulido
- **Dark Mode** — System preference + manual toggle
- **Fully Responsive** — Mobile-first, touch-friendly seat selection
- **SSR Ready** — Inertia SSR support configured
- **Accessibility** — Radix UI primitives, semantic HTML, ARIA labels

</details>

---

## Architecture Highlights / Aspectos Tecnicos Destacados
<details>
<summary><strong>View Architecture / Ver Arquitectura</strong></summary>

### Patterns & Decisions / Patrones y Decisiones
| Aspect / Aspecto | Implementation / Implementacion |
|---|---|
| **SPA without API** | Inertia.js — Laravel controllers return Inertia responses directly |
| **Data Transfer** | Spatie Laravel Data — Typed DTOs for Movie, Screening, Ticket, Payment |
| **Service Layer** | `TmdbService`, `PaymentService` — External API isolation, caching |
| **Form Handling** | Server-side validation + Inertia form helpers, client-side UX |
| **Queue Jobs** | Async payment processing, email notifications (database queue) |
| **Route Helpers** | Ziggy — Type-safe `route()` in React via `resources/js/routes/` |

### Database Design / Diseno de Base de Datos
```
movies ──┬── screenings ──┬── seats
         │                └── seat_ticket (pivot)
         │                     │
         └── genres (M2M)      └── tickets ──┬── payments
                                           └── users
```
- **Movies**: Local cache of TMDB data + custom fields (status, local slug)
- **Screenings**: Date/time + hall + movie + pricing
- **Seats**: Hall-specific, typed (Standard/VIP/Accessible), numbered
- **Tickets**: User + screening + seats + QR code + expiration
- **Payments**: MercadoPago reference, status, amounts, webhook payload

### Key Files / Archivos Clave
```
app/
├── Http/Controllers/
│   ├── HomeController.php        # TMDB aggregation + caching
│   ├── MovieController.php       # Show + index
│   ├── SeatController.php        # Selection + locking logic
│   ├── CheckoutController.php    # Cart → Order flow
│   ├── PaymentController.php     # MercadoPago callbacks
│   └── ReservationController.php # User history
├── Models/                       # Eloquent + relationships
├── Services/
│   ├── TmdbService.php           # HTTP pool, genre mapping, caching
│   └── PaymentService.php        # MercadoPago preference creation
└── Data/                         # Spatie Data DTOs (MovieData, etc.)

resources/js/
├── pages/                        # Inertia pages (home, movie, checkout, etc.)
├── components/                   # Radix-based UI library
├── layouts/MainLayout.tsx        # Navigation, dark mode, user menu
├── hooks/                        # useSeatSelection, useCart, etc.
└── types/                        # Shared TypeScript interfaces
```

</details>

---

## Prerequisites / Prerrequisitos
<details>
<summary><strong>View Requirements / Ver Requisitos</strong></summary>

| Tool | Version | Install |
|---|---|---|
| **PHP** | 8.2+ | [php.net](https://php.net/downloads) |
| **Composer** | 2.x | [getcomposer.org](https://getcomposer.org) |
| **Node.js** | 20+ | [nodejs.org](https://nodejs.org) (recommend `fnm`/`nvm`) |
| **pnpm** | 9+ | `npm i -g pnpm` (or use `npm`) |
| **Database** | SQLite (default) / PostgreSQL / MySQL | — |

</details>

---

## Installation & Setup / Instalacion y Configuracion
<details>
<summary><strong>View Steps / Ver Pasos</strong></summary>

```bash
# 1. Clone repository / Clonar repositorio
git clone https://github.com/jbernalme/movie-tickets.git
cd movie-tickets

# 2. Backend dependencies / Dependencias backend
composer install

# 3. Environment / Entorno
cp .env.example .env
php artisan key:generate

# 4. Configure .env (see below) / Configurar .env (ver abajo)
# Required: TMDB_TOKEN, MERCADOPAGO_*, TICKET_EXPIRATION_MINUTES

# 5. Database / Base de datos
php artisan migrate --seed

# 6. Frontend dependencies / Dependencias frontend
pnpm install  # or npm install

# 7. Build assets / Compilar assets
pnpm run build  # or npm run build

# 8. Run development / Ejecutar desarrollo
composer run dev
```

> **All-in-one dev command** / **Comando todo-en-uno**:
> ```bash
> composer run dev
> # Runs: php artisan serve + queue:listen + pail (logs) + vite
> ```

</details>

---

## Environment Variables / Variables de Entorno
<details>
<summary><strong>View Variables / Ver Variables</strong></summary>

| Variable | Required | Description / Descripcion |
|---|---|---|
| `APP_KEY` | Yes | `php artisan key:generate` |
| `DB_CONNECTION` | Yes | `sqlite` (default) / `pgsql` / `mysql` |
| `TMDB_URL` | Yes | `https://api.themoviedb.org/3` |
| `TMDB_TOKEN` | Yes | **Bearer token** from [TMDB Settings](https://www.themoviedb.org/settings/api) |
| `MERCADOPAGO_BASE_URI` | Yes | `https://api.mercadopago.com` (or sandbox) |
| `MERCADOPAGO_PUBLIC_KEY` | Yes | Client-side key from [MercadoPago Dev](https://www.mercadopago.com/developers) |
| `MERCADOPAGO_ACCESS_TOKEN` | Yes | Server-side access token |
| `TICKET_EXPIRATION_MINUTES` | Yes | Seat lock + payment timeout (e.g., `15`) |
| `SESSION_DRIVER` | No | `database` (recommended) |
| `QUEUE_CONNECTION` | No | `database` (default) / `redis` |
| `CACHE_STORE` | No | `database` / `redis` |

> **Get TMDB Token**: Create account → Settings → API → "Read Access Token" (v4 auth)
> **Get MercadoPago Keys**: Developer Dashboard → Your Integrations → Credentials

</details>

---

## Running the Project / Ejecutando el Proyecto
<details>
<summary><strong>View Commands / Ver Comandos</strong></summary>

| Command / Comando | Description / Descripcion |
|---|---|
| `composer run dev` | **Recommended**: Starts server, queue, logs, Vite concurrently |
| `composer run dev:ssr` | With Inertia SSR (`php artisan inertia:start-ssr`) |
| `pnpm run dev` | Vite dev server only (HMR) |
| `php artisan serve` | Laravel server only (`http://localhost:8000`) |
| `php artisan queue:listen` | Process queued jobs |
| `php artisan pail` | Real-time log viewer |
| `pnpm run build` | Production build |
| `pnpm run build:ssr` | Production + SSR build |
| `composer run test` | Run Pest test suite |
| `pnpm run lint` | ESLint + auto-fix |
| `pnpm run format` | Prettier write |
| `pnpm run types` | TypeScript type-check |

</details>

---

## Project Structure / Estructura del Proyecto
<details>
<summary><strong>View Structure / Ver Estructura</strong></summary>

```
movie-tickets/
├── app/
│   ├── Actions/Fortify/        # Fortify customizations (2FA, password reset)
│   ├── Data/                   # Spatie Data DTOs (MovieData, ScreeningData...)
│   ├── Http/Controllers/       # Inertia controllers (Home, Movie, Seat, Payment...)
│   ├── Models/                 # Eloquent models + relationships
│   └── Services/               # External API services (Tmdb, Payment)
├── database/
│   ├── factories/              # Model factories (Pest tests)
│   ├── migrations/             # 16 migrations (users, movies, seats, tickets...)
│   └── seeders/                # DatabaseSeeder + MovieSeeder (TMDB sync)
├── resources/
│   ├── css/app.css             # Tailwind v4 imports
│   └── js/
│       ├── app.tsx             # Inertia + React entry
│       ├── ssr.tsx             # SSR entry
│       ├── components/         # Reusable UI (Button, Modal, SeatMap, Cart...)
│       ├── layouts/            # MainLayout, AuthLayout
│       ├── pages/              # Inertia pages (home, movie, checkout, reservations...)
│       ├── hooks/              # Custom hooks (useSeatMap, useCart, useDebounce...)
│       ├── routes.ts           # Ziggy route helpers (typed)
│       └── types/              # Shared TS interfaces (Movie, Screening, Ticket...)
├── routes/
│   ├── web.php                 # Main routes (public + auth + payment)
│   └── settings.php            # Fortify settings routes
├── tests/                      # Pest tests (Feature + Unit)
├── .env.example                # Environment template
├── composer.json               # PHP dependencies + scripts
├── package.json                # Node dependencies + scripts
├── vite.config.ts              # Vite + Laravel plugin + React + Tailwind
└── tsconfig.json               # TypeScript config (strict)
```

</details>

---

## API & Routes / Rutas Principales
<details>
<summary><strong>View Routes / Ver Rutas</strong></summary>

### Public / Publicas
| Method | URI | Controller | Name |
|---|---|---|---|
| GET | `/` | `HomeController@index` | `home` |
| GET | `/movies` | `MovieController@index` | `movies` |
| GET | `/movie/{movie}/{slug?}` | `MovieController@show` | `movie.show` |
| GET | `/booking/{slug?}/{screening}` | `SeatController@select` | `seat.select` |

### Authenticated / Autenticadas
| Method | URI | Controller | Name |
|---|---|---|---|
| GET | `/dashboard` | Inertia: `dashboard` | `dashboard` |
| GET | `/reservations` | `ReservationController@index` | `reservations.index` |
| POST | `/checkout` | `CheckoutController@index` | `checkout.index` |
| POST | `/payment/process` | `PaymentController@process` | `payment.process` |
| GET | `/payment/approval` | `PaymentController@approval` | `payment.approval` |
| GET | `/payment/cancelled` | `PaymentController@cancelled` | `payment.cancelled` |
| GET | `/payment/pending` | `PaymentController@pending` | `payment.pending` |

### Auth (Fortify) / Auth (Fortify)
`/login`, `/register`, `/password/reset`, `/email/verification`, `/two-factor-challenge`

</details>

---

## Payment Flow / Flujo de Pagos
<details>
<summary><strong>View Flow / Ver Flujo</strong></summary>

```
User selects seats
        │
        ▼
  Checkout page
        │
        ▼
Create MercadoPago Preference
        │
        ▼
Redirect to MercadoPago
        │
        ▼
   ┌────┴────┐
   │  User   │
   │  pays?  │
   └────┬────┘
        │
   ┌────┼────┐
   ▼    ▼    ▼
  OK  Pending Cancel
   │    │      │
   ▼    ▼      ▼
/approval /pending /cancelled
   │
   ▼
Create Tickets + QR
   │
   ▼
Send confirmation email
   │
   ▼
Redirect to reservations
```

### Payment Controller Actions
- **`process`**: Creates MercadoPago preference → returns `init_point` URL
- **`approval`**: Verifies payment → creates `Ticket` records → clears cart
- **`cancelled`**: Returns to checkout with flash message
- **`pending`**: Shows waiting state, polls for update

</details>

---

## Testing / Pruebas
<details>
<summary><strong>View Testing / Ver Pruebas</strong></summary>

```bash
# Run all tests / Ejecutar todas las pruebas
composer run test
# or: php artisan test

# With coverage / Con cobertura
php artisan test --coverage

# Specific test file / Archivo especifico
php artisan test tests/Feature/PaymentTest.php

# Parallel (faster) / Paralelo (mas rapido)
php artisan test --parallel
```

**Test Stack**: Pest PHP (v4) + Laravel Pest Plugin
- **Feature tests**: Controllers, payment flow, seat locking
- **Unit tests**: Services (TmdbService, PaymentService), Data DTOs
- **Factories**: User, Movie, Screening, Seat, Ticket, Payment

</details>

---

## Deployment / Despliegue
<details>
<summary><strong>View Deployment / Ver Despliegue</strong></summary>

### Build for Production / Construir para Produccion
```bash
# Standard SPA build
pnpm run build
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# With SSR (separate Node process)
pnpm run build:ssr
# Then run: php artisan inertia:start-ssr (via Supervisor/PM2)
```

### Production Checklist / Lista de Produccion
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Strong `APP_KEY` (rotate if needed)
- [ ] Production database (PostgreSQL/MySQL)
- [ ] `SESSION_DRIVER=redis` or `database`
- [ ] `QUEUE_CONNECTION=redis` + Supervisor/Horizon
- [ ] `CACHE_STORE=redis`
- [ ] HTTPS + secure cookies (`SESSION_SECURE_COOKIE=true`)
- [ ] MercadoPago **production** credentials (not sandbox)
- [ ] TMDB production token
- [ ] Queue worker: `php artisan queue:work --sleep=3 --tries=3`
- [ ] Schedule: `php artisan schedule:work` (if using scheduler)
- [ ] SSR process manager (PM2/Supervisor) if using SSR

### Docker (Optional) / Docker (Opcional)
> Add `Dockerfile`, `docker-compose.yml` for containerized deployment

</details>

---

## What I Learned / Lo que Aprendi
<details>
<summary><strong>View Highlights / Ver Aspectos Destacados</strong></summary>

### Technical Challenges / Desafios Tecnicos
| Challenge / Desafio | Solution / Solucion |
|---|---|
| **Inertia + React SSR** | Configured Vite SSR entry (`ssr.tsx`), separate build script, PM2 process management |
| **Real-time seat locking** | Database row locking (`SELECT FOR UPDATE`), TTL-based expiration via queue job |
| **TMDB data sync** | Scheduled command + HTTP pooling for parallel requests, genre mapping cache |
| **MercadoPago integration** | Preference creation, webhook verification, idempotency handling, sandbox/production toggle |
| **Type-safe routes** | Ziggy + TypeScript generation via `laravel-vite-plugin-wayfinder` |
| **Complex form state** | React Hook Form + Zod (client) + Spatie Data (server) dual validation |
| **Dark mode + Tailwind v4** | CSS-first config, `dark:` variant, system preference detection |

### Key Takeaways / Conclusiones Clave
- **Inertia.js** eliminates API boilerplate while keeping SPA UX — best of both worlds
- **Spatie Laravel Data** transforms validation → DTO → resource pipeline cleanly
- **Service classes** for external APIs make testing trivial (mock `Http::fake()`)
- **Radix UI + Tailwind** = accessible components without fighting CSS
- **Database queue + jobs** handles async payment flows reliably at low cost
- **Pest** makes testing expressive — `expect($response)->toBeSuccessful()`

### Would Improve / Mejoraria
- [ ] Event sourcing for seat reservations (audit trail)
- [ ] Redis-backed seat locks (distributed locking)
- [ ] Webhook signature verification middleware
- [ ] E2E tests with Playwright
- [ ] OpenAPI/Swagger docs for payment callbacks
- [ ] Admin panel (Filament) for movie/screening management

</details>

---

## Contributing / Contribuir
<details>
<summary><strong>View Guidelines / Ver Guias</strong></summary>

This is a **portfolio project** — contributions aren't expected, but feel free to:
- Fork & experiment
- Open issues for bugs
- Suggest improvements via PR

```bash
# Development workflow
git checkout -b feature/amazing-feature
# Make changes
pnpm run lint && pnpm run format && pnpm run types
composer run test
git commit -m "feat: amazing feature"
git push origin feature/amazing-feature
```

</details>

---

## License / Licencia

**MIT License** — see [LICENSE](LICENSE) for details.

---

## Contact / Contacto
<details>
<summary><strong>View Contact / Ver Contacto</strong></summary>

> **Placeholder — Replace with your info / Marcador de posicion — Reemplaza con tu info**

- **GitHub**: [@jbernalme](https://github.com/jbernalme)
- **LinkedIn**: [Your LinkedIn](https://linkedin.com/in/your-profile)
- **Email**: your.email@example.com
- **Portfolio**: https://your-portfolio.dev

</details>

---

## Star History / Historial de Estrellas

[![Star History Chart](https://api.star-history.com/svg?repos=jbernalme/movie-tickets&type=Date)](https://star-history.com/#jbernalme/movie-tickets&Date)

---

<div align="center">

**Built with love using Laravel + React + Inertia.js**

**Construido con amor usando Laravel + React + Inertia.js**

</div>
