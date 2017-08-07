<?php 

namespace MapDog\API;

/**
 * Class AbstractEndPoint
 * 
 * @category MapDog
 * @package API
 * @abstract
 */
abstract class AbstractEndPoint
{
    
    protected
        /**
         * @var stdClass json object
         */
        $json;

    /**
     * Construct AbstractEndPoint
     * 
     * @return null
     */
    public function __construct()
    {
        $this->json = new \stdClass();
    }

    /**
     * Call
     * 
     * @param string $name
     * @param array $args
     * @return null
     * 
     * @throws \BadMethodCallException
     */
    public final function __call($name, $args)
    {
       throw new \BadMethodCallException(
           "Method " . static::class . "::" . $name . " do not exists"
       );
    }

    /**
     * Render
     * 
     * @return string json encoded object
     */
    public final function render(): string
    {
        return json_encode($this->json, JSON_PRETTY_PRINT);
    }

}
