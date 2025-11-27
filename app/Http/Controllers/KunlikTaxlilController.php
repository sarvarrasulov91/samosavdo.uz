<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\filial;
use App\Models\Savdo;
use App\Models\NaqdSavdo;
use App\Models\fondSavdo;
use App\Models\BonusSavdo;
use App\Models\Tulovlar;
use App\Models\Shartnoma;


class KunlikTaxlilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('xisobotlar.kunliktaxlil');
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
            $ushtsumma = 0;
            $unssoni = 0;
            $unssumma = 0;
            $ufssoni = 0;
            $ufssumma = 0;
            $ubssoni = 0;
            $ubssumma = 0;
            $ubtsumma = 0;
            $unchegirmasumma = 0;
            $ufchegirmasumma = 0;

            if(Auth::user()->filial_id == 10){
                $filialbase = filial::where('status', 'Актив')->whereNotIn('id', [10])->get();
            }else{
                $filialbase = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
            }

            foreach ($filialbase as $filial) {

                $shsoni = $shtsumma = 0;

                $shartnoma1 = Shartnoma::whereBetween('kun', [$boshkun, $yakunkun])
                    ->whereIn('status', ['Актив', 'Ёпилган'])
                    ->where('filial_id', $filial->id)
                    ->get();

                foreach ($shartnoma1 as $shart) {

                    $mSumma = Savdo::where('status', 'Шартнома')
                        ->where('shartnoma_id', $shart->id)
                        ->where('filial_id', Auth::user()->filial_id)
                        ->sum('msumma');

                    $chegirma = Tulovlar::where('tulovturi', 'Олдиндан тўлов')
                        ->where('status', 'Актив')
                        ->where('shartnoma_id', $shart->id)
                        ->where('filial_id', $filial->id)
                        ->sum('chegirma');

                    $shtsumma += ($mSumma - $chegirma);

                    $shsoni++;
                }

                $ushsoni += $shsoni;
                $ushtsumma += $shtsumma;

                // naqd savdo taxlilini aniqlash

                $nssoni = $nssumma = $nchegirmasumma = 0;

                $naqdsavdo = NaqdSavdo::whereBetween('kun', [$boshkun, $yakunkun])
                    ->where('status','Актив')
                    ->where('filial_id', $filial->id)
                    ->get();

                foreach ($naqdsavdo as $naqd) {

                    $savdosumma = Savdo::where('status', 'Нақд')
                        ->where('shartnoma_id', $naqd->id)
                        ->where('filial_id', $filial->id)
                        ->sum('msumma');

                    $nssumma += $savdosumma;

                    $nchegirmasum = Tulovlar::where('filial_id', $filial->id)
                        ->where('tulovturi', 'Нақд')
                        ->where('status', 'Актив')
                        ->where('shartnoma_id', $naqd->id)
                        ->sum('chegirma');

                    $nchegirmasumma += $nchegirmasum;

                    $nssoni++;
                }
                    $unssoni += $nssoni;
                    $unssumma += $nssumma;
                    $unchegirmasumma += $nchegirmasumma;

                // fond savdo taxlilini aniqlash
                $fssoni = $fssumma = $fchegirmasumma = 0;

                $fondsavdo1 = fondSavdo::whereBetween('kun', [$boshkun, $yakunkun])
                    ->where('filial_id', $filial->id)
                    ->where('status','Актив')
                    ->get();

                foreach ($fondsavdo1 as $fond) {

                    $fsavdosumma = Savdo::where('status', 'Фонд')
                        ->where('shartnoma_id', $fond->id)
                        ->where('filial_id', $filial->id)
                        ->sum('msumma');

                    $fssoni++;
                    $fssumma += $fsavdosumma;

                    $fchegirmasum = Tulovlar::where('tulovturi', 'Фонд')
                        ->where('status', 'Актив')
                        ->where('shartnoma_id', $fond->id)
                        ->where('filial_id', $filial->id)
                        ->sum('chegirma');

                    $fchegirmasumma += $fchegirmasum;
                }

                $ufssoni += $fssoni;
                $ufssumma += $fssumma;
                $ufchegirmasumma += $fchegirmasumma;

                    /*Bonus savdoni taxlilini ko'rish*/

                $bssoni = $bssumma = $btsumma = 0;

                $savdobonus1 = BonusSavdo::whereBetween('kun', [$boshkun, $yakunkun])
                    ->where('status','Актив')
                    ->where('filial_id', $filial->id)
                    ->get();

                foreach ($savdobonus1 as $bonus) {
                    $bssoni ++;
                    $bssumma += $bonus->msumma;
                }
                    $ubssoni += $bssoni;
                    $ubssumma += $bssumma;

                //Bonuslar  tulov summasi sonini aniqlash

                $tulovlar1 = Tulovlar::whereBetween('kun', [$boshkun, $yakunkun])
                    ->where('filial_id', $filial->id)
                    ->where('status','Актив')
                    ->get();

                foreach ($tulovlar1 as $tulov) {

                    $savdosumma = Tulovlar::where('tulovturi','Бонус')
                        ->where('id', $tulov->id)
                        ->where('filial_id', $filial->id)
                        ->sum('umumiysumma');

                    $btsumma += $savdosumma;
                }
                    $ubtsumma += $btsumma;

                echo '
                    <tr class="text-center align-middle">
                        <td>' . $filial->id . '</td>
                        <td>' . $filial->fil_name . '</td>
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
