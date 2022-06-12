<?php


namespace App\Http\Repository;


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
     * @param string|null $search
     * @param int|null $state_id
     * @param int|null $city_id
     * @param int|null $brand_id
     * @param float|null $volume_from
     * @param float|null $volume_to
     * @param float|null $distance_from
     * @param float|null $distance_to
     * @param int|null $owners_from
     * @param int|null $owners_to
     * @return array
     */
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
    ): array {
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
        $this->prepareQueryFilters($query, $search, $state_id, $city_id, $brand_id, $volume_from, $volume_to, $distance_from, $distance_to, $owners_from, $owners_to);
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
     * @param string|null $search
     * @param int|null $state_id
     * @param int|null $city_id
     * @param int|null $brand_id
     * @param float|null $volume_from
     * @param float|null $volume_to
     * @param float|null $distance_from
     * @param float|null $distance_to
     * @param int|null $owners_from
     * @param int|null $owners_to
     * @return int
     */
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
    ): int {
        $query = Car::query();
        $this->prepareQueryFilters($query, $search, $state_id, $city_id, $brand_id, $volume_from, $volume_to, $distance_from, $distance_to, $owners_from, $owners_to);
        return $query->count();
    }

    /**
     * @param Builder $query
     * @param string|null $search
     * @param int|null $state_id
     * @param int|null $city_id
     * @param int|null $brand_id
     * @param float|null $volume_from
     * @param float|null $volume_to
     * @param float|null $distance_from
     * @param float|null $distance_to
     * @param int|null $owners_from
     * @param int|null $owners_to
     */
    private function prepareQueryFilters(
        Builder $query,
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
    ) {
        if (!empty($search)) {
            $query->leftJoin('states AS cs', 'cars.state_id', '=', 'cs.id');
            $query->leftJoin('cities AS cc', 'cars.city_id', '=', 'cc.id');
            $query->leftJoin('brands AS cb', 'cars.brand_id', '=', 'cb.id');
            $query->where(function ($query) use($search) {
                $query->where('cs.name', 'LIKE', '%'.$search.'%');
                $query->orWhere('cc.name', 'LIKE', '%'.$search.'%');
                $query->orWhere('cb.name', 'LIKE', '%'.$search.'%');
            });
        }
        $query->when(!empty($state_id), function($query) use($state_id) {
            $query->where('cars.state_id', '=', $state_id);
        });
        $query->when(!empty($city_id), function($query) use($city_id) {
            $query->where('cars.city_id', '=', $city_id);
        });
        $query->when(!empty($brand_id), function($query) use($brand_id) {
            $query->where('cars.brand_id', '=', $brand_id);
        });
        $query->when(!empty($volume_from), function($query) use($volume_from) {
            $query->where('cars.volume', '>=', $volume_from);
        });
        $query->when(!empty($volume_to), function($query) use($volume_to) {
            $query->where('cars.volume', '<=', $volume_to);
        });
        $query->when(!empty($distance_from), function($query) use($distance_from) {
            $query->where('cars.distance', '>=', $distance_from);
        });
        $query->when(!empty($distance_to), function($query) use($distance_to) {
            $query->where('cars.distance', '<=', $distance_to);
        });
        $query->when(!empty($owners_from), function($query) use($owners_from) {
            $query->where('cars.owners', '>=', $owners_from);
        });
        $query->when(!empty($owners_to), function($query) use($owners_to) {
            $query->where('cars.owners', '<=', $owners_to);
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
