<?php

namespace App\Services;

// use Illuminate\Database\Eloquent\Model;
use App\Models;
use App\Helpers\ApiResponse;

class FilterService
{
    public function filter($model, $filters, $sortField = 'id', $sortDirection = 'desc', $relationships = null)
    {
        // $query = $model::with($relationships);

        $query = $model::query();

        if ($relationships !== null) {
            $query->with($relationships);
        }

        // foreach ($filters as $field => $value) {
        //     if (!empty($value)) {
        //         if (is_array($value)) 
        //         {
        //             // Handle range filters
        //             $query->whereIn($field, $value);
        //         } 
        //         elseif((is_array($value) && count($value) == 2))
        //         {
        //             $query->whereBetween($field, [$value[1], $value[0]]);
        //         }
        //         else 
        //         {
        //             // Handle single value filters
        //             $query->where($field, $value);
        //         }
        //     }
        // }

        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                if ($field === 'price' || $field === 'area') {
                    // Handle price or area range filter
                    if (is_array($value) && count($value) == 2) {
                        $query->whereBetween($field, [$value[1], $value[0]]);
                    } else {
                        // Handle single value filters for price or area
                        $query->where($field, $value);
                    }
                } elseif (is_array($value)) {
                    // Handle other array filters
                    $query->whereIn($field, $value);
                } else {
                    // Handle single value filters
                    $query->where($field, $value);
                }
            }
        }
        

        //sorting
        if ($sortField && in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $query->orderBy($sortField, $sortDirection);
        }

        $results = $query->get();

        return $results;
    }
}
