<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\mijozlar;
use App\Models\filial;
use Illuminate\Support\Facades\Auth;

class TugilganKunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mijozlaroy = mijozlar::whereMonth('t_sana',date('m'))->where('status', 1)->where('filial_id', Auth::user()->filial_id)->orderBy('t_sana', 'asc')->get();
        $mijozlarkun = mijozlar::whereMonth('t_sana',date('m'))->whereDay('t_sana',date('d'))->where('status', 1)->where('filial_id', Auth::user()->filial_id)->orderBy('t_sana', 'asc')->get();
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        return view('mijoz.TugilganKun', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'mijozlaroy' => $mijozlaroy,'mijozlarkun'=>$mijozlarkun ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
