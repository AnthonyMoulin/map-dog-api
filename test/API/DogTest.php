<?php

namespace MapDog\Test\API;

use MapDog\API\Dog;

/**
 * Class ManagerTest
 *
 * @category MapDog
 * @package Test
 * @subpackage API
 */
class DogTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Get Dog
     * 
     * @return ReflectionClass Dog
     */
    private function getDog()
    {
        return (new \ReflectionClass(Dog::class))->newInstanceArgs([]);
    }

    /**
     * Test methods
     */
    public function testMethods()
    {
        $dog = $this->getDog();
        $this->assertTrue(
            method_exists($dog, "get")
         && method_exists($dog, "put")
         && method_exists($dog, "post")
         && !method_exists($dog, "delete")
        );
    }

    /**
     * Test get throwable
     * 
     * @expectedException \InvalidArgumentException
     */
    public function testGetThrowable()
    {
        $this->getDog()->get();
    }

    /**
     * Test get throwable
     * 
     * @expectedException \InvalidArgumentException
     */
    public function testPostThrowable()
    {
        $this->getDog()->post();
    }

    /**
     * Test get throwable
     *
     * @expectedException \InvalidArgumentException
     */
    public function testPutThrowable()
    {
        $this->getDog()->put();
    }

}
