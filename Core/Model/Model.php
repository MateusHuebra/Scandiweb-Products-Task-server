<?php

namespace Core\Model;

use Core\Dao\Dao;
use Exception;

abstract class Model {

    const PRIMARY_KEY = 'id';

    static function GetAll() : Array {
        $table = static::getTableName();
        
        $dao = new Dao();
        $sql = "SELECT *
            FROM {$table}";
        $results = $dao->selectAll($sql);
        
        return static::getModelsOrThrow($results, true);
    }

    static function getByUnique(string $column, $value) : Model {
        $table = static::getTableName();

        $dao = new Dao();
        $sql = "SELECT *
            FROM {$table}
            WHERE {$column} = ?";
        $values = [$value];
        $result = $dao->selectOne($sql, $values);

        return static::getModelsOrThrow($result);
    }

    static function doesExist(string $column, $value) : bool {
        $table = static::getTableName();

        $dao = new Dao();
        $sql = "SELECT *
            FROM {$table}
            WHERE {$column} = ?";
        $values = [$value];
        $result = $dao->selectOne($sql, $values);

        return !!$result;
    }

    public function save() {
        $table = static::getTableName();
        $attributes = get_object_vars($this);
        foreach($attributes as $key => $value) {
            if(!is_array($value) && !is_object($value)) {
                $columns[] = $key;
                $values[$key] = $value;
                $onDuplicates[] = $key.' = :'.$key;
            }
        }
        
        $dao = new Dao();
        $sql = "INSERT INTO {$table} (";
        $sql.= implode(', ', $columns).') VALUES (:';
        $sql.= implode(', :', $columns).') ON DUPLICATE KEY UPDATE ';
        $sql.= implode(', ', $onDuplicates);

        return $dao->run($sql, null, $values);
    }

    public function delete() {
        $table = static::getTableName();

        $dao = new Dao();
        $sql = "DELETE FROM {$table}
            WHERE ".static::PRIMARY_KEY." = ?";
        $primaryKey = static::PRIMARY_KEY;
        $values = [$this->$primaryKey];
        
        return $dao->run($sql, $values);
    }

    protected static function getTableName() : string {
        if(!preg_match('/\\\?(\w+)$/', strtolower(static::class), $matches)) {
            throw new Exception('invalid class');
        }
        return $matches[1];
    }

    protected static function getModelsOrThrow(array $results, bool $isArray = false) {
        if($isArray) {
            $models = [];
            foreach($results as $result) {
                $models[] = static::createModelFromArray($result);
            }
            return $models;
        }

        if(empty($results)) {
            throw new Exception('nothing found');
        }
        return static::createModelFromArray($results);
    }

    protected static function createModelFromArray(array $result) : Model {
        $model = new static();
        foreach($result as $key => $value) {
            if($value !== null) {
                $model->$key = $value;
            }
        }
        return $model;
    }

}