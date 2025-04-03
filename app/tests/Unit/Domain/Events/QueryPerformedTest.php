<?php

namespace Tests\Unit\Domain\Events;

use App\Domain\Events\QueryPerformed;
use PHPUnit\Framework\TestCase;

class QueryPerformedTest extends TestCase
{
    public function testConstructor(): void
    {
        $query = 'luke';
        $type = 'person';
        
        $event = new QueryPerformed($query, $type);
        
        $this->assertSame($query, $event->query);
        $this->assertSame($type, $event->type);
    }
    
    public function testWithEmptyQuery(): void
    {
        $query = '';
        $type = 'person';
        
        $event = new QueryPerformed($query, $type);
        
        $this->assertSame('', $event->query);
        $this->assertSame($type, $event->type);
    }
    
    public function testWithEmptyType(): void
    {
        $query = 'vader';
        $type = '';
        
        $event = new QueryPerformed($query, $type);
        
        $this->assertSame($query, $event->query);
        $this->assertSame('', $event->type);
    }
    
    public function testWithDifferentTypes(): void
    {
        $queries = ['luke', 'leia', 'han'];
        $types = ['person', 'film', 'unknown'];
        
        foreach ($queries as $index => $query) {
            $type = $types[$index];
            $event = new QueryPerformed($query, $type);
            
            $this->assertSame($query, $event->query);
            $this->assertSame($type, $event->type);
        }
    }
} 