<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Star Wars API - Laravel com React

Aplicação Laravel com React para explorar a API Star Wars (SWAPI)

## Configuração do Projeto

### Requisitos
- Docker e Docker Compose

### Executando com Docker
```
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan migrate
docker-compose exec app npm install
docker-compose exec app npm run build
```

### Permissões
```
docker-compose exec app chmod -R 755 /app/storage
docker-compose exec app chmod -R 755 /app/bootstrap/cache
```

## Configuração de Cache
A aplicação utiliza cache para melhorar a performance e reduzir as chamadas para a API externa.

### Configuração do Cache
1. Execute as migrações para criar a tabela de cache:
```
docker-compose exec app php artisan migrate
```

2. Certifique-se que as variáveis de ambiente no arquivo `.env` estão configuradas:
```
CACHE_DRIVER=database
```

### Gerenciamento de Cache
A aplicação oferece as seguintes rotas para gerenciar o cache:

1. **Limpar todo o cache**:
```
GET /api/clear-cache
```

2. **Limpar cache específico de pessoa**:
```
GET /api/clear-cache/person/{id}
```

3. **Limpar cache específico de filme**:
```
GET /api/clear-cache/film/{id}
```

### Rotas de Depuração
Para auxiliar no desenvolvimento e depuração, as seguintes rotas estão disponíveis:

1. **Verificar estado do cache de uma pessoa**:
```
GET /api/debug/person/{id}
```

2. **Verificar estado do cache de um filme**:
```
GET /api/debug/film/{id}
```

## Estrutura do Projeto
O projeto segue os princípios de Clean Architecture:

- **Domain**: Entidades e regras de negócio
- **Application**: Casos de uso e interfaces
- **Infrastructure**: Implementações de interfaces e acesso a dados
- **Presentation**: Controllers e UI (React)

## API Endpoints

### Filmes
- `GET /api/v1/films` - Lista todos os filmes
- `GET /api/v1/films/{id}` - Obtém detalhes de um filme específico

### Pessoas
- `GET /api/v1/people` - Lista todas as pessoas
- `GET /api/v1/people/{id}` - Obtém detalhes de uma pessoa específica

## Funcionalidades

- **Caching**: Implementação de cache em múltiplos níveis para melhorar a performance
- **Entity References**: Sistema de referências entre entidades para gerenciar relacionamentos
- **Clean Architecture**: Separação clara de responsabilidades com domínio agnóstico ao framework
- **Testes Automatizados**: Testes unitários e de integração

### Executando os Testes
```
docker-compose exec app php artisan test
```

## Depuração e Desenvolvimento

### Logs
Os logs da aplicação incluem informações detalhadas sobre o cache, requisições à API e enriquecimento de dados. Configure o arquivo `.env` para ajustar o nível de log:

```
LOG_LEVEL=debug
```

### Limpar Cache do Laravel
Para limpar os caches de configuração e views do Laravel:
```
docker-compose exec app php artisan optimize:clear
```

### Servidor de Desenvolvimento
```
docker-compose exec app php artisan serve --host=0.0.0.0 --port=8000
```

### Frontend
```
docker-compose exec app npm run dev
```

---
steps

docker-compose exec app chmod -R 755 /app/storage
docker-compose exec app chmod -R 755 /app/bootstrap/cache

docker compose exec app bash -c "cd /app/app && php artisan test --filter=PersonTest"
php artisan test --coverage-html coverage


php artisan optimize:clear
php artisan serve --host=0.0.0.0 --port=8000


npm run build
