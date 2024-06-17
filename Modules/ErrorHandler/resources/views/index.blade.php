@extends('errorhandler::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('errorhandler.name') !!}</p>
@endsection
