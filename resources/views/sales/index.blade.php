@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Product Name</h6>
                                    <p class="card-text">Rp. 10.000</p>
                                    <button class="btn btn-primary btn-sm w-100">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Shopping Cart
                </div>
                <div class="card-body">
                    <div class="cart-items mb-3">
                        
                    </div>
                    <div class="cart-total border-top pt-3">
                        <h5>Total: Rp. 0</h5>
                        <button class="btn btn-success w-100 mt-2">Process Payment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
