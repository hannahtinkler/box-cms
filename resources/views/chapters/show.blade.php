@extends('layouts.master')

@section('content')

<h1>{{ $chapter->category->title }}</h1>

@include('partials.chapter_options')

<h2>{{ $chapter->title }}</h2>

<hr>

@if(session('message'))
    <p class="bg-success error-message"> {!! session('message') !!}</p>
@endif

@if($chapter->description)
    <p>{!! $chapter->description !!}</p>
@endif


<h2 class="top-offset-2">Chapter Pages:</h2>
@if($chapter->pages->isEmpty())
    <p class="italic">There are no pages for this chapter yet.</p>
@else
    <ul class="chapter-list m-t--sm">
        @foreach($chapter->pages as $page)
            <li>
                <h4><i class="glyphicon glyphicon-file"></i> <a href="/p/{{ $page->chapter->category->slug }}/{{ $page->chapter->slug }}/{{ $page->slug }}">{{ $page->title }}</a></h4>
                <p>{!! substr($page->description, 0, 500) . (strlen($page->description) > 500 ? '...' : null)!!}</p>
            </li>
        @endforeach
    </ul>
@endif

@stop


@section('scripts')

@stop
