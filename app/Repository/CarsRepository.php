<?php


namespace App\Repository;


use App\Dto\CarFiltersDto;
use App\Models\Car;
use App\Models\CarPhoto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarsRepository implements CarsRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @param CarFiltersDto $dto
     * @return array
     */
    public function getCarsData(int $offset, int $limit, CarFiltersDto $dto): array {
        $data = [];
        $query = Car::with(['state', 'city', 'brand']);
        $query->select([
                           'cars.id',
                           'cars.state_id',
                           'cars.city_id',
                           'cars.brand_id',
                           'cars.volume',
                           'cars.distance',
                           'cars.owners',
                           'cars.price',
                           'cars.content',
                           DB::raw('cars.created_at as `created_at`')
                       ]);
        $this->prepareQueryFilters($query, $dto);
        $query->skip($offset)->take($limit);
        $query->orderBy('cars.created_at', 'DESC');
        $cars = $query->get();
        foreach ($cars as $car) {
            $photos = CarPhoto::where('car_id', '=', $car->id)->get()->map(
                function ($photo) {
                    return $photo->id;
                }
            );
            $data[] = [
                'id' => $car->id,
                'state' => $car->state?->name,
                'city' => $car->city?->name,
                'brand' => $car->brand?->name,
                'volume' => $car->volume,
                'distance' => $car->distance,
                'owners' => $car->owners,
                'photos' => $photos,
                'price' => number_format($car->price, 2).' '.__('maratauto.currencySign'),
                'content' => $car->content,
                'date' => $car->created_at?->format('d.m.Y H:i:s'),
            ];
        }
        return $data;
    }

    /**
     * @param CarFiltersDto $dto
     * @return int
     */
    public function getCarsTotalCount(CarFiltersDto $dto): int {
        $query = Car::query();
        $this->prepareQueryFilters($query, $dto);
        return $query->count();
    }

    /**
     * @param Builder $query
     * @param CarFiltersDto $dto
     */
    private function prepareQueryFilters(Builder $query, CarFiltersDto $dto): void {
        if (!empty($dto->getSearch())) {
            $query->leftJoin('states AS cs', 'cars.state_id', '=', 'cs.id');
            $query->leftJoin('cities AS cc', 'cars.city_id', '=', 'cc.id');
            $query->leftJoin('brands AS cb', 'cars.brand_id', '=', 'cb.id');
            $query->where(function ($query) use($dto) {
                $query->where('cs.name', 'LIKE', '%'.$dto->getSearch().'%');
                $query->orWhere('cc.name', 'LIKE', '%'.$dto->getSearch().'%');
                $query->orWhere('cb.name', 'LIKE', '%'.$dto->getSearch().'%');
            });
        }
        $query->when(!empty($dto->getStateId()), function($query) use($dto) {
            $query->where('cars.state_id', '=', $dto->getStateId());
        });
        $query->when(!empty($dto->getCityId()), function($query) use($dto) {
            $query->where('cars.city_id', '=', $dto->getCityId());
        });
        $query->when(!empty($dto->getBrandId()), function($query) use($dto) {
            $query->where('cars.brand_id', '=', $dto->getBrandId());
        });
        $query->when(!empty($dto->getVolumeFrom()), function($query) use($dto) {
            $query->where('cars.volume', '>=', $dto->getVolumeFrom());
        });
        $query->when(!empty($dto->getVolumeTo()), function($query) use($dto) {
            $query->where('cars.volume', '<=', $dto->getVolumeTo());
        });
        $query->when(!empty($dto->getDistanceFrom()), function($query) use($dto) {
            $query->where('cars.distance', '>=', $dto->getDistanceFrom());
        });
        $query->when(!empty($dto->getDistanceTo()), function($query) use($dto) {
            $query->where('cars.distance', '<=', $dto->getDistanceTo());
        });
        $query->when(!empty($dto->getOwnersFrom()), function($query) use($dto) {
            $query->where('cars.owners', '>=', $dto->getOwnersFrom());
        });
        $query->when(!empty($dto->getOwnersTo()), function($query) use($dto) {
            $query->where('cars.owners', '<=', $dto->getOwnersTo());
        });
    }

    public function getUserCarsCount($userId = null): int
    {
        if (empty($userId)) {
            $userId = Auth::user()->id;
        }
        return Car::where('user_id', '=', $userId)->count();
    }
}
