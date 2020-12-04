@extends('layouts.app')
@section('content')
<div class="container-lg">
    <h1 class="mt-5 mb-3">{{ $domain->name }}</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-nowrap">
            <tbody>
                <tr>
                    <td>id</td>
                    <td>{{ $domain->id }}</td>
                </tr>
                <tr>
                    <td>name</td>
                    <td>{{ $domain->name }}</td>
                </tr>
                <tr>
                    <td>created_at</td>
                    <td>{{ $domain->created_at }}</td>
                </tr>
                <tr>
                    <td>updated_at</td>
                    <td>{{ $domain->updated_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <h2 class="mt-5 mb-3">Checks</h2>
    <form method="post" action="{{route('domains.checks.store', $domain->id)}}">
        @csrf       
    <input type="submit" class="btn btn-primary" value="Run check">
    </form>
    <table class="table table-bordered table-hover text-wrap">
        <tbody>
            <tr>
                <th>Id</th>
                <th>Status Code</th>
                <th>h1</th>
                <th>Keywords</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
            @foreach ($domainChecks as $check)
            <tr>
                <td>{{$check->id}}</td>
                <td>{{$check->status_code}}</td>
                <td>{{Str::limit($check->h1, 60)}}</td>
                <td>{{Str::limit($check->keywords, 40)}}</td>
                <td>{{Str::limit($check->description, 120)}}</td>
                <td>{{$check->created_at}}</td>   
            @endforeach
        </tbody>
    </table>
</div>
@endsection