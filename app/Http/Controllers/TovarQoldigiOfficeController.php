<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ktovar1;
use App\Models\tmodel;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;

class TovarQoldigiOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
            $filial = filial::where('status', 'Актив')->get();
            return view('tovarlar.tovarlartahlili', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'filial' => $filial]);
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
            <table class="table table-bordered table-responsive-sm text-center align-middle" style="font-size: 12px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th rowspan="3">№</th>
                        <th rowspan="3">Филиал</th>
                        <th colspan="4">Ой бошига</th>
                        <th colspan="4">Кирим</th>
                        <th colspan="4">Чиқим</th>
                        <th colspan="4">Ой Охирига</th>
                    </tr>
                    <tr class="text-bold text-primary align-middle">
                        <th colspan="2">Доллар</th>
                        <th colspan="2">Сўм</th>
                        <th colspan="2">Доллар</th>
                        <th colspan="2">Сўм</th>
                        <th colspan="2">Доллар</th>
                        <th colspan="2">Сўм</th>
                        <th colspan="2">Доллар</th>
                        <th colspan="2">Сўм</th>
                    </tr>
                    <tr class="text-bold text-primary align-middle">
                        <th>Сони</th>
                        <th>Суммаси</th>
                        <th>Сони</th>
                        <th>Суммаси</th>
                        <th>Сони</th>
                        <th>Суммаси</th>
                        <th>Сони</th>
                        <th>Суммаси</th>
                        <th>Сони</th>
                        <th>Суммаси</th>
                        <th>Сони</th>
                        <th>Суммаси</th>
                        <th>Сони</th>
                        <th>Суммаси</th>
                        <th>Сони</th>
                        <th>Суммаси</th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                    $i=1;

                    $UTOBDSoni=0;
                    $UTOBDSumma=0;
                    $UTOBSSoni=0;
                    $UTOBSSumma=0;

                    $UTOBKDSoni=0;
                    $UTOBKDSumma=0;
                    $UTOBKSSoni=0;
                    $UTOBKSSumma=0;

                    $UTOBCHDSoni=0;
                    $UTOBCHDSumma=0;
                    $UTOBCHSSoni=0;
                    $UTOBCHSSumma=0;

                    $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

                    $filial = filial::where('status', 'Актив')->get();
                    foreach ($filial as $filialinfo){

                        $TOBDSoni=0;
                        $TOBDSumma=0;
                        $TOBSSoni=0;
                        $TOBSSumma=0;

                        $TOBKDSoni=0;
                        $TOBKDSumma=0;
                        $TOBKSSoni=0;
                        $TOBKSSumma=0;

                        $TOBCHDSoni=0;
                        $TOBCHDSumma=0;
                        $TOBCHSSoni=0;
                        $TOBCHSSumma=0;

                        echo'
                            <tr>
                                <td>' . $i . '</td>
                                <td>' . $filialinfo->fil_name . '</td>';
                                    $ktovar = new ktovar1($filialinfo->id);

                                    $TOBSSoni=$ktovar->where('valyuta_id', '1')->where('xis_oyi', '<', $xis_oyi)->where('status', 'Сотилмаган')->
                                        orWhere('valyuta_id', '1')->where('xis_oyi', '<', $xis_oyi)->where('ch_xis_oyi', '>=', $xis_oyi)
                                        ->count('id');

                                    $TOBSSumma=$ktovar->where('valyuta_id', '1')->where('xis_oyi', '<', $xis_oyi)->where('status', 'Сотилмаган')->
                                        orWhere('valyuta_id', '1')->where('xis_oyi', '<', $xis_oyi)->where('ch_xis_oyi', '>=', $xis_oyi)
                                        ->sum('narhi');
                                    $TOBDSoni=$ktovar->where('valyuta_id', '2')->where('xis_oyi', '<', $xis_oyi)->where('status', 'Сотилмаган')->
                                        orWhere('valyuta_id', '2')->where('xis_oyi', '<', $xis_oyi)->where('ch_xis_oyi', '>=', $xis_oyi)
                                        ->count('id');
                                    $TOBDSumma=$ktovar->where('valyuta_id', '2')->where('xis_oyi', '<', $xis_oyi)->where('status', 'Сотилмаган')->
                                        orWhere('valyuta_id', '2')->where('xis_oyi', '<', $xis_oyi)->where('ch_xis_oyi', '>=', $xis_oyi)
                                        ->sum('narhi');

                                    $TOBKSSoni=$ktovar->where('valyuta_id', '1')->where('xis_oyi', $xis_oyi)->where('status', '!=', 'Удалит')->where('status', '!=', 'Актив')->count('id');
                                    $TOBKSSumma=$ktovar->where('valyuta_id', '1')->where('xis_oyi', $xis_oyi)->where('status', '!=', 'Удалит')->where('status', '!=', 'Актив')->sum('narhi');
                                    $TOBKDSoni=$ktovar->where('valyuta_id', '2')->where('xis_oyi', $xis_oyi)->where('status', '!=', 'Удалит')->where('status', '!=', 'Актив')->count('id');
                                    $TOBKDSumma=$ktovar->where('valyuta_id', '2')->where('xis_oyi', $xis_oyi)->where('status', '!=', 'Удалит')->where('status', '!=', 'Актив')->sum('narhi');

                                    $TOBCHSSoni=$ktovar->where('valyuta_id', '1')->where('ch_xis_oyi', $xis_oyi)->where('status', '!=', 'Удалит')->count('id');
                                    $TOBCHSSumma=$ktovar->where('valyuta_id', '1')->where('ch_xis_oyi', $xis_oyi)->where('status', '!=', 'Удалит')->sum('narhi');
                                    $TOBCHDSoni=$ktovar->where('valyuta_id', '2')->where('ch_xis_oyi', $xis_oyi)->where('status', '!=', 'Удалит')->count('id');
                                    $TOBCHDSumma=$ktovar->where('valyuta_id', '2')->where('ch_xis_oyi', $xis_oyi)->where('status', '!=', 'Удалит')->sum('narhi');

                                    echo'
                                    <td>' . number_format($TOBDSoni, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBDSumma, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBSSoni, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBSSumma, 0, ",", " ") . '</td>

                                    <td>' . number_format($TOBKDSoni, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBKDSumma, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBKSSoni, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBKSSumma, 0, ",", " ") . '</td>

                                    <td>' . number_format($TOBCHDSoni, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBCHDSumma, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBCHSSoni, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBCHSSumma, 0, ",", " ") . '</td>

                                    <td>' . number_format($TOBDSoni+$TOBKDSoni-$TOBCHDSoni, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBDSumma+$TOBKDSumma-$TOBCHDSumma, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBSSoni+$TOBKSSoni-$TOBCHSSoni, 0, ",", " ") . '</td>
                                    <td>' . number_format($TOBSSumma+$TOBKSSumma-$TOBCHSSumma, 0, ",", " ") . '</td>
                                    </tr>
                                ';
                            $i++;

                            $UTOBDSoni+=$TOBDSoni;
                            $UTOBDSumma+=$TOBDSumma;
                            $UTOBSSoni+=$TOBSSoni;
                            $UTOBSSumma+=$TOBSSumma;

                            $UTOBKDSoni+=$TOBKDSoni;
                            $UTOBKDSumma+=$TOBKDSumma;
                            $UTOBKSSoni+=$TOBKSSoni;
                            $UTOBKSSumma+=$TOBKSSumma;

                            $UTOBCHDSoni+=$TOBCHDSoni;
                            $UTOBCHDSumma+=$TOBCHDSumma;
                            $UTOBCHSSoni+=$TOBCHSSoni;
                            $UTOBCHSSumma+=$TOBCHSSumma;
                    }

                     echo'
                            <tr class="text-bold">
                                <td></td>
                                <td><b>ЖАМИ</b></td>
                                <td><b>' . number_format($UTOBDSoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBDSumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBSSoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBSSumma, 0, ",", " ") . '</b></td>

                                <td><b>' . number_format($UTOBKDSoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBKDSumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBKSSoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBKSSumma, 0, ",", " ") . '</b></td>

                                <td><b>' . number_format($UTOBCHDSoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBCHDSumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBCHSSoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBCHSSumma, 0, ",", " ") . '</b></td>

                                <td><b>' . number_format($UTOBDSoni+$UTOBKDSoni-$UTOBCHDSoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBDSumma+$UTOBKDSumma-$UTOBCHDSumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBSSoni+$UTOBKSSoni-$UTOBCHSSoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($UTOBSSumma+$UTOBKSSumma-$UTOBCHSSumma, 0, ",", " ") . '</b></td>

                            </tr>
                </tbody>
            </table>';

            return ;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        echo'
            <table class="table table-bordered table-responsive-sm text-center align-middle" style="font-size: 12px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th rowspan="3">№</th>
                        <th rowspan="3">Модел ID</th>
                        <th rowspan="3">Товар номи</th>
                        <th colspan="4">Товарлар қолдиғи</th>
                    </tr>
                    <tr class="text-bold text-primary align-middle">
                        <th colspan="2">Доллар</th>
                        <th colspan="2">Сўм</th>
                    </tr>
                    <tr class="text-bold text-primary align-middle">
                        <th>Сони</th>
                        <th>Суммаси</th>
                        <th>Сони</th>
                        <th>Суммаси</th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                    $i=1;
                    $TovarJamiDollarSoni=0;
                    $TovarJamiDollasrummasi=0;
                    $TovarJamiSumSoni=0;
                    $TovarJamiSumsummasi=0;
                    $ktovar = new ktovar1($id);
                    $filial = $ktovar->select('tmodel_id')->where('status', 'Сотилмаган')->groupBy('tmodel_id')->get();
                    foreach ($filial as $filialinfo){

                        $model = tmodel::where('id', $filialinfo->tmodel_id)->first();
                        $tmodel_name = $model->tur->tur_name .' '. $model->brend->brend_name .' '. $model->model_name ;

                        echo'
                            <tr>
                                <td>' . $i . '</td>
                                <td>' . $filialinfo->tmodel_id . '</td>
                                <td>' . $tmodel_name . '</td>';
                                    $rkrimtomarssoni = $ktovar->where('valyuta_id', 1)->where('status', 'Сотилмаган')->where('tmodel_id', $filialinfo->tmodel_id)->count('id');
                                    $rkrimtomars = $ktovar->where('valyuta_id', 1)->where('status', 'Сотилмаган')->where('tmodel_id', $filialinfo->tmodel_id)->sum('narhi');
                                    $rkrimtomardsoni = $ktovar->where('valyuta_id', 2)->where('status', 'Сотилмаган')->where('tmodel_id', $filialinfo->tmodel_id)->count('id');
                                    $rkrimtomard = $ktovar->where('valyuta_id', 2)->where('status', 'Сотилмаган')->where('tmodel_id', $filialinfo->tmodel_id)->sum('narhi');
                                    echo'
                                    <td>' . number_format($rkrimtomardsoni, 0, ",", " ") . '</td>
                                    <td>' . number_format($rkrimtomard, 0, ",", " ") . '</td>
                                    <td>' . number_format($rkrimtomarssoni, 0, ",", " ") . '</td>
                                    <td>' . number_format($rkrimtomars, 0, ",", " ") . '</td>
                                    </tr>
                                ';
                            $i++;
                            $TovarJamiDollarSoni+=$rkrimtomardsoni;
                            $TovarJamiDollasrummasi+=$rkrimtomard;
                            $TovarJamiSumSoni+=$rkrimtomarssoni;
                            $TovarJamiSumsummasi+=$rkrimtomars;
                    }

                     echo'
                            <tr class="text-bold">
                                <td></td>
                                <td><b>ЖАМИ</b></td>
                                <td></td>
                                <td><b>' . number_format($TovarJamiDollarSoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($TovarJamiDollasrummasi, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($TovarJamiSumSoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($TovarJamiSumsummasi, 0, ",", " ") . '</b></td>
                            </tr>
                </tbody>
            </table>';

            return ;
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
