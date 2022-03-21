<?php

namespace Model;

use Core\Dao\Dao;
use Exception;

class Book extends Product {

    protected $weight;

    public function save() {
        $this->saveBase();

        $dao = new Dao();
        $sql = "INSERT INTO book (product_sku, weight) 
            VALUES ('{$this->sku}', {$this->weight})
            ON DUPLICATE KEY UPDATE
            weight = {$this->weight}";
        $dao->run($sql);
    }

    public function delete() {
        $dao = new Dao();
        $sql = "DELETE FROM book
            WHERE product_sku = ?";
        $values = [$this->sku];
        
        $dao->run($sql, $values);
        
        $this->deleteBase();
    }

    public function toArray() : array {
        $array = parent::toArray();
        $array['weight'] = $this->weight;
        return $array;
    }

    public function setWeight($weight) {
        if(!preg_match('/[0-9]{1,7}.[0-9]{1,2}/', $weight)) {
            throw new Exception('invalid value for weight');
        }
        $this->weight = $weight;
    }

    public function getWeight() {
        return $this->weight;
    }

}