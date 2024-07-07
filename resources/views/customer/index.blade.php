@extends('layout.layout')

@section('content')
    <div class="pt-3 pb-2">
        <h3 style="font-weight: 600">Customer</h1>
    </div>

    <div class="row d-flex justify-content-between">
        <div class="col-md-4">
            <form action="{{ route('customer.index') }}" method="GET">
                <div class="pt-2 pb-4 input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search"
                        value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" style="border-radius: 0 5px 5px 0">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-1">
            <div class="text-end pt-2">
                <a class="btn btn-sm btn-primary mt-auto" target="_blank" href="{{ route('customer.download') }}">
                    <span class="material-symbols-outlined">
                        description
                    </span>
                </a>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-striped table-bordered">
            <tr>
                <th>No</th>
                <th>Zendesk ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created</th>
            </tr>

            @foreach ($customer as $cst)
                <tr>
                    <td>{{ $cst->id }}</td>
                    <td>{{ $cst->zd_id }}</td>
                    <td>{{ $cst->name }}</td>
                    <td>{{ $cst->email }}</td>
                    <td>
                        {{ $cst->created_at->format('d M Y H:i:s') }}
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="d-flex justify-content-end">
            {{ $customer->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
