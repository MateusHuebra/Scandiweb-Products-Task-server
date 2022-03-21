<?php

namespace Controller;

use Core\Controller\Controller;

class PageNotFound extends Controller {

    function index() {
        http_response_code(404);
    }

}