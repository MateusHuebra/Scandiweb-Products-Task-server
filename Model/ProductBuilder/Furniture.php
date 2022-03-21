<?php

namespace Model\ProductBuilder;

use Exception;
use Model\Furniture as ModelFurniture;

class Furniture implements ProductBuilder {

    public function build($request) {
        if(!(isset($request['height']) && isset($request['width']) && isset($request['length']))) {
            throw new Exception('needed data not sent');
        }

        $product = new ModelFurniture();
        $product->setSku($request['sku']);
        $product->setName($request['name']);
        $product->setType($request['type']);
        $product->setPrice($request['price']);
        $product->setHeight($request['height']);
        $product->setWidth($request['width']);
        $product->setLength($request['length']);
        return $product;
    }

}