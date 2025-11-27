<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\kirimTovar;
use App\Models\filial;



class TovarlarJamiOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {

            $filial = filial::where('status', 'Актив')->get();

            return view('tovarlar.OfficeJamiTovarlar', ['filial' => $filial]);
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
       //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
                            <th>Филиал</th>
                            <th>Таъминотчи</th>
                            <th>Қайтариш</th>
                        </tr>
                    </thead>
                    <tbody id="tab1">
                    ';

                        $model = kirimTovar::whereNotIn('status', ['Актив', 'Удалит'])
                            ->where('filial_id', $id)
                            ->orderBy('id', 'desc')
                            ->get();

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
                                <td>' . $mode->tur->transport_id . '</td>
                                <td>' . round($kirim_narxi * $valyuta * (100 + $natsenka + $trans_xarajat) / 100, -3)  . '</td>
                                <td>' . $mode->shatnomaid . '</td>
                                <td>' . $mode->status . '</td>
                                <td>' . $mode->filial->fil_name . '</td>
                                <td>' . $mode->pastavshik->pastav_name . '</td>
                                <td>
                                        <a onclick="tovarudalit(' . $mode->id . ')"
                                            class="btn btn-outline-danger btn-xxs">Қайтариш</a>
                                    </td>
                                </tr>';
                        }
                        echo '
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
        $id = $request->id;
        $filial = $request->filial;

        if (Auth::user()->lavozim_id != 1 || Auth::user()->status != 'Актив') {

            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');

        }

        $tovar = kirimTovar::where('filial_id', $filial)
            ->where('id', $id)
            ->where('status', 'Сотилмаган')
            ->first();

        // update() qaytargan qiymat — nechta qator o‘zgarganligi
        if (!$tovar) {
            return response()->json(['message' => 'Товар топилмади'], 404);
        }

        $tovar->update([
            'status' => 'Удалит',
            'del_user_id' => Auth::user()->id,
            'del_kun' => now(),
        ]);

        return response()->json(['message' => 'Товар учирилди.'], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
