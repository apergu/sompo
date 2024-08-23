@extends('layout.layout')

@section('style')
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('script')
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<script>
    $('#dateFrom').datepicker({
        uiLibrary: 'bootstrap5',
        format: 'yyyy-mm-dd'
    });

    $('#dateTo').datepicker({
        uiLibrary: 'bootstrap5',
        format: 'yyyy-mm-dd'
    })
</script>
@endsection

@section('content')
    <div class="pt-3 pb-2">
        <h3 style="font-weight: 600">Reports</h3>
    </div>

    <div class="row d-flex justify-content-between">
        <div class="col-md-4">
            @php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://'; @endphp
            {{-- <form action="{{ route('reports.index') }}" method="GET"> --}}
            <form action="{{ $protocol.$_SERVER['HTTP_HOST'] }}/reports" method="GET">
                <div class="col-md-12">
                    <input type="text" name="from_date" class="form-control" placeholder="From Date" value="{{ $start_date }}" id="dateFrom" />
                </div>
                <div class="col-md-12">
                    <input type="text" name="to_date" class="form-control" placeholder="To Date" value="{{ $end_date }}" id="dateTo" />
                </div>
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
                <a class="btn btn-sm btn-primary mt-auto" target="_blank" href="{{ route('reports.download') }}{{ $params }}">
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
                <th>Schedule Date</th>
                <th>Sent Date</th>
                <th>Customer Name</th>
                <th>Contact</th>
                <th>Content</th>
                <th>Reference ID</th>
                <th>Chargable</th>
                <th>DR Source</th>
                <th>Status</th>
                <th>Remark</th>
            </tr>

            @if (count($delivery_reports) > 0)
                @php
                    $no = 1;
                    if ($delivery_reports->currentPage() > 1) {
                        $no = ($delivery_reports->currentPage() - 1) * 10 + 1;
                    }
                @endphp
                @foreach ($delivery_reports as $val)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $val->BroadcastDate ?? '-' }}</td>
                        <td>{{ $val->DeliveryDateTime ?? '-' }}</td>
                        <td>{{ $val->ReceiverName ?? '-' }}</td>
                        <td>{{ $val->MobileNo ?? '-' }}</td>
                        <td>{{ $val->Message ?? '-' }}</td>
                        <td>{{ $val->deliveryReport->referenceid ?? '-' }}</td>
                        <td>{{ $val->deliveryReport->chargable ?? '-' }}</td>
                        <td>{{ $val->deliveryReport->drsource ?? '-' }}</td>
                        <td>{{ $val->deliveryReport->status ?? '-' }}</td>
                        <td>{{ $val->deliveryReport->description ?? '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="12" class="text-center">Data not found</td>
                </tr>
            @endif
        </table>

        <div class="d-flex justify-content-end">
            {{ $delivery_reports->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection