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
               
                @if($domains ?? '')
                    @foreach($domains ?? '' as $domain)
                    <tr>
                        <td>{{ $domain->id }}</td>
                        <td><a href="{{route('domains.show', $domain->id)}}">{{ $domain->name }}</a></td>
                        <td>{{ $domain->updated_at }} </td>
                        <td></td>
                    </tr>    
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection