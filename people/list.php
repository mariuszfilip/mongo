<?php
/**
 * Date: 06.05.15
 * Time: 21:54
 * @author Mariusz Filipkowski
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
try{


    $connection = new Mongo();
    $db = $connection->selectDB( "nbd" );


    $collection = $db->selectCollection( "people" );


    $result = $collection->findOne();

    var_dump($result);


}catch (Exception $e){
    var_dump($e->getMessage());

}
