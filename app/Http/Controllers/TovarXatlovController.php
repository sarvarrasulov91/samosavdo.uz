<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KirimTovar;
use App\Models\filial;


class TovarXatlovController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->filial_id == 10 && Auth::user()->status == 'Актив'){

            $filial = filial::where('status', 'Актив')->get();

        }else{
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }

        return view('tovarlar.xatlov', ['filial' => $filial]);

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
        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив'){

            $krimt = $request->krimt;
            $filial = $request->filial;

            $model = KirimTovar::where('shtrix_kod', $krimt)
                ->where('filial_id', $filial)
                ->where('status', 'Сотилмаган')
                ->where('inv_shtrix_kod', '0')
                ->count();

            $tovar = KirimTovar::where('shtrix_kod', $krimt)
                ->where('filial_id', $filial)
                ->where('status', 'Сотилмаган')
                ->where('inv_shtrix_kod', '0')
                ->first();

            if ($model == 1) {

                $tovar->update(['inv_shtrix_kod' => $krimt]);

                return response()->json(['message' => $krimt.'<br> Товар хатловдан ўтказилди.'], 200);

            } else{

                return response()->json(['message' => $krimt.'<br> Хатолик!!! Товар топилмади ёки хатловдан ўтказилган бўлиши мумкин.'], 200);
            }

        }else{
            return response()->json(['message' => "Админга мурожаат килинг."], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        echo'
            <table class="table table-bordered table-responsive-sm text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Куни</th>
                        <th>Тури</th>
                        <th>Бренди</th>
                        <th>Модели</th>
                        <th>Штрих-раками</th>
                        <th>Штрих-раками<br>Хатлов</th>
                        <th>Хатлов сана</th>
                        <th>Холати</th>
                        <th>Таъминотчи</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                    $model = KirimTovar::where('status', 'Сотилмаган')->where('filial_id', $id)->orderBy('id', 'desc')->get();

                    foreach ($model as $mode){

                        if ($mode->inv_shtrix_kod == 0){
                            echo'
                            <tr>
                                <td>' . $mode->id . '</td>
                                <td>' . date('d.m.Y', strtotime($mode->kun)) . '</td>
                                <td>' . $mode->tur->tur_name . '</td>
                                <td>' . $mode->brend->brend_name . '</td>
                                <td>' . $mode->tmodel->model_name . '</td>
                                <td>' . $mode->shtrix_kod . '</td>
                                <td>' . $mode->inv_shtrix_kod . '</td>
                                <td>' . date('d.m.Y', strtotime($mode->updated_at)) . '</td>
                                <td>' . $mode->status . '</td>
                                <td>' . $mode->pastavshik->pastav_name . '</td>
                            </tr>';
                        }
                    }
                    echo'
                </tbody>
            </table>';
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
        $filialName = filial::where('id', $id)->value('fil_name');

        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив'){

            KirimTovar::where('status', 'Сотилмаган')
                ->where('filial_id', $id)
                ->update([
                    'inv_shtrix_kod' => '0'
                ]);

            return response()->json(['message' => $filialName.'<br> База тозаланди.'], 200);

        }else{
            return response()->json(['message' => "Админга мурожаат килинг."], 200);
        }
    }
}
