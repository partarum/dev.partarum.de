<?php
error_reporting(-1);
ini_set("display_errors", "1");

session_start();

if (!class_exists('Partarum')) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
}

use Partarum\HTTP\API;
use Partarum\Security\Token\JWT;

$backend = new Partarum("getToken");

$api = new API();

$postKeyCheckALL = $api->fromPOST("all");

$postKeys = [
    "kvh" => $api->fromPOST("kvh"),
    "lsh" => $api->fromPOST("lsh"),
    "nsh" => $api->fromPOST("nsh"),
    "rhb" => $api->fromPOST("rhb"),
    "rhd" => $api->fromPOST("rhd")
];

$bearer = explode("Bearer ", $api->fromHeader("Authorization", API::REQUEST));

if($backend->isIntoCache("Bearer:" . $bearer[1])){

    $db = $backend->createDBConnection();

    $result = [];

    foreach ($postKeys as $key => $value) {

        if($postKeyCheckALL !== NULL){

            $jwt = new JWT();

            $jwt->setSecretKey($_ENV["JWT_SECRET_KEY"]);

            $jwt->setPayload($key);

            $result[$key] = $jwt->getToken();

        } else {
            if($value !== null) {

                $jwt = new JWT();

                $jwt->setSecretKey($_ENV["JWT_SECRET_KEY"]);

                $jwt->setPayload($key);

                $result[$key] = $jwt->getToken();

            }
        }
    }

// TODO: Token zur weiteren Verarbeitung noch entsprechend abspeichern
    /*
     * Was wird mit dem Token gemacht?
     *
     * Der Token wird genutzt um die Berechtigung zu setzen, auf der jeweiligen Seite sein zu dürfen
     * Der erste Seitenaufruf erfolgt über einen GET
     * Bei den darauffolgenden Seitenaufrufen befindet sich der Token nicht mehr im GET, sondern in der $_SESSION des USERS
     */

    $insertArray = [];

    $insertArrayResult = [];

    $updateArrayResult = [];

    $testInsert = FALSE;

    foreach ($result as $key => $value) {

        if($db->has("token", [
                "name" => $key
            ]
        )) {

            $db->update("token", [
                "value" => $value
            ], [
                "name" => $key
            ]);

            $updateArrayResult[] = $key;

        } else {

            $insertArray[] = [
                "name" => $key,
                "value" => $value
            ];

            $insertArrayResult[] = $key;
        }
    }

    (count($insertArray) > 0) && $db->insert("token", $insertArray);

    $api->createJSONResponse(["token" => $result, "insert" => $insertArrayResult, "update" => $updateArrayResult])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale", "beer" => $bearer])->run(403);
}