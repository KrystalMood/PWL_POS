<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', "Welcome"]
        ];
        $activeMenu = 'dashboard';

        $userCount = UserModel::count();
        $barangCount = BarangModel::count();
        $kategoriCount = KategoriModel::count();

        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'userCount' => $userCount,
            'barangCount' => $barangCount,
            'kategoriCount' => $kategoriCount
        ]);
    }
}
