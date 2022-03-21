<?php

namespace Core\Controller;

abstract class Controller {

    public function request() {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function response($response) {
        header('content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

}