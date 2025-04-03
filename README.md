# Star Wars Explorer

A modern full-stack application that consumes the Star Wars API (SWAPI), built with Laravel backend and React frontend. The application is developed following Clean Architecture, Domain-Driven Design (DDD), Design Patterns, and SOLID principles.

## Overview

Star Wars Explorer allows users to search and view information about Star Wars characters and films, using the [Star Wars API (SWAPI)](https://swapi.dev/) as a data source. It also includes a statistics system that tracks and analyzes search queries, providing insights into the most popular searches.

## Architecture

The application follows a strict implementation of Clean Architecture with well-defined layers across both backend and frontend components, ensuring:

- **Framework Independence**: The core business logic is framework-agnostic, allowing portability across different platforms
- **High Testability**: All layers can be tested in isolation
- **Maintainability**: Changes in one layer don't affect others
- **Low Coupling**: Dependencies point inward, with inner layers having no knowledge of outer layers

### Layer Structure

1. **Domain Layer**
   - Contains pure business logic with entities (Person, Film, TopQueriesStatistic)
   - Defines value objects and domain events
   - No framework dependencies
   - Houses domain-specific validations

2. **Application Layer**
   - Implements use cases that orchestrate data flow and business rules
   - Orchestrates domain objects
   - Defines repository and service interfaces
   - Has no knowledge of HTTP, databases, or frameworks
   - Contains event listeners for statistics tracking

3. **Infrastructure Layer**
   - Provides concrete implementations of repository interfaces
   - Handles external API connections (SWAPI)
   - Manages data persistence
   - Provides statistics data storage

4. **Presentation Layer**
   - Controllers, routes, and API endpoints
   - React components and UI logic
   - Statistics visualization

## Domain-Driven Design (DDD)

The project incorporates DDD principles:
- Rich domain entities with business logic encapsulation
- Value objects for immutable concepts
- Repository pattern for data access abstraction

## Design Patterns

The application utilizes several design patterns to ensure clean code and maintainability:

1. **Repository Pattern** - Abstracts data access logic through interfaces like `FilmRepositoryInterface`
2. **Factory Method Pattern** - Static creation methods like `Film::fromArray()` to construct domain objects
3. **DTO Pattern** - Input/Output DTOs in use cases for data transfer between layers
4. **Adapter Pattern** - API clients adapting external services to application interfaces
5. **Dependency Injection** - Constructor injection throughout the application
7. **Caching Strategy Pattern** - Systematic caching approach in repositories to improve performance

## SOLID Principles Implementation

1. **Single Responsibility Principle** - Classes have clear responsibilities (e.g., repositories, entities, controllers)
2. **Open/Closed Principle** - Extension through interfaces and implementations
3. **Liskov Substitution Principle** - Implementations adhere to interface contracts
4. **Interface Segregation** - Focused interfaces with specific methods
5. **Dependency Inversion** - High-level modules depend on abstractions, not concrete implementations

## API Features

### Access Points

- **UI Application**: http://localhost
- **API**: http://localhost/api/v1

### Films
- `GET /api/v1/films`: List all films
- `GET /api/v1/films?q={term}`: Search films by term
- `GET /api/v1/films/{id}`: Get details of a specific film

### People
- `GET /api/v1/people`: List all characters
- `GET /api/v1/people?q={term}`: Search characters by term
- `GET /api/v1/people/{id}`: Get details of a specific character

### Statistics
- `GET /api/v1/statistics/top-queries`: Returns the most frequent search queries


## Frontend Architecture

The React frontend follows similar clean architecture principles:

1. **Domain Layer** - Contains entity models and TypeScript types
2. **Application Layer** - Services and interfaces
3. **Infrastructure Layer** - HTTP clients and external service adapters
4. **Presentation Layer** - React components and UI logic

The frontend React application is embedded and rendered within a Laravel view, creating a seamless integration between the two technologies.


## Statistics System

The application includes a statistics system that tracks user search queries and provides analytics on the five most frequently performed searches.

### How It Works

1. **Query Recording**: Every time a search query is performed (film or character search), a `QueryPerformed` domain event is dispatched.

2. **Event Processing**: The `RecordQueryStatistics` listener captures this event and records the query in the `query_logs` table.

3. **Statistics Generation**: A scheduled command (`UpdateStatisticsCommand`) runs every 5 minutes to process query logs and generate statistics on the most frequent searches.

4. **Data Storage**: The statistics are stored in the `top_queries_statistics` table in JSON format.

5. **Data Access**: The statistics can be accessed through the `/api/v1/statistics/top-queries` endpoint and visualized in the frontend.

6. **Data Retention**: The system only keeps the 20 most recent records to maintain performance and relevance.

## Automated Tests

Unit tests have been implemented for the Domain layer only as a demonstration of the testing approach. These tests showcase how the clean architecture facilitates testability by isolating the core business logic from external dependencies. The Domain layer has achieved 100% test coverage, ensuring that all business rules and core entities are thoroughly verified.

## Testing Practices

- **TDD Approach** - Test structure suggests test-driven development
- **Unit Testing** - Domain-level unit tests for core business logic
- **Test Organization** - Tests structured to mirror the application architecture

## Additional Best Practices

1. **Immutability** - Objects designed to be immutable where possible
2. **Error Handling** - Comprehensive error handling with domain-specific exceptions
3. **Separation of Concerns** - Clear boundaries between application components
4. **Logging** - Detailed logging for API calls and performance metrics
5. **Performance Optimization** - Caching strategies for external API calls
6. **Type Safety** - Strong typing across both PHP and TypeScript codebases

## Getting Started

### Prerequisites

- Docker
- Docker Compose

### Running the Application

```bash
# Clone the repository
git clone https://github.com/fernandopioli/starwars-app.git
cd starwars-app

# Start the containers
docker compose up -d
```

The application automatically:
- Installs all dependencies (backend and frontend)
- Runs database migrations
- Builds front-end assets for development
- Sets up all configurations
- Runs the scheduler for statistics

### Running Tests

To run the automated test suite:

```bash
docker compose exec app php artisan test
```

## Docker Container Structure

- `starwars-app`: PHP/Laravel container
- `starwars-nginx`: Nginx web server container
- `starwars-node`: Node.js container for frontend development
- `starwars-queue`: Scheduler service for statistics generation

## Development Utilities

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

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.