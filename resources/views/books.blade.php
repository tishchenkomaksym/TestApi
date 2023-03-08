@extends('layouts.app')

@section('content')
    <div class="container">
        {{ $products }}
        <books-component :productsdata="{{ $products }}"></books-component>
    </div>
@endsection

