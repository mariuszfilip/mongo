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
    $map = new MongoCode("function() {
                      for (var idx = 0; idx < this.account.length; idx++) {
                           var key = 1;
                           var value = {
                                         count: 1,
                                         balance: this.account[idx].balance
                                       };
                           emit(key, value);
                       }


                       }");
    $reduce = new MongoCode("function(k, vals) { ".
        "var reducedVal = { count: 0 ,balance : 0};".
        "for (var i in vals) {".
            "reducedVal.balance += vals[i].balance;".
            "reducedVal.count++;".
        "}".
        "return reducedVal; }");

   /* $reduce_sum = new MongoCode("function(k, vals) { ".
        "var reducedVal =0;".
        "for (var i in vals) {".
             "reducedVal+= vals[i].balance;".
        "}".
        "return reducedVal; }");


    $finalize_reduce = new MongoCode("function(k, reducedVal) { ".
        "reducedVal.sum_all+=reducedVal.sum;".
        "return reducedVal; }");*/


    $sex = $db->command(array(

                               "mapreduce" => "people",
                               "map" => $map,
                               "reduce" => $reduce,
                               'verbose' => true,
                               'limit' => 1000,
                               //'finalize'=>  $finalize_reduce,
                               "out" => "account"));

    var_dump($sex);

    echo '<br/>----------------------<br/>';

    $users = $db->selectCollection($sex['result'])->find();
    foreach ($users as $user) {
        var_dump($user);
       // echo "{$user['_id']} : {$user['value']} .<br/>";
    }

}
catch (Exception $e){
    var_dump($e->getMessage());
}