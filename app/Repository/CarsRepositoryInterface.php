<?php


namespace App\Repository;


use App\Dto\CarFiltersDto;

interface CarsRepositoryInterface
{
    public function getCarsData(int $offset, int $limit, CarFiltersDto $dto): array;

    public function getCarsTotalCount(CarFiltersDto $dto): int;

    public function getUserCarsCount($userId = null): int;
}
