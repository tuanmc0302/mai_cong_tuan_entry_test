@extends('layouts.app')

@section('title', 'Locations')

@section('breadcrumb')

    <span class="breadcrumb-item active">
        Locations
    </span>

@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center mb-3">
        <h5 class="mb-0">Locations</h5>
        <a href="{{ route('locations.create') }}" class="btn btn-primary ms-auto">Create</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success mx-3 my-3">{{ session('status') }}</div>
    @endif

    <table class="table" id="locations-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Lat</th>
                <th>Lng</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
    </table>
</div>

<form id="delete-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
    const esc = s => $('<div>').text(s ?? '').html();
    const badge = {active: 'bg-success', pending: 'bg-warning', suspended: 'bg-danger'};

    $('#locations-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: @json(route('api.locations.index')), type: 'GET' },
        order: [[0, 'asc']],
        columns: [
            { data: 'id' },
            { data: 'name',   render: d => esc(d) },
            { data: 'lat',    render: d => esc(d) },
            { data: 'lng',    render: d => esc(d) },
            { data: 'status', render: d => d
                ? '<span class="badge ' + (badge[d] || 'bg-secondary') + '">' + esc(d) + '</span>' : '' },
            { data: null, orderable: false, searchable: false, className: 'text-center',
              render: (d, t, row) =>
                '<a href="' + esc(row.edit_url) + '" class="btn btn-sm btn-primary me-1">Edit</a>' +
                '<button type="button" class="btn btn-sm btn-danger btn-delete" data-url="' + esc(row.delete_url) + '">Delete</button>'
            },
        ],
    });

    $(document).on('click', '.btn-delete', function () {
        if (! confirm('Are you sure you want to delete this location?')) return;
        const form = $('#delete-form');
        form.attr('action', $(this).data('url'));
        form.get(0).submit();
    });
});
</script>
@endpush
