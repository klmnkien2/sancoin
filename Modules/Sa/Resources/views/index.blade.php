@extends('sa::layouts.main')
@section('title')
    {{trans('admin.label.dashboard')}}
@endsection
@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('sa.name') !!}
    </p>
@stop
