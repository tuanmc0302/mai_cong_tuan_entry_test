<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" value="{{ old('name', $location?->name) }}"
           class="form-control @error('name') is-invalid @enderror">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Latitude</label>
        <input type="text" name="lat" value="{{ old('lat', $location?->lat) }}"
               class="form-control @error('lat') is-invalid @enderror">
        @error('lat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Longitude</label>
        <input type="text" name="lng" value="{{ old('lng', $location?->lng) }}"
               class="form-control @error('lng') is-invalid @enderror">
        @error('lng') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select @error('status') is-invalid @enderror">
        <option value="">-- Chọn --</option>
        @foreach (\App\Models\Location::STATUSES as $s)
            <option value="{{ $s }}" @selected(old('status', $location?->status) === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="text-end">
    <a href="{{ route('locations.index') }}" class="btn btn-light">Cancel</a>
    <button type="submit" class="btn btn-primary">Save</button>
</div>