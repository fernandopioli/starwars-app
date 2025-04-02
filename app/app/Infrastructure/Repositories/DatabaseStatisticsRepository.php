<?php

namespace App\Infrastructure\Repositories;

use App\Application\Interfaces\Repositories\StatisticsRepositoryInterface;
use App\Domain\Entities\TopQueriesStatistic;
use App\Domain\ValueObjects\QueryStatistic;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseStatisticsRepository implements StatisticsRepositoryInterface
{
    /**
     * Record a search query event
     */
    public function recordQuery(string $query, string $type): void
    {
        try {
            DB::beginTransaction();
            
            DB::table('query_logs')->insert([
                'query' => $query,
                'type' => $type,
                'created_at' => now()
            ]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao registrar consulta: ' . $e->getMessage());
        }
    }

    /**
     * Get the top queries statistics
     */
    public function getTopQueries(int $limit = 5): TopQueriesStatistic
    {
        try {
            $latestStats = DB::table('top_queries_statistics')
                ->orderBy('updated_at', 'desc')
                ->first();

            if (!$latestStats) {
                // Se não houver estatísticas, calcular na hora
                $this->updateTopQueriesStatistics($limit);
                $latestStats = DB::table('top_queries_statistics')
                    ->orderBy('updated_at', 'desc')
                    ->first();
            }

            $data = json_decode($latestStats->data, true);

            $queries = [];
            foreach ($data as $item) {
                $queries[] = new QueryStatistic(
                    $item['query'],
                    $item['count'],
                    $item['percentage']
                );
            }

            return new TopQueriesStatistic(
                $queries,
                new DateTime($latestStats->updated_at)
            );
        } catch (\Exception $e) {
            Log::error('Error getting top queries statistics: ' . $e->getMessage());
            // Retornar estatísticas vazias em caso de erro
            return new TopQueriesStatistic([], new DateTime());
        }
    }

    /**
     * Update top queries statistics
     */
    public function updateTopQueriesStatistics(int $limit = 5): void
    {
        // Número máximo de tentativas
        $maxAttempts = 3;
        $attempt = 0;
        $success = false;

        while (!$success && $attempt < $maxAttempts) {
            try {
                $attempt++;
                Log::info('Iniciando atualização de estatísticas (tentativa ' . $attempt . ')');
                
                // Obter o total de consultas
                $totalQueries = DB::table('query_logs')->count();
                Log::info('Total de consultas encontradas: ' . $totalQueries);
                
                if ($totalQueries === 0) {
                    Log::info('Nenhuma consulta encontrada, inserindo estatísticas vazias');
                    // Se não houver consultas, inserir estatísticas vazias
                    DB::table('top_queries_statistics')->insert([
                        'data' => json_encode([]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $success = true;
                    return;
                }
                
                // Obter as consultas mais populares
                $topQueries = DB::table('query_logs')
                    ->select('query', DB::raw('COUNT(*) as count'))
                    ->groupBy('query')
                    ->orderBy('count', 'desc')
                    ->limit($limit)
                    ->get();
                
                Log::info('Consultas top encontradas: ' . $topQueries->count());
                
                $formattedQueries = [];
                
                foreach ($topQueries as $query) {
                    $percentage = ($query->count / $totalQueries) * 100;
                    
                    $formattedQueries[] = [
                        'query' => $query->query,
                        'count' => $query->count,
                        'percentage' => round($percentage, 2)
                    ];
                    
                    Log::info('Consulta formatada: ' . $query->query . ' (Count: ' . $query->count . ', %: ' . round($percentage, 2) . ')');
                }
                
                // Armazenar as estatísticas
                Log::info('Inserindo estatísticas na tabela top_queries_statistics');
                
                // Verificar se a tabela já tem muitas entradas e limitar
                $statsCount = DB::table('top_queries_statistics')->count();
                if ($statsCount > 50) {
                    Log::info('Removendo estatísticas antigas (mantendo apenas as 20 mais recentes)');
                    // Manter apenas as 20 mais recentes
                    $oldStatsIds = DB::table('top_queries_statistics')
                        ->orderBy('updated_at', 'asc')
                        ->limit($statsCount - 20)
                        ->pluck('id');
                    
                    DB::table('top_queries_statistics')
                        ->whereIn('id', $oldStatsIds)
                        ->delete();
                }
                
                DB::table('top_queries_statistics')->insert([
                    'data' => json_encode($formattedQueries),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                Log::info('Estatísticas de consultas atualizadas com sucesso', [
                    'total_queries' => $totalQueries,
                    'top_queries_count' => count($formattedQueries)
                ]);
                
                $success = true;
            } catch (\Exception $e) {
                if (stripos($e->getMessage(), 'database is locked') !== false && $attempt < $maxAttempts) {
                    // Esperar um tempo antes de tentar novamente (backoff exponencial)
                    $sleepSeconds = pow(2, $attempt);
                    Log::warning("Banco de dados bloqueado, tentando novamente em {$sleepSeconds} segundos (tentativa {$attempt}/{$maxAttempts})");
                    sleep($sleepSeconds);
                } else {
                    Log::error('Erro ao atualizar estatísticas de consultas: ' . $e->getMessage(), [
                        'attempt' => $attempt,
                        'exception' => $e->getTraceAsString()
                    ]);
                    
                    // Se for o último erro, lançar exceção
                    if ($attempt >= $maxAttempts) {
                        throw $e;
                    }
                }
            }
        }
    }
} 