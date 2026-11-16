<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(private LocationRepository $locationRepository)
    {
    }

    public function index()
    {
        return view('locations.index');
    }

    /** Data source API for DataTables (server-side) */
    public function datatable(Request $request): JsonResponse
    {
        $columns = [0 => 'id', 1 => 'name', 2 => 'lat', 3 => 'lng', 4 => 'status'];

        $column = $columns[(int) $request->input('order.0.column', 0)] ?? 'id';
        $dir    = $request->input('order.0.dir') === 'desc' ? 'desc' : 'asc';

        [$total, $filtered, $rows] = $this->locationRepository->datatable(
            trim((string) $request->input('search.value', '')),
            $column,
            $dir,
            max(0, (int) $request->input('start', 0)),
            (int) $request->input('length', 10),
        );

        return response()->json([
            'draw'            => (int) $request->input('draw'),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $rows->map(fn (Location $l) => [
                'id'         => $l->id,
                'name'       => $l->name,
                'lat'        => $l->lat,
                'lng'        => $l->lng,
                'status'     => $l->status,
                'edit_url'   => route('locations.edit', $l),
                'delete_url' => route('locations.destroy', $l),
            ])->values(),
        ]);
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(LocationRequest $request)
    {
        $this->locationRepository->create($request->validated());

        return redirect()->route('locations.index')
            ->with('status', 'Tạo location thành công.');
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(LocationRequest $request, Location $location)
    {
        $this->locationRepository->update($location->id, $request->validated());

        return redirect()->route('locations.index')
            ->with('status', 'Cập nhật location thành công.');
    }

    public function destroy(Location $location)
    {
        $this->locationRepository->delete($location->id);

        return redirect()->route('locations.index')
            ->with('status', 'Đã xoá location.');
    }
}