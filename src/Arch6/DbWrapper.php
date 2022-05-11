<?php

namespace Arch6;

class DbWrapper
{
    public const TABLE_NAME = 'stest';

    private $pdo;

    public function __construct(string $dsn, string $username, string $password)
    {
        $this->pdo = new \PDO($dsn, $username, $password);
    }

    public function createStructure()
    {
        $this->pdo->exec(
            '
            CREATE TABlE IF NOT EXISTS `' . self::TABLE_NAME . '` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `rnd` VARCHAR (20)
            )
        '
        );
    }

    public function insert()
    {
        $statement = $this->pdo->prepare('INSERT INTO ' . self::TABLE_NAME . ' (rnd) VALUES (?)');
        $statement->execute([\uniqid()]);
    }

    public function getAll()
    {
        $statement = $this->pdo->query('SELECT * FROM ' . self::TABLE_NAME, \PDO::FETCH_ASSOC);
        var_dump($statement->fetchAll());
    }
}
