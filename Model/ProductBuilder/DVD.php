<?php

namespace Model\ProductBuilder;

use Exception;
use Model\DVD as ModelDVD;

class DVD implements ProductBuilder {

    public function build($request) {
        if(!(isset($request['size']))) {
            throw new Exception('needed data not sent');
        }

        $product = new ModelDVD();
        $product->setSku($request['sku']);
        $product->setName($request['name']);
        $product->setType($request['type']);
        $product->setPrice($request['price']);
        $product->setSize($request['size']);
        return $product;
    }

}