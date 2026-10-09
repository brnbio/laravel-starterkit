# Development Context

## Tech Stack

**Backend**
- PHP 8.5
- Laravel 13
- Inertia.js 3
- Laravel Fortify 1
- Laravel Wayfinder
- Spatie Media Library 11
- Pest 5 (+ Type Coverage plugin)
- PHPStan level max via Larastan 3
- Pint
- Laravel Debugbar 4 (dev)

**Frontend**
- Vue 3 (Composition API) + TypeScript 6 (strict)
- Vite 8
- Tailwind CSS 4
- shadcn/vue (reka-ui) in `resources/js/components/ui`
- unplugin-vue-components: auto-imports components from `resources/js/components` + `resources/js/layouts` (no explicit imports needed)
- Material Symbols via unplugin-icons (auto-imported as `<IconMaterialSymbols... />`)
- ESLint 10

**Services** (Docker via Laravel Sail)
- MySQL 8.0
- MinIO (S3)
- Mailpit

## Development Workflow

The Docker/Sail setup (`compose.yml`, `Makefile`, `docker/`) lives in the project root; the Laravel app lives in `src/`.

- Run everything from the project root — never `cd src/`.
- Never run `php`, `composer`, `artisan` or `npm` directly on the host; everything runs inside the Sail container.
- Use the `make` targets. Only if there is no target, call Sail from the root (e.g. `sail artisan make:model Example -m`, `sail test --filter=UserTest`).
- Run `make precommit` before every commit.

**Make targets** (run `make` without target for the list):

| Target | Purpose |
| --- | --- |
| `make up` / `down` / `restart` | Container lifecycle |
| `make install` | Fresh install (env, composer, npm, key, migrate + seed) |
| `make dev` | Vite dev server |
| `make refresh` | `migrate:fresh --seed` |
| `make pint` | Format PHP (fixes in place) |
| `make phpstan` | Static analysis |
| `make eslint` | Lint frontend (fixes in place) |
| `make test` | Pest tests |
| `make precommit` | Pint, PHPStan, 100% type coverage, 100% code coverage, composer + npm audit, ESLint |

## Project Structure

```
src/
├── app/                     # PHP code (Models, Controllers, Requests, Providers)
├── resources/js/
│   ├── components/          # Vue components (ui/ = shadcn/vue, don't edit by hand)
│   ├── composables/         # Vue composables
│   ├── layouts/             # Inertia layouts
│   ├── pages/               # Inertia pages
│   ├── types/               # TypeScript types (wayfinder/ + components.d.ts are generated)
│   └── lib/                 # Utilities
├── routes/
├── database/                # Migrations, factories, seeders
├── tests/                   # Pest tests (Feature/, Unit/)
└── config/
```

## Code Guidelines

Formatting is enforced by tooling — `src/pint.json` and `src/eslint.config.js` are the source of truth. Run `make pint` / `make eslint` instead of formatting by hand.

### Backend (PHP)

- `declare(strict_types=1);` in every file (including tests)
- Complete type hints for parameters, return types and closures — 100% type coverage is required
- PHPStan level max must pass
- PHPDoc only for information the types can't express
- Use helper functions where one exists: `inertia()`, `auth()`, `view()`, `redirect()`, `response()`, `request()`, `route()`, `session()`, `config()`, `cache()`. Facades are only allowed when no helper exists (e.g. `RateLimiter`, `Password`, `Date`, `Route`).
- Inertia page names are lowercase paths (`users/index`, not `Users/Index`)

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Response;

final class UserController
{
    public function index(): Response
    {
        return inertia('users/index', [
            'user' => auth()->user(),
        ]);
    }

    public function store(): RedirectResponse
    {
        return redirect()->route('users.index');
    }
}
```

### Project Conventions & Gotchas

- **All classes are `final`.**
- **Dates are immutable** (`Date::use(CarbonImmutable::class)`): `$date->addDay()` returns a new instance — always use the return value.
- **Strict models** (`Model::shouldBeStrict()` in local env): lazy loading, missing attributes and silently discarded attributes throw. Relationships are eager loaded automatically (`Model::automaticallyEagerLoadRelationships()`), so explicit `with()` is usually unnecessary.
- **Validation lives in Form Requests** (`app/Http/Requests/...`), not inline in controllers.
- **Auth split**: Fortify handles login and two-factor auth (views registered in `FortifyServiceProvider`); registration and password reset live in `AuthController`. Don't reimplement either side.
- **Flash messages**: use `flash('...')` (laracasts/flash) in the backend; they reach the frontend as the shared `flash` prop from `HandleInertiaRequests`.
- **API resources are unwrapped** (`JsonResource::withoutWrapping()`): no `data` key.
- **TypeScript model types** in `resources/js/types/models` are maintained by hand — update them whenever a model's attributes or relations change.
- `Number` formatting uses locale `de` and currency `EUR`, while the app locale is `en`.

### Frontend (Vue/TypeScript)

- Always `<script setup lang="ts">`, block order `<script>` → `<template>` → `<style>`
- Typed props and emits via `defineProps<Props>()` / `defineEmits<...>()`
- Components from `components/` and `layouts/` and icons are auto-imported — don't import them explicitly (Inertia components like `Link`/`Head` still need an import)
- Path alias `@/*` → `resources/js/*`
- Use Wayfinder for URLs instead of hardcoded paths (`@/types/wayfinder/routes`, `@/types/wayfinder/actions`)

```vue
<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { User } from '@/types'
import { index } from '@/types/wayfinder/routes/users'

interface Props {
    users: User[]
}

defineProps<Props>()
</script>

<template>
    <AppLayout title="Users">
        <Link :href="index.url()">Users</Link>
    </AppLayout>
</template>
```

### Wayfinder

TypeScript routes and actions are generated by the Vite plugin (`make dev` / build) into `resources/js/types/wayfinder` with form variants enabled. Never edit these files by hand.

## Testing

- Pest tests for every PHP change; `make precommit` requires 100% code and type coverage
- Feature tests use `describe()` / `test()` with factories and `Pest\Laravel` functions
- Tests run against the MySQL `testing` database (created by `docker/create-testing-database.sh`, refreshed via `RefreshDatabase`) — deliberately not SQLite, so MySQL-specific behaviour and side effects surface in tests. The `db` container must be running.

```php
<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

describe('dashboard', function (): void {

    test('authenticated users can visit the dashboard', function (): void {
        actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page->component('dashboard'));
    });
});
```

## Environment

- Service hosts inside Docker: `DB_HOST=db`, `AWS_ENDPOINT=http://s3:9000`, `MAIL_HOST=mailpit`
- App on `APP_PORT` (default 80), Vite on `VITE_PORT` (default 5173) — defaults come from `compose.yml`
- Mailpit UI: http://localhost:8025 · MinIO console: http://localhost:8900
- Xdebug via `SAIL_XDEBUG_MODE` in the root `.env`; Laravel Debugbar is enabled in development
