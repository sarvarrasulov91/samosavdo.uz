<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\pastavshik;
use App\Models\chiqim_taminot;
use App\Models\xissobotoy;
use App\Models\filial;


class XisobotTaminotchiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {

            return view('xisobotlar.taminotchlar');

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

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $i = 1;
            $umzadd01s = 0;
            $umzadd01d = 0;
            $umnachs = 0;
            $umnachd = 0;
            $uqayts = 0;
            $uqaytd = 0;

            $umopls = 0;
            $umopld = 0;
            $umzadd13s = 0;
            $umzadd13d = 0;

            $pastavshik = pastavshik::where('status', 'Актив')->get();
            foreach ($pastavshik as $pastavshi) {
                $pas_id = $pastavshi->id;

                $zadd01s = 0;
                $zadd01d = 0;
                $nachs = 0;
                $nachd = 0;

                $qayt01s = 0;
                $qayt01d = 0;
                $qayts = 0;
                $qaytd = 0;

                $filial = filial::where('status', 'Актив')->get();
                foreach ($filial as $filial) {
                    $ktovar = 'ktovar' . $filial->id;
                    $rkrimtomars = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('xis_oyi', '<', $xis_oyi)->where('status', '!=', 'Удалит')->sum('narhi');
                    $rkrimtomard = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('xis_oyi', '<', $xis_oyi)->where('status', '!=', 'Удалит')->sum('narhi');

                    $rkrimtqayts = DB::table($ktovar)->where('pastavshik2_id', $pas_id)->where('valyuta_id', '1')->where('ch_xis_oyi', '<', $xis_oyi)->where('status', 'Кайтган')->sum('narhi');
                    $rkrimtqaytd = DB::table($ktovar)->where('pastavshik2_id', $pas_id)->where('valyuta_id', '2')->where('ch_xis_oyi', '<', $xis_oyi)->where('status', 'Кайтган')->sum('narhi');

                    $rkrimts = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('xis_oyi', '=', $xis_oyi)->where('status', '!=', 'Удалит')->sum('narhi');
                    $rkrimtd = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('xis_oyi', '=', $xis_oyi)->where('status', '!=', 'Удалит')->sum('narhi');

                    $xisoyqaytgans = DB::table($ktovar)->where('pastavshik2_id', $pas_id)->where('valyuta_id', '1')->where('ch_xis_oyi', '=', $xis_oyi)->where('status', 'Кайтган')->sum('narhi');
                    $xisoyqaytgand = DB::table($ktovar)->where('pastavshik2_id', $pas_id)->where('valyuta_id', '2')->where('ch_xis_oyi', '=', $xis_oyi)->where('status', 'Кайтган')->sum('narhi');



                    $zadd01s += $rkrimtomars;
                    $zadd01d += $rkrimtomard;
                    $qayt01s += $rkrimtqayts;
                    $qayt01d += $rkrimtqaytd;

                    $nachs += $rkrimts;
                    $nachd += $rkrimtd;
                    $qayts += $xisoyqaytgans;
                    $qaytd += $xisoyqaytgand;

                }

                $rtulovsums01 = DB::table('chiqim_taminot')->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('xis_oyi', '<', $xis_oyi)->where('status', '!=', 'Удалит')->sum('rsumma');
                $rtulovsumd01 = DB::table('chiqim_taminot')->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('xis_oyi', '<', $xis_oyi)->where('status', '!=', 'Удалит')->sum('rsumma');

                $rtulovsumsx = DB::table('chiqim_taminot')->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('xis_oyi', '=', $xis_oyi)->where('status', '!=', 'Удалит')->sum('rsumma');
                $rtulovsumdx = DB::table('chiqim_taminot')->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('xis_oyi', '=', $xis_oyi)->where('status', '!=', 'Удалит')->sum('rsumma');


                echo "
                <tr id='modalgamurojat' data-id='".$pastavshi->id."' data-pas_name='". $pastavshi->pastav_name."'>";
                echo '
                        <td>' . $pastavshi->id . '</td>
                        <td>' . $pastavshi->pastav_name . '</td>
                        <td>' . number_format($zadd01s - $qayt01s - $rtulovsums01, 0, ",", " ") . '</td>
                        <td>' . number_format($zadd01d - $qayt01d - $rtulovsumd01, 2, ",", " ") . '</td>
                        <td>' . number_format($nachs, 0, ",", " ") . '</td>
                        <td>' . number_format($nachd, 2, ",", " ") . '</td>
                        <td>' . number_format($qayts, 0, ",", " ") . '</td>
                        <td>' . number_format($qaytd, 2, ",", " ") . '</td>
                        <td>' . number_format($rtulovsumsx, 0, ",", " ") . '</td>
                        <td>' . number_format($rtulovsumdx, 2, ",", " ") . '</td>
                        <td>' . number_format(($zadd01s - $qayt01s - $rtulovsums01) + $nachs - $qayts - $rtulovsumsx, 0, ",", " ") . '</td>
                        <td>' . number_format(($zadd01d - $qayt01d - $rtulovsumd01) + $nachd - $qaytd - $rtulovsumdx, 2, ",", " ") . '</td>

                    </tr>
                ';

                $umzadd01s += $zadd01s - $rtulovsums01;
                $umzadd01d += $zadd01d - $rtulovsumd01;
                $umnachs += $nachs;
                $umnachd += $nachd;
                $uqayts += $qayts;
                $uqaytd += $qaytd;

                $umopls += $rtulovsumsx;
                $umopld += $rtulovsumdx;
                $umzadd13s += ($zadd01s - $qayt01s - $rtulovsums01) + $nachs - $qayts - $rtulovsumsx;
                $umzadd13d += ($zadd01d - $qayt01d - $rtulovsumd01) + $nachd - $qaytd - $rtulovsumdx;
            }

            echo '
                <tr class="fw-bold">
                    <td></td>
                    <td>ЖАМИ</td>
                    <td>' . number_format($umzadd01s, 0, ",", " ") . '</td>
                    <td>' . number_format($umzadd01d, 2, ",", " ") . '</td>
                    <td>' . number_format($umnachs, 0, ",", " ") . '</td>
                    <td>' . number_format($umnachd, 2, ",", " ") . '</td>
                    <td>' . number_format($uqayts, 0, ",", " ") . '</td>
                    <td>' . number_format($uqaytd, 2, ",", " ") . '</td>
                    <td>' . number_format($umopls, 0, ",", " ") . '</td>
                    <td>' . number_format($umopld, 2, ",", " ") . '</td>
                    <td>' . number_format($umzadd13s, 0, ",", " ") . '</td>
                    <td>' . number_format($umzadd13d, 2, ",", " ") . '</td>
                </tr>
            ';
        return ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $pas_id = $request->pas_id;
        $xis_oy = $request->xis_oy;
        $pash_name = $request->pash_name;
        $du2 = $request->du2;
        $i = 1;
        $dollarsoni = 0;
        $dollarjami = 0;
        $sumsoni = 0;
        $sumjami = 0;

        echo '
        <table class="table table-bordered table-responsive-sm text-center align-middle ">
        <thead>
            <tr class="text-bold text-primary align-middle">
                <th rowspan="2">ID</th>
                <th rowspan="2">Филиал<br>номи</th>
                <th rowspan="2">Хисобот<br>ойи</th>
                <th colspan="2">Сўм</th>
                <th colspan="2">Доллар</th>
            </tr>
            <tr class="text-bold text-primary align-middle">
                <th>Сони</th>
                <th>Суммаси</th>
                <th>Сони</th>
                <th>Суммаси</th>
            </tr>
        </thead>
        <tbody >
        ';

        $filial = filial::where('status', 'Актив')->get();
        foreach ($filial as $filial) {
            $ktovar = 'ktovar' . $filial->id;

            $rssoni = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('xis_oyi', '=', $xis_oy)->where('status', '!=', 'Удалит')->count('narhi');
            $rs = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('xis_oyi', '=', $xis_oy)->where('status', '!=', 'Удалит')->sum('narhi');
            $rdsoni = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('xis_oyi', '=', $xis_oy)->where('status', '!=', 'Удалит')->count('narhi');
            $rd = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('xis_oyi', '=', $xis_oy)->where('status', '!=', 'Удалит')->sum('narhi');


            echo "
            <tr id='modalkunlarfil' data-rfilia='".$filial->id."' data-fil_name='".$filial->fil_name."' data-pas_id='".$pas_id."' data-name_pas='".$du2."' data-tek_oy='".$pash_name."' data-xis_oy='".$xis_oy."'>";
            echo '
                    <td>' . $i . '</td>
                    <td>' . $filial->fil_name . '</td>
                    <td>' . $du2 . '</td>
                    <td>' . number_format($rssoni, 0, ",", " ") . '</td>
                    <td>' . number_format($rs, 0, ",", " ") . '</td>
                    <td>' . number_format($rdsoni, 0, ",", " ") . '</td>
                    <td>' . number_format($rd, 2, ",", " ") . '</td>
                </tr>
            ';
            $i++;
            $sumsoni += $rssoni;
            $sumjami += $rs;
            $dollarsoni += $rdsoni;
            $dollarjami += $rd;
        }
        echo '
            <tr class="fw-bold">
                <td></td>
                <td>ЖАМИ</td>
                <td></td>
                <td>' . number_format($sumsoni, 0, ",", " ") . '</td>
                <td>' . number_format($sumjami, 0, ",", " ") . '</td>
                <td>' . number_format($dollarsoni, 0, ",", " ") . '</td>
                <td>' . number_format($dollarjami, 2, ",", " ") . '</td>
            </tr>
            </tbody>
            </table>
        ';

                echo'
                <h5 class=" text-center text-uppercase" style="color: RoyalBlue;">Таъминотчига кайтарилган туловлар</h5>
                <table class="table table-bordered table-responsive-sm text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary">
                        <th>ID</th>
                        <th>Куни</th>
                        <th>Таъминотчи</th>
                        <th>Валюта</th>
                        <th>Нақд</th>
                        <th>Пластик</th>
                        <th>Х-р</th>
                        <th>Сlick</th>
                        <th>Жами</th>
                        <th>Изох</th>
                    </tr>
                </thead>
                <tbody id="chiqim_taminot_show">
            ';

                $chiqimtaminot = chiqim_taminot::where('pastavshik_id', $pas_id)->where('status', 'Актив')->where('xis_oyi', $xis_oy)->orderBy('id', 'desc')->get();

                    foreach ($chiqimtaminot as $chiqimtam){
                        echo'
                        <tr>
                            <td>' . $chiqimtam->id . '</td>
                            <td>' . date('d.m.Y', strtotime($chiqimtam->kun)) . '</td>
                            <td>' . $chiqimtam->pastavshik->pastav_name . '</td>
                            <td>' . $chiqimtam->valyuta->valyuta__nomi . '</td>
                            <td>' . number_format($chiqimtam->naqd, 2, ",", " ") . '</td>
                            <td>' . number_format($chiqimtam->pastik, 2, ",", " ") . '
                            </td>
                            <td>' . number_format($chiqimtam->hr, 2, ",", " ") . '</td>
                            <td>' . number_format($chiqimtam->click, 2, ",", " ") . '
                            </td>
                            <td>' . number_format($chiqimtam->rsumma, 2, ",", " ") . '
                            </td>
                            <td>' . $chiqimtam->izox . '</td>
                        </tr>
                        ';
                    }

        echo'
                </tbody>
            </table>
        ';

        return;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $i = 1;
        $umzadd01s = 0;
        $umzadd01d = 0;
        $umnachs = 0;
        $umnachd = 0;
        $umopls = 0;
        $umopld = 0;
        $pas_id = $id;

        $uqayts = 0;
        $uqaytd = 0;

        $umzadd13s = 0;
        $umzadd13d = 0;

        $pastavshik = pastavshik::where('id', $pas_id)->get()->toArray();
        $pash_name = $pastavshik[0]['pastav_name'];

        $rktovar01 = DB::table('ktovar1')->select('xis_oyi')->groupBy('xis_oyi')->get();
        foreach ($rktovar01 as $rktovar) {
            $xis_oyi = $rktovar->xis_oyi;
            $xiso = date("m", strtotime($xis_oyi));

            if ($xiso == 01) {
                $du2 = "Январь-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 2) {
                $du2 = "Февраль-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 3) {
                $du2 = "Март-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 4) {
                $du2 = "Апрель-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 5) {
                $du2 = "Май-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 6) {
                $du2 = "Июнь-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 7) {
                $du2 = "Июль-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 8) {
                $du2 = "Август-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 9) {
                $du2 = "Сентябрь-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 10) {
                $du2 = "Октябрь-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 11) {
                $du2 = "Ноябрь-" . date("Y", strtotime($xis_oyi)) . "й";
            } elseif ($xiso == 12) {
                $du2 = "Декабрь-" . date("Y", strtotime($xis_oyi)) . "й";
            }

            $zadd01s = 0;
            $zadd01d = 0;

            $qayt01s = 0;
            $qayt01d = 0;


            $nachs = 0;
            $nachd = 0;
            $qayts = 0;
            $qaytd = 0;

            $filial = filial::where('status', 'Актив')->get();
            foreach ($filial as $filial) {
                $ktovar = 'ktovar' . $filial->id;
                $rkrimtomars = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('xis_oyi', '<', $xis_oyi)->where('status', '!=', 'Удалит')->sum('narhi');
                $rkrimtomard = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('xis_oyi', '<', $xis_oyi)->where('status', '!=', 'Удалит')->sum('narhi');

                $rkrimtqayts = DB::table($ktovar)->where('pastavshik2_id', $pas_id)->where('valyuta_id', '1')->where('ch_xis_oyi', '<', $xis_oyi)->where('status', 'Кайтган')->sum('narhi');
                $rkrimtqaytd = DB::table($ktovar)->where('pastavshik2_id', $pas_id)->where('valyuta_id', '2')->where('ch_xis_oyi', '<', $xis_oyi)->where('status', 'Кайтган')->sum('narhi');



                $rkrimts = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('xis_oyi', '=', $xis_oyi)->where('status', '!=', 'Удалит')->sum('narhi');
                $rkrimtd = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('xis_oyi', '=', $xis_oyi)->where('status', '!=', 'Удалит')->sum('narhi');

                $xisoyqaytgans = DB::table($ktovar)->where('pastavshik2_id', $pas_id)->where('valyuta_id', '1')->where('ch_xis_oyi', '=', $xis_oyi)->where('status', 'Кайтган')->sum('narhi');
                $xisoyqaytgand = DB::table($ktovar)->where('pastavshik2_id', $pas_id)->where('valyuta_id', '2')->where('ch_xis_oyi', '=', $xis_oyi)->where('status', 'Кайтган')->sum('narhi');


                $zadd01s += $rkrimtomars;
                $zadd01d += $rkrimtomard;
                $nachs += $rkrimts;
                $nachd += $rkrimtd;

                $qayt01s += $rkrimtqayts;
                $qayt01d += $rkrimtqaytd;

                $qayts += $xisoyqaytgans;
                $qaytd += $xisoyqaytgand;

            }

            $rtulovsums01 = DB::table('chiqim_taminot')->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('xis_oyi', '<', $xis_oyi)->where('status', '!=', 'Удалит')->sum('rsumma');
            $rtulovsumd01 = DB::table('chiqim_taminot')->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('xis_oyi', '<', $xis_oyi)->where('status', '!=', 'Удалит')->sum('rsumma');

            $rtulovsumsx = DB::table('chiqim_taminot')->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('xis_oyi', '=', $xis_oyi)->where('status', '!=', 'Удалит')->sum('rsumma');
            $rtulovsumdx = DB::table('chiqim_taminot')->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('xis_oyi', '=', $xis_oyi)->where('status', '!=', 'Удалит')->sum('rsumma');

            echo "<tr id='modalgamurojatfil' data-pas_id='".$pas_id."' data-du2='".$du2."' data-pash_name='".$pash_name."' data-xis_oy='".$xis_oyi."'>";

            echo '<td>' . $i . '</td>
                    <td>' . $du2 . '</td>
                    <td>' . number_format($zadd01s - $qayt01s - $rtulovsums01, 0, ",", " ") . '</td>
                    <td>' . number_format($zadd01d - $qayt01d - $rtulovsumd01, 2, ",", " ") . '</td>
                    <td>' . number_format($nachs, 0, ",", " ") . '</td>
                    <td>' . number_format($nachd, 2, ",", " ") . '</td>
                    <td>' . number_format($qayts, 0, ",", " ") . '</td>
                    <td>' . number_format($qaytd, 2, ",", " ") . '</td>
                    <td>' . number_format($rtulovsumsx, 0, ",", " ") . '</td>
                    <td>' . number_format($rtulovsumdx, 2, ",", " ") . '</td>
                    <td>' . number_format(($zadd01s - $qayt01s - $rtulovsums01) + $nachs - $qayts - $rtulovsumsx, 0, ",", " ") . '</td>
                    <td>' . number_format(($zadd01d - $qayt01d - $rtulovsumd01) + $nachd - $qaytd - $rtulovsumdx, 2, ",", " ") . '</td>
               </tr>
            ';

            $i++;
            $umzadd01s += $zadd01s - $rtulovsums01;
            $umzadd01d += $zadd01d - $rtulovsumd01;
            $umnachs += $nachs;
            $umnachd += $nachd;
            $umopls += $rtulovsumsx;
            $umopld += $rtulovsumdx;

            $uqayts += $qayts;
            $uqaytd += $qaytd;

            $umzadd13s += ($zadd01s - $qayt01s - $rtulovsums01) + $nachs - $uqayts - $rtulovsumsx;
            $umzadd13d += ($zadd01d - $qayt01d - $rtulovsumd01) + $nachd - $uqaytd - $rtulovsumdx;
        }
        echo '<tr class="fw-bold">
                <td></td>
                <td>ЖАМИ</td>
                <td></td>
                <td></td>
                <td>' . number_format($umnachs, 0, ",", " ") . '</td>
                <td>' . number_format($umnachd, 2, ",", " ") . '</td>
                <td>' . number_format($uqayts, 0, ",", " ") . '</td>
                <td>' . number_format($uqaytd, 2, ",", " ") . '</td>
                <td>' . number_format($umopls, 0, ",", " ") . '</td>
                <td>' . number_format($umopld, 2, ",", " ") . '</td>
                <td></td>
                <td></td>
            ';

            return;
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


    public function storekunlar(Request $request)
    {
        $rfilia = $request->rfilia;
        $fil_name = $request->fil_name;
        $pas_id = $request->pas_id;
        $name_pas = $request->name_pas;
        $tek_oy = $request->tek_oy;
        $xis_oy = $request->xis_oy;

        $ktovar = 'ktovar' . $rfilia;
        $i = 1;
        $dollarsoni = 0;
        $dollarjami = 0;
        $sumsoni = 0;
        $sumjami = 0;

        $rktovar = DB::table($ktovar)->select('kun')->where('pastavshik_id', $pas_id)->where('xis_oyi', $xis_oy)->where('status', '!=', 'Удалит')->groupBy('kun')->get();
        foreach ($rktovar as $rktova) {
            $kuntekshir = $rktova->kun;

            $rssoni = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('kun', $kuntekshir)->where('xis_oyi', '=', $xis_oy)->where('status', '!=', 'Удалит')->count('narhi');
            $rs = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '1')->where('kun', $kuntekshir)->where('xis_oyi', '=', $xis_oy)->where('status', '!=', 'Удалит')->sum('narhi');
            $rdsoni = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('kun', $kuntekshir)->where('xis_oyi', '=', $xis_oy)->where('status', '!=', 'Удалит')->count('narhi');
            $rd = DB::table($ktovar)->where('pastavshik_id', $pas_id)->where('valyuta_id', '2')->where('kun', $kuntekshir)->where('xis_oyi', '=', $xis_oy)->where('status', '!=', 'Удалит')->sum('narhi');

            echo "<tr id='mnomanom' data-rfilia='".$rfilia."' data-fil_name='".$fil_name."' data-pas_id='".$pas_id."' data-name_pas='".$name_pas."' data-tek_oy='".$tek_oy."' data-xis_oy='".$xis_oy."' data-kuni='".$kuntekshir."'>";

            echo '
                <td>' . $i . '</td>
                <td>' . date("d.m.Y", strtotime($kuntekshir)) . '</td>
                <td>' . number_format($rssoni, 0, ",", " ") . '</td>
                <td>' . number_format($rs, 0, ",", " ") . '</td>
                <td>' . number_format($rdsoni, 0, ",", " ") . '</td>
                <td>' . number_format($rd, 2, ",", " ") . '</td>
            </tr>
            ';
            $i++;
            $dollarsoni += $rdsoni;
            $dollarjami += $rd;
            $sumsoni += $rssoni;
            $sumjami += $rs;
        }

        echo '
        <tr class="fw-bold">
            <td></td>
            <td>ЖАМИ</td>
            <td>' . number_format($sumsoni, 0, ",", " ") . '</td>
            <td>' . number_format($sumjami, 0, ",", " ") . '</td>
            <td>' . number_format($dollarsoni, 0, ",", " ") . '</td>
            <td>' . number_format($dollarjami, 2, ",", " ") . '</td>
        </tr>
    ';

        return;
    }


    public function storename(Request $request)
    {
        $rfilia = $request->rfilia;
        $fil_name = $request->fil_name;
        $pas_id = $request->pas_id;
        $name_pas = $request->name_pas;
        $tek_oy = $request->tek_oy;
        $xis_oy = $request->xis_oy;
        $kuni = $request->kuni;

        $ktovar = 'ktovar' . $rfilia;

        $i = 1;
        $model = DB::table($ktovar)
            ->join('tur', $ktovar . '.tur_id', '=', 'tur.id')
            ->join('brend', $ktovar . '.brend_id', '=', 'brend.id')
            ->join('tmodel', $ktovar . '.tmodel_id', '=', 'tmodel.id')
            ->join('valyuta', $ktovar . '.valyuta_id', '=', 'valyuta.id')
            ->join('pastavshik', $ktovar . '.pastavshik_id', '=', 'pastavshik.id')
            ->select($ktovar . '.*', 'tur.tur_name', 'brend.brend_name', 'tmodel.model_name', 'valyuta.valyuta__nomi', 'pastavshik.pastav_name')
            ->where($ktovar . '.status', '!=', 'Удалит')->where($ktovar . '.status', '!=', 'Кайтган')->where($ktovar . '.pastavshik_id', '=', $pas_id)->where($ktovar . '.kun', '=', $kuni)->get();

        foreach ($model as $model) {

            echo '
            <tr>
               <td>' . $model->id . '</td>
               <td>' . date("d.m.Y", strtotime($model->kun)) . '</td>
               <td>' . $model->tur_name . ' ' . $model->brend_name . ' ' . $model->model_name . '</td>
               <td>' . $model->valyuta__nomi . '</td>
               <td>1</td>
               <td>' . number_format($model->narhi, 0, ",", " ") . '</td>
               <td>' . $model->status . '</td>
            </tr>
            ';
        }
        return;
    }
}

