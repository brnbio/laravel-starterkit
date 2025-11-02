# Entwicklungs-Kontext

## Tech Stack

**Backend**: PHP 8.4 • Laravel 12 • Inertia.js • Laravel Fortify • Spatie Media Library • Pest v4 • PHPStan (max level) • Pint

**Frontend**: Vue 3 (Composition API) • TypeScript (strict) • Vite • Tailwind CSS v4 • Reka UI • Lucide Icons • VueUse • Ziggy • ESLint

**Services**: MySQL 8.0 • MinIO (S3) • Mailpit

## Entwicklungs-Workflow

### 🚨 Grundregeln

1. **Alle Befehle vom Projekt-Root ausführen** - NIEMALS `cd src/`
2. **Laravel Sail für alle Befehle verwenden** - Keine direkten Host-Befehle
3. **Makefile nutzen** - `make test` statt `./vendor/bin/sail test`
4. **Code-Qualität prüfen** - `make precommit` vor jedem Commit

### Make-Befehle

**Container**:
```bash
make up / down / restart
```

**Development**:
```bash
make dev         # Vite Dev-Server
make refresh     # DB reset mit Seeds
```

**Code Quality**:
```bash
make pint        # PHP Code Formatter
make phpstan     # Static Analysis
make eslint      # Frontend Linter
make test        # Pest Tests
make precommit   # Alle Checks
```

## Projektstruktur

```
src/
├── app/                     # PHP Code (Models, Controllers, etc.)
├── resources/js/            # Frontend Code
│   ├── components/          # Vue Components
│   │   └── ui/              # UI Components
│   ├── composables/         # Vue Composables
│   ├── layouts/             # Inertia Layouts
│   ├── pages/               # Inertia Pages
│   ├── types/               # TypeScript Types
│   └── lib/                 # Utilities
├── routes/                  # Laravel Routes
├── database/                # Migrations, Seeders
├── tests/                   # Pest Tests
└── config/                  # Configuration
```

## Code Guidelines

### Backend (PHP)

**Standards**: Laravel PER • Strict Types • 100% Type Coverage • PHPStan Level max

**Regeln**:
- `declare(strict_types=1);` in jeder Datei
- Short Array Syntax `[]`
- Single Quotes `'string'`
- Vollständige Type Hints (Parameter + Return Types)
- PHPDoc nur bei zusätzlicher Information
- **Helper-Funktionen vor Facades**: `inertia()`, `auth()`, `view()`, `redirect()`, `response()`, `request()`, `route()`, `session()`, `config()`, `cache()`
- Inertia Page Names lowercase (z.B. `users/index` statt `Users/Index`)

**Beispiel**:
```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

final class UserController
{
    public function index()
    {
        // ✅ Helpers verwenden
        return inertia('users/index', [
            'user' => auth()->user(),
            'config' => config('app.name'),
        ]);
    }

    public function store()
    {
        // ✅ Nicht: Auth::user(), Config::get(), Redirect::route()
        return redirect()->route('users.index');
    }
}
```

### Frontend (Vue/TypeScript)

**Standards**: TypeScript strict • Vue 3 Composition API • ESLint

**ESLint-Regeln**:
- Block Order: `<script>` → `<template>` → `<style>`
- Immer `<script setup lang="ts">`
- 4 Spaces Indentation
- Object Spacing: `{ foo: 'bar' }`
- Max 8 Attribute inline, sonst 1 pro Zeile
- Leerzeile zwischen Blöcken

**Path Aliases**:
- `@/*` → `./resources/js/*`
- `ziggy-js` → Vendor

**Komponenten-Struktur**:
```vue
<script setup lang="ts">
import { ref, computed } from 'vue'
import type { User } from '@/types'

interface Props {
    user: User
    isActive?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    isActive: false
})

const count = ref<number>(0)

function handleClick(): void {
    count.value++
}
</script>

<template>
    <div class="example">
        <h1>{{ user.name }}</h1>
        <button @click="handleClick">Count: {{ count }}</button>
    </div>
</template>

<style scoped>
/* Styles */
</style>
```

**Composables**:
```typescript
import { ref, type Ref } from 'vue'

export function useExample() {
    const state: Ref<string> = ref('')

    function updateState(value: string): void {
        state.value = value
    }

    return { state, updateState }
}
```

**Types**:
```typescript
export interface User {
    id: number
    name: string
    email: string
}
```

## Inertia.js Workflow

**Controller → Vue**:
```php
public function index()
{
    return inertia('users/index', [
        'users' => User::all()
    ]);
}
```

**Vue Page** (`resources/js/pages/users/index.vue`):
```vue
<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import type { User } from '@/types'

interface Props {
    users: User[]
}

defineProps<Props>()
</script>

<template>
    <Head title="Users" />

    <Link href="/users">Users</Link>
</template>
```

## Testing

**Pest PHP**:
```php
<?php

use App\Models\User;

it('creates a user', function (): void {
    $user = User::factory()->create();
    expect($user)->toBeInstanceOf(User::class);
});
```

**Befehle**:
```bash
make test                                      # Alle Tests
./vendor/bin/sail test --filter=UserTest      # Spezifischer Test
```

## Häufige Artisan-Befehle

```bash
./vendor/bin/sail artisan make:model Example -m       # Model + Migration
./vendor/bin/sail artisan make:controller UserController
./vendor/bin/sail artisan migrate
make refresh                                           # DB reset + seed
```

## Environment

**Wichtige .env-Variablen**:
```bash
APP_PORT=80
VITE_PORT=5173
DB_HOST=db                  # Docker Service Name
AWS_ENDPOINT=http://s3:9000 # MinIO
MAIL_HOST=mailpit           # Mailpit (localhost:8025)
```

**Debugging**:
- Laravel Debugbar aktiviert (Development)
- Xdebug über `SAIL_XDEBUG_MODE` in `.env`

## Claude Code Hinweise

**Workflow**:
- Verwende Makefile-Befehle (`make test`, `make precommit`)
- Alle Befehle vom Projekt-Root ausführen (NIEMALS `cd src/`)
- `make precommit` vor jedem Commit

**Code-Generierung**:
- PHP: `declare(strict_types=1);`, vollständige Type Hints, PHPStan Level max, Helper-Funktionen statt Facades
- Vue: `<script setup lang="ts">`, typisierte Props/Emits
- Folge Pint/ESLint-Regeln strikt

**Testing**:
- Pest-Tests für alle PHP-Änderungen
- 100% Type Coverage erforderlich
- `make test` vor Commits

**Dateipfade**:
- Backend: `src/app/...`
- Frontend: `src/resources/js/...`
