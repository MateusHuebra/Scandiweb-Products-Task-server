<?php

namespace Model;

use Core\Dao\Dao;
use Exception;

class Furniture extends Product {

    protected $height;
    protected $width;
    protected $length;

    public function save() {
        $this->saveBase();

        $dao = new Dao();
        $sql = "INSERT INTO furniture (product_sku, height, width, length) 
            VALUES ('{$this->sku}', {$this->height}, {$this->width}, {$this->length})
            ON DUPLICATE KEY UPDATE
            height = {$this->height},
            width = {$this->width},
            length = {$this->length}";
        $dao->run($sql);
    }

    public function delete() {
        $dao = new Dao();
        $sql = "DELETE FROM furniture
            WHERE product_sku = ?";
        $values = [$this->sku];
        
        $dao->run($sql, $values);
        
        $this->deleteBase();
    }

    public function toArray() : array {
        $array = parent::toArray();
        $array['height'] = $this->height;
        $array['width'] = $this->width;
        $array['length'] = $this->length;
        return $array;
    }

    public function setHeight($height) {
        if(!preg_match('/[0-9]{1,7}.[0-9]{1,2}/', $height)) {
            throw new Exception('invalid value for height');
        }
        $this->height = $height;
    }
    public function setWidth($width) {
        if(!preg_match('/[0-9]{1,7}.[0-9]{1,2}/', $width)) {
            throw new Exception('invalid value for width');
        }
        $this->width = $width;
    }
    public function setLength($length) {
        if(!preg_match('/[0-9]{1,7}.[0-9]{1,2}/', $length)) {
            throw new Exception('invalid value for length');
        }
        $this->length = $length;
    }

    public function getHeight() {
        return $this->height;
    }
    public function getWidth() {
        return $this->width;
    }
    public function getLength() {
        return $this->length;
    }

}