<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\EntityReference;
use PHPUnit\Framework\TestCase;

class EntityReferenceTest extends TestCase
{
    public function testConstructor(): void
    {
        $entityRef = new EntityReference('1', 'Test Name');
        
        $this->assertEquals('1', $entityRef->id);
        $this->assertEquals('Test Name', $entityRef->name);
    }
    
    public function testConstructorWithoutName(): void
    {
        $entityRef = new EntityReference('1');
        
        $this->assertEquals('1', $entityRef->id);
        $this->assertNull($entityRef->name);
    }
    
    public function testToArray(): void
    {
        $entityRef = new EntityReference('1', 'Test Name');
        $result = $entityRef->toArray();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertEquals('1', $result['id']);
        $this->assertEquals('Test Name', $result['name']);
    }
    
    public function testToArrayWithNullName(): void
    {
        $entityRef = new EntityReference('1');
        $result = $entityRef->toArray();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertEquals('1', $result['id']);
        $this->assertNull($result['name']);
    }
    
    public function testFromUrlWithValidUrl(): void
    {
        $url = 'https://swapi.dev/api/films/1/';
        $entityRef = EntityReference::fromUrl($url);
        
        $this->assertEquals('1', $entityRef->id);
        $this->assertNull($entityRef->name);
    }
    
    public function testFromUrlWithCustomPathFormat(): void
    {
        $url = 'https://example.com/resource/42';
        $entityRef = EntityReference::fromUrl($url);
        
        $this->assertEquals('42', $entityRef->id);
        $this->assertNull($entityRef->name);
    }
    
    public function testFromUrlWithInvalidUrl(): void
    {
        $url = 'https://example.com/resource/invalid';
        $entityRef = EntityReference::fromUrl($url);
        
        $this->assertEquals('0', $entityRef->id);
        $this->assertNull($entityRef->name);
    }
    
    public function testFromUrlWithEmptyUrl(): void
    {
        $url = '';
        $entityRef = EntityReference::fromUrl($url);
        
        $this->assertEquals('0', $entityRef->id);
        $this->assertNull($entityRef->name);
    }
} 