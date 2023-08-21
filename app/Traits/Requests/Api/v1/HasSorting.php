<?php

namespace App\Traits\Requests\Api\v1;

use Illuminate\Http\Request;

trait HasSorting
{
    /**
     * Get pagination parameters for the request.
     * 
     * @return array
     */
    protected static function getSortingParams(Request $request)
    {
        $sort_column = null;
        $sort_direction = null;
        $random_sort_seed = null;
        $random_sort = !empty($request->input('random_sort_seed'));

        if ($random_sort) {
          $random_sort_seed = $request->input('random_sort_seed');
        } else {
          $sort_column = !empty($request->input('sort_column'))
            ? $request->input('sort_column')
            : 'created_at';

          $sort_direction = !empty($request->input('sort_direction'))
            ? $request->input('sort_direction')
            : 'DESC';
        }

        $limit = !empty($request->input('limit')) ? $request->input('limit') : 10;

        return [
          'limit' => $limit,
          'sort_column' => $sort_column,
          'sort_direction' => $sort_direction,
          'random_sort_seed' => $random_sort_seed
        ];
    }

    /**
     * Sort collection randomely using modulus of record field and a user provided sort seed 
     * 
     * @return collection
     */    
    protected static function sortRandomely($collection, $random_sort_seed, $sort_field = 'created_at')
    {
      return $collection->orderByRaw("UNIX_TIMESTAMP($sort_field) % $random_sort_seed");
    }
}
