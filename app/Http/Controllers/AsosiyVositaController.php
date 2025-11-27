<?php

namespace App\Http\Controllers;

use App\Models\xissobotoy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\kirimTovar;
use App\Models\asosiyvositalar;

use Illuminate\Support\Facades\DB;

class AsosiyVositaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userRole = Auth::user()->lavozim_id;
        if (in_array($userRole, [1, 2]) && Auth::user()->status == 'Актив') {

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
        $userRole = Auth::user()->lavozim_id;
        if (in_array($userRole, [1, 2]) && Auth::user()->status == 'Актив') {
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
                            <th>-</th>
                        </tr>
                    </thead>
                    <tbody id="tab1">';


                    if (Auth::user()->filial_id == 10){
                        $model = asosiyvositalar::where('status', 'Актив')->orderBy('id', 'desc')->get();
                    }else{
                        $model = asosiyvositalar::where('filial_id', Auth::user()->filial_id)->where('status', 'Актив')->orderBy('id', 'desc')->get();

                    }
                        foreach ($model as $mode){
                            echo"
                            <tr>
                                <td>{$mode->id}</td>
                                <td>{$mode->kun}</td>
                                <td>{$mode->tmodel_id}</td>
                                <td>{$mode->tur->tur_name}</td>
                                <td>{$mode->brend->brend_name}</td>
                                <td>{$mode->tmodel->model_name}</td>
                                <td>{$mode->shtrix_kod}</td>
                                <td>{$mode->status}</td>
                                <td>{$mode->filial->fil_name}</td>
                                <td>{$mode->pastavshik->pastav_name}</td>
                                <td>
                                    <button
                                        type='button'
                                        data-id={$mode->id}
                                        class='btn btn-outline-danger btn-sm me-2 delete-btn'>
                                        <i class='flaticon-381-trash-1'></i>
                                    </button>

                                </td>
                            </tr>
                            ";
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
        if (Auth::user()->lavozim_id != 2 && Auth::user()->status != 'Актив') {
            return response()->json(['message' => "Sizda dostup yo'q" ], 200);
        }

        $krimt = $request->krimt;

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

        $model = kirimTovar::where('filial_id', Auth::user()->filial_id)
            ->where('shtrix_kod', $krimt)
            ->where('status', 'Сотилмаган')
            ->count();

        if ($model == 1) {

            $modelread = kirimTovar::where('shtrix_kod', $krimt)
                ->where('status', 'Сотилмаган')
                ->where('filial_id', Auth::user()->filial_id)
                ->first();

            try {
                DB::beginTransaction();

                $zaqista = new asosiyvositalar();
                $zaqista->kun = $modelread->kun;
                $zaqista->filial_id = Auth::user()->filial_id;
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
                $zaqista->xis_oyi = $xis_oyi;
                $zaqista->user_id = Auth::user()->id;
                $zaqista->kirim_id = $modelread->id;
                $zaqista->save();

                $modelread->update([
                        'status' => 'Асосий восита',
                        'ch_kun' => now(),
                        'ch_xis_oyi' => $xis_oyi,
                        'ch_user_id' => Auth::id(),
                    ]);

                DB::commit();

                return response()->json(['message' => "Tovar asosiy vositaga olindi" ], 200);

            } catch (\Exception $e) {

                DB::rollBack();

                return response()->json(['message' => "Asosiy vositaga olishda xatolik" ], 200);
                // throw $e;
            }

        }

        return response()->json(['message' =>  $krimt . "<br> Хатолик!!! Товар топилмади ёки илгари асосий воситага олинган булиши мумкин."], 200);

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
        $AsosiyVosita = asosiyvositalar::find($id);

        $tovar = kirimTovar::where('shtrix_kod', $AsosiyVosita->shtrix_kod)
            ->where('filial_id', $AsosiyVosita->filial_id)
            ->where('id', $AsosiyVosita->kirim_id)
            ->first();

        if (!$tovar){
            return response()->json([
                'success' => false,
                'message' => "Tovar topilmadi."
            ], 422);
        }

        try {
            DB::beginTransaction();

            $AsosiyVosita->update([
                'status' => 'Удалит'
            ]);

            $tovar->update([
                'status' => 'Сотилмаган',
                'ch_kun' => null,
                'ch_xis_oyi' => null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Tovar o'chirildi."
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Asosiy vositaga olishda xatolik"
            ]);
        }


    }
}
