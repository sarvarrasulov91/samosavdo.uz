<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\valyuta;
use App\Models\tmodel;
use App\Models\brend;
use App\Models\user;
use App\Models\mijozlar;
use App\Models\shartnoma1;

class dashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $valyuta = valyuta::where('id',2)->value('valyuta_narhi');
        $tmodel = tmodel::count('id');
        $brend = brend::count('id');
        $userlar = user::count('id');
        $mijozsoni = Mijozlar::where('filial_id', Auth::user()->filial_id)->count('id');
        $shartnomasoni = shartnoma1::count('id');
        $mijozlaroy = mijozlar::whereMonth('t_sana',date('m'))->where('status', 1)->where('filial_id', Auth::user()->filial_id)->orderBy('t_sana', 'asc')->get();
        $mijozlarkun = mijozlar::whereMonth('t_sana',date('m'))->whereDay('t_sana',date('d'))->where('status', 1)->where('filial_id', Auth::user()->filial_id)->orderBy('t_sana', 'asc')->get();

        $mfy = Mijozlar::
        with(['mfy'=>function ($query) {
            $query->select('id','tuman_id','name_uz');
        }])->
        select('mfy_id', DB::raw('COUNT(mfy_id) as soni'))
        ->where('status', 1)
        ->where('filial_id', Auth::user()->filial_id)
        ->groupBy('mfy_id')
        ->get();

        $mtashrif = shartnoma1::
        with(['tashrif'=>function ($query) {
            $query->select('id','tashrif_name');
        }])->
        select('tashrif_id', DB::raw('COUNT(tashrif_id) as soni'))
        ->groupBy('tashrif_id')
        ->get();

        return view('dashboard',[
            'valyuta' => $valyuta,
            'tmodel' => $tmodel,
            'brend' => $brend,
            'userlar' => $userlar,
            'mijozsoni' => $mijozsoni,
            'mfy' => $mfy,
            'shartnomasoni' => $shartnomasoni,
            'mtashrif' => $mtashrif,
            'mijozlarkun' => $mijozlarkun
        ]);
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
