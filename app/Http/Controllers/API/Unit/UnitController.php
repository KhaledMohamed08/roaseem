<?php

namespace App\Http\Controllers\API\Unit;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\Notification;
use App\Models\Service;
use App\Models\Unit;
use App\Models\UnitViews;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $notificationsService;

    public function __construct(NotificationService $notificationsService)
    {
        $this->notificationsService = $notificationsService;
    }

    public function index()
    {
        $perPage = 9;
        $unites = Unit::orderBy('created_at', 'desc')->paginate($perPage);

        return ApiResponse::success(
            [
                'units' => UnitResource::collection($unites),
            ],
            'Unites',
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        $validatedData = $request->validated();
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }
        $unitData = $validatedData;
        unset($unitData['services'], $unitData['main_image']);
        $unitData['user_id'] = $userId;
        $unit = Unit::create($unitData); // Crete New Unit
        foreach ($request['services'] as $service) {
            Service::create([
                'service_id' => $service,
                'unit_id' => $unit->id,
            ]);
        }
        $unit->addMediaFromRequest('main_image')->toMediaCollection('unit-Main-image');
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $unit->addMedia($image)->toMediaCollection('images'); // Add images in media model for this unit
            }
        }

        $notificationData = [];
        $usersQuery = DB::table('users')->select('id')->where('role', 'user');
        $notificationData = $usersQuery->get()->map(function ($user) use ($unit) {
            return [
                'user_id' => $user->id,
                'message' => 'A new unit has been Added',
                'event' => 'new_unit',
                'is_read' => false,
                'url' => "unit/$unit->id"
            ];
        })->toArray();

        Notification::insert($notificationData);

        return ApiResponse::success(
            [
                'unit' => new UnitResource($unit),
            ],
            'Unit Creted Successfully',
            200,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        $user = null;
        if ($token = request()->bearerToken()) {
            $user = Auth::guard('sanctum')->user();
        }

        if ($user) {
            UnitViews::create([
                "user_id" => $user->id,
                "unit_id" => $unit->id
            ]);
            $notification = $this->notificationsService
                ->createNotification($unit->user_id, "Your Unit has been viewed from $user->name", "unit_viewed", "$unit->id");
        } else {
            UnitViews::create([
                "unit_id" => $unit->id
            ]);
            $notification = $this->notificationsService
                ->createNotification($unit->user_id, "Your Unit has been viewed from Guest", "unit_viewed", "$unit->id");
        }

        return ApiResponse::success(
            [
                'unit' => new UnitResource($unit),
                'user' => new UserResource($unit->user),
            ],
            'Unit',
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $this->authorize('update', $unit);
        $validatedData = $request->validated();
        $unit->update($validatedData);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $unit->addMedia($image)->toMediaCollection('images'); // Add images in media model for this unit
            }
        }

        return ApiResponse::success(
            [
                'unit' => new UnitResource($unit),
            ],
            'Unit Updated Successfully',
            200,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $this->authorize('delete', $unit);
        $unit->delete();

        return ApiResponse::success(
            [
                'unit' => new UnitResource($unit),
            ],
            'Unit Deleted Successfully',
            200,
        );
    }

    public function deleteImage($id)
    {
        $image = Media::find($id);

        if (!$image) {
            return ApiResponse::error('Image not found', 404);
        }

        if ($image->model_id != auth()->id()) {
            return ApiResponse::error(
                'User Can Not Delete This Image',
                403
            );
        }
        $image->delete();

        return ApiResponse::success(
            [
                'image' => $image,
            ],
            'Image Deleted Successfully',
            200
        );
    }
}
