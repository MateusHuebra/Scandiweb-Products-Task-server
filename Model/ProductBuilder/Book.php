<?php

namespace Model\ProductBuilder;

use Exception;
use Model\Book as ModelBook;

class Book implements ProductBuilder {

    public function build($request) {
        if(!(isset($request['weight']))) {
            throw new Exception('needed data not sent');
        }

        $product = new ModelBook();
        $product->setSku($request['sku']);
        $product->setName($request['name']);
        $product->setType($request['type']);
        $product->setPrice($request['price']);
        $product->setWeight($request['weight']);
        return $product;
    }

}