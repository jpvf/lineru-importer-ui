# CLAUDE.md — lineru-importer-ui

Laravel 13 + Livewire 4 — UI de monitoreo para aurora-sync daemon.
Corre en puerto 8091. Se comunica con el daemon en `AURORA_API_URL` (default: http://192.168.1.113:8090).

## Stack

- Laravel 13, PHP 8.3
- Livewire 4 (componentes reactivos con polling)
- Tailwind CSS via CDN (sin Vite/npm build)
- Docker: php:8.3-cli + php artisan serve

## Estructura custom

```
app/
├── Services/
│   └── AuroraSyncClient.php   # HTTP client para todos los endpoints del daemon
└── Livewire/
    ├── TablesSelector.php     # Lista de tablas + checkboxes + discovery
    ├── SyncProgress.php       # Progreso en tiempo real (polling 2s)
    ├── JobHistory.php         # Historial de jobs con detalle expandible
    ├── AppSettings.php        # Configuración + test connections
    └── TwingateStatus.php     # Badge en header (polling 30s)

resources/views/
├── layouts/app.blade.php      # Layout principal con tabs (Alpine.js x-show)
├── livewire/                  # Vistas de cada componente
└── components/
    ├── status-badge.blade.php # Badge coloreado por status
    └── form-field.blade.php   # Input reutilizable con label

config/aurora.php              # AURORA_API_URL config key
```

## Variables de entorno relevantes

```
AURORA_API_URL=http://192.168.1.113:8090
APP_PORT=8091
```

## Blade directive custom

`@bytes($n)` → formatea bytes a KB/MB/GB/TB. Registrado en `AppServiceProvider`.

## Livewire polling

- `SyncProgress`: `#[Polling(2000)]` — cada 2 segundos cuando hay job activo
- `TwingateStatus`: `#[Polling(30000)]` — cada 30 segundos

## Daemon (backend)

Repo: `aurora-sync` / `lineru-importer` en GitHub.
API base: `http://192.168.1.113:8090/api/`
