<?php

/**
 * This file contain MapDog\API\ facade
 */

require "./../vendor/autoload.php";

try {
    $controllerName = filter_input(INPUT_GET, "controller");
    $action = strtolower(filter_input (INPUT_SERVER, "REQUEST_METHOD"));
    if ("map" === $controllerName
     || "dog" === $controllerName) {
        $className = "MapDog\API\\" . ucfirst($controllerName);
        $output = (new $className())->{$action}()->render();
        header("HTTP/1.1 200 OK");
    } else {
        throw new OutOfRangeException("End point no found");
    }
} catch (Throwable $e) {
    if ($e instanceof OutOfRangeException) {
        header("HTTP/1.1 404 No Found");
    } else if ($e instanceof BadMethodCallException) {
        header("HTTP/1.1 405 Bad Method Call");
    } else if ($e instanceof InvalidArgumentException) {
        header("HTTP/1.1 412 Precondition failed");
    } else if ($e instanceof RuntimeException) {
        header("HTTP/1.1 409 Conflict");
    } else {
        header("HTTP/1.1 500 Internal Server Error");
    }
    $json = new stdClass();
    $json->error = $e->getMessage();
    $output = json_encode($json);
} finally {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Accept");
    header("Content-Type: application/json");
    die($output);
}
