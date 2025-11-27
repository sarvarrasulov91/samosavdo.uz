<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KirimTovar;
use App\Models\filial;


class ChiqimTovarOmborController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->filial_id == 10){
            $filial = filial::where('status', 'Актив')->where('id', '!=', '10')->get();
        }else{
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }
        return view('tovarlar.chiqimtovarombor', ['filial' => $filial]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $boshkun = $request->boshkun;
        $yakunkun = $request->yakunkun;

        echo'
        <table class="table table-bordered table-responsive-sm text-center align-middle ">
            <thead>
                <tr class="text-bold text-primary">
                    <th>ID</th>
                    <th>Куни</th>
                    <th>Модел ИД</th>
                    <th>Тури</th>
                    <th>Бренди</th>
                    <th>Модели</th>
                    <th>Штрих-раками</th>
                    <th>Сотув нархи</th>
                    <th>Холати</th>
                    <th>Раками</th>
                    <th>Масъул ходим</th>
                </tr>
            </thead>
            <tbody id="tab1">';

                $i = 1;

                $model = KirimTovar::whereDate('ch_kun', '>=', $boshkun)
                    ->whereDate('ch_kun', '<=', $yakunkun)
                    ->whereNotIn('status', ['Актив', 'Удалит'])
                    ->where('filial_id', $request->filial)
                    ->orderBy('ch_kun', 'desc')
                    ->get();

                foreach ($model as $mode){

                    $sotuvNarx = $mode->snarhi * $mode->valyuta->valyuta_narhi * (100 + $mode->tur->transport_id + $mode->tur->natsenka_id) / 100;
                    echo'
                    <tr>
                        <td>' . $i++ . '</td>
                        <td>' . date('d.m.Y', strtotime($mode->ch_kun)) . '</td>
                        <td>' . $mode->tmodel_id . '</td>
                        <td>' . $mode->tur->tur_name . '</td>
                        <td>' . $mode->brend->brend_name . '</td>
                        <td>' . $mode->tmodel->model_name . '</td>
                        <td>' . $mode->shtrix_kod . '</td>
                        <td>' . number_format(round($sotuvNarx, -3), 0, ",", " ")  . '</td>
                        <td>' . $mode->status . '</td>
                        <td>' . $mode->shid . '</td>
                        <td>' . $mode->user->name . '</td>
                    </tr>
                    ';
                }

                echo'
            </tbody>
        </table>
    ';
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
