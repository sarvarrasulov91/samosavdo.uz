<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\ktovar1;
use App\Models\mijozlar;
use App\Models\filial;
use App\Models\lavozim;
use App\Models\shartnoma1;
use App\Models\User;
use App\Models\naqdsavdo1;
use App\Models\savdobonus1;

class ChiqimTovarOmborController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        
        if((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив'){
            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();
        }else{
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }
        return view('tovarlar.chiqimtovarombor', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'filial' => $filial]);
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
                
                $i=1;
                $tovarlar = new ktovar1($request->filial);
                $model=$tovarlar->whereDate('ch_kun', '>=', $boshkun)->whereDate('ch_kun', '<=', $yakunkun)->where('status', '!=', 'Актив')->where('status', '!=', 'Удалит')->orderBy('ch_kun', 'desc')->get();
    
                foreach ($model as $mode){
                     $ch_user_fio=User::where('id', $mode->ch_user_id)->value('name');
                  
                    echo'
                    <tr>
                        <td>' . $i++ . '</td>
                        <td>' . date('d.m.Y', strtotime($mode->ch_kun)) . '</td>
                        <td>' . $mode->tmodel_id . '</td>
                        <td>' . $mode->tur->tur_name . '</td>
                        <td>' . $mode->brend->brend_name . '</td>
                        <td>' . $mode->tmodel->model_name . '</td>
                        <td>' . $mode->shtrix_kod . '</td>
                        <td>' . round(($mode->snarhi * $mode->valyuta->valyuta_narhi * $mode->tur->transport_id) / 100 + ($mode->snarhi * $mode->valyuta->valyuta_narhi * $mode->brend->natsenka_id) / 100 + $mode->snarhi * $mode->valyuta->valyuta_narhi, -3)  . '</td>
                        <td>' . $mode->status . '</td>
                        <td>' . $mode->shatnomaid . '</td>

                        <td>' . $ch_user_fio . '</td>
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
