<?php

namespace Model\ProductBuilder;

use Model\Product;

interface ProductBuilder {

    public function build($request) : Product;

}