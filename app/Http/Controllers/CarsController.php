<?php

namespace App\Http\Controllers;

use App\Dto\CarFiltersDto;
use App\Repository\CarsRepositoryInterface;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarPhoto;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CarsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {
        $states = State::get();
        $brands = Brand::get();
        $stateOptions = $states->mapWithKeys(function ($state) {
            return [$state->id => $state->name];
        });
        $brandOptions = $brands->mapWithKeys(function ($brand) {
            return [$brand->id => $brand->name];
        });
        return view('index', compact('states', 'brands', 'stateOptions', 'brandOptions'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Repository\CarsRepositoryInterface $carsRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexAjax(Request $request, CarsRepositoryInterface $carsRepository) {
        try {
            if (!Auth::check()) {
                throw new \Exception('User is not logged in');
            }
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $search = $request->get('search');
            $state_id = $request->get('state_id');
            $city_id = $request->get('city_id');
            $brand_id = $request->get('brand_id');
            $volume_from = $request->get('volume_from');
            $volume_to = $request->get('volume_to');
            $distance_from = $request->get('distance_from');
            $distance_to = $request->get('distance_to');
            $owners_from = $request->get('owners_from');
            $owners_to = $request->get('owners_to');
            if (!empty($search)) {
                $search = $search['value'];
            }
            $filtersDto = new CarFiltersDto(
                $search, $state_id, $city_id, $brand_id, $volume_from, $volume_to,
                $distance_from, $distance_to, $owners_from, $owners_to
            );
            $totalCount = $carsRepository->getCarsTotalCount($filtersDto);
            $data = $carsRepository->getCarsData($start, $length, $filtersDto);
            return response()->json([
                'success' => true,
                'data' => $data,
                'recordsTotal' => $totalCount,
                'recordsFiltered' => $totalCount,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param \App\Repository\CarsRepositoryInterface $carsRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request, CarsRepositoryInterface $carsRepository) {
        try {
            if (!Auth::check()) {
                throw new \Exception(__('maratauto.userNotLoggedIn'));
            }
            $userCarsCount = $carsRepository->getUserCarsCount();
            if ($userCarsCount >= (int)config('maratauto.cars_limit')) {
                throw new \Exception(__('maratauto.carsLimitExceeded'));
            }
            $data = $request->post();
            $car = new Car();
            $car->fill($data);
            $car->user_id = Auth::user()->id;
            $car->save();
            // Photos upload
            $photos = $request->file('photos');
            foreach ($photos as $photo) {
                $path = $photo->store('photos');
                $photo = new CarPhoto();
                $photo->fill([
                    'car_id' => $car->id,
                    'filename' => File::basename($path),
                ]);
                $photo->save();
            }
            return response()->json(['success' => true, 'message' => 'Car is added successfully', 'data' => $car->toArray()]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param int $carPhotoId
     * @param int|null $width
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function photo(int $carPhotoId, int $width = null)
    {
        try {
            $carPhoto = CarPhoto::findOrFail($carPhotoId);
            $originalPath = storage_path('app/photos').'/'.$carPhoto->filename;
            if (!empty($width)) {
                $thumbnailPath = storage_path('app/photos').'/'.File::name($carPhoto->filename).'-'.$width.'.'.File::extension($carPhoto->filename);
                if (!File::exists($thumbnailPath)) {
                    $image = Image::make($originalPath)->resize($width, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($thumbnailPath);
                }
                return response()->file($thumbnailPath);
            } else {
                return response()->file($originalPath);
            }
        } catch (\Throwable $e) {
            return response()->file(storage_path('app/photos').'/noimage.png');
        }
    }
}
