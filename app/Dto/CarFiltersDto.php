<?php


namespace App\Dto;


class CarFiltersDto
{
    public function __construct(
        private ?string $search = null,
        private ?int $state_id = null,
        private ?int $city_id = null,
        private ?int $brand_id = null,
        private ?float $volume_from = null,
        private ?float $volume_to = null,
        private ?float $distance_from = null,
        private ?float $distance_to = null,
        private ?int $owners_from = null,
        private ?int $owners_to = null,
    )
    {
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @return int|null
     */
    public function getStateId(): ?int
    {
        return $this->state_id;
    }

    /**
     * @return int|null
     */
    public function getCityId(): ?int
    {
        return $this->city_id;
    }

    /**
     * @return int|null
     */
    public function getBrandId(): ?int
    {
        return $this->brand_id;
    }

    /**
     * @return float|null
     */
    public function getVolumeFrom(): ?float
    {
        return $this->volume_from;
    }

    /**
     * @return float|null
     */
    public function getVolumeTo(): ?float
    {
        return $this->volume_to;
    }

    /**
     * @return float|null
     */
    public function getDistanceFrom(): ?float
    {
        return $this->distance_from;
    }

    /**
     * @return float|null
     */
    public function getDistanceTo(): ?float
    {
        return $this->distance_to;
    }

    /**
     * @return int|null
     */
    public function getOwnersFrom(): ?int
    {
        return $this->owners_from;
    }

    /**
     * @return int|null
     */
    public function getOwnersTo(): ?int
    {
        return $this->owners_to;
    }
}
