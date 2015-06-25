<?php
/**
 * Date: 06.05.15
 * Time: 22:15
 * @author Mariusz Filipkowski
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
try{

    $connection = new Mongo();
    $db = $connection->selectDB( "nbd" );

    // przyklad http://docs.mongodb.org/manual/core/map-reduce/
    $map = new MongoCode("function() { emit(this.sex,this.height); }");
    $reduce = new MongoCode("function(k, vals) { ".
        "var sum = 0,count = 0;".
        "for (var i in vals) {".
        "sum += vals[i];".
        "count++".
        "}".
        "return sum/count; }");

    $female_only = $db->command(array(

                             "mapreduce" => "people",
                             "map" => $map,
                             "reduce" => $reduxce,
                             'verbose' => true,
                             "query" => array("sex" => "Female"),
                             "out" => "female_avg"));

    $sex = $db->command(array(

                               "mapreduce" => "people",
                               "map" => $map,
                               "reduce" => $reduce,
                               'verbose' => true,
                               "out" => "height_avg"));

    var_dump($sex);
    echo '<br/>----------------------<br/>';
    $users = $db->selectCollection($sex['result'])->find();
    foreach ($users as $user) {
        var_dump($user);
        echo "{$user['_id']} : {$user['value']} .<br/>";
    }

}
catch (Exception $e){
    var_dump($e->getMessage());
}