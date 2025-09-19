<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ktovar1;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;
use App\Models\tmodel;


class TovarlarNarxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $model = ktovar1::where('status', 'Сотилмаган')->orderBy('id', 'desc')->get();

        return view('tovarlar.narx', ['model' => $model]);
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
        $ttovarlar = $request->belginatija;
        if ($ttovarlar == null) {
            return redirect()->route('narx.index');
        } else {

            $foiz = xissobotoy::latest('id')->value('foiz');

            $foiz = $foiz/12;
            $array = [];

            foreach ($ttovarlar as $ttovarla) {
                $model = ktovar1::where('id',$ttovarla)->get();
                foreach ($model as $mode) {

                    $trhar = ($mode->snarhi * $mode->tur->transport_id) / 100;
                    $trharnatsenka = ($mode->snarhi * $mode->tur->natsenka_id) / 100;
                    $jamsotnarx = round(($mode->snarhi + $trhar + $trharnatsenka) *  $mode->valyuta->valyuta_narhi, -3);
                    $oldindantulov = $jamsotnarx / 10;
                    if ($mode->tur_id == 1 || $mode->tur_id == 46 || $mode->tur_id == 47){
                        $oldindantulov = $jamsotnarx / 5;
                    }
                    $sotuv_narx3oy = round($jamsotnarx*(1+$foiz*0.03)-$oldindantulov, -2);
                    $sotuv_narx6oy = round($jamsotnarx*(1+$foiz*0.06)-$oldindantulov, -2);
                    $sotuv_narx9oy = round($jamsotnarx*(1+$foiz*0.09)-$oldindantulov, -2);
                    $sotuv_narx12oy = round($jamsotnarx*(1+$foiz*0.12)-$oldindantulov, -2);
                }

                    $arrayitem = [
                        "id" => $mode->id,
                        "tmodel_id" => $mode->tmodel_id,
                        "tur_name" => $mode->tur->tur_name,
                        "brend_name" => $mode->brend->brend_name,
                        "model_name" => $mode->tmodel->model_name,
                        "jamsotnarx" => $jamsotnarx,
                        "oldindantulov" => $oldindantulov,
                        "sotuv_narx3oy" => $sotuv_narx3oy,
                        "sotuv_narx6oy" => $sotuv_narx6oy,
                        "sotuv_narx9oy" => $sotuv_narx9oy,
                        "sotuv_narx12oy" => $sotuv_narx12oy,
                        "kun" => $mode->kun,
                        "shtrix_kod" => $mode->shtrix_kod,
                    ];

                array_push($array, $arrayitem);
            }

            return view('tovarlar.narxpechat', ['arrayid' => $array]);
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
