<?php
/**
 * Date: 11.05.15
 * Time: 21:12
 * @author Mariusz Filipkowski
 */



error_reporting(E_ALL);
ini_set('display_errors', 1);
try{

    $connection = new Mongo();
    $db = $connection->selectDB( "nbd" );

    //
    $map = new MongoCode("function() {
                      for (var idx = 0; idx < this.account.length; idx++) {
                           var key = this.account[idx].cardtype;
                           var value = 1;
                           emit(key, value);
                       }


                       }");
    $reduce = new MongoCode("function(k, vals) { ".
        "var reducedVal = { count: 0};".
        "for (var i in vals) {".
            "reducedVal.count++;".
        "}".
        "return reducedVal; }");




    $sex = $db->command(array(

                             "mapreduce" => "people",
                             "map" => $map,
                             "reduce" => $reduce,
                             'verbose' => true,
                             'limit' => 1000,
                             "out" => "account"));

    var_dump($sex);

    echo '<br/>----------------------<br/>';

    $users = $db->selectCollection($sex['result'])->find();
    $i = 0;
    foreach ($users as $user) {
        var_dump($user);
        $i++;
        // echo "{$user['_id']} : {$user['value']} .<br/>";
    }
    echo 'count';
    var_dump($i);

}
catch (Exception $e){
    var_dump($e->getMessage());
}
