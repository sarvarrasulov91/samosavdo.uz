<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\ipnazorati;
use App\Models\filial;
use Illuminate\Support\Facades\Auth;


class IPNazoratiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ipnazoratioy = ipnazorati::whereMonth('created_at',date('m'))->where('status','Актив')->orderBy('id', 'desc')->get();
        $ipnazoratikun = ipnazorati::whereMonth('created_at',date('m'))->whereDay('created_at',date('d'))->where('status','Актив')->orderBy('id', 'desc')->get();
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        return view('qushmchalar.IPNazorati', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'ipnazoratioy' => $ipnazoratioy,'ipnazoratikun'=>$ipnazoratikun ]);
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
