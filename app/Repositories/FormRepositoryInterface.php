<?php

namespace App\Repositories;

use App\Models\Form;

interface FormRepositoryInterface
{
    function getDatagrid(int $perPage, string $column, bool $desc): array;

    function getOne(int $id): Form|null;

    function upsert(array $payload, ?int $id = null): bool;
}
