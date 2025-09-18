<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\xissobotoy;
use Illuminate\Support\Facades\Auth;
use App\Models\ktovar1;
use App\Models\filial;
use App\Models\lavozim;
use App\Models\NarxChange;

class TovarlarSotilmaganOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $filial = filial::where('status', 'Актив')->get();
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
            return view('tovarlar.OfficeSotilmaganTovarlar', ['xis_oyi' => $xis_oyi, 'filial' => $filial, 'filial_name' => $filial_name, 'lavozim_name' => $lavozim_name]);
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

        echo'
        <table class="table table-bordered table-responsive-sm text-center align-middle ">
            <thead>
                <tr class="text-bold text-primary">
                    <th>ID</th>
                    <th>Куни</th>
                    <th>Модел ID</th>
                    <th>Тури</th>
                    <th>Бренди</th>
                    <th>Модели</th>
                    <th>Штрих-раками</th>
                    <th>Кирим нархи</th>
                    <th>Валюта</th>
                    <th>Натсенка</th>
                    <th>Транспорт</th>
                    <th>Сотув нархи</th>
                    <th>Шарт. ID</th>
                    <th>Холати</th>
                    <th>Чиким сана</th>
                    <th>Таъминотчи</th>
                    <th>Қайтариш</th>
                </tr>
            </thead>
            <tbody id="tab1">';
            
                $ktovar = new ktovar1($request->filial);
                $model=$ktovar->where('status', 'Сотилмаган')->orderBy('id', 'desc')->get();
                foreach ($model as $mode){
                    $valyuta = $mode->valyuta->valyuta_narhi;
                    $kirim_narxi = $mode->snarhi;
                    $natsenka = $mode->tur->natsenka_id;
                    $trans_xarajat = $mode->tur->transport_id;
                    
                    echo'
                    <tr>
                        <td>' . $mode->id . '</td>
                        <td>' . date('d.m.Y', strtotime($mode->kun)) . '</td>
                        <td>' . $mode->tmodel_id . '</td>
                        <td>' . $mode->tur->tur_name . '</td>
                        <td>' . $mode->brend->brend_name . '</td>
                        <td>' . $mode->tmodel->model_name . '</td>
                        <td>' . $mode->shtrix_kod . '</td>
                        <td>' . $mode->snarhi . '</td>
                        <td>' . $mode->valyuta->valyuta__nomi . '</td>
                        <td>' . $natsenka . '</td>
                        <td>' . $trans_xarajat . '</td>
                        <td>' . round($kirim_narxi * $valyuta * (100 + $natsenka + $trans_xarajat) / 100, -3)  . '</td>
                        <td>' . $mode->shatnomaid . '</td>
                        <td>' . $mode->status . '</td>
                        <td>' . $mode->ch_kun . '</td>
                        <td>' . $mode->pastavshik->pastav_name . '</td>
                        <td>';
                        if (Auth::user()->lavozim_id == 1 && $mode->status == "Сотилмаган" ){
                            echo'
                                <a onclick="tovarudalit(' . $mode->id . ')" class="btn btn-outline-danger btn-xxs">Қайтариш</a>';
                        }
                        echo'
                        </td>
                    </tr>';
                }
                echo '
            </tbody>
        </table>';

        return;
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
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $id = $request->id;
            $ktovar = new ktovar1($request->filial);
            $ktovar->where('id', $id)->where('status', 'Сотилмаган')->update([
                'status' => 'Удалит',
                'u_user_id' => Auth::user()->id,
                'u_kun' => now(),
            ]);
            return response()->json(['message' => 'Товар омбордан қайтарилди.'], 200);
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
