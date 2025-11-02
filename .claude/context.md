# Development Context

## Tech Stack

**Backend**
- PHP 8.4
- Laravel v12
- Inertia.js v2
- Laravel Fortify
- Spatie Media Library
- Pest v4
- PHPStan (max level)
- Pint with Larastan

**Frontend**
- Vue 3 (Composition API)
- TypeScript (strict)
- Vite
- Tailwind CSS v4
- shadcn/vue
- Material Symbols & Icons (as font) 
- Ziggy
- ESLint

**Services**
- MySQL 8.0
- MinIO (S3)
- Mailpit

## Development Workflow

### 🚨 Core Rules

1. **Execute all commands from project root** - NEVER `cd src/`
2. **Use Laravel Sail for all commands** - No direct host commands
3. **Use Makefile** - `make test` instead of `./vendor/bin/sail test`
4. **Check code quality** - `make precommit` before every commit

### Make Commands

**Container**:
```bash
make up / down / restart
```

**Development**:
```bash
make dev         # Vite Dev Server
make refresh     # DB reset with Seeds
```

**Code Quality**:
```bash
make pint        # PHP Code Formatter
make phpstan     # Static Analysis
make eslint      # Frontend Linter
make test        # Pest Tests
make precommit   # All Checks
```

## Project Structure

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

**Rules**:
- `declare(strict_types=1);` in every file
- Short Array Syntax `[]`
- Single Quotes `'string'`
- Complete Type Hints (Parameters + Return Types)
- PHPDoc only for additional information
- **Helper functions over Facades**: `inertia()`, `auth()`, `view()`, `redirect()`, `response()`, `request()`, `route()`, `session()`, `config()`, `cache()`
- Inertia Page Names lowercase (e.g. `users/index` instead of `Users/Index`)

**Example**:
```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

final class UserController
{
    public function index()
    {
        // ✅ Use helpers
        return inertia('users/index', [
            'user' => auth()->user(),
            'config' => config('app.name'),
        ]);
    }

    public function store()
    {
        // ✅ Not: Auth::user(), Config::get(), Redirect::route()
        return redirect()->route('users.index');
    }
}
```

### Frontend (Vue/TypeScript)

**Standards**: TypeScript strict • Vue 3 Composition API • ESLint

**ESLint Rules**:
- Block Order: `<script>` → `<template>` → `<style>`
- Always `<script setup lang="ts">`
- 4 Spaces Indentation
- Object Spacing: `{ foo: 'bar' }`
- Max 8 attributes inline, otherwise 1 per line
- Empty line between blocks

**Path Aliases**:
- `@/*` → `./resources/js/*`
- `ziggy-js` → Vendor

**Component Structure**:
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

**Commands**:
```bash
make test                                      # All Tests
./vendor/bin/sail test --filter=UserTest      # Specific Test
```

## Common Artisan Commands

```bash
./vendor/bin/sail artisan make:model Example -m       # Model + Migration
./vendor/bin/sail artisan make:controller UserController
./vendor/bin/sail artisan migrate
make refresh                                           # DB reset + seed
```

## Environment

**Important .env Variables**:
```bash
APP_PORT=80
VITE_PORT=5173
DB_HOST=db                  # Docker Service Name
AWS_ENDPOINT=http://s3:9000 # MinIO
MAIL_HOST=mailpit           # Mailpit (localhost:8025)
```

**Debugging**:
- Laravel Debugbar enabled (Development)
- Xdebug via `SAIL_XDEBUG_MODE` in `.env`

## Claude Code Notes

**Workflow**:
- Use Makefile commands (`make test`, `make precommit`)
- Execute all commands from project root (NEVER `cd src/`)
- `make precommit` before every commit

**Code Generation**:
- PHP: `declare(strict_types=1);`, complete Type Hints, PHPStan Level max, Helper functions instead of Facades
- Vue: `<script setup lang="ts">`, typed Props/Emits
- Follow Pint/ESLint rules strictly

**Testing**:
- Pest tests for all PHP changes
- 100% Type Coverage required
- `make test` before commits

**File Paths**:
- Backend: `src/app/...`
- Frontend: `src/resources/js/...`
