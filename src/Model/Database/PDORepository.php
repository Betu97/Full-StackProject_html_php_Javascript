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
            "INSERT into user(name, username, email, birthdate, phone_number, password, is_active, created_at, updated_at) values(:name, :username, :email, :birthdate, :phone_number, :password, :is_active, :created_at, :updated_at)"
        );

        $name = $user->getName();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $filteredEmail = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $birthdate = $user->getBirthdate()->format('Y-m-d H:i:s');
        $phone_number = $user->getPhoneNumber();
        $password = $user->getPassword();
        $filteredPassword = md5(filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $is_active = $user->getIsActive();
        $createdAt = $user->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAt = $user->getUpdatedAt()->format('Y-m-d H:i:s');

        $statement->bindParam('name', $name, PDO::PARAM_STR);
        $statement->bindParam('username', $username, PDO::PARAM_STR);
        $statement->bindParam('email', $filteredEmail, PDO::PARAM_STR);
        $statement->bindParam('birthdate', $birthdate, PDO::PARAM_STR);
        $statement->bindParam('phone_number', $phone_number, PDO::PARAM_STR);
        $statement->bindParam('password', $filteredPassword, PDO::PARAM_STR);
        $statement->bindParam('is_active', $is_active, PDO::PARAM_BOOL);
        $statement->bindParam('created_at', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updated_at', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }

    public function saveItem(Item $item)
    {
        $statement = $this->database->connection->prepare(
            "INSERT into item(title, owner, description, price, product_image, category, is_active, created_at, updated_at) values(:title, :owner, :description, :price, :product_image, :category, :is_active, :created_at, :updated_at)"
        );
        $title = $item->getTitle();
        $owner = $item->getOwner();
        $description = $item->getDescription();
        $price = $item->gePrice();
        $product_image = $item->getProduct_image();
        $category = $item->getCategory();
        $is_active = $item->getIsActive();
        $createdAt = $item->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAt = $item->getUpdatedAt()->format('Y-m-d H:i:s');

        $statement->bindParam('title', $title, PDO::PARAM_STR);
        $statement->bindParam('owner', $owner, PDO::PARAM_STR);
        $statement->bindParam('description', $description, PDO::PARAM_STR);
        $statement->bindParam('price', $price, PDO::PARAM_STR);
        $statement->bindParam('product_image', $product_image, PDO::PARAM_STR);
        $statement->bindParam('category', $category, PDO::PARAM_STR);
        $statement->bindParam('is_active', $is_active, PDO::PARAM_BOOL);
        $statement->bindParam('created_at', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updated_at', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }

    public function deleteAccount(int $id)
    {

        $statement = $this->database->connection->prepare(
            "UPDATE user SET is_active = 0 WHERE id = :id"
        );

        $statement->bindParam('id', $id, PDO::PARAM_INT);

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
        if (!empty($title)) {
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

    public function checkUser(string $user): int
    {
        $strUser = strval($user);
        $statement = $this->database->connection->prepare(
            "SELECT * FROM user WHERE (username = :username AND is_active = 1)"
        );
        $statement->bindParam(':username', $strUser, PDO::PARAM_STR);
        $statement->execute();
        $infoUser = $statement->fetchAll();

        if(!isset($infoUser[0]['id'])){
            return -1;
        }
        return 1;
    }

    public function signIn(string $user, string $password): int
    {
        $strUser = strval($user);
        $filteredPassword = md5(filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $strPass = strval($filteredPassword);
        $statement = $this->database->connection->prepare(
            "SELECT * FROM user WHERE (username = :username AND password = :password)"
        );
        $statement->bindParam(':username', $strUser, PDO::PARAM_STR);
        $statement->bindParam(':password', $strPass, PDO::PARAM_STR);
        $statement->execute();
        $info = $statement->fetchAll();

        if($this->checkUser($user) == -1){
            return -2;
        }
        if(!isset($info[0]['id'])){
            return -1;
        }
        return $info[0]['id'];
    }

}
