<?php
// GenericFilterService.php
namespace App\Services;

// use Illuminate\Database\Eloquent\Model;
use App\Models;
use App\Helpers\ApiResponse;

class FilterService
{
    public function filter($model, $relationships, $filters, $sortField, $sortDirection = 'asc')
    {
        $query = $model::with($relationships);

        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    // Handle range filters
                    $query->whereBetween($field, $value);
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
