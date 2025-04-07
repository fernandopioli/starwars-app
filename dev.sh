#!/bin/bash

case "$1" in
    up)
        echo "Starting development environment..."
        docker compose -f docker-compose.dev.yml up -d
        ;;
    down)
        echo "Stopping development environment..."
        docker compose -f docker-compose.dev.yml down
        ;;
    logs)
        if [ "$2" ]; then
            echo "Showing logs for $2..."
            docker compose -f docker-compose.dev.yml logs -f $2
        else
            echo "Showing all logs..."
            docker compose -f docker-compose.dev.yml logs -f
        fi
        ;;
    build)
        echo "Building development environment..."
        docker compose -f docker-compose.dev.yml build
        ;;
    restart)
        if [ "$2" ]; then
            echo "Restarting $2..."
            docker compose -f docker-compose.dev.yml restart $2
        else
            echo "Restarting development environment..."
            docker compose -f docker-compose.dev.yml restart
        fi
        ;;
    ps)
        echo "Listing containers..."
        docker compose -f docker-compose.dev.yml ps
        ;;
    artisan)
        shift
        echo "Running Artisan command: $@"
        docker compose -f docker-compose.dev.yml exec app php artisan $@
        ;;
    bash)
        if [ "$2" ]; then
            echo "Opening bash shell in $2 container..."
            docker compose -f docker-compose.dev.yml exec $2 bash
        else
            echo "Opening bash shell in app container..."
            docker compose -f docker-compose.dev.yml exec app bash
        fi
        ;;
    *)
        echo "Usage: $0 {up|down|logs [service]|build|restart [service]|ps|artisan [command]|bash [service]}"
        exit 1
esac

exit 0