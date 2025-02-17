@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>{{ $title }}</h1>
        <div class="row mt-4">
            @forelse($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name ?? 'Product Name' }}</h5>
                            <p class="card-text">{{ $product->description ?? 'Product description' }}</p>
                            <p class="card-text"><strong>Price: </strong>${{ $product->price ?? '0.00' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p>No products available in this category.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
