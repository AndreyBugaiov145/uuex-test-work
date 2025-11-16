# Test work for ueex, author Buhaiov

## Requirements

- Docker + Docker Compose
- Git
- Composer (optional, used by Sail internally)

## Getting Started


### 1. Copy .env and Set App Key

```bash
cp .env.example .env
```

### 2. Start the Environment via Sail

```bash
composer install
```

### 4. Start docker

```bash
./vendor/bin/sail up -d
```

### 4. Generate Application Key

```bash
./vendor/bin/sail artisan key:generate
```

### 5. Run Migrations

```bash
./vendor/bin/sail artisan migrate
```

### 6. Access the App

Visit http://localhost

### 7. Stop the App

```bash
./vendor/bin/sail stop
```

## PHP code

```bash
./vendor/bin/pint
```

```bash
./vendor/bin/ sail artisan test --parallel
```
