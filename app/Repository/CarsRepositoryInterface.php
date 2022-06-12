<?php


namespace App\Repository;


interface CarsRepositoryInterface
{
    public function getCarsData(
        int $offset = 0,
        int $limit = 10,
        string $search = null,
        int $state_id = null,
        int $city_id = null,
        int $brand_id = null,
        float $volume_from = null,
        float $volume_to = null,
        float $distance_from = null,
        float $distance_to = null,
        int $owners_from = null,
        int $owners_to = null,
    ): array;

    public function getCarsTotalCount(
        string $search = null,
        int $state_id = null,
        int $city_id = null,
        int $brand_id = null,
        float $volume_from = null,
        float $volume_to = null,
        float $distance_from = null,
        float $distance_to = null,
        int $owners_from = null,
        int $owners_to = null,
    ): int;

    public function getUserCarsCount($userId = null): int;
}
