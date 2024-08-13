@extends('layout.layout')

@section('content')
    <div class="pt-3 pb-2">
        <h3 style="font-weight: 600; text-align: center; margin-bottom: 50px;">Register</h3>
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
                    <form action="{{ route('add.register') }}" method="POST" enctype="multipart/form-data"> @csrf
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label">Fullname</label>
                            <input type="text" name="fullname" class="form-control" />
                        </div>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label">Email</label>
                            <input type="text" name="email" class="form-control" />
                        </div>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" />
                        </div>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" />
                        </div>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" />
                        </div>
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign up</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">&nbsp;</div>
    </div>
@endsection