<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\savdo1;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;

class SavdolarTahliliController extends Controller
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
            $filial = filial::where('id','!=',10)->get();
            return view('xisobotlar.SavdolarTahlili', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'filial' => $filial]);
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
            <table class="table table-bordered table-responsive-sm text-center align-middle" style="font-size: 11px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th rowspan="2">№</th>
                        <th rowspan="2">Филиал</th>
                        <th colspan="2">Кирим нархи</th>
                        <th colspan="2">Сотув нархи</th>
                        <th colspan="2">Сотилган нархи</th>
                        <th colspan="2">Чегирма</th>
                        <th colspan="2">Қўшимча</th>
                        <th colspan="2">Бонус</th>
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
                    </tr>
                </thead>
                <tbody id="tab1">';

                    $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
                    $i=1;
                    $uksoni=0;
                    $uksumma=0;
                    $ussoni=0;
                    $ussumma=0;
                    $usotsoni=0;
                    $usotsumma=0;
                    $uqsoni=0;
                    $uqsumma=0;
                    $uchsoni=0;
                    $uchsumma=0;
                    $ubsoni=0;
                    $ubsumma=0;

                    $filial = filial::where('status', 'Актив')->get();
                    foreach ($filial as $filialinfo){

                        $ksoni=0;
                        $ksumma=0;
                        $ssoni=0;
                        $ssumma=0;
                        $sotsoni=0;
                        $sotsumma=0;
                        $qsoni=0;
                        $qsumma=0;
                        $chsoni=0;
                        $chsumma=0;
                        $bsoni=0;
                        $bsumma=0;

                        $savdo = new savdo1($filialinfo->id);
                        $savdosvod=$savdo->where('status','!=','Удалит')->where('xis_oyi',$xis_oyi)->get();
                        foreach ($savdosvod as $savdosvodname) {
                            $ksoni+=1;
                            $ksumma+=$savdosvodname->kirimnarhi;
                            if($savdosvodname->sotuvnarhi>0){
                                $ssoni+=1;
                                $ssumma+=$savdosvodname->sotuvnarhi;
                            }
                            if($savdosvodname->msumma>0){
                                $sotsoni+=1;
                                $sotsumma+=$savdosvodname->msumma;
                            }
                            if($savdosvodname->qushimch>0){
                                $qsoni+=1;
                                $qsumma+=$savdosvodname->qushimch;
                            }
                            if($savdosvodname->chegirma>0){
                                $chsoni+=1;
                                $chsumma+=$savdosvodname->chegirma;
                            }
                            if($savdosvodname->bonus>0){
                                $bsoni+=1;
                                $bsumma+=$savdosvodname->bonus;
                            }

                        }


                        echo'
                            <tr>
                                <td>' . $i . '</td>
                                <td>' . $filialinfo->fil_name . '</td>
                                <td>' . number_format($ksoni, 0, ",", " ") . '</td>
                                <td>' . number_format($ksumma, 0, ",", " ") . '</td>
                                <td>' . number_format($ssoni, 0, ",", " ") . '</td>
                                <td>' . number_format($ssumma, 0, ",", " ") . '</td>
                                <td>' . number_format($sotsoni, 0, ",", " ") . '</td>
                                <td>' . number_format($sotsumma, 0, ",", " ") . '</td>
                                <td>' . number_format($chsoni, 0, ",", " ") . '</td>
                                <td>' . number_format($chsumma, 0, ",", " ") . '</td>
                                <td>' . number_format($qsoni, 0, ",", " ") . '</td>
                                <td>' . number_format($qsumma, 0, ",", " ") . '</td>
                                <td>' . number_format($bsoni, 0, ",", " ") . '</td>
                                <td>' . number_format($bsumma, 0, ",", " ") . '</td>

                            </tr>
                        ';
                        $uksoni+=$ksoni;
                        $uksumma+=$ksumma;
                        $ussoni+=$ssoni;
                        $ussumma+=$ssumma;
                        $usotsoni+=$sotsoni;
                        $usotsumma+=$sotsumma;
                        $uqsoni+=$qsoni;
                        $uqsumma+=$qsumma;
                        $uchsoni+=$chsoni;
                        $uchsumma+=$chsumma;
                        $ubsoni+=$bsoni;
                        $ubsumma+=$bsumma;

                    }

                     echo'
                            <tr class="text-bold">
                                <td></td>
                                <td><b>ЖАМИ</b></td>
                                <td><b>' . number_format($uksoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($uksumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($ussoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($ussumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($usotsoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($usotsumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($uchsoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($uchsumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($uqsoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($uqsumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($ubsoni, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($ubsumma, 0, ",", " ") . '</b></td>
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
            <table class="table table-bordered table-responsive-sm text-center align-middle" style="font-size: 11px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>№</th>
                        <th>Модел ИД</th>
                        <th>Модел номи</th>
                        <th>Савдо рақами</th>
                        <th>Савдо тури</th>
                        <th>Кирим нархи</th>
                        <th>Сотув нархи</th>
                        <th>Сотилган нархи</th>
                        <th>Чегирма</th>
                        <th>Қўшимча</th>
                        <th>Бонус</th>
                        <th>Фарқи</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                    $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
                    $i=1;
                    $uksumma=0;
                    $ussumma=0;
                    $usotsumma=0;
                    $uqsumma=0;
                    $uchsumma=0;
                    $ubsumma=0;

                    $filial = filial::where('status', 'Актив')->where('id', $id)->get();
                    foreach ($filial as $filialinfo){

                        $savdo = new savdo1($filialinfo->id);
                        $savdosvod=$savdo->where('status','!=','Удалит')->where('xis_oyi',$xis_oyi)->get();
                        foreach ($savdosvod as $savdosvodname) {
                            echo'
                                <tr>
                                    <td>' . $i . '</td>
                                    <td>' . $savdosvodname->tmodel_id . '</td>
                                    <td>' . $savdosvodname->tur->tur_name .' '.$savdosvodname->brend->brend_name .' '.$savdosvodname->tmodel->model_name . '</td>
                                    <td>' . $savdosvodname->unix_id . '</td>
                                    <td>' . $savdosvodname->status . '</td>
                                    <td>' . number_format($savdosvodname->kirimnarhi, 0, ",", " ") . '</td>
                                    <td>' . number_format($savdosvodname->sotuvnarhi, 0, ",", " ") . '</td>
                                    <td>' . number_format($savdosvodname->msumma, 0, ",", " ") . '</td>
                                    <td>' . number_format($savdosvodname->chegirma, 0, ",", " ") . '</td>
                                    <td>' . number_format($savdosvodname->qushimch, 0, ",", " ") . '</td>
                                    <td>' . number_format($savdosvodname->bonus, 0, ",", " ") . '</td>
                                    <td>' . number_format($savdosvodname->msumma-$savdosvodname->kirimnarhi, 0, ",", " ") . '</td>
                                </tr>
                            ';
                            $uksumma+=$savdosvodname->kirimnarhi;
                            $ussumma+=$savdosvodname->sotuvnarhi;
                            $usotsumma+=$savdosvodname->msumma;
                            $uqsumma+=$savdosvodname->qushimch;
                            $uchsumma+=$savdosvodname->chegirma;
                            $ubsumma+=$savdosvodname->bonus;

                        }


                    }

                     echo'
                            <tr class="text-bold">
                                <td></td>
                                <td></td>
                                <td><b>ЖАМИ</b></td>
                                <td></td>
                                <td></td>
                                <td><b>' . number_format($uksumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($ussumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($usotsumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($uchsumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($uqsumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($ubsumma, 0, ",", " ") . '</b></td>
                                <td><b>' . number_format($usotsumma-$uksumma, 0, ",", " ") . '</b></td>
                            </tr>
                </tbody>
            </table>';
        return;
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
