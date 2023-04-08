<?php

namespace app\core\db;

use app\core\Application;

/**
 * Class Database.
 * 
 * @package app\core
 */
class Database
{
    public \PDO $pdo;

    /**
     * Database constructor.
     * 
     * @param array $config Database configuration array from the .env file.
     * @return void 
     */
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Applies database migrations.
     * 
     * @return void 
     * @throws \PDOException 
     */
    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log('Applying migration ' . $migration);
            $instance->up();
            $this->log('Successfully applied ' . $migration);
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log('All migrations are applied');
        }
    }

    /**
     * Creates the migrations table in the database if it does not
     * already exist.  This table is used to store which migrations were
     * applied and when.
     * 
     * @return void 
     */
    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    /**
     * Retrieves migrations that have already been applied.
     * 
     * @return array|false 
     * @throws \PDOException 
     */
    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Compares saves the migrations being applied to the database.  This
     * information is later used to compare migrations already applied with
     * new ones being applied to the database in the future.
     * 
     * @param array $migrations 
     * @return void 
     * @throws \PDOException 
     */
    public function saveMigrations(array $migrations)
    {
        $string = implode(',', array_map(fn ($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $string ");
        $statement->execute();
    }

    /**
     * Shortcut method to prepare PDO statements.
     * 
     * @param string $sql 
     * @return \PDOStatement|false 
     */
    public function prepare(string $sql)
    {
        return $this->pdo->prepare($sql);
    }

    /**
     * Logs messages to the console.
     * 
     * @param string $message Message text.
     * @return void 
     */
    protected function log(string $message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}
