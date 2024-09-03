@extends('layout.layout')

@section('content')
    <div class="pt-3 pb-2">
        <h3 style="font-weight: 600; text-align: center; margin-bottom: 50px;">Login</h3>
    </div>

    <div class="row d-flex justify-content-between">
        <div class="col-md-4">&nbsp;</div>
        <div class="col-md-4">
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="alert-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    @php
                        $uri = "";
                        if ($_SERVER['SERVER_NAME'] === env("APP_IP_LOCALHOST")) {
                            $uri = "http://".env("APP_IP_LOCALHOST").":8000";
                        } else if ($_SERVER['SERVER_NAME'] === env("APP_IP_LOCALSOMPO")) {
                            $uri = "http://".env("APP_IP_LOCALSOMPO").":9005";
                        } else {
                            $uri = env("APP_IP_DOMAIN");
                        }
                    @endphp
                    {{-- <form action="https://smsgw.sompo.co.id/check-login" method="POST" enctype="multipart/form-data"> @csrf --}}
                    <form action="{{ $uri }}/check-login" method="POST" enctype="multipart/form-data"> @csrf
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" />
                        </div>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" />
                        </div>
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">&nbsp;</div>
    </div>
@endsection