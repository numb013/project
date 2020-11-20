<?php

namespace App\Http\Controllers;

use App\Cast;
use Illuminate\Http\Request;

class CastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('/cast_admin/cast/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castAdminCreate()
    {
        return view('/cast_admin/cast/detail');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castAdminEdit()
    {
        return view('/cast_admin/cast/detail');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castAdminDetail()
    {
        return view('/cast_admin/cast/detail');
    }
}
