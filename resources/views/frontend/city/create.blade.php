@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">Add City</h5>
                    <div class="card-body">
                        <form action="{{ route('cities.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter name" required value="{{ old('name') }}" />
                                <div class="invalid-feedback">Please enter name.</div>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select2Basic" class="form-label">City</label>
                                <select id="select2Basic" name="region_id" class="select2 form-select form-select-lg" data-allow-clear="true">
                                    <option value="">Select Region</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('region_id') == $city->id ? 'selected' : '' }}>
                                            {{ strtoupper(ucfirst($city->name)) }}
                                            @if($city->region)
                                                ({{ strtoupper(ucfirst($city->region->name)) }})
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
    </div>
@endsection
