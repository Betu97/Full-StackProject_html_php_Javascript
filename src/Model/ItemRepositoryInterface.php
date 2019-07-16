<?php

namespace SallePW\SlimApp\Model;

interface ItemRepositoryInterface
{
    public function saveItem(Item $item);
    public function loadItem(int $id): array;
}
