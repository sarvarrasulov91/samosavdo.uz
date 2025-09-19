<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ktovar1;


class TovarlarJamiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {

            $model2 = ktovar1::where('status', 'Сотилмаган')->orderBy('id', 'desc')->get();

            return view('tovarlar.jamitovarlar', [
                'model2'=>$model2
                ]);
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }
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

        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            $ttovarlar = $request->belginatija;
            if ($ttovarlar == null) {
                return redirect()->route('barcod.index');
            } else {

                $array = [];

                foreach ($ttovarlar as $ttovarla) {

                    $model = ktovar1::where('id', $ttovarla)->get();

                    foreach ($model as $mode) {

                        $arrayitem = [
                            "id" => $mode->id,
                            "tmodel_id" => $mode->tmodel_id,
                            "tur_name" => $mode->tur->tur_name,
                            "brend_name" => $mode->brend->brend_name,
                            "model_name" => $mode->tmodel->model_name,
                            "kun" => date('d.m.Y', strtotime($mode->kun)),
                            "shtrix_kod" => $mode->shtrix_kod,
                        ];

                        array_push($array, $arrayitem);
                    }
                }

                return view('tovarlar.barcodpechat', ['arrayid' => $array]);
            }
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }

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
