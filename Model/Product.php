<?php

namespace Model;

use Core\Dao\Dao;
use Core\Model\Model;
use Exception;

abstract class Product extends Model {

    const PRIMARY_KEY = 'sku';

    protected $sku;
    protected $name;
    protected $price;
    protected $type;

    public function toArray() : array {
        $array['sku'] = $this->sku;
        $array['name'] = $this->name;
        $array['type'] = $this->type;
        $array['price'] = $this->price;
        return $array;
    }

    public function save() {
        throw new Exception('you cannot save the product supertype');
    }

    public function delete() {
        throw new Exception('you cannot delete the product supertype');
    }

    static function GetAll() : Array {
        $dao = new Dao();
        $sql = "SELECT * FROM product
            LEFT JOIN dvd ON product.sku = dvd.product_sku
            LEFT JOIN furniture ON product.sku = furniture.product_sku
            LEFT JOIN book ON product.sku = book.product_sku";
        $results = $dao->selectAll($sql);
        
        return static::getModelsOrThrow($results, true);
    }

    static function getByUnique(string $column, $value) : Product {
        $dao = new Dao();
        $sql = "SELECT * FROM product
            LEFT JOIN dvd ON product.sku = dvd.product_sku
            LEFT JOIN furniture ON product.sku = furniture.product_sku
            LEFT JOIN book ON product.sku = book.product_sku
            WHERE {$column} = ?";
        $values = [$value];
        $result = $dao->selectOne($sql, $values);
        //print_r($result);die;
        return static::getModelsOrThrow($result);
    }

    public function setSku($sku) {
        if(!preg_match('/[A-z0-9]{1,12}/', $sku)) {
            throw new Exception('invalid value for sku');
        }
        $this->sku = $sku;
    }
    public function setName($name) {
        if(!preg_match('/[A-z 0-9]{1,32}/', $name)) {
            throw new Exception('invalid value for name');
        }
        $this->name = $name;
    }
    public function setPrice($price) {
        if(!preg_match('/[0-9]{1,7}.[0-9]{1,2}/', $price)) {
            throw new Exception('invalid value for price');
        }
        $this->price = $price;
    }
    public function setType($type) {
        if(!in_array($type, ['dvd', 'furniture', 'book'])) {
            throw new Exception('invalid value for price');
        }
        $this->type = $type;
    }

    public function getSku() {
        return $this->sku;
    }
    public function getName() {
        return $this->name;
    }
    public function getPrice() {
        return $this->price;
    }
    public function getType() {
        return $this->type;
    }

    protected function saveBase() {
        $dao = new Dao();
        $sql = "INSERT INTO product (sku, name, type, price) 
            VALUES ('{$this->sku}', '{$this->name}', '{$this->type}', {$this->price})
            ON DUPLICATE KEY UPDATE
            name = '{$this->name}',
            type = '{$this->type}',
            price = {$this->price}";

        $dao->run($sql);
    }

    protected function deleteBase() {
        $dao = new Dao();
        $sql = "DELETE FROM product
            WHERE sku = ?";
        $values = [$this->sku];
        
        $dao->run($sql, $values);
    }

    protected static function createModelFromArray(array $result) : Product {
        if($result['type']==='dvd') {
            $model = new DVD();
        } else if($result['type']==='furniture') {
            $model = new Furniture();
        } else if($result['type']==='book') {
            $model = new Book();
        }
        
        foreach($result as $key => $value) {
            if($value !== null && $key!=='product_sku') {
                $model->$key = $value;
            }
        }
        return $model;
    }

};