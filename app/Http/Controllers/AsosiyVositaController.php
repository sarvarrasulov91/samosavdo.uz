<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\ktovar1;
use App\Models\lavozim;
use App\Models\filial;
use App\Models\asosiyvositalar;

use Illuminate\Support\Facades\DB;

class AsosiyVositaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {
            
            return view('tovarlar.AsosiyVosita');
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
        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {
            echo'
                <table class="table table-bordered table-responsive-sm text-center align-middle ">
                    <thead>
                        <tr class="text-bold text-primary">
                            <th>№</th>
                            <th>Куни</th>
                            <th>Модел ИД</th>
                            <th>Тури</th>
                            <th>Бренди</th>
                            <th>Модели</th>
                            <th>Штрих-раками</th>
                            <th>Холати</th>
                            <th>Филиал</th>
                            <th>Таъминотчи</th>
                        </tr>
                    </thead>
                    <tbody id="tab1">';

                    
                    if (Auth::user()->filial_id == 10){
                        $model = asosiyvositalar::where('status', 'Актив')->orderBy('id', 'desc')->get();
                    }else{
                        $model = asosiyvositalar::where('filial_id', Auth::user()->filial_id)->where('status', 'Актив')->orderBy('id', 'desc')->get();
                        
                    }
                        foreach ($model as $mode){
                            echo'
                            <tr>
                                <td>' . $mode->id . '</td>
                                <td>' . date('d.m.Y', strtotime($mode->kun)) . '</td>
                                <td>' . $mode->tmodel_id . '</td>
                                <td>' . $mode->tur->tur_name . '</td>
                                <td>' . $mode->brend->brend_name . '</td>
                                <td>' . $mode->tmodel->model_name . '</td>
                                <td>' . $mode->shtrix_kod . '</td>
                                <td>' . $mode->status . '</td>
                                <td>' . $mode->filial->fil_name . '</td>
                                <td>' . $mode->pastavshik->pastav_name . '</td>
                            </tr>
                            ';
                        }
                        echo'
                    </tbody>
                </table>
            ';
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            $krimt = $request->krimt;
            $model = ktovar1::where('shtrix_kod', $krimt)->where('status', 'Сотилмаган')->count();
            if ($model == 1) {
                $modelread = ktovar1::where('shtrix_kod', $krimt)->where('status', 'Сотилмаган')->first();
                try {
                    DB::beginTransaction();

                    $zaqista = new asosiyvositalar();
                    $zaqista->kun = $modelread->kun;
                    $zaqista->tur_id = $modelread->tur_id;
                    $zaqista->brend_id = $modelread->brend_id;
                    $zaqista->tmodel_id = $modelread->tmodel_id;
                    $zaqista->shtrix_kod = $modelread->shtrix_kod;
                    $zaqista->valyuta_id = $modelread->valyuta_id;
                    $zaqista->narhi = $modelread->narhi;
                    $zaqista->snarhi = $modelread->snarhi;
                    $zaqista->valyuta_narhi = $modelread->valyuta_narhi;
                    $zaqista->tannarhi = $modelread->tannarhi;
                    $zaqista->pastavshik_id = $modelread->pastavshik_id;
                    $zaqista->xis_oyi = $modelread->xis_oyi;
                    $zaqista->user_id = Auth::user()->id;
                    $zaqista->filial_id = Auth::user()->filial_id;
                    $zaqista->kirim_id = $modelread->id;
                    $zaqista->save();

                    $ktovar1Updated =  ktovar1::where('shtrix_kod', $krimt)->limit(1)
                    ->update([
                        'status' => 'Асосий восита'
                    ]);

                    DB::commit();
                    $message = $krimt . "<br> Товар асосий воситага олинди.";
                
                } catch (\Exception $e) {
                    DB::rollBack();
                    $message = $krimt . "<br> Товар асосий воситага олишда хатолик2";
                    // throw $e;
                }

                return response()->json(['message' => $message ], 200);

            } elseif ($model != 1) {
                return response()->json(['message' =>  $krimt . "<br> Хатолик!!! Товар топилмади ёки илгари асосий воситага олинган булиши мумкин."], 200);
            }
            return;
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
        // if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 12) && ($request->id>0 && $request->filial>0)) {
        //     $model = new ktovar1($request->filial);
        //     $data=$model->where('id', $request->id)->update([
        //         'status' => "Удалит",
        //     ]);
        //     return response()->json(['message' => 'Маълумот ўчирилди.'], 200);
        // }else{
        //     Auth::guard('web')->logout();
        //     session()->invalidate();
        //     session()->regenerateToken();
        //     return redirect('/');
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
