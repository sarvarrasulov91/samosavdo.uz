<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\shartnoma1;
use App\Models\savdo1;
use App\Models\tulovlar1;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;
use Illuminate\Support\Facades\Auth;

class SHartTahlilOfficeController extends Controller
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

            return view('shartnoma.SHTahlil', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'filial' => $filial, 'du2' => $du2 ]);
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

        echo'
            <table
                class="table table-bordered table-responsive-sm text-center align-middle table-hover"
                style="font-size: 11px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th rowspan="2">ID</th>
                        <th rowspan="2">Филиал</th>
                        <th colspan="2">Ой бошига</th>
                        <th colspan="2">Тузилди</th>
                        <th colspan="2">Ёпилди</th>
                        <th colspan="2">Қўшилди</th>
                        <th colspan="2">Камайди</th>
                        <th colspan="2">Ой охирига</th>
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
                $uob_shsoni = 0;
                $uob_shtsumma = 0;
                $u_yo_shsoni = 0;
                $u_yo_shsumma = 0;
                $u_nach_shsoni = 0;
                $U_nach_shsumma = 0;
                $u_q_shsoni = 0;
                $u_q_shsumma = 0;
                $u_u_shsoni = 0;
                $u_u_shsumma = 0;
                $uoo_shsoni = 0;
                $uoo_shtsumma = 0;

                $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
                $filialbase = filial::where('status', 'Актив')->get();
                foreach ($filialbase as $filial) {
                    $shsoni = 0;
                    $shtsumma = 0;
                    $yo_shsoni = 0;
                    $yo_shsumma = 0;
                    $nach_shsoni = 0;
                    $nach_shsumma = 0;
                    $q_shsoni = 0;
                    $q_shsumma = 0;
                    $u_shsoni = 0;
                    $u_shsumma = 0;

                    $shartnoma = new shartnoma1($filial->id);
                    $shartnoma1=$shartnoma->get();
                    foreach ($shartnoma1 as $shart) {

                        if(($shart->xis_oyi < $xis_oyi && $shart->status=='Актив')){
                            $obsavdo = new savdo1($filial->id);
                            $obumumsavdo = $obsavdo->where('status2', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');
                            if($obumumsavdo>0){
                                $shsoni++;
                                $shtsumma+=$obumumsavdo;
                            }
                        }

                        if($shart->xis_oyi<$xis_oyi && $shart->yo_xis_oyi>0 && $shart->yo_xis_oyi>=$xis_oyi){
                            $yo_shsoni ++;
                            $savdo = new savdo1($filial->id);
                            $yo_shsumma+=$savdo->where('status', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');
                        }

                        if($shart->xis_oyi==$xis_oyi){
                            $nach_shsoni ++;
                            $savdo = new savdo1($filial->id);
                            $nach_shsumma +=$savdo->where('status2', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');
                        }

                        if($shart->xis_oyi<=$xis_oyi){
                            $qsavdo = new savdo1($filial->id);
                            $qushsavdo = $qsavdo->where('status', 'Шартнома')->where('shartnoma_id', $shart->id)->where('q_xis_oyi', $xis_oyi)->sum('msumma');
                            if($qushsavdo>0){
                                $q_shsoni ++;
                                $q_shsumma+=$qushsavdo;
                            }
                            $usavdo = new savdo1($filial->id);
                            $uushsavdo = $usavdo->where('status2', 'Шартнома')->where('shartnoma_id', $shart->id)->where('del_xis_oyi', $xis_oyi)->sum('msumma');
                            if($uushsavdo>0){
                                $u_shsoni ++;
                                $u_shsumma+=$uushsavdo;
                            }
                        }
                    }

                    echo '
                            <tr class="text-center align-middle" id="modalfil" data-filial_id="'.$filial->id.'"  data-filial_name="'.$filial->fil_name.'">
                                <td>' . $filial->id . '</td>
                                <td>' . $filial->fil_name . '</td>
                                <td>' . number_format($shsoni+$yo_shsoni, 0, ',', ' ') . '</td>
                                <td>' . number_format($shtsumma+$yo_shsumma, 0, ',', ' ') . '</td>
                                <td>' . number_format($nach_shsoni, 0, ',', ' ') . '</td>
                                <td>' . number_format($nach_shsumma, 0, ',', ' ') . '</td>
                                <td>' . number_format($yo_shsoni, 0, ',', ' ') . '</td>
                                <td>' . number_format($yo_shsumma, 0, ',', ' ') . '</td>
                                <td>' . number_format($q_shsoni, 0, ',', ' ') . '</td>
                                <td>' . number_format($q_shsumma, 0, ',', ' ') . '</td>
                                <td>' . number_format($u_shsoni, 0, ',', ' ') . '</td>
                                <td>' . number_format($u_shsumma, 0, ',', ' ') . '</td>
                                <td>' . number_format($shsoni+$nach_shsoni-$yo_shsoni, 0, ',', ' ') . '</td>
                                <td>' . number_format($shtsumma+$yo_shsumma+$nach_shsumma-$yo_shsumma-$u_shsumma, 0, ',', ' ') . '</td>

                            </tr>
                        ';
                        $u_nach_shsoni += $nach_shsoni;
                        $U_nach_shsumma += $nach_shsumma;
                        $u_yo_shsoni += $yo_shsoni;
                        $u_yo_shsumma += $yo_shsumma;
                        $u_q_shsoni += $q_shsoni;
                        $u_q_shsumma += $q_shsumma;
                        $u_u_shsoni += $u_shsoni;
                        $u_u_shsumma += $u_shsumma;
                        $uob_shsoni += ($shsoni+$yo_shsoni);
                        $uob_shtsumma += ($shtsumma+$yo_shsumma);
                        $uoo_shsoni += $shsoni+$nach_shsoni-$yo_shsoni;
                        $uoo_shtsumma += $shtsumma+$yo_shsumma+$nach_shsumma-$yo_shsumma-$u_shsumma;
                }

                echo '
                    <tr class="text-center align-middle fw-bold">
                        <td></td>
                        <td>ЖАМИ:</td>
                        <td>' . number_format($uob_shsoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($uob_shtsumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($u_nach_shsoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($U_nach_shsumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($u_yo_shsoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($u_yo_shsumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($u_q_shsoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($u_q_shsumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($u_u_shsoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($u_u_shsumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($uoo_shsoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($uoo_shtsumma, 0, ',', ' ') . '</td>

                    </tr>
                </tbody>
            </table>
            ';
        return;

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

        $shartnoma = new shartnoma1($id);
        $shartnoma1=$shartnoma->select('xis_oyi')->groupBy('xis_oyi')->get();
        foreach ($shartnoma1 as $shart) {
            $xis_oyi = $shart->xis_oyi;
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

                $shsoni = 0;
                $shtsumma = 0;

                $Oy_bosh_yo_soni = 0;
                $Oy_bosh_yo_shsumma = 0;

                $Oy_bosh_udalit = 0;

                $yo_shsoni = 0;
                $yo_shsumma = 0;
                $nach_shsoni = 0;
                $nach_shsumma = 0;
                $q_shsoni = 0;
                $q_shsumma = 0;
                $u_shsoni = 0;
                $u_shsumma = 0;

                $shartnoma = new shartnoma1($id);
                $shartnoma1=$shartnoma->get();
                foreach ($shartnoma1 as $shart) {

                    if(($shart->xis_oyi < $xis_oyi && $shart->status=='Актив')){
                        $obsavdo = new savdo1($id);
                        $obumumsavdo = $obsavdo->where('status2', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');
                        if($obumumsavdo>0){
                            $shsoni++;
                            $shtsumma+=$obumumsavdo;
                        }
                    }

                    if($shart->xis_oyi<$xis_oyi && $shart->yo_xis_oyi>0 && $shart->yo_xis_oyi>=$xis_oyi){
                        $Oy_bosh_yo_soni ++;
                        $savdo = new savdo1($id);
                        $Oy_bosh_yo_shsumma+=$savdo->where('status', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');

                    }

                    if($shart->yo_xis_oyi==$xis_oyi){
                        $yo_shsoni++;
                        $savdo = new savdo1($id);
                        $yo_shsumma+=$savdo->where('status', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');
                    }

                    if($shart->xis_oyi==$xis_oyi){
                        $nach_shsoni ++;
                        $savdonach = new savdo1($id);
                        $nach_shsumma +=$savdonach->where('status2', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');
                    }

                    if($shart->xis_oyi<=$xis_oyi){
                        $qsavdo = new savdo1($id);
                        $qushsavdo = $qsavdo->where('status', 'Шартнома')->where('shartnoma_id', $shart->id)->where('q_xis_oyi', $xis_oyi)->sum('msumma');
                        if($qushsavdo>0){
                            $q_shsoni ++;
                            $q_shsumma+=$qushsavdo;
                        }
                        $usavdo = new savdo1($id);
                        $uushsavdo = $usavdo->where('status2', 'Шартнома')->where('shartnoma_id', $shart->id)->where('del_xis_oyi', $xis_oyi)->sum('msumma');
                        if($uushsavdo>0){
                            $u_shsoni ++;
                            $u_shsumma+=$uushsavdo;
                        }
                    }

                    if($shart->xis_oyi==$xis_oyi){
                        $savdo = new savdo1($id);
                        $Oy_bosh_udalit+=$usavdo->where('status2', 'Шартнома')->where('shartnoma_id', $shart->id)->where('del_xis_oyi', $xis_oyi)->sum('msumma');
                    }

                }
                    echo '
                        <tr class="text-center align-middle">
                            <td>' . $du2 . '</td>
                            <td>' . number_format($shsoni+$Oy_bosh_yo_soni, 0, ',', ' ') . '</td>
                            <td>' . number_format($shtsumma+$Oy_bosh_yo_shsumma, 0, ',', ' ') . '</td>
                            <td>' . number_format($nach_shsoni, 0, ',', ' ') . '</td>
                            <td>' . number_format($nach_shsumma, 0, ',', ' ') . '</td>
                            <td>' . number_format($yo_shsoni, 0, ',', ' ') . '</td>
                            <td>' . number_format($yo_shsumma, 0, ',', ' ') . '</td>
                            <td>' . number_format($q_shsoni, 0, ',', ' ') . '</td>
                            <td>' . number_format($q_shsumma, 0, ',', ' ') . '</td>
                            <td>' . number_format($u_shsoni, 0, ',', ' ') . '</td>
                            <td>' . number_format($u_shsumma, 0, ',', ' ') . '</td>
                            <td>' . number_format($shsoni+$Oy_bosh_yo_soni+$nach_shsoni-$yo_shsoni, 0, ',', ' ') . '</td>
                            <td>' . number_format($shtsumma+$Oy_bosh_yo_shsumma+$nach_shsumma-$yo_shsumma-$u_shsumma, 0, ',', ' ') . '</td>

                        </tr>
                    ';

        }

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
}
