<?php

namespace App\Http\Controllers\API\Unit;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Http\Responses\ApiResponse;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        $validatedData['user_id'] = $userId;
        $unit = Unit::create($validatedData); // Crete New Unit
        $unit->addMediaFromRequest('main_image')->toMediaCollection('unit-Main-image');
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $unit->addMedia($image)->toMediaCollection('images'); // Add images in media model for this unit
            }
        }
        
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
        return ApiResponse::success(
            [
                'unit' => new UnitResource($unit),
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
