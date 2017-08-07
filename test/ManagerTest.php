<?php

namespace MapDog\Test;

use MapDog\Manager;

/**
 * Class ManagerTest
 *
 * @category MapDog
 * @package Test
 */
class ManagerTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Test constructor throwable
     * 
     * @expectedException \Error
     */
    public function testNotInstanciable()
    {
        new Manager();
    }

    /**
     * Test getInstance
     */
    public function testGetInstance()
    {
        $this->assertTrue(Manager::getInstance() === Manager::getInstance());
    }

    /**
     * Test getPdo
     */
    public function testGetPdo()
    {
        try {
            $this->assertTrue(Manager::getPdo() instanceof \PDO);
        } catch (\PDOException $e) {
            $this->assertTrue(2002 === $e->getCode());
        }
    }

}
