<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\xissobotoy;
use Illuminate\Support\Facades\Auth;
use App\Models\filial;
use App\Models\savdo1;
use App\Models\naqdsavdo1;
use App\Models\fond1;
use App\Models\savdobonus1;
use App\Models\tulovlar1;
use App\Models\shartnoma1;
use App\Models\lavozim;


class KunlikTaxlilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив'){
            $filial = filial::where('status', 'Актив')->whereNotIn('id', [10])->get();
        }else{
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }

        return view('xisobotlar.kunliktaxlil', ['filial' => $filial]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // echo "<h4>Salom</h4>";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $boshkun = $request->boshkun;
        $yakunkun = $request->yakunkun;

        // Shartnomalar taxlili ko'rish

        echo '<br><div class="row justify-content-md-center">
                <h3 class=" text-center text-primary"><b>КУНЛИК ТАХЛИЛ</b></h3>
                <div class="col-xl-12">
                <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Филиал</th>
                        <th>Шартнома <br>  сони</th>
                        <th>Шартнома <br>  суммаси</th>
                        <th>Накд савдо <br>  сони</th>
                        <th>Накд савдо <br>  суммаси</th>
                        <th>Фонд савдо <br>  сони</th>
                        <th>Фонд савдо <br>  суммаси</th>
                        <th>Бонус товар <br>  сони</th>
                        <th>Бонус Товар <br> суммаси</th>
                        <th>Бонус <br>  фарки</th>
                        <th>Жами <br>  сони</th>
                        <th>Жами <br>  сумма</th>
                    </tr>
                </thead>
                <tbody id="tab1">';
            $ushsoni = 0;
            $ushqsumma = 0;
            $ushtsumma = 0;
            $unssoni = 0;
            $unssumma = 0;
            $ufssoni = 0;
            $ufssumma = 0;
            $ubssoni = 0;
            $ubssumma = 0;
            $ubtsoni = 0;
            $ubtsumma = 0;
            $unchegirmasumma = 0;
            $ufchegirmasumma = 0;
            $fil_soni = 0;

            $filialbase = filial::where('status', 'Актив')->whereNotIn('id', [10])->get();
            foreach ($filialbase as $filia) {
                $shsoni = 0;
                $shtsumma = 0;
                $shqsumma = 0;
                $fil_soni++;

                $shartnoma = new shartnoma1($filia->id);
                $shartnoma1 = $shartnoma->whereBetween('kun', [$boshkun, $yakunkun])
                ->where(function($query) {
                    $query->where('status','Актив')->orWhere('status', 'Ёпилган');
                })->get();

                foreach ($shartnoma1 as $shart) {
                    $shsoni++;
                    $savdo = new savdo1($filia->id);
                    $savdosumma = $savdo->where('status', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');

                    $oldindantulovinfo = new tulovlar1($filia->id);
                    $oldindantulov = $oldindantulovinfo->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shart->id)->sum('umumiysumma');
                    $chegirma = $oldindantulovinfo->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shart->id)->sum('chegirma');

                    $foiz = xissobotoy::where('xis_oy', $shart->xis_oyi)->value('foiz');
                    $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

                    if($shart->fstatus == 0){
                        $foiz=0;
                    }

                    //йиллик фойиз
                    $foiz = (($foiz / 12) * $shart->muddat);

                    $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);

                    $shtsumma += $savdosumma - $chegirma;
                    $shqsumma += $savdosumma - $oldindantulov - $chegirma + $xis_foiz;

                }
                $ushsoni += $shsoni;
                $ushtsumma += $shtsumma;
                $ushqsumma += $shqsumma;

                // naqd savdo taxlilini aniqlash

                $nssoni = 0;
                $nssumma = 0;
                $nchegirmasumma = 0;

                $naqdsavdo = new naqdsavdo1($filia->id);
                $naqdsavdo1 = $naqdsavdo->whereBetween('kun', [$boshkun, $yakunkun])->where('status','Актив')->get();
                foreach ($naqdsavdo1 as $naqd) {

                    $savdosumma = new savdo1($filia->id);
                    $savdosumma = $savdosumma->where('status', 'Нақд')->where('shartnoma_id', $naqd->id)->sum('msumma');
                    $nssoni++;
                    $nssumma += $savdosumma;

                    $naqdchegirma = new tulovlar1($filia->id);
                    $nchegirmasum = $naqdchegirma->where('tulovturi', 'Нақд')->where('status', 'Актив')->where('shartnomaid', $naqd->id)->sum('chegirma');
                    $nchegirmasumma += $nchegirmasum;

                }
                    $unssoni += $nssoni;
                    $unssumma += $nssumma;
                    $unchegirmasumma += $nchegirmasumma;

                // fond savdo taxlilini aniqlash
                $fssoni = 0;
                $fssumma = 0;
                $fchegirmasumma = 0;

                $fondsavdo = new fond1($filia->id);
                $fondsavdo1 = $fondsavdo->whereBetween('kun', [$boshkun, $yakunkun])->where('status','Актив')->get();
                foreach ($fondsavdo1 as $fond) {

                    $fsavdosumma = new savdo1($filia->id);
                    $fsavdosumma = $fsavdosumma->where('status', 'Фонд')->where('shartnoma_id', $fond->id)->sum('msumma');
                    $fssoni++;
                    $fssumma += $fsavdosumma;

                    $fondchegirma = new tulovlar1($filia->id);
                    $fchegirmasum = $fondchegirma->where('tulovturi', 'Фонд')->where('status', 'Актив')->where('shartnomaid', $fond->id)->sum('chegirma');
                    $fchegirmasumma += $fchegirmasum;

                }
                    $ufssoni += $fssoni;
                    $ufssumma += $fssumma;
                    $ufchegirmasumma += $fchegirmasumma;

                    /*Bonus savdoni taxlilini ko'rish*/

                    $bssoni = 0;
                    $bssumma = 0;
                    $btsoni=0;
                    $btsumma=0;

                $savdobonus = new savdobonus1($filia->id);
                $savdobonus1 = $savdobonus->whereBetween('kun', [$boshkun, $yakunkun])->where('status','Актив')->get();
                foreach ($savdobonus1 as $bonus) {
                    $bssoni ++;
                    $bssumma += $bonus->msumma;
                }
                    $ubssoni += $bssoni;
                    $ubssumma += $bssumma;

                //Bonuslar  tulov summasi sonini aniqlash

                $tulovlar = new tulovlar1($filia->id);
                $tulovlar1 = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status','Актив')->get();
                foreach ($tulovlar1 as $tulov) {
                    $bonussavdo = new tulovlar1($filia->id);
                    $savdosumma = $bonussavdo->where('tulovturi','Бонус')->where('id', $tulov->id)->sum('umumiysumma');
                    $btsoni ++;
                    $btsumma += $savdosumma;
                }
                    $ubtsoni += $btsoni;
                    $ubtsumma += $btsumma;

                echo '
                    <tr class="text-center align-middle">
                        <td>' . $filia->id . '</td>
                        <td>' . $filia->fil_name . '</td>
                        <td>' . number_format($shsoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($shtsumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($nssoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($nssumma - $nchegirmasumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($fssoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($fssumma - $fchegirmasumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($bssoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($bssumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($btsumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($shsoni+$nssoni+$fssoni+$bssoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($shtsumma+$nssumma-$nchegirmasumma+$fssumma-$fchegirmasumma+$btsumma, 0, ',', ' ') . '</td>
                    </tr>';
            }

            echo '
            <tr class="text-center align-middle fw-bold">
            <td></td>
            <td>ЖАМИ</td>
            <td>' . number_format($ushsoni, 0, ',', ' ') . '</td>
            <td>' . number_format($ushtsumma, 0, ',', ' ') . '</td>
            <td>' . number_format($unssoni, 0, ',', ' ') . '</td>
            <td>' . number_format($unssumma - $unchegirmasumma, 0, ',', ' ') . '</td>
            <td>' . number_format($ufssoni, 0, ',', ' ') . '</td>
            <td>' . number_format($ufssumma - $ufchegirmasumma, 0, ',', ' ') . '</td>
            <td>' . number_format($ubssoni, 0, ',', ' ') . '</td>
            <td>' . number_format($ubssumma, 0, ',', ' ') . '</td>
            <td>' . number_format($ubtsumma, 0, ',', ' ') . '</td>
            <td>' . number_format($ushsoni+$unssoni+$ufssoni+$ubssoni, 0, ',', ' ') . '</td>
            <td>' . number_format($ushtsumma+$unssumma-$unchegirmasumma+$ufssumma-$ufchegirmasumma+$ubtsumma, 0, ',', ' ') . '</td>
        </tr>
        </tbody>
                </table>
            </div>
            </div>';

    return;

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
