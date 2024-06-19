@extends('layout.layout')

@section('content')
    <table>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
        </tr>

        @foreach ($customer as $cst)
            <tr>
                <td>{{ $cst->id }}</td>
                <td>{{ $cst->name }}</td>
                <td>{{ $cst->email }}</td>
            </tr>
        @endforeach
    </table>
@endsection
