<?php

namespace app\core\db;

use app\core\Model;
use app\core\Application;

/**
 * Class DbModel.
 * 
 * @package app\core
 */
abstract class DbModel extends Model
{
    /**
     * The table name to apply methods to.
     * 
     * @return string 
     */
    abstract public static function tableName(): string;

    /**
     * Array of column names that should be saved in the current table.
     * 
     * @return array 
     */
    abstract public function attributes(): array;

    /**
     * The primary key for the current model's database table.
     * 
     * @return string 
     */
    abstract public static function primaryKey(): string;

    /**
     * Saves a record into the database.
     * 
     * @return true 
     * @throws \PDOException 
     */
    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ")
            VALUES(" . implode(',', $params) . ")");
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    /**
     * Returns a single object for the given parameters passed.
     * 
     * @param array $where Associative array of parameters to query by.
     * 
     * @param array $where 
     * @return \app\core\db\DbModel|\stdClass|null 
     * @throws \PDOException 
     */
    public static function findOne(array $where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn ($attr) => "$attr = :$attr", $attributes));
        // SELECT * FROM $tableName WHERE email = :email AND firstname = :firstname
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    /**
     * Prepares the SQL statement and returns the string.
     * 
     * @param string $sql SQL statement to prepare.
     * @return \PDOStatement|false 
     */
    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}
