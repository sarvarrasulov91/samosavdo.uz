<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KirimTovar;
use App\Models\tqaytarish;
use Illuminate\Support\Facades\DB;
use App\Models\xissobotoy;
use App\Models\filial;



class TovarTaminotQaytarishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {

            $filial = filial::where('status', 'Актив')->get();

            return view('tovarlar.tovarqaytarish', ['filial' => $filial]);
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
        $filial = $request->filial;
        $krimt = $request->krimt;

        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {

            $CountKtovar = KirimTovar::where('shtrix_kod', $krimt)
                ->where('filial_id', $filial)
                ->where('status', 'Сотилмаган')
                ->count();

            if ($CountKtovar == 1) {
                $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

                $ReadKtovar = KirimTovar::where('shtrix_kod', $krimt)
                    ->where('filial_id', $filial)
                    ->where('status', 'Сотилмаган')
                    ->first();

                try {
                    DB::beginTransaction();

                    $CreateTqaytarish = new tqaytarish;
                    $CreateTqaytarish->kun = $ReadKtovar->kun;
                    $CreateTqaytarish->filial_id = $request->filial;
                    $CreateTqaytarish->tur_id = $ReadKtovar->tur_id;
                    $CreateTqaytarish->brend_id = $ReadKtovar->brend_id;
                    $CreateTqaytarish->tmodel_id = $ReadKtovar->tmodel_id;
                    $CreateTqaytarish->shtrix_kod = $ReadKtovar->shtrix_kod;
                    $CreateTqaytarish->soni = 1;
                    $CreateTqaytarish->valyuta_id = $ReadKtovar->valyuta_id;
                    $CreateTqaytarish->narhi = $ReadKtovar->narhi;
                    $CreateTqaytarish->snarhi = $ReadKtovar->snarhi;
                    $CreateTqaytarish->valyuta_narhi = $ReadKtovar->valyuta_narhi;
                    $CreateTqaytarish->tannarhi = $ReadKtovar->tannarhi;
                    $CreateTqaytarish->pastavshik_id = $ReadKtovar->pastavshik2_id;
                    $CreateTqaytarish->xis_oyi = $xis_oyi;
                    $CreateTqaytarish->user_id = Auth::user()->id;
                    $CreateTqaytarish->save();

                    $ktovar1Updated = KirimTovar::where('shtrix_kod', $krimt)
                        ->where('filial_id', $filial)
                        ->where('status', 'Сотилмаган')
                        ->limit(1)
                        ->update([
                            'status' => 'Кайтган',
                            'ch_kun' => now(), // Yoki date('Y-m-d H:i:s') ko'rsatilgan shakli
                            'ch_xis_oyi' => $xis_oyi,
                            'ch_user_id' => Auth::user()->id,
                        ]);

                    DB::commit();

                    return response()->json(['message' => "Товар таъминотчига қайтарилди"], 200);

                } catch (\Exception $e) {

                    DB::rollBack();

                    return response()->json(['message' => "Товар таъминотчига қайтаршда хатолик"], 200);
                    // throw $e;
                }

            } else {
                return response()->json(['message' => $request->krimt."<br> Хатолик!!! Товар топилмади."], 200);
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
                        <th>Пул бр.</th>
                        <th>Нархи</th>
                        <th>Холати</th>
                        <th>Таъминотчи</th>
                        <th>Филиал</th>
                        <th>Қайтарилган<br>куни</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                    $ktovar = tqaytarish::where('status', 'Актив')->where('filial_id', $id)->orderBy('id', 'desc')->get();
                    foreach ($ktovar as $mode){
                        echo'
                            <tr>
                                <td>' . $mode->id . '</td>
                                <td>' . date("d.m.Y", strtotime($mode->kun)) . '</td>
                                <td>' . $mode->tur->tur_name . '</td>
                                <td>' . $mode->brend->brend_name . '</td>
                                <td>' . $mode->tmodel->model_name . '</td>
                                <td>' . $mode->shtrix_kod . '</td>
                                <td>' . $mode->valyuta->valyuta__nomi . '</td>
                                <td>' . $mode->narhi . '</td>
                                <td>' . $mode->status . '</td>
                                <td>' . $mode->pastavshik->pastav_name . '</td>
                                <td>' . $mode->filial->fil_name . '</td>
                                <td>' . date("d.m.Y", strtotime($mode->created_at)) . '</td>
                            </tr>';
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
        //
    }
}
