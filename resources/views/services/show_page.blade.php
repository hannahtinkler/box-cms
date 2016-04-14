@extends('layouts.master')

@section('content')

<h1>{{ $page->chapter->title }}</h1>
<h2>{{ $page->title }}</h2>

<hr>

<table>
    <thead>
        <tr>
            <td>Service Name</td>
            <td>Area</td>
            <td>Service ID</td>
            <td>Type</td>
            <td>Server Location</td>
        </tr>
    </thead>
    <tbody>
        @foreach($services as $service)
            <tr id="{{ $service->id }}" {!! $service->id == Request::segment(4) ? ' class="highlight-row"' : null !!}>
                <td>{{ ucwords($service->name) }}</td>
                <td>{{ ucwords($service->area) }}</td>
                <td>{{ $service->service_id }}</td>
                <td>{{ ucwords($service->type) }}</td>
                <td>{{ ucwords($service->server->location . ' ' . $service->server->node_number) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@stop


@section('scripts')
<script>
    $(window).load(function() {
        if(window.location.hash) {
            var offset = $('{{ "#" . Request::segment(4) }}').offset().top - 100;
             $("html,body").animate({scrollTop: offset}, 300);
         }
    });
</script>
@stop
