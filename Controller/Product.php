<?php

namespace Controller;

use Core\Controller\Controller;
use Exception;
use Model\Book;
use Model\DVD;
use Model\Furniture;
use Model\Product as ModelProduct;
use Model\ProductBuilder\Factory;

class Product extends Controller {

    public function getAll() {
        $products = ModelProduct::GetAll();
        $response = [];
        foreach ($products as $key => $product) {
            $response[$key] = $product->toArray();
        }
        
        $this->response($response);
    }

    // not useful yet since we don't have a single product page or edit product page
    public function get() {
        if(!isset($_GET['sku'])) {
            throw new Exception('no sku sent');
        }

        $product = ModelProduct::getByUnique('sku', $_GET['sku']);
        $this->response($product->toArray());
    }
    
    public function save() {
        $request = $this->request();
        if(!(isset($request['sku']) && isset($request['name']) && isset($request['type']) && isset($request['price']))) {
            throw new Exception('needed data not sent');
        }

        if(ModelProduct::doesExist('sku', $request['sku'])) {
            throw new Exception('product already exists');
        }

        $builder = Factory::createBuilder($request['type']);
        $product = $builder->build($request);

        $product->save();
    }

    public function delete(string $sku = null) {
        if($sku===null) {
            if(!isset($_GET['sku'])) {
                http_response_code(400);
                throw new Exception('no sku sent');
            }
            $sku = $_GET['sku'];
        }
        if(!ModelProduct::doesExist('sku', $sku)) {
            return;
        }

        $product = ModelProduct::getByUnique('sku', $sku);
        $product->delete();
    }

    public function massDelete() {
        if(!isset($_GET['skus'])) {
            throw new Exception('no skus sent');
        }
        $skus = explode(';', $_GET['skus']);
        foreach ($skus as $sku) {
            $this->delete($sku);
        }
    }
    
}