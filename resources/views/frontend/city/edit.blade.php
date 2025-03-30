@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">Edit City</h5>
                    <div class="card-body">
                        <form action="{{ route('cities.update', $city->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter name" required value="{{ old('name', $city->name) }}" />
                                <div class="invalid-feedback">Please enter name.</div>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select2Basic" class="form-label">City</label>
                                <select id="select2Basic" name="region_id" class="select2 form-select form-select-lg" data-allow-clear="true">
                                    <option value="">Select Region</option>
                                    @foreach($cities as $city_item)
                                        <option value="{{ $city_item->id }}" {{ old('region_id', $city->region_id) == $city_item->id ? 'selected' : '' }}>
                                            {{ strtoupper(ucfirst($city_item->name)) }}
                                            @if($city_item->region)
                                                ({{ strtoupper(ucfirst($city_item->region->name)) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('city_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('cities.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection