<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Support\Collection;

class LocationRepository extends BaseRepository
{
    public function model(): string
    {
        return Location::class;
    }

    /**
     * Query for DataTables server-side.
     * @return array{0:int,1:int,2:Collection}  [recordsTotal, recordsFiltered, rows]
     */
    public function datatable(string $search, string $orderColumn, string $orderDir, int $start, int $length): array
    {
        $total = $this->model->newQuery()->count();

        $query = $this->model->newQuery();

        if ($search !== '') {
            $like = '%' . addcslashes($search, '%_\\') . '%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('lat', 'like', $like)
                  ->orWhere('lng', 'like', $like)
                  ->orWhere('status', 'like', $like);
            });
        }

        $filtered = (clone $query)->count();

        // lat/lng are stored as varchar, so cast them to numbers for correct sorting
        if (in_array($orderColumn, ['lat', 'lng'], true)) {
            $query->orderByRaw("CAST({$orderColumn} AS DECIMAL(10,7)) {$orderDir}");
        } else {
            $query->orderBy($orderColumn, $orderDir);
        }

        if ($length > 0) {
            $query->skip($start)->take($length);
        }

        return [$total, $filtered, $query->get()];
    }
}