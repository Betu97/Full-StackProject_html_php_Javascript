<?php

namespace SallePW\SlimApp\Model\Database;

use PDO;
use SallePW\SlimApp\Model\Item;
use SallePW\SlimApp\Model\ItemRepositoryInterface;
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

    public function saveItem(Item $item)
    {
        $statement = $this->database->connection->prepare(
            "INSERT into item(title, owner, description, price, product_image, category, created_at, updated_at) values(:title, :owner, description, price, product_image, category, :created_at, :updated_at)"
        );
        $title = $item->getTitle();
        $owner = $item->getOwner();
        $description = $item->getDescription();
        $price = $item->gePrice();
        $product_image = $item->getProduct_image();
        $category = $item->getCategory();
        $createdAt = $item->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAt = $item->getUpdatedAt()->format('Y-m-d H:i:s');

        $statement->bindParam('title', $title, PDO::PARAM_STR);
        $statement->bindParam('owner', $owner, PDO::PARAM_STR);
        $statement->bindParam('description', $description, PDO::PARAM_STR);
        $statement->bindParam('price', $price, PDO::PARAM_STR);
        $statement->bindParam('product_image', $product_image, PDO::PARAM_STR);
        $statement->bindParam('category', $category, PDO::PARAM_STR);
        $statement->bindParam('created_at', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updated_at', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }
    public function loadItem(int $id): array
    {
        $strId = strval($id);
        $statement = $this->database->connection->prepare(
            "SELECT * FROM item WHERE id = :id"
        );
        $statement->bindParam(':id', $strId, PDO::PARAM_STR);
        $statement->execute();
        $item = $statement->fetchAll();

        return $item[0];
    }

    public function searchItem(string $title): array
    {
        if(!empty($title)) {
            $title = '%' . $title . '%';
        }
        $statement = $this->database->connection->prepare(
            "SELECT * FROM item WHERE title LIKE :title"
        );
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->execute();
        $item = $statement->fetchAll();

        return $item;
    }
}
