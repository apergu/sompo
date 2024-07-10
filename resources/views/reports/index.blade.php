@extends('layout.layout')

@section('content')
    <div class="pt-3 pb-2">
        <h3 style="font-weight: 600">Reports</h3>
    </div>

    <div class="row d-flex justify-content-between">
        <div class="col-md-4">
            <form action="{{ route('reports.index') }}" method="GET">
                <div class="pt-2 pb-4 input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" />
                    <div class="input-group-append">
                        <button class="btn btn-secondary" style="border-radius: 0 5px 5px 0">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-1">
            <div class="text-end pt-2">
                <a class="btn btn-sm btn-primary mt-auto" target="_blank" href="{{ route('reports.download') }}">
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
                <th>Transaction ID</th>
                <th>Reference ID</th>
                <th>Chargable</th>
                <th>DR Source</th>
                <th>Status</th>
                <th>Description</th>
                <th>Created</th>
            </tr>

            @if (count($delivery_reports) > 0)
                @foreach ($delivery_reports as $val)
                    <tr>
                        <td>{{ $val->id }}</td>
                        <td>{{ $val->transid }}</td>
                        <td>{{ $val->referenceid }}</td>
                        <td>{{ $val->chargable }}</td>
                        <td>{{ $val->drsource }}</td>
                        <td>{{ $val->status }}</td>
                        <td>{{ $val->description }}</td>
                        <td>{{ $val->created_at }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">Data not found</td>
                </tr>
            @endif
        </table>

        <div class="d-flex justify-content-end">
            {{ $delivery_reports->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection