<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\QueryStatistic;
use PHPUnit\Framework\TestCase;

class QueryStatisticTest extends TestCase
{
    public function testConstructor(): void
    {
        $queryStatistic = new QueryStatistic('luke', 10, 25.5);
        
        $this->assertEquals('luke', $queryStatistic->getQuery());
        $this->assertEquals(10, $queryStatistic->getCount());
        $this->assertEquals(25.5, $queryStatistic->getPercentage());
    }
    
    public function testGetQuery(): void
    {
        $queryStatistic = new QueryStatistic('darth vader', 5, 12.3);
        
        $this->assertEquals('darth vader', $queryStatistic->getQuery());
    }
    
    public function testGetCount(): void
    {
        $queryStatistic = new QueryStatistic('yoda', 15, 30.0);
        
        $this->assertEquals(15, $queryStatistic->getCount());
    }
    
    public function testGetPercentage(): void
    {
        $queryStatistic = new QueryStatistic('r2d2', 8, 18.75);
        
        $this->assertEquals(18.75, $queryStatistic->getPercentage());
    }
    
    public function testToArray(): void
    {
        $queryStatistic = new QueryStatistic('leia', 12, 22.5);
        $result = $queryStatistic->toArray();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('query', $result);
        $this->assertArrayHasKey('count', $result);
        $this->assertArrayHasKey('percentage', $result);
        $this->assertEquals('leia', $result['query']);
        $this->assertEquals(12, $result['count']);
        $this->assertEquals(22.5, $result['percentage']);
    }
    
    public function testWithZeroValues(): void
    {
        $queryStatistic = new QueryStatistic('no results', 0, 0.0);
        
        $this->assertEquals('no results', $queryStatistic->getQuery());
        $this->assertEquals(0, $queryStatistic->getCount());
        $this->assertEquals(0.0, $queryStatistic->getPercentage());
        
        $result = $queryStatistic->toArray();
        $this->assertEquals(0, $result['count']);
        $this->assertEquals(0.0, $result['percentage']);
    }
    
    public function testWithHighPrecisionPercentage(): void
    {
        $queryStatistic = new QueryStatistic('rare query', 1, 0.0123456789);
        
        $this->assertEquals(0.0123456789, $queryStatistic->getPercentage());
        
        $result = $queryStatistic->toArray();
        $this->assertEquals(0.0123456789, $result['percentage']);
    }
} 