# Star Wars Explorer - Laravel with React

 A modern application that consumes the Star Wars API (SWAPI) using Laravel for the backend and React for the frontend, developed following Clean Architecture, Domain-Driven Design (DDD), Design Patterns, SOLID principles, etc.

## Overview

This application allows users to search and view information about Star Wars characters and films, using the [Star Wars API (SWAPI)](https://swapi.dev/) as a data source. 

## Clean Architecture Implementation

This project strictly follows Clean Architecture principles to ensure:

- **Framework Independence**: The core business logic is framework-agnostic, allowing portability across different platforms
- **High Testability**: All layers can be tested in isolation
- **Maintainability**: Changes in one layer don't affect others
- **Low Coupling**: Dependencies point inward, with inner layers having no knowledge of outer layers

### Layer Structure

1. **Domain Layer**: Contains business entities (Person, Film)
2. **Application Layer**: Implements use cases that orchestrate data flow and business rules
3. **Infrastructure Layer**: Provides concrete implementations of repositories and external services
4. **Presentation Layer**: Laravel controllers and React frontend components

## Getting Started

### Prerequisites

- Docker
- Docker Compose

### Running the Application

```bash
# Clone the repository
git clone https://github.com/your-username/starwars-api.git
cd starwars-api

# Start the containers
docker compose up -d
```

The application automatically:
- Installs all dependencies
- Runs migrations
- Builds front-end assets for development

### Access Points

- Application: http://localhost
- API: http://localhost/api/v1

### Running Tests

To run the automated test suite:

```bash
docker compose exec app php artisan test
```

## Development Notes

This application is designed with minimal external dependencies, making it highly maintainable and adaptable. The clean architecture approach ensures that business logic remains unaffected by changes in external frameworks or libraries.

The entire setup is development-ready with:
- Nginx web server for efficient request handling
- Pre-built frontend assets for optimal performance
- SQLite database that is automatically created and migrated

### Docker Container Structure

- `starwars-app`: PHP/Laravel container
- `starwars-nginx`: Nginx web server container
- `starwars-node`: Node.js container for frontend development

### Useful Commands

- Stop all containers:
```bash
docker compose down
```

- View logs:
```bash
docker compose logs -f
```

- Compile frontend assets in development mode:
```bash
docker compose exec node npm run dev
```

- Compile frontend assets for production:
```bash
docker compose exec node npm run build
```

## Architecture Details

### Domain Layer
- Contains pure business logic
- No framework dependencies
- Defines repository interfaces

### Application Layer
- Implements use cases
- Orchestrates domain objects
- No knowledge of HTTP, databases, or frameworks

### Infrastructure Layer
- Implements repository interfaces
- Handles external API connections
- Manages data persistence

### Presentation Layer
- Controllers and routes
- React components
- API response formatting

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details. 