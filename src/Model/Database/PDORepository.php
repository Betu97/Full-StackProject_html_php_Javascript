<?php

namespace SallePW\SlimApp\Model\Database;

use PDO;
use SallePW\SlimApp\Model\User;
use SallePW\SlimApp\Model\UserRepositoryInterface;

final class PDORepository implements UserRepositoryInterface
{
    /** @var Database */
    private $database;

    /**
     * PDORepository constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function save(User $user)
    {
        $statement = $this->database->connection->prepare(
            "INSERT into user(email, password, created_at, updated_at) values(:email, :password, :created_at, :updated_at)"
        );

        $email = $user->getEmail();
        $password = $user->getPassword();
        $createdAt = $user->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAt = $user->getUpdatedAt()->format('Y-m-d H:i:s');

        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $password, PDO::PARAM_STR);
        $statement->bindParam('created_at', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updated_at', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }
}
