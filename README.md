# Star Wars Explorer - Laravel with React

 A modern application that consumes the Star Wars API (SWAPI) using Laravel for the backend and React for the frontend, developed following Clean Architecture, Domain-Driven Design (DDD), Design Patterns, SOLID principles, etc.

## Overview

This application allows users to search and view information about Star Wars characters and films, using the [Star Wars API (SWAPI)](https://swapi.dev/) as a data source. It also includes a statistics system that tracks and analyzes search queries.

## Clean Architecture Implementation

This project strictly follows Clean Architecture principles to ensure:

- **Framework Independence**: The core business logic is framework-agnostic, allowing portability across different platforms
- **High Testability**: All layers can be tested in isolation
- **Maintainability**: Changes in one layer don't affect others
- **Low Coupling**: Dependencies point inward, with inner layers having no knowledge of outer layers

### Layer Structure

1. **Domain Layer**: Contains business entities (Person, Film, TopQueriesStatistic)
2. **Application Layer**: Implements use cases that orchestrate data flow and business rules
3. **Infrastructure Layer**: Provides concrete implementations of repositories and external services
4. **Presentation Layer**: Laravel controllers and React frontend components

## API Features

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

## Statistics System

The application includes a robust statistics system that tracks user search queries and provides analytics:

### How It Works

1. **Query Recording**: Every time a search query is performed (film or character search), a `QueryPerformed` event is dispatched.

2. **Event Processing**: The `RecordQueryStatistics` listener captures this event and records the query in the `query_logs` table.

3. **Statistics Generation**: A scheduled command (`UpdateStatisticsCommand`) runs every 5 minutes to process query logs and generate statistics on the most frequent searches.

4. **Data Storage**: The statistics are stored in the `top_queries_statistics` table in JSON format.

5. **Data Access**: The statistics can be accessed through the `/api/v1/statistics/top-queries` endpoint.

### System Components

- **Domain Entities and Value Objects**:
  - `TopQueriesStatistic`: Represents statistics of most frequent queries
  - `QueryStatistic`: Represents an individual query statistic

- **Events and Listeners**:
  - `QueryPerformed`: Event triggered when a search is performed
  - `RecordQueryStatistics`: Listener that processes the event and records the query

- **Repository**:
  - `StatisticsRepositoryInterface`: Interface for the statistics repository
  - `DatabaseStatisticsRepository`: Implementation that stores statistics in the database

- **Use Cases**:
  - `GetTopQueriesStatisticsUseCase`: Retrieves most frequent query statistics
  - `UpdateTopQueriesStatisticsUseCase`: Updates query statistics

- **Scheduling**:
  - The `UpdateStatisticsCommand` is scheduled to run every 5 minutes to keep statistics up to date

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
- Includes entities for films, people, and statistics

### Application Layer
- Implements use cases
- Orchestrates domain objects
- No knowledge of HTTP, databases, or frameworks
- Contains event listeners for statistics tracking

### Infrastructure Layer
- Implements repository interfaces
- Handles external API connections
- Manages data persistence
- Provides statistics data storage

### Presentation Layer
- Controllers and routes
- React components
- API response formatting
- Statistics visualization

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details. 