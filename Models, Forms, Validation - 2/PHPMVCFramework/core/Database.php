<?php

namespace app\core;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations(); // ['m0001_initial.php']

        $files = scandir(Application::$ROOT_DIR . '/migrations'); // ['.','..','file1','file2', file...n]

        $toApplyMigrations = array_diff($files, $appliedMigrations); // So sánh giá trị của hai mảng và trả về sự khác biệt
        foreach($toApplyMigrations as $migration){
            if($migration === '.' || $migration === '..'){
                continue;
            }
            require_once Application::$ROOT_DIR . '/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migrations $migration");
            $instance -> up();
            $this->log("Applied migrations $migration");
            $newMigrations[] = $migration;

            if(!empty($newMigrations)){
                $this->saveMigrations($newMigrations);
            }else{
                $this->log("All migrations are applied");
            }
        }
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations ( 
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ) ENGINE=INNODB;
        ");
    }

    public function getAppliedMigrations()
    {
       $statement = $this->pdo->prepare("SELECT migration FROM  migrations");
       $statement->execute();
        return $statement->fetchAll();
    }

    public function saveMigrations(array $migrations){
        $str = implode(",",array_map(fn($m) => "('$m')", $migrations));
        $statement =  $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
                    $str
            ");
        $statement->execute();
    }

    protected  function log($message){
        echo '['.date('Y-m-d H:i:s') . '] - ' . $message.PHP_EOL;
    }
}