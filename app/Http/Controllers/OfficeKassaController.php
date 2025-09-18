<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\chiqim_boshqa;
use App\Models\chiqim_taminot;
use App\Models\kirim;
use App\Models\kirim_dollar;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;

use Illuminate\Support\Facades\Validator;


class OfficeKassaController extends Controller
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
            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();


            $duombor = date("m", strtotime($xis_oyi));
            switch ($duombor) {
                case 1:
                    $du2 =  date("Y") . " йил Январь";
                    break;
                case 2:
                    $du2 =  date("Y") . " йил Февраль";
                    break;
                case 3:
                    $du2 =  date("Y") . " йил Март";
                    break;
                case 4:
                    $du2 =  date("Y") . " йил Апрель";
                    break;
                case 5:
                    $du2 =  date("Y") . " йил Май";
                    break;
                case 6:
                    $du2 =  date("Y") . " йил Июнь";
                    break;
                case 7:
                    $du2 =  date("Y") . " йил Июль";
                    break;
                case 8:
                    $du2 =  date("Y") . " йил Август";
                    break;
                case 9:
                    $du2 =  date("Y") . " йил Сентябрь";
                    break;
                case 10:
                    $du2 =  date("Y") . " йил Октябрь";
                    break;
                case 11:
                    $du2 =  date("Y") . " йил Ноябрь";
                    break;
                case 12:
                    $du2 =  date("Y") . " йил Декабрь";
                    break;
            }

            return view('kassa.officekassa', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'filial' => $filial, 'du2' => $du2 ]);
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

            $naqd_oy_boshi_s = 0;
            $pastik_oy_boshi_s = 0;
            $hr_oy_boshi_s = 0;
            $click_oy_boshi_s = 0;
            $avtot_oy_boshi_s = 0;
            $umumiy_oy_boshi_s = 0;

            $naqd_oy_boshi_d = 0;
            $pastik_oy_boshi_d = 0;
            $hr_oy_boshi_d = 0;
            $click_oy_boshi_d = 0;
            $avtot_oy_boshi_d = 0;
            $umumiy_oy_boshi_d = 0;


            $naqd_xis_oy_s = 0;
            $pastik_xis_oy_s = 0;
            $hr_xis_oy_s = 0;
            $click_xis_oy_s = 0;
            $avtot_xis_oy_s = 0;
            $umumiy_xis_oy_s = 0;

            $naqd_xis_oy_d = 0;
            $pastik_xis_oy_d = 0;
            $hr_xis_oy_d = 0;
            $click_xis_oy_d = 0;
            $avtot_xis_oy_d = 0;
            $umumiy_xis_oy_d = 0;

            $kirim = kirim::where('status', 'Актив')->get();
            foreach ($kirim as $kirim) {

                if ($kirim->xis_oyi < $xis_oyi)
                {
                    if ($kirim->valyuta_id == 1) {
                        $naqd_oy_boshi_s += $kirim->naqd;
                        $pastik_oy_boshi_s += $kirim->pastik;
                        $hr_oy_boshi_s += $kirim->hr;
                        $click_oy_boshi_s += $kirim->click;
                        $avtot_oy_boshi_s += $kirim->avtot;
                        $umumiy_oy_boshi_s += $kirim->umumiy;
                    } else {
                        $naqd_oy_boshi_d += $kirim->naqd;
                        $pastik_oy_boshi_d += $kirim->pastik;
                        $hr_oy_boshi_d += $kirim->hr;
                        $click_oy_boshi_d += $kirim->click;
                        $avtot_oy_boshi_d += $kirim->avtot;
                        $umumiy_oy_boshi_d += $kirim->umumiy;
                    }
                }

                if ($kirim->xis_oyi == $xis_oyi)
                {
                    if ($kirim->valyuta_id == 1)
                    {
                        $naqd_xis_oy_s += $kirim->naqd;
                        $pastik_xis_oy_s += $kirim->pastik;
                        $hr_xis_oy_s += $kirim->hr;
                        $click_xis_oy_s += $kirim->click;
                        $avtot_xis_oy_s += $kirim->avtot;
                        $umumiy_xis_oy_s += $kirim->umumiy;
                    } else {
                        $naqd_xis_oy_d += $kirim->naqd;
                        $pastik_xis_oy_d += $kirim->pastik;
                        $hr_xis_oy_d += $kirim->hr;
                        $click_xis_oy_d += $kirim->click;
                        $avtot_xis_oy_d += $kirim->avtot;
                        $umumiy_xis_oy_d += $kirim->umumiy;
                    }
                }
            }


            $ch_naqd_oy_boshi_s = 0;
            $ch_pastik_oy_boshi_s = 0;
            $ch_hr_oy_boshi_s = 0;
            $ch_click_oy_boshi_s = 0;
            $ch_avtot_oy_boshi_s = 0;
            $ch_umumiy_oy_boshi_s = 0;

            $ch_naqd_oy_boshi_d = 0;
            $ch_pastik_oy_boshi_d = 0;
            $ch_hr_oy_boshi_d = 0;
            $ch_click_oy_boshi_d = 0;
            $ch_avtot_oy_boshi_d = 0;
            $ch_umumiy_oy_boshi_d = 0;


            $ch_naqd_xis_oy_s = 0;
            $ch_pastik_xis_oy_s = 0;
            $ch_hr_xis_oy_s = 0;
            $ch_click_xis_oy_s = 0;
            $ch_avtot_xis_oy_s = 0;
            $ch_umumiy_xis_oy_s = 0;

            $ch_naqd_xis_oy_d = 0;
            $ch_pastik_xis_oy_d = 0;
            $ch_hr_xis_oy_d = 0;
            $ch_click_xis_oy_d = 0;
            $ch_avtot_xis_oy_d = 0;
            $ch_umumiy_xis_oy_d = 0;

            $chiqim = chiqim_boshqa::where('status', 'Актив')->get();
            foreach ($chiqim as $chiqim) {

                if ($chiqim->xis_oyi < $xis_oyi)
                {
                    if($chiqim->valyuta_id == 1){
                        $ch_naqd_oy_boshi_s += $chiqim->naqd;
                        $ch_pastik_oy_boshi_s += $chiqim->pastik;
                        $ch_hr_oy_boshi_s += $chiqim->hr;
                        $ch_click_oy_boshi_s += $chiqim->click;
                        $ch_avtot_oy_boshi_s += $chiqim->avtot;
                        $ch_umumiy_oy_boshi_s += $chiqim->rsumma;
                    } else {
                        $ch_naqd_oy_boshi_d += $chiqim->naqd;
                        $ch_pastik_oy_boshi_d += $chiqim->pastik;
                        $ch_hr_oy_boshi_d += $chiqim->hr;
                        $ch_click_oy_boshi_d += $chiqim->click;
                        $ch_avtot_oy_boshi_d += $chiqim->avtot;
                        $ch_umumiy_oy_boshi_d += $chiqim->rsumma;
                    }
                }

                if ($chiqim->xis_oyi == $xis_oyi)
                {
                    if ($chiqim->valyuta_id == 1)
                    {
                        $ch_naqd_xis_oy_s += $chiqim->naqd;
                        $ch_pastik_xis_oy_s += $chiqim->pastik;
                        $ch_hr_xis_oy_s += $chiqim->hr;
                        $ch_click_xis_oy_s += $chiqim->click;
                        $ch_avtot_xis_oy_s += $chiqim->avtot;
                        $ch_umumiy_xis_oy_s += $chiqim->rsumma;
                    } else {
                        $ch_naqd_xis_oy_d += $chiqim->naqd;
                        $ch_pastik_xis_oy_d += $chiqim->pastik;
                        $ch_hr_xis_oy_d += $chiqim->hr;
                        $ch_click_xis_oy_d += $chiqim->click;
                        $ch_avtot_xis_oy_d += $chiqim->avtot;
                        $ch_umumiy_xis_oy_d += $chiqim->rsumma;
                    }
                }
            }


            $t_ch_naqd_oy_boshi_s = 0;
            $t_ch_pastik_oy_boshi_s = 0;
            $t_ch_hr_oy_boshi_s = 0;
            $t_ch_click_oy_boshi_s = 0;
            $t_ch_avtot_oy_boshi_s = 0;
            $t_ch_umumiy_oy_boshi_s = 0;

            $t_ch_naqd_oy_boshi_d = 0;
            $t_ch_pastik_oy_boshi_d = 0;
            $t_ch_hr_oy_boshi_d = 0;
            $t_ch_click_oy_boshi_d = 0;
            $t_ch_avtot_oy_boshi_d = 0;
            $t_ch_umumiy_oy_boshi_d = 0;


            $t_ch_naqd_xis_oy_s = 0;
            $t_ch_pastik_xis_oy_s = 0;
            $t_ch_hr_xis_oy_s = 0;
            $t_ch_click_xis_oy_s = 0;
            $t_ch_avtot_xis_oy_s = 0;
            $t_ch_umumiy_xis_oy_s = 0;

            $t_ch_naqd_xis_oy_d = 0;
            $t_ch_pastik_xis_oy_d = 0;
            $t_ch_hr_xis_oy_d = 0;
            $t_ch_click_xis_oy_d = 0;
            $t_ch_avtot_xis_oy_d = 0;
            $t_ch_umumiy_xis_oy_d = 0;

            $chiqim_tami = chiqim_taminot::where('status', 'Актив')->get();
            foreach ($chiqim_tami as $chiqim_tam) {

                if ($chiqim_tam->xis_oyi < $xis_oyi)
                {
                    if($chiqim_tam->valyuta_id == 1){
                        $t_ch_naqd_oy_boshi_s += $chiqim_tam->naqd;
                        $t_ch_pastik_oy_boshi_s += $chiqim_tam->pastik;
                        $t_ch_hr_oy_boshi_s += $chiqim_tam->hr;
                        $t_ch_click_oy_boshi_s += $chiqim_tam->click;
                        $t_ch_avtot_oy_boshi_s += $chiqim_tam->avtot;
                        $t_ch_umumiy_oy_boshi_s += $chiqim_tam->rsumma;
                    } else {
                        $t_ch_naqd_oy_boshi_d += $chiqim_tam->naqd;
                        $t_ch_pastik_oy_boshi_d += $chiqim_tam->pastik;
                        $t_ch_hr_oy_boshi_d += $chiqim_tam->hr;
                        $t_ch_click_oy_boshi_d += $chiqim_tam->click;
                        $t_ch_avtot_oy_boshi_d += $chiqim_tam->avtot;
                        $t_ch_umumiy_oy_boshi_d += $chiqim_tam->rsumma;
                    }
                }

                if ($chiqim_tam->xis_oyi == $xis_oyi)
                {
                    if ($chiqim_tam->valyuta_id == 1)
                    {
                        $t_ch_naqd_xis_oy_s += $chiqim_tam->naqd;
                        $t_ch_pastik_xis_oy_s += $chiqim_tam->pastik;
                        $t_ch_hr_xis_oy_s += $chiqim_tam->hr;
                        $t_ch_click_xis_oy_s += $chiqim_tam->click;
                        $t_ch_avtot_xis_oy_s += $chiqim_tam->avtot;
                        $t_ch_umumiy_xis_oy_s += $chiqim_tam->rsumma;
                    } else {
                        $t_ch_naqd_xis_oy_d += $chiqim_tam->naqd;
                        $t_ch_pastik_xis_oy_d += $chiqim_tam->pastik;
                        $t_ch_hr_xis_oy_d += $chiqim_tam->hr;
                        $t_ch_click_xis_oy_d += $chiqim_tam->click;
                        $t_ch_avtot_xis_oy_d += $chiqim_tam->avtot;
                        $t_ch_umumiy_xis_oy_d += $chiqim_tam->rsumma;
                    }
                }
            }


            $da_naqd_oy_boshi_s = 0;
            $da_pastik_oy_boshi_s = 0;
            $da_hr_oy_boshi_s = 0;
            $da_click_oy_boshi_s = 0;
            $da_umumiy_oy_boshi_s = 0;
            $da_summa_oy_boshi = 0;

            $da_naqd_xis_oy_s = 0;
            $da_pastik_xis_oy_s = 0;
            $da_hr_xis_oy_s = 0;
            $da_click_xis_oy_s = 0;
            $da_umumiy_xis_oy_s = 0;
            $da_summa_xis_oy_s = 0;


            $kirim_dollar = kirim_dollar::where('status', 'Актив')->get();
            foreach ($kirim_dollar as $kirim_d) {

                if ($kirim_d->xis_oyi < $xis_oyi)
                {
                    $da_naqd_oy_boshi_s += $kirim_d->naqd;
                    $da_pastik_oy_boshi_s += $kirim_d->pastik;
                    $da_hr_oy_boshi_s += $kirim_d->hr;
                    $da_click_oy_boshi_s += $kirim_d->click;
                    $da_umumiy_oy_boshi_s += $kirim_d->umumiy;
                    $da_summa_oy_boshi += $kirim_d->dollar_summa;
                }

                if ($kirim_d->xis_oyi == $xis_oyi)
                {
                    $da_naqd_xis_oy_s += $kirim_d->naqd;
                    $da_pastik_xis_oy_s += $kirim_d->pastik;
                    $da_hr_xis_oy_s += $kirim_d->hr;
                    $da_click_xis_oy_s += $kirim_d->click;
                    $da_umumiy_xis_oy_s += $kirim_d->umumiy;
                    $da_summa_xis_oy_s += $kirim_d->dollar_summa;
                }
            }

            $zads01 = 0;
            $zadd01 = 0;
            $opls = 0;
            $opld = 0;
            $nachs = 0;
            $nachd = 0;

            $zads01 = (
                        $naqd_oy_boshi_s-$ch_naqd_oy_boshi_s-$t_ch_naqd_oy_boshi_s-$da_naqd_oy_boshi_s+
                        $pastik_oy_boshi_s-$ch_pastik_oy_boshi_s-$t_ch_pastik_oy_boshi_s-$da_pastik_oy_boshi_s+
                        $hr_oy_boshi_s-$ch_hr_oy_boshi_s-$t_ch_hr_oy_boshi_s-$da_hr_oy_boshi_s+
                        $click_oy_boshi_s-$ch_click_oy_boshi_s-$t_ch_click_oy_boshi_s-$da_click_oy_boshi_s
                    );

            $zadd01 = (
                        $naqd_oy_boshi_d-$ch_naqd_oy_boshi_d-$t_ch_naqd_oy_boshi_d+$da_summa_oy_boshi+
                        $pastik_oy_boshi_d-$ch_pastik_oy_boshi_d-$t_ch_pastik_oy_boshi_d+
                        $hr_oy_boshi_d-$ch_hr_oy_boshi_d-$t_ch_hr_oy_boshi_d+
                        $click_oy_boshi_s-$ch_click_oy_boshi_s-$t_ch_click_oy_boshi_s-$da_click_oy_boshi_s
                    );

            $opls =  (
                        $naqd_xis_oy_s+
                        $pastik_xis_oy_s+
                        $hr_xis_oy_s+
                        $click_xis_oy_s
                    );
            $opld =  (
                        $naqd_xis_oy_d + $da_summa_xis_oy_s+
                        $pastik_xis_oy_d+
                        $hr_xis_oy_d+
                        $click_xis_oy_d
                    );
            $nachs = (
                        $ch_naqd_xis_oy_s+$t_ch_naqd_xis_oy_s + $da_naqd_xis_oy_s+
                        $ch_pastik_xis_oy_s+$t_ch_pastik_xis_oy_s+$da_pastik_xis_oy_s+
                        $ch_hr_xis_oy_s+$t_ch_hr_xis_oy_s+$da_hr_xis_oy_s+
                        $ch_click_xis_oy_s+$t_ch_click_xis_oy_s+$da_click_xis_oy_s
                    );
            $nachd = (
                        $ch_naqd_xis_oy_d+$t_ch_naqd_xis_oy_d+
                        $ch_pastik_xis_oy_d+$t_ch_pastik_xis_oy_d+
                        $ch_hr_xis_oy_d+$t_ch_hr_xis_oy_d+
                        $ch_click_xis_oy_d+$t_ch_click_xis_oy_d
                    );


        echo"
            <tr>
                <td>1</td>
                <td>Нақд</td>
                <td>" . number_format($naqd_oy_boshi_s-$ch_naqd_oy_boshi_s-$t_ch_naqd_oy_boshi_s-$da_naqd_oy_boshi_s, 2, ',', ' ') . "</td>
                <td>" . number_format($naqd_oy_boshi_d-$ch_naqd_oy_boshi_d-$t_ch_naqd_oy_boshi_d+$da_summa_oy_boshi, 2, ',', ' ') . "</td>
                <td>" . number_format($naqd_xis_oy_s, 2, ',', ' ') . "</td>
                <td>" . number_format($naqd_xis_oy_d + $da_summa_xis_oy_s, 2, ',', ' ') . "</td>
                <td>" . number_format($ch_naqd_xis_oy_s+$t_ch_naqd_xis_oy_s + $da_naqd_xis_oy_s, 2, ',', ' ') . "</td>
                <td>" . number_format($ch_naqd_xis_oy_d+$t_ch_naqd_xis_oy_d, 2, ',', ' ') . "</td>
                <td>" . number_format(($naqd_oy_boshi_s-$ch_naqd_oy_boshi_s-$t_ch_naqd_oy_boshi_s-$da_naqd_oy_boshi_s)+($naqd_xis_oy_s)-($ch_naqd_xis_oy_s+$t_ch_naqd_xis_oy_s + $da_naqd_xis_oy_s), 2, ',', ' ') . "</td>
                <td>" . number_format(($naqd_oy_boshi_d-$ch_naqd_oy_boshi_d-$t_ch_naqd_oy_boshi_d+$da_summa_oy_boshi)+($naqd_xis_oy_d + $da_summa_xis_oy_s)-($ch_naqd_xis_oy_d+$t_ch_naqd_xis_oy_d), 2, ',', ' ') . "</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Пластик</td>
                <td>" . number_format($pastik_oy_boshi_s-$ch_pastik_oy_boshi_s-$t_ch_pastik_oy_boshi_s-$da_pastik_oy_boshi_s, 2, ',', ' ') . "</td>
                <td>" . number_format($pastik_oy_boshi_d-$ch_pastik_oy_boshi_d-$t_ch_pastik_oy_boshi_d, 2, ',', ' ') . "</td>
                <td>" . number_format($pastik_xis_oy_s , 2, ',', ' ') . "</td>
                <td>" . number_format($pastik_xis_oy_d, 2, ',', ' ') . "</td>
                <td>" . number_format($ch_pastik_xis_oy_s+$t_ch_pastik_xis_oy_s+$da_pastik_xis_oy_s, 2, ',', ' ') . "</td>
                <td>" . number_format($ch_pastik_xis_oy_d+$t_ch_pastik_xis_oy_d,2, ',', ' ') . "</td>
                <td>" . number_format(($pastik_oy_boshi_s-$ch_pastik_oy_boshi_s-$t_ch_pastik_oy_boshi_s-$da_pastik_oy_boshi_s)+($pastik_xis_oy_s)-($ch_pastik_xis_oy_s+$t_ch_pastik_xis_oy_s+$da_pastik_xis_oy_s), 2, ',', ' ') . "</td>
                <td>" . number_format(($pastik_oy_boshi_d-$ch_pastik_oy_boshi_d-$t_ch_pastik_oy_boshi_d)+($pastik_xis_oy_d)-($ch_pastik_xis_oy_d+$t_ch_pastik_xis_oy_d), 2, ',', ' ') . "</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Хисоб-рақам</td>
                <td>" . number_format($hr_oy_boshi_s-$ch_hr_oy_boshi_s-$t_ch_hr_oy_boshi_s-$da_hr_oy_boshi_s, 2, ',', ' ') . "</td>
                <td>" . number_format($hr_oy_boshi_d-$ch_hr_oy_boshi_d-$t_ch_hr_oy_boshi_d, 2, ',', ' ') . "</td>
                <td>" . number_format($hr_xis_oy_s , 2, ',', ' ') . "</td>
                <td>" . number_format($hr_xis_oy_d, 2, ',', ' ') . "</td>
                <td>" . number_format($ch_hr_xis_oy_s+$t_ch_hr_xis_oy_s+$da_hr_xis_oy_s, 2, ',', ' ') . "</td>
                <td>" . number_format($ch_hr_xis_oy_d+$t_ch_hr_xis_oy_d, 2, ',', ' ') . "</td>
                <td>" . number_format(($hr_oy_boshi_s-$ch_hr_oy_boshi_s-$t_ch_hr_oy_boshi_s-$da_hr_oy_boshi_s)+($hr_xis_oy_s)-($ch_hr_xis_oy_s+$t_ch_hr_xis_oy_s+$da_hr_xis_oy_s), 2, ',', ' ') . "</td>
                <td>" . number_format(($hr_oy_boshi_d-$ch_hr_oy_boshi_d-$t_ch_hr_oy_boshi_d)+($hr_xis_oy_d)-($ch_hr_xis_oy_d+$t_ch_hr_xis_oy_d), 2, ',', ' ') . "</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Click</td>
                <td>" . number_format($click_oy_boshi_s-$ch_click_oy_boshi_s-$t_ch_click_oy_boshi_s-$da_click_oy_boshi_s, 2, ',', ' ') . "</td>
                <td>" . number_format($click_oy_boshi_d-$ch_click_oy_boshi_d-$t_ch_click_oy_boshi_d, 2, ',', ' ') . "</td>
                <td>" . number_format($click_xis_oy_s , 2, ',', ' ') . "</td>
                <td>" . number_format($click_xis_oy_d, 2, ',', ' ') . "</td>
                <td>" . number_format($ch_click_xis_oy_s+$t_ch_click_xis_oy_s+$da_click_xis_oy_s, 2, ',', ' ') . "</td>
                <td>" . number_format($ch_click_xis_oy_d+$t_ch_click_xis_oy_d, 2, ',', ' ') . "</td>
                <td>" . number_format(($click_oy_boshi_s-$ch_click_oy_boshi_s-$t_ch_click_oy_boshi_s-$da_click_oy_boshi_s)+($click_xis_oy_s)-($ch_click_xis_oy_s+$t_ch_click_xis_oy_s+$da_click_xis_oy_s), 2, ',', ' ') . "</td>
                <td>" . number_format(($click_oy_boshi_d-$ch_click_oy_boshi_d-$t_ch_click_oy_boshi_d)+($click_xis_oy_d)-($ch_click_xis_oy_d+$t_ch_click_xis_oy_d), 2, ',', ' ') . "</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Авто тўлов</td>
                <td>" . number_format(0, 2, ',', ' ') . "</td>
                <td>" . number_format(0, 2, ',', ' ') . "</td>
                <td>" . number_format(0, 2, ',', ' ') . "</td>
                <td>" . number_format(0, 2, ',', ' ') . "</td>
                <td>" . number_format(0, 2, ',', ' ') . "</td>
                <td>" . number_format(0, 2, ',', ' ') . "</td>
                <td>" . number_format(0, 2, ',', ' ') . "</td>
                <td>" . number_format(0, 2, ',', ' ') . "</td>
            </tr>
            <tr class='text-center align-middle fw-bold'>
                <td></td>
                <td>Жами</td>
                <td>" . number_format($zads01, 2, ',', ' ') . "</td>
                <td>" . number_format($zadd01, 2, ',', ' ') . "</td>
                <td>" . number_format($opls, 2, ',', ' ') . "</td>
                <td>" . number_format($opld, 2, ',', ' ') . "</td>
                <td>" . number_format($nachs, 2, ',', ' ') . "</td>
                <td>" . number_format($nachd, 2, ',', ' ') . "</td>
                <td>" . number_format($zads01+$opls-$nachs, 2, ',', ' ') . "</td>
                <td>" . number_format($zadd01+$opld-$nachd, 2, ',', ' ') . "</td>
            </tr>
        ";
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
