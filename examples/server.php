<?php
require_once(__DIR__.'/../src/Provider.php');

$server = new Provider();

$server->get( '/student', new Api(), 'fetchStudent' );
$server->post( '/student', new Api(), 'createStudent' );
$server->put( '/student', new Api(), 'modifyStudent' );
$server->delete( '/student', new Api(), 'deleteStudent' );


class Api {

    function fetchStudent($param) {
        echo "fetchStudent:";
        echo json_encode($param);
    }

    function createStudent($param) {
        echo "createStudent:";
        echo json_encode($param);
    }

    function modifyStudent($param) {
        echo "modifyStudent:";
        echo json_encode($param);
    }

    function deleteStudent($param) {
        echo "deleteStudent:";
        echo json_encode($param);
    }

}
