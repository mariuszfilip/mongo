<?php
/**
 * Date: 16.05.15
 * Time: 11:22
 * @author Mariusz Filipkowski
 */

//http://www.quora.com/How-do-I-perform-subqueries-in-MongoDB




    error_reporting(E_ALL);
    ini_set('display_errors', 1);
try{

$connection = new Mongo();
$db = $connection->selectDB( "nbd" );


$collection = new MongoCollection($db, 'people');

// search for documents where 5 < x < 20
$rangeQuery = array('_id' => new MongoId("554a861c9a0c7f8389357ce7"));

$cursor = $collection->find($rangeQuery);

    //db.people.find( { currenposition: { $nearSphare: { $geometry: { type:"Point",coordinates : [124,7] },$maxDistance: 1000} } } )
    var_dump($cursor->count());
foreach ($cursor as $doc) {

    $query = array(
        'loc' => array(
            '$near' => array(
                '$geometry' =>array(
                    'type' => 'Point',
                    'coordinates'=>array(floatval($doc['currentposition'][0]['longitude']),floatval($doc['currentposition'][0]['latitude'])),
                    '$maxDistance' => intval(100000000),
                    ))));

    print_r($query);

    //print($doc);


    $cursorResult = $collection->find($rangeQuery);
    var_dump($cursorResult);
}









}catch (Exception $e){
    var_dump($e->getMessage());
}
