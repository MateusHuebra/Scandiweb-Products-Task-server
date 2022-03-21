<?php

namespace Model\ProductBuilder;

use Exception;

class Factory {

    static function createBuilder($type) {
        if($type==='dvd') {
            return new DVD();
        } else if($type==='furniture') {
            return new Furniture();
        } else if($type==='book') {
            return new Book();
        } else {
            throw new Exception('invalid product type');
        }
    }

}