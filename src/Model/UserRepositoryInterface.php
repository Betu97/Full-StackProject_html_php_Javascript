<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 02/05/2019
 * Time: 20:16
 */

namespace SallePW\SlimApp\Model;

interface UserRepositoryInterface
{
    public function save(User $user);
}
