@extends('layouts.template')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline shadow-lg" style="border-top: 3px solid #007bff; background: linear-gradient(to right, #f8f9fa, #ffffff);">
                <div class="card-body box-profile py-3">
                    <div class="row">
                        <div class="col-md-3 text-center d-flex align-items-center justify-content-center">
                            <img class="profile-user-img img-fluid img-circle shadow" src="{{ asset('adminlte/dist/img/user4-128x128.jpg') }}" alt="User profile picture" style="width: 150px; height: 150px; border: 3px solid #fff;">
                        </div>
                        
                        <div class="col-md-9">
                            <h3 class="profile-username mb-1">Admin POS</h3>
                            <p class="text-muted mb-3"><i class="fas fa-user-shield mr-1"></i> Administrator Sistem</p>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <strong><i class="fas fa-user text-primary mr-1"></i> Username</strong>
                                    <p class="text-muted mb-0">{{ auth()->check() ? auth()->user()->username : 'Tidak tersedia' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong><i class="fas fa-calendar text-primary mr-1"></i> Bergabung</strong>
                                    <p class="text-muted mb-0">{{ date('d F Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <a href="#" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="fas fa-user-edit mr-1"></i> Edit Profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h3 class="card-title"><i class="fas fa-tachometer-alt mr-2"></i>Halo, apakabar!!!</h3>
                    <div class="card-tools"></div>
                </div>
                <div class="card-body">
                    <h5 class="font-weight-bold text-primary mb-3">Selamat datang di Dashboard POS</h5>
                    <p class="text-muted mb-4">Selamat datang semua, ini adalah halaman utama dari aplikasi ini.</p>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="small-box bg-info shadow-sm">
                                <div class="inner">
                                    <h3>150</h3>
                                    <p>Produk</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="#" class="small-box-footer">Info Lebih <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-success shadow-sm">
                                <div class="inner">
                                    <h3>25</h3>
                                    <p>Kategori</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <a href="#" class="small-box-footer">Info Lebih <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-warning shadow-sm">
                                <div class="inner">
                                    <h3>12</h3>
                                    <p>Supplier</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <a href="#" class="small-box-footer">Info Lebih <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection