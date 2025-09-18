<?php

namespace App\Http\Controllers;

use App\Models\boshqaharajat1;
use App\Models\chiqim_taminot;
use App\Models\filial;
use App\Models\kirim;
use App\Models\kirim_dollar;
use App\Models\kirim_old;
use App\Models\tulovlar1;
use App\Models\xissobotoy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class XisobotOfficeInvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            if (Auth::user()->filial_id == 10){
                $filial = filial::where('status', 'Актив')->get();
            }else{
                $filial = filial::where('id', Auth::user()->filial_id)->get();
            }

            return view('xisobotlar.XisobotOfficeInv', ['xis_oyi' => $xis_oyi, 'filial' => $filial]);
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
        $boshkun = $request->boshkun;
        $yakunkun = $request->yakunkun;

        $savdoData = kirim::whereBetween('kun', [$boshkun, $yakunkun])
            ->where('status', 'Актив')
            ->get(['naqd', 'pastik', 'hr', 'click', 'avtot', 'kirimtur_id', 'valyuta_id']);

        // savdo pullarini hisoblash
        $savdoPuliNaqd = $savdoData->where('kirimtur_id', 1)->where('valyuta_id', 1)->sum('naqd');
        $savdoPuliPlastik = $savdoData->whereIn('kirimtur_id', [1, 5])->where('valyuta_id', 1)->sum('pastik');
        $savdoPuliHr = $savdoData->whereIn('kirimtur_id', [1, 5])->where('valyuta_id', 1)->sum('hr');
        $savdoPuliClick = $savdoData->whereIn('kirimtur_id', [1, 5])->where('valyuta_id', 1)->sum('click');
        $savdoPuliAvtot = $savdoData->whereIn('kirimtur_id', [1, 5])->where('valyuta_id', 1)->sum('avtot');
        $savdoPuliInkassa = $savdoData->where('kirimtur_id', 5)->where('valyuta_id', 1)->sum('naqd');

        $savdoPuliBank = $savdoPuliPlastik + $savdoPuliHr + $savdoPuliClick + $savdoPuliAvtot + $savdoPuliInkassa;

        // tasischi kirim pullari
        $tasischiKirimNaqd = $savdoData->whereIn('kirimtur_id', [2, 3])->where('valyuta_id', 1)->sum('naqd');
        $tasischiKirimDollar = $savdoData->whereIn('kirimtur_id', [2, 3])->where('valyuta_id', 2)->sum('naqd');
        $tasischiKirimPlastik = $savdoData->whereIn('kirimtur_id', [2, 3])->where('valyuta_id', 1)->sum('pastik');
        $tasischiKirimHr = $savdoData->whereIn('kirimtur_id', [2, 3])->where('valyuta_id', 1)->sum('hr');
        $tasischiKirimClick = $savdoData->whereIn('kirimtur_id', [2, 3])->where('valyuta_id', 1)->sum('click');
        $tasischiKirimAvtot = $savdoData->whereIn('kirimtur_id', [2, 3])->where('valyuta_id', 1)->sum('avtot');

        $tasischiKirimBank = $tasischiKirimPlastik + $tasischiKirimHr + $tasischiKirimClick + $tasischiKirimAvtot;

        // firmalardan bonus summalar. tamonitchi hisob kitob farqlaridagi
        $firmaBonusNaqd = $savdoData->whereIn('kirimtur_id', [6])->where('valyuta_id', 1)->sum('naqd');
        $firmaBonusDollar = $savdoData->whereIn('kirimtur_id', [6])->where('valyuta_id', 2)->sum('naqd');
        $firmaBonusPlastik = $savdoData->whereIn('kirimtur_id', [6])->where('valyuta_id', 1)->sum('pastik');
        $firmaBonusHr = $savdoData->whereIn('kirimtur_id', [6])->where('valyuta_id', 1)->sum('hr');
        $firmaBonusClick = $savdoData->whereIn('kirimtur_id', [6])->where('valyuta_id', 1)->sum('click');
        $firmaBonusAvtot = $savdoData->whereIn('kirimtur_id', [6])->where('valyuta_id', 1)->sum('avtot');

        $firmaBonusBank = $firmaBonusPlastik + $firmaBonusHr + $firmaBonusClick + $firmaBonusAvtot;

        // Dollar almashinish hisoblash
        $dollarData = kirim_dollar::whereBetween('kun', [$boshkun, $yakunkun])
            ->where('status', 'Актив')
            ->get(['naqd', 'pastik', 'hr', 'click', 'avtot', 'dollar_summa', 'valyuta_id']);

        $dollarAlmNaqd = $dollarData->where('valyuta_id', 1)->sum('naqd');
        $dollarAlmPlastik = $dollarData->where('valyuta_id', 1)->sum('pastik');
        $dollarAlmHr = $dollarData->where('valyuta_id', 1)->sum('hr');
        $dollarAlmClick = $dollarData->where('valyuta_id', 1)->sum('click');
        $dollarAlmAvtot = $dollarData->where('valyuta_id', 1)->sum('avtot');
        $dollarAlmDollar = $dollarData->where('valyuta_id', 1)->sum('dollar_summa');

        $dollarBank = $dollarAlmPlastik + $dollarAlmHr + $dollarAlmClick + $dollarAlmAvtot;

        // tasischiga chiqim  hisoblash
        $ofisXarajat = new boshqaharajat1(10);
        $xarajatData = $ofisXarajat->whereBetween('kun', [$boshkun, $yakunkun])
            ->where('status', 'Актив')
            ->get(['naqd', 'pastik', 'hr', 'click', 'avtot', 'valyuta_id', 'turharajat_id']);

        $tasischiChiqimNaqd = $xarajatData->where('valyuta_id', 1)->whereIn('turharajat_id', [35, 36, 37, 38])->sum('naqd');
        $tasischiChiqimPlastik = $xarajatData->where('valyuta_id', 1)->whereIn('turharajat_id', [35, 36, 37, 38])->sum('pastik');
        $tasischiChiqimHr = $xarajatData->where('valyuta_id', 1)->whereIn('turharajat_id', [35, 36, 37, 38])->sum('hr');
        $tasischiChiqimClick = $xarajatData->where('valyuta_id', 1)->whereIn('turharajat_id', [35, 36, 37, 38])->sum('click');
        $tasischiChiqimAvtot = $xarajatData->where('valyuta_id', 1)->whereIn('turharajat_id', [35, 36, 37, 38])->sum('avtot');
        $tasischiChiqimDollar = $xarajatData->where('valyuta_id', 2)->whereIn('turharajat_id', [35, 36, 37, 38])->sum('naqd');

        $tasischiChiqimBank = $tasischiChiqimPlastik + $tasischiChiqimHr + $tasischiChiqimClick + $tasischiChiqimAvtot;

        // ofis xarajatini  hisoblash
        $xarajatNaqd = $xarajatData->where('valyuta_id', 1)->whereNotIn('turharajat_id', [35, 36, 37, 38])->sum('naqd');
        $xarajatPlastik = $xarajatData->where('valyuta_id', 1)->whereNotIn('turharajat_id', [35, 36, 37, 38])->sum('pastik');
        $xarajatHr = $xarajatData->where('valyuta_id', 1)->whereNotIn('turharajat_id', [35, 36, 37, 38])->sum('hr');
        $xarajatClick = $xarajatData->where('valyuta_id', 1)->whereNotIn('turharajat_id', [35, 36, 37, 38])->sum('click');
        $xarajatAvtot = $xarajatData->where('valyuta_id', 1)->whereNotIn('turharajat_id', [35, 36, 37, 38])->sum('avtot');
        $xarajatDollar = $xarajatData->where('valyuta_id', 2)->whereNotIn('turharajat_id', [35, 36, 37, 38])->sum('naqd');

        $xarajatBank = $xarajatPlastik + $xarajatHr + $xarajatClick + $xarajatAvtot;

        // chiqim taminot hisoblash
        $taminotchiChiqim = chiqim_taminot::whereBetween('kun', [$boshkun, $yakunkun])
            ->where('status', 'Актив')
            ->get(['naqd', 'pastik', 'hr', 'click', 'avtot', 'valyuta_id']);

        $taminotchiNaqd = $taminotchiChiqim->where('valyuta_id', 1)->sum('naqd');
        $taminotchiPlastik = $taminotchiChiqim->where('valyuta_id', 1)->sum('pastik');
        $taminotchiHr = $taminotchiChiqim->where('valyuta_id', 1)->sum('hr');
        $taminotchiClick = $taminotchiChiqim->where('valyuta_id', 1)->sum('click');
        $taminotchiAvtot = $taminotchiChiqim->where('valyuta_id', 1)->sum('avtot');
        $taminotchiDollar = $taminotchiChiqim->where('valyuta_id', 2)->sum('naqd');

        $taminotchiBank = $taminotchiPlastik + $taminotchiHr + $taminotchiClick + $taminotchiAvtot;

        $jamiNaqdSumma = $savdoPuliNaqd+$tasischiKirimNaqd+$firmaBonusNaqd;
        $jamiBankSumma = $savdoPuliBank+$tasischiKirimBank+$firmaBonusBank;
        $jamiDollarSumma = $tasischiKirimDollar+$firmaBonusDollar+$dollarAlmDollar;

        $jamiNaqdChiqimSumma = $dollarAlmNaqd+$tasischiChiqimNaqd+$xarajatNaqd+$taminotchiNaqd;
        $jamiBankChiqimSumma = $dollarBank+$tasischiChiqimBank+$xarajatBank+$taminotchiBank;
        $jamiDollarChiqimSumma = $tasischiChiqimDollar+$xarajatDollar+$taminotchiDollar;

        echo'
        <table class="table table-bordered table-responsive-sm text-center align-middle m-auto" style="font-size: 12px; width: 95%;">
            <thead>
                <tr class="text-bold text-primary align-middle">
                    <th>Тури</th>
                    <th>Савдо <br> Пули</th>
                    <th>Тасисчи <br> кирим</th>
                    <th>Бонус <br> фирмалар</th>
                    <th>Жами <br> сумма</th>
                    <th>Доллар <br> алмашиш</th>
                    <th>Инвестор <br> Чиким</th>
                    <th>Офис <br> харажати</th>
                    <th>Таминотчига <br> чиким</th>
                    <th>Жами <br> чиким</th>
                    <th>Касса <br> колдик</th>
                </tr>
            </thead>
            <tbody id="tab1">';


        echo'
                <tr>
                    <td>Накд сумма</td>
                    <td>' . number_format($savdoPuliNaqd, 0, ",", " ") . '</td>
                    <td>' . number_format($tasischiKirimNaqd, 0, ",", " ") . '</td>
                    <td>' . number_format($firmaBonusNaqd, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiNaqdSumma, 0, ",", " ") . '</td>
                    <td>' . number_format($dollarAlmNaqd, 0, ",", " ") . '</td>
                    <td>' . number_format($tasischiChiqimNaqd, 0, ",", " ") . '</td>
                    <td>' . number_format($xarajatNaqd, 0, ",", " ") . '</td>
                    <td>' . number_format($taminotchiNaqd, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiNaqdChiqimSumma, 0, ",", " ") . '</td>
                    <td>' . number_format(($jamiNaqdSumma-$jamiNaqdChiqimSumma), 0, ",", " ") . '</td>
                </tr>
                
                <tr>
                    <td>Х/Р сумма</td>
                    <td>' . number_format($savdoPuliBank, 0, ",", " ") . '</td>
                    <td>' . number_format($tasischiKirimBank, 0, ",", " ") . '</td>
                    <td>' . number_format($firmaBonusBank, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiBankSumma, 0, ",", " ") . '</td>
                    <td>' . number_format($dollarBank, 0, ",", " ") . '</td>
                    <td>' . number_format($tasischiChiqimBank, 0, ",", " ") . '</td>
                    <td>' . number_format($xarajatBank, 0, ",", " ") . '</td>
                    <td>' . number_format($taminotchiBank, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiBankChiqimSumma, 0, ",", " ") . '</td>
                    <td>' . number_format(($jamiBankSumma-$jamiBankChiqimSumma), 0, ",", " ") . '</td>
                </tr>
                
                <tr>
                    <td>Доллар</td>
                    <td>' . number_format(0, 0, ",", " ") . '</td>
                    <td>' . number_format($tasischiKirimDollar, 0, ",", " ") . '</td>
                    <td>' . number_format($firmaBonusDollar, 0, ",", " ") . '</td>
                    <td class="text-primary">' . number_format($jamiDollarSumma, 0, ",", " ") . '</td>
                    <td class="text-danger">' . number_format($dollarAlmDollar, 0, ",", " ") . '</td>
                    <td>' . number_format($tasischiChiqimDollar, 0, ",", " ") . '</td>
                    <td>' . number_format($xarajatDollar, 0, ",", " ") . '</td>
                    <td>' . number_format($taminotchiDollar, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiDollarChiqimSumma, 0, ",", " ") . '</td>
                    <td>' . number_format(($jamiDollarSumma-$jamiDollarChiqimSumma), 0, ",", " ") . '</td>
                </tr>
            </tbody>
        </table>';
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
