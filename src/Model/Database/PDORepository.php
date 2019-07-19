<?php

namespace SallePW\SlimApp\Model\Database;

use PDO;
use DateTime;
use SallePW\SlimApp\Model\Item;
use SallePW\SlimApp\Model\ItemRepositoryInterface;
use SallePW\SlimApp\Model\User;
use SallePW\SlimApp\Model\UserRepositoryInterface;

final class PDORepository implements UserRepositoryInterface, ItemRepositoryInterface
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
            "INSERT into item(title, owner, description, price, product_image, category, is_active, sold, created_at, updated_at) values(:title, :owner, :description, :price, :product_image, :category, :is_active, :sold, :created_at, :updated_at)"
        );
        $title = $item->getTitle();
        $owner = $item->getOwner();
        $description = $item->getDescription();
        $price = $item->getPrice();
        $product_image = $item->getProductImage();
        $category = $item->getCategory();
        $is_active = $item->getIsActive();
        $sold = $item->getSold();
        $createdAt = $item->getCreatedAt()->format('Y-m-d H:i:s');
        $updatedAt = $item->getUpdatedAt()->format('Y-m-d H:i:s');

        $statement->bindParam('title', $title, PDO::PARAM_STR);
        $statement->bindParam('owner', $owner, PDO::PARAM_STR);
        $statement->bindParam('description', $description, PDO::PARAM_STR);
        $statement->bindParam('price', $price, PDO::PARAM_STR);
        $statement->bindParam('product_image', $product_image, PDO::PARAM_STR);
        $statement->bindParam('category', $category, PDO::PARAM_STR);
        $statement->bindParam('is_active', $is_active, PDO::PARAM_BOOL);
        $statement->bindParam('sold', $sold, PDO::PARAM_BOOL);
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

    public function deleteItems(int $owner)
    {

        $statement = $this->database->connection->prepare(
            "UPDATE item SET is_active = 0 WHERE owner = :owner"
        );

        $statement->bindParam('owner', $owner, PDO::PARAM_INT);

        $statement->execute();

    }

    public function deleteItem(int $id)
    {

        $statement = $this->database->connection->prepare(
            "UPDATE item SET is_active = 0 WHERE id = :id"
        );

        $statement->bindParam('id', $id, PDO::PARAM_INT);

        $statement->execute();

    }

    public function insertProductImage()
    {
        $id = $this->getMaxId();
        $product_image = $id;
        $statement = $this->database->connection->prepare(
            "UPDATE item SET product_image = :product_image WHERE id = :id"
        );

        $statement->bindParam('product_image', $product_image, PDO::PARAM_STR);
        $statement->bindParam('id', $id, PDO::PARAM_STR);
        $statement->execute();
    }

    public function updateProfile(array $data, int $id)
    {

        if (!empty($data['name'])){
            $statement = $this->database->connection->prepare(
                "UPDATE user SET name = :name WHERE id = :id"
            );

            $statement->bindParam('name', $data['name'], PDO::PARAM_STR);
            $statement->bindParam('id', $id, PDO::PARAM_STR);
            $statement->execute();
        }

        if (!empty($data['email'])){
            $statement = $this->database->connection->prepare(
                "UPDATE user SET email = :email WHERE id = :id"
            );

            $statement->bindParam('email', $data['email'], PDO::PARAM_STR);
            $statement->bindParam('id', $id, PDO::PARAM_STR);
            $statement->execute();
        }

        if (!empty($data['birthdate'])){

            $date = new DateTime($data['birthdate']);
            $date = $date->format('Y-m-d H:i:s');
            $statement = $this->database->connection->prepare(
                "UPDATE user SET birthdate = :birthdate WHERE id = :id"
            );

            $statement->bindParam('birthdate', $date, PDO::PARAM_STR);
            $statement->bindParam('id', $id, PDO::PARAM_STR);
            $statement->execute();
        }

        if (!empty($data['phone_number'])){
            $statement = $this->database->connection->prepare(
                "UPDATE user SET phone_number = :phone_number WHERE id = :id"
            );

            $statement->bindParam('phone_number', $data['phone_number'], PDO::PARAM_STR);
            $statement->bindParam('id', $id, PDO::PARAM_STR);
            $statement->execute();
        }
    }

    public function updateOverview(array $data)
    {

        if (!empty($data['title'])){
            $statement = $this->database->connection->prepare(
                "UPDATE item SET title = :title WHERE id = :id"
            );

            $statement->bindParam('title', $data['title'], PDO::PARAM_STR);
            $statement->bindParam('id', $data['item'], PDO::PARAM_STR);
            $statement->execute();
        }

        if (!empty($data['price'])){
            $statement = $this->database->connection->prepare(
                "UPDATE item SET price = :price WHERE id = :id"
            );

            $statement->bindParam('price', $data['price'], PDO::PARAM_STR);
            $statement->bindParam('id', $data['item'], PDO::PARAM_STR);
            $statement->execute();
        }

        if (!empty($data['category'])){

            $statement = $this->database->connection->prepare(
                "UPDATE item SET category = :category WHERE id = :id"
            );

            $statement->bindParam('category', $data['category'], PDO::PARAM_STR);
            $statement->bindParam('id', $data['item'], PDO::PARAM_STR);
            $statement->execute();
        }

        if (!empty($data['description'])){
            $statement = $this->database->connection->prepare(
                "UPDATE item SET description = :description WHERE id = :id"
            );

            $statement->bindParam('description', $data['description'], PDO::PARAM_STR);
            $statement->bindParam('id', $data['item'], PDO::PARAM_STR);
            $statement->execute();
        }
    }

    public function getMaxId(): String
    {
        $statement = $this->database->connection->prepare(
            "SELECT MAX(id) FROM item"
        );
        $statement->execute();

        $item = $statement->fetchAll();
        return $item[0]['MAX(id)'];
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

    public function checkUser(string $email): int
    {
        $strEmail = strval($email);
        $statement = $this->database->connection->prepare(
            "SELECT * FROM user WHERE (email = :email AND is_active = 1)"
        );
        $statement->bindParam(':email', $strEmail, PDO::PARAM_STR);
        $statement->execute();
        $infoUser = $statement->fetchAll();

        if(!isset($infoUser[0]['id'])){
            return -1;
        }
        return 1;
    }

    public function signIn(string $email, string $password): int
    {
        $strEmail = strval($email);
        $filteredPassword = md5(filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $strPass = strval($filteredPassword);
        $statement = $this->database->connection->prepare(
            "SELECT * FROM user WHERE (email = :email AND password = :password AND is_active = 1)"
        );
        $statement->bindParam(':email', $strEmail, PDO::PARAM_STR);
        $statement->bindParam(':password', $strPass, PDO::PARAM_STR);
        $statement->execute();
        $info = $statement->fetchAll();

        if($this->checkUser($email) == -1){
            return -2;
        }
        if(!isset($info[0]['id'])){
            return -1;
        }
        return $info[0]['id'];
    }

    public function buy(int $id)
    {

        $statement = $this->database->connection->prepare(
            "UPDATE item SET sold = 1 WHERE id = :id AND sold = 0"
        );

        $statement->bindParam('id', $id, PDO::PARAM_INT);

        $statement->execute();

    }

    public function loadUser(int $id): array
    {
        $strId = strval($id);
        $statement = $this->database->connection->prepare(
            "SELECT * FROM user WHERE id = :id"
        );
        $statement->bindParam(':id', $strId, PDO::PARAM_STR);
        $statement->execute();
        $user = $statement->fetchAll();

        return $user[0];
    }


}