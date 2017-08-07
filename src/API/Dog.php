<?php 

namespace MapDog\API;

use MapDog\Manager;

/**
 * Class Dog
 *
 * @category MapDog
 * @package API
 */
class Dog extends AbstractEndPoint
{

    /**
     * Get action
     * 
     * @return self
     * 
     * @throws \InvalidArgumentException for invalid name
     * @throws \OutOfRangeException for no found name
     * @throws Throwable for pdo exception
     */
    public function get(): self
    {
    	try {
    	    $name = filter_input(INPUT_GET, "name");
    	    if(!$name || strlen($name) >= 32 ) {
    	        throw new \InvalidArgumentException("Expect a valid name");
    	    }
    		$sth = Manager::getPdo()->prepare(
    		    "SELECT `name`,`avatar`,`lat`,`lng`,`time` FROM `dog` "
		      . "JOIN `map` ON `dog`.`id`=`map`.`id` "
	          . "WHERE `name` = :name"
    		);
    		$sth->bindValue(":name", $name);
    		$sth->execute();
    		$result = $sth->fetch(\PDO::FETCH_OBJ);
        	if (!$result) {
                throw new \OutOfRangeException("No dog for the name");
         	}
         	$this->json = $result;
         	$this->json->dogs = new \stdClass();
         	$sth = Manager::getPdo()->prepare(
         	    "SELECT `name`,`avatar`,`lat`,`lng`,`time` FROM `dog` "
         	    . "JOIN `map` ON `dog`.`id`=`map`.`id` "
         	    . "WHERE `lat`>:lat "
         	    . "AND `name`!=:name "
         	    . "UNION "
         	    . "SELECT `name`,`avatar`,`lat`,`lng`,`time` FROM `dog` "
         	    . "JOIN `map` ON `dog`.`id`=`map`.`id` "
         	    . "WHERE `lat`<:lat "
         	    . "AND `name`!=:name "
         	    . "UNION "
         	    . "SELECT `name`,`avatar`,`lat`,`lng`,`time` FROM `dog` "
         	    . "JOIN `map` ON `dog`.`id`=`map`.`id` "
         	    . "WHERE `lng`>:lng "
         	    . "AND `name`!=:name "
         	    . "UNION "
         	    . "SELECT `name`,`avatar`,`lat`,`lng`,`time` FROM `dog` "
         	    . "JOIN `map` ON `dog`.`id`=`map`.`id` "
         	    . "WHERE `lng`<:lng "
         	    . "AND `name`!=:name "
         	    . "ORDER BY ABS(`lat` - :lat), ABS(`lng` - :lng) LIMIT 5"
         	    );
    		$sth->bindValue(":lat", $result->lat);
    		$sth->bindValue(":lng", $result->lng);
    		$sth->bindValue(":name", $name);
    		$sth->execute();
    		$result = $sth->fetchAll(\PDO::FETCH_OBJ);
    		if ($result) {
    		    $this->json->dogs = $result;
    		}
         	return $this;
    	} catch (\Throwable $e) {
    		throw $e;
    	}
    }

    /**
     * Post
     * 
     * @return self
     * 
     * @throws \InvalidArgumentException for invalid name or lat or lng
     * @throws Throwable for pdo exception
     */
    public function put()
    {
        try {
            $input = [];
            parse_str(file_get_contents("php://input"), $input);
            $name = array_key_exists("name", $input) ? $input["name"] : null;
            $lng = array_key_exists("lng", $input) ? $input["lng"] : null;
            $lat = array_key_exists("lat", $input) ? $input["lat"] : null;
            if (!$name || !$lat || !$lng) {
                throw new \InvalidArgumentException("Expect name, lat and lng");
            }
            $sth = Manager::getPdo()->prepare(
                "UPDATE `map` JOIN `dog` ON `dog`.`id`=`map`.`id`"
              . "SET `lat`=:lat,`lng`=:lng,`time`=:time "
              . "WHERE `dog`.`name`=:name"
            );
            $sth->bindValue(":name", $name);
            $sth->bindValue(":lat", $lat);
            $sth->bindValue(":lng", $lng);
            $sth->bindValue(":time", time());
            $sth->execute();
            return $this;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * Put action
     *
     * @return self
     * 
     * @throws \InvalidArgumentException for invalid name or avatar
     * @throws Throwable for pdo exception
     */
    public function post(): self
    {
        try {
            $name = filter_input(
                INPUT_POST,
                "name",
                FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $avatar = filter_input(
                INPUT_POST,
                "avatar",
                FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	    if ($name >= 32 || !$name || !$avatar) {
    	        throw new \InvalidArgumentException(
    	            "Expect a valid name and avatar"
    	        );
    	    }
            Manager::getPdo()->beginTransaction();
            $sth = Manager::getPdo()->prepare(
                "INSERT INTO `dog`(`name`, `avatar`) "
              . "VALUES (:name, :avatar)"
            );
            $sth->bindValue(":name", $name);
            $sth->bindValue(":avatar", $avatar);
            $sth->execute();
            $id = Manager::getPdo()->lastInsertId();
            $sth = Manager::getPdo()->prepare(
                "INSERT INTO `map`(`id`, `lng`, `lat`, `time`) "
              . "VALUES (:id,:lng,:lat,:time)"
            );
            $sth->bindValue(":id", $id);
            $sth->bindValue(":lng", 0);
            $sth->bindValue(":lat", 0);
            $sth->bindValue(":time", time());
            $sth->execute();
            Manager::getPdo()->commit();
            return $this;
        } catch (\Throwable $e) {
            if (Manager::getPdo()->inTransaction()) {
                Manager::getPdo()->rollBack();
            }
            throw $e;
        }
    }

    /**
     * OPTIONS action
     *
     * @return self
     */
    public function options()
    {
        header("Access-Control-Allow-Methods: GET, POST, PUT");
        return $this;
    }

}
