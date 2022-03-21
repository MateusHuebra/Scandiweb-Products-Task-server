<?php

namespace Model;

use Core\Dao\Dao;
use Exception;

class DVD extends Product {

    protected $size;

    public function save() {
        $this->saveBase();

        $dao = new Dao();
        $sql = "INSERT INTO dvd (product_sku, size) 
            VALUES ('{$this->sku}', {$this->size})
            ON DUPLICATE KEY UPDATE
            size = {$this->size}";
        $dao->run($sql);
    }

    public function delete() {
        $dao = new Dao();
        $sql = "DELETE FROM dvd
            WHERE product_sku = ?";
        $values = [$this->sku];
        
        $dao->run($sql, $values);
        
        $this->deleteBase();
    }

    public function toArray() : array {
        $array = parent::toArray();
        $array['size'] = $this->size;
        return $array;
    }

    public function setSize($size) {
        if(!preg_match('/[0-9]{1,7}.[0-9]{1,2}/', $size)) {
            throw new Exception('invalid value for size');
        }
        $this->size = $size;
    }

    public function getSize() {
        return $this->size;
    }

}