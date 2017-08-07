<?php

namespace MapDog;

use PDO;

/**
 * Class Manager
 *
 * @category MapDog
 */
class Manager
{

   private
       /**
        * @var Manager
        */
        static $instance;

   private
       /**
        * @var array
        */
       $config,
       /**
        * @var PDO
        */
       $dbh;
   
   /**
    * Construct Manager
    */
   private function __construct()
   {
       $this->config = json_decode(
           file_get_contents(__DIR__  . "/config.json")
       );
   }

   /**
    * Get instance
    *
    * @return Manager
    */
   public static function getInstance(): self
   {
     if(null === self::$instance) {
       self::$instance = new Manager();  
     }
     return self::$instance;
   }

   /**
    * Get PDO
    * 
    * @return PDO
    */
   public static function getPdo(): PDO
   {
       if (null === self::getInstance()->dbh) {
           self::$instance->dbh = new PDO(
               self::$instance->config->driver . "; dbname="
             . self::$instance->config->dbname . "; charset="
             . self::$instance->config->charset,
               self::$instance->config->username,
               self::$instance->config->password,
               [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
           );
       }
       return self::$instance->dbh;
   }

}
