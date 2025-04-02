#!/bin/bash
set -e

# Garantir permissões corretas no diretório de banco de dados
mkdir -p /app/app/database
chmod -R 777 /app/app/database

echo "Iniciando worker de queue e scheduler..."

# Iniciar o scheduler em background com um loop
(
  while true; do
    echo "[$(date)] Executando scheduler..."
    cd /app/app && php artisan schedule:run
    echo "[$(date)] Scheduler executado, aguardando 60 segundos..."
    sleep 60
  done
) &

# Executar o worker da fila em primeiro plano
echo "[$(date)] Iniciando worker da fila..."
cd /app/app && php artisan queue:work --tries=3 