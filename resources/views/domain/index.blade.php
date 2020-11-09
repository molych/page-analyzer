@extends('layouts.app')

@section('content')
<div class="container-lg">
    <h1 class="mt-5 mb-3">Domains</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-nowrap">
            <tbody>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Last check</th>
                    <th>Status Code</th>
                </tr>
                <tr>
                    @foreach($domains as $domain)
                        <td>{{ $domain->id }}</td>
                        <td><a href="/domains/{{$domain->id}}">{{ $domain->name }}</a></td>
                        <td>{{ $domain->update_at }} </td>
                        <td>200</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection