<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\TopQueriesStatistic;
use App\Domain\ValueObjects\QueryStatistic;
use App\Application\Interfaces\Repositories\StatisticsRepositoryInterface;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DateTime;

class DatabaseStatisticsRepository implements StatisticsRepositoryInterface
{

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
            Log::error('Error recording query: ' . $e->getMessage());
        }
    }

    public function getTopQueries(int $limit = 5): TopQueriesStatistic
    {
        try {
            $latestStats = DB::table('top_queries_statistics')
                ->orderBy('updated_at', 'desc')
                ->first();

            if (!$latestStats) {
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
            return new TopQueriesStatistic([], new DateTime());
        }
    }

    public function updateTopQueriesStatistics(int $limit = 5): void
    {
        try {
            Log::info('Starting statistics update');
            
            $totalQueries = DB::table('query_logs')->count();
            Log::info('Total queries found: ' . $totalQueries);
            
            if ($totalQueries === 0) {
                Log::info('No queries found, inserting empty statistics');
                DB::table('top_queries_statistics')->insert([
                    'data' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                return;
            }
            
            $topQueries = DB::table('query_logs')
                ->select('query', DB::raw('COUNT(*) as count'))
                ->groupBy('query')
                ->orderBy('count', 'desc')
                ->limit($limit)
                ->get();
            
            Log::info('Top queries found: ' . $topQueries->count());
            
            $formattedQueries = [];
            
            foreach ($topQueries as $query) {
                $percentage = ($query->count / $totalQueries) * 100;
                
                $formattedQueries[] = [
                    'query' => $query->query,
                    'count' => $query->count,
                    'percentage' => round($percentage, 2)
                ];
                
                Log::info('Formatted query: ' . $query->query . ' (Count: ' . $query->count . ', %: ' . round($percentage, 2) . ')');
            }
            
            Log::info('Inserting statistics into top_queries_statistics table');
            
            $statsCount = DB::table('top_queries_statistics')->count();
            if ($statsCount > 20) {
                Log::info('Removing old statistics (keeping only the 20 most recent)');
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
            
            Log::info('Query statistics updated successfully', [
                'total_queries' => $totalQueries,
                'top_queries_count' => count($formattedQueries)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating query statistics: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 