<?php

namespace SallePW\SlimApp\Model;

interface UserRepositoryInterface
{
    public function saveItem(Item $item);
    public function loadItem(int $id): array;
}
