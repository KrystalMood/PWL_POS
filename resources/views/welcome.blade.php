@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 text-center mb-5">
        <h1>Welcome to POS System</h1>
        <p class="lead">Choose a category to browse products</p>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="card-title">Food & Beverage</h3>
                <p class="card-text">Explore our food and beverage products</p>
                <a href="{{ route('category.food-beverage') }}" class="btn btn-primary">Browse Products</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="card-title">Beauty & Health</h3>
                <p class="card-text">Discover beauty and health items</p>
                <a href="{{ route('category.beauty-health') }}" class="btn btn-primary">Browse Products</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="card-title">Home Care</h3>
                <p class="card-text">Find home care products</p>
                <a href="{{ route('category.home-care') }}" class="btn btn-primary">Browse Products</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h3 class="card-title">Baby & Kid</h3>
                <p class="card-text">Shop for baby and kid products</p>
                <a href="{{ route('category.baby-kid') }}" class="btn btn-primary">Browse Products</a>
            </div>
        </div>
    </div>
</div>
@endsection
