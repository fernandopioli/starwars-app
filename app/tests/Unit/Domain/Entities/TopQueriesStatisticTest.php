<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\TopQueriesStatistic;
use App\Domain\ValueObjects\QueryStatistic;
use PHPUnit\Framework\TestCase;
use DateTime;

class TopQueriesStatisticTest extends TestCase
{
    public function testConstructor(): void
    {
        $queries = [
            new QueryStatistic('luke', 10, 40.0),
            new QueryStatistic('vader', 8, 32.0),
            new QueryStatistic('leia', 7, 28.0)
        ];
        
        $dateTime = new DateTime('2023-04-01 12:00:00');
        $topQueries = new TopQueriesStatistic($queries, $dateTime);
        
        $this->assertSame($queries, $topQueries->getQueries());
        $this->assertSame($dateTime, $topQueries->getUpdatedAt());
    }
    
    public function testGetQueries(): void
    {
        $queries = [
            new QueryStatistic('luke', 10, 40.0),
            new QueryStatistic('vader', 8, 32.0)
        ];
        
        $topQueries = new TopQueriesStatistic($queries, new DateTime());
        
        $this->assertCount(2, $topQueries->getQueries());
        $this->assertContainsOnlyInstancesOf(QueryStatistic::class, $topQueries->getQueries());
        $this->assertSame($queries[0], $topQueries->getQueries()[0]);
        $this->assertSame($queries[1], $topQueries->getQueries()[1]);
    }
    
    public function testGetUpdatedAt(): void
    {
        $dateTime = new DateTime('2023-04-01 12:00:00');
        $topQueries = new TopQueriesStatistic([], $dateTime);
        
        $this->assertSame($dateTime, $topQueries->getUpdatedAt());
    }
    
    public function testToArray(): void
    {
        $queries = [
            new QueryStatistic('luke', 10, 40.0),
            new QueryStatistic('vader', 8, 32.0)
        ];
        
        $dateTime = new DateTime('2023-04-01 12:00:00');
        $topQueries = new TopQueriesStatistic($queries, $dateTime);
        
        $result = $topQueries->toArray();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('queries', $result);
        $this->assertArrayHasKey('updated_at', $result);
        
        $this->assertCount(2, $result['queries']);
        $this->assertEquals('luke', $result['queries'][0]['query']);
        $this->assertEquals(10, $result['queries'][0]['count']);
        $this->assertEquals(40.0, $result['queries'][0]['percentage']);
        
        $this->assertEquals('vader', $result['queries'][1]['query']);
        $this->assertEquals(8, $result['queries'][1]['count']);
        $this->assertEquals(32.0, $result['queries'][1]['percentage']);
        
        $this->assertEquals('2023-04-01 12:00:00', $result['updated_at']);
    }
    
    public function testToArrayWithEmptyQueries(): void
    {
        $dateTime = new DateTime('2023-04-01 12:00:00');
        $topQueries = new TopQueriesStatistic([], $dateTime);
        
        $result = $topQueries->toArray();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('queries', $result);
        $this->assertEmpty($result['queries']);
    }
    
    public function testWithMultipleQueries(): void
    {
        $queries = [
            new QueryStatistic('luke', 20, 33.33),
            new QueryStatistic('vader', 15, 25.0),
            new QueryStatistic('leia', 10, 16.67),
            new QueryStatistic('han', 9, 15.0),
            new QueryStatistic('yoda', 6, 10.0)
        ];
        
        $dateTime = new DateTime('2023-04-01 12:00:00');
        $topQueries = new TopQueriesStatistic($queries, $dateTime);
        
        $this->assertCount(5, $topQueries->getQueries());
        
        $result = $topQueries->toArray();
        $this->assertCount(5, $result['queries']);
        
        // Check first and last query
        $this->assertEquals('luke', $result['queries'][0]['query']);
        $this->assertEquals(20, $result['queries'][0]['count']);
        
        $this->assertEquals('yoda', $result['queries'][4]['query']);
        $this->assertEquals(6, $result['queries'][4]['count']);
    }
} 