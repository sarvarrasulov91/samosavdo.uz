<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\shartnoma1;
use App\Models\tulovlar1;
use App\Models\savdo1;
use App\Models\mijozlar;
use App\Models\tashrif;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;
use DateTime;

class ShartnomalarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tashrif = tashrif::all();

        $savdounix_id = savdo1::select('unix_id')->where('status', 'Актив')->orderBy('unix_id', 'desc')->groupBy('unix_id')->get();
        $mijozlar = mijozlar::where('status', '1')->where('m_type', '1')->get();

        return view('shartnoma.shartnomalar', [
            'savdounix_id' => $savdounix_id,
            'mijozlar' => $mijozlar,
            'tashrif' => $tashrif
            ]);
    }

    /**
     * Шарномалар рўйхати.
     */
    public function create()
    {
        echo'
            <table class="table table-bordered text-center align-middle table-hover"
                style="font-size: 14px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Манзили</th>
                        <th>Телефон<br>рақами</th>
                        <th>Ташриф</th>
                        <th>Шартнома<br>санаси</th>
                        <th>Шартнома<br>муддати</th>
                        <th>Паспорт</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                    $shartnoma = shartnoma1::whereIn('status', ['Актив', 'Ёпилган'])->orderBy('id', 'desc')->get();

                    foreach ($shartnoma as $shartnom){

                        if ($shartnom->status == 'Ёпилган'){
                            $trrang = 'align-middle text-success';
                        }else{
                            $trrang = 'align-middle';
                        }

                        echo'
                        <tr id="modalshartshow" data-id="'.$shartnom->id.'" data-fio="'.addslashes($shartnom->mijozlar->last_name) . ' ' . addslashes($shartnom->mijozlar->first_name) . ' ' . addslashes($shartnom->mijozlar->middle_name).'"  class="'.$trrang.'" data-bs-toggle="modal"
                            data-bs-target="#shartnoma_show">
                            <td>' . $shartnom->id . '</td>
                            <td style="white-space: wrap; width: 20%;">' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name . '</td>
                            <td style="white-space: wrap; width: 30%;">' . $shartnom->mijozlar->tuman->name_uz .' '. $shartnom->mijozlar->mfy->name_uz . ' ' . $shartnom->mijozlar->manzil . '</td>
                            <td>' . $shartnom->mijozlar->phone . '</td>
                            <td>' . $shartnom->tashrif->tashrif_name . '</td>
                            <td>' . date('d.m.Y', strtotime($shartnom->kun)) . '</td>
                            <td>' . $shartnom->muddat . '</td>
                            <td>' . $shartnom->mijozlar->passport_sn . '</td>
                            <td>' . $shartnom->status . '</td>
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>';
    }

    /**
     * Янги шартнома.
     */
    public function store(Request $request)
    {
        //
    }

      public function show(string $id)
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $shartnoma = shartnoma1::where('id', $id)->get();
        foreach ($shartnoma as $shartnom) {
            $savdosumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');
            $oldindantulov = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('umumiysumma');
            $chegirma = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('chegirma');
            $tulov = tulovlar1::where('tulovturi', 'Шартнома')->where('shartnomaid', $id)->where('status', 'Актив')->sum('umumiysumma');

            $tsana = date('d.m.Y', strtotime($shartnom->mijozlar->t_sana));
            $kun = date('d.m.Y', strtotime($shartnom->kun));
            $muddat = number_format($shartnom->muddat, 0, ',', ' ');
            $shsumma = number_format($savdosumma, 2, ',', ' ');
            $oldintulov2 = number_format($oldindantulov, 2, ',', ' ');
            $chegirm = number_format($chegirma, 2, ',', ' ');
            $tani = number_format($savdosumma-$oldindantulov-$chegirma, 2, ',', ' ');
            // $xis_foiz = number_format($shartnom->xis_foiz, 2, ',', ' ');
            $tulovshart = number_format($tulov, 2, ',', ' ');

            $foiz = xissobotoy::where('xis_oy', $shartnom->xis_oyi)->value('foiz');

            if($shartnom->fstatus == 0){
                $foiz = 0;
            }

            //йиллик фойиз
            $foiz = (($foiz / 12) * $shartnom->muddat);
            $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);

            $umumiySumma = $savdosumma-$oldindantulov-$chegirma+$xis_foiz;

            $date1 = new DateTime($shartnom->kun);
            $date2 = new DateTime($shartnom->tug_sana);
            $interval = $date1->diff($date2);
            $dukun = $interval->days;
            $birkunlikfoiz = $xis_foiz / ($dukun);
            $krxiob2 = 0;

            if ($shartnom->tug_sana >= date("Y-m-d")) {

                $date22 = new DateTime(date("Y-m-d"));
                $interval1 = $date1->diff($date22);
                $dukun2 = $interval1->days;
                $months = ($interval1->y * 12) + $interval1->m;
                $krxiob2 = $xis_foiz - ($birkunlikfoiz * $dukun2);

                $joqarzm = ($umumiySumma / $shartnom->muddat) * $months - $tulov;
                $prSumma = $joqarzm - ($umumiySumma / $shartnom->muddat);

                $tkun = date('Y-m', strtotime($xis_oyi)) . '-' . date('d', strtotime($shartnom->kun));

                if ($tkun >= date("Y-m-d")) {
                    $joqarzm = $prSumma;
                }

                if ($joqarzm < 1000) {
                    $joqarzm = 0;
                }

            }else{
                $joqarzm = $umumiySumma - $tulov;
            }

            $zadd13 = number_format($umumiySumma - $tulov - $krxiob2, 2, ',', ' ');

            echo '
            <div class="row">
                <div class="col-xl-6">
                    <div class="card h-auto blog-card">
                        <div class="card-body">
                            <div class="c-profile text-center">
                                <img src="images/user1.jpg" class="rounded-circle mb-2">
                                <h3 class="text-primary">' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . '<br>' . $shartnom->mijozlar->middle_name . '
                                    <a href="' . route('showClient', ['id' => $shartnom->mijozlar_id]) . '" class="ms-2">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.25 16.3341V7.66512C21.25 4.64512 19.111 2.75012 16.084 2.75012H7.916C4.889 2.75012 2.75 4.63512 2.75 7.66512L2.75 16.3341C2.75 19.3641 4.889 21.2501 7.916 21.2501H16.084C19.111 21.2501 21.25 19.3641 21.25 16.3341Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16.0861 12.0001H7.91406" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12.3223 8.25211L16.0863 12.0001L12.3223 15.7481" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>

                                </h3>
                            </div>
                            <div class="c-details" style="margin-top: -10px;">
                                <ul>
                                    <li>
                                        <span>Маънзили</span>
                                        <p class="text-primary">' . $shartnom->mijozlar->tuman->name_uz . ' ' . $shartnom->mijozlar->manzil . '</p>
                                    </li>
                                    <li>
                                        <span>Пасспорт</span>
                                        <p class="text-primary">' . $shartnom->mijozlar->passport_sn . ' - ' . $shartnom->mijozlar->pinfl . '</p>
                                    </li>
                                    <li>
                                        <span>Телефон</span>
                                        <p class="text-primary">' . $shartnom->mijozlar->phone . '</p>
                                    </li>
                                    <li>
                                        <span>Телефон2</span>
                                        <p class="text-primary">' . $shartnom->mijozlar->extra_phone . '</p>
                                    </li>
                                    <li>
                                        <span>Тугилган йили</span>
                                        <p class="text-primary">' . $tsana . '</p>
                                    </li>
                                    <div class="text-center p-1">
                                        <button type="button" onclick="shbetlik(' . $shartnom->id . ')" class="btn btn-primary light text-center">Бетлик</button>
                                        <button type="button" onclick="shpecht(' . $shartnom->id . ')" class="btn btn-primary light text-center">Шартнома</button>
                                        <button type="button" onclick="shgrafik(' . $shartnom->id . ')" class="btn btn-primary light text-center">График</button>
                                        <button type="button" onclick="shariza(' . $shartnom->id . ')" class="btn btn-primary light text-center">Ариза</button>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="basic-list-group blog-card">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Шартнома муддати</span> <span class="badge-pill text-primary">' . $muddat . '</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Шартнома санаси</span><span class="badge-pill text-primary">' . $kun . '</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Шартнома суммаси</span> <span class="badge-pill text-primary">' . $shsumma . '</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Олдиндан тўлови</span> <span class="badge-pill text-primary">' . $oldintulov2 . '</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Чегирма</span> <span class="badge-pill text-primary">' . $chegirm . '</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Тани</span> <span class="badge-pill text-primary">' . $tani . '</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Фоизи</span> <span class="badge-pill text-primary">' . number_format($xis_foiz, 2, ',', ' ') . '</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Жами сумма</span> <span class="badge-pill text-primary">' . number_format($umumiySumma, 2, ',', ' ') . '</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Жами тўлови</span> <span class="badge-pill text-primary">' . $tulovshart . '</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Колдик карз</span> <span class="badge-pill text-primary">' . number_format($umumiySumma-$tulov, 2, ',', ' ') . '</span>
                            </li>';

                            if ($shartnom->status == 'Ёпилган'){
                                echo'
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Просрочка</span> <span class="badge-pill text-primary">' . number_format(0, 2, ',', ' ') . '</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Ёпилиш скидка</span> <span class="badge-pill text-primary">' . $shartnom->skidka . '</span>
                                </li>';
                                }else{
                                    echo'
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Просрочка</span> <span class="badge-pill text-primary">' . number_format($joqarzm, 2, ',', ' ') . '</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Жами ёпилиши</span> <span class="badge-pill text-primary">' . $zadd13 . '</span>
                                </li>';
                            }

                            echo'
                        </ul>
                    </div>
                </div>
            </div>

            <br>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="align-middle text-center text-primary">
                        <th>№</th>
                        <th>Ойлар</th>
                        <th>Утган Карздорлик</th>
                        <th>График тулови</th>
                        <th>Тўлади</th>
                    </tr>
                </thead>
                <tbody>';

            $kkuni = $shartnom->kun;

            $i = 0;
            $zadd01 = $grafik = 0;
            $opljami = $nachjami = $zaddjami = 0;

            while ($i <= $muddat) {

                $tekshuzgar2 = strtotime("+$i month", strtotime($kkuni));
                $tekshtuga2  = strtotime("last day of +$i month", strtotime($kkuni));

                // Keyingi oy sanasi
                $du22 = ($tekshuzgar2 >= $tekshtuga2)
                    ? date("Y-m-d", strtotime("last day of +$i month", strtotime($kkuni)))
                    : date("Y-m-d", strtotime("+$i month", strtotime($kkuni)));

                // Carbon orqali oy va yilni olish
                $du2 = Carbon::parse($du22)->locale('ru')->translatedFormat('Y-F');

                //Тулов жамланяпти
                $boshibaza = date("Y-m-", strtotime($du22)) . "01";

                $opl01 = tulovlar1::where('tulovturi', 'Шартнома')
                    ->where('shartnomaid', $shartnom->id)
                    ->where('xis_oyi', $boshibaza)
                    ->where('status', 'Актив')
                    ->sum('umumiysumma');

                $ksumma = ($savdosumma) - ($oldindantulov + $chegirma);

                // Joriy oy bo‘lsa sariq rang
                $trclass = (date('Y-m', strtotime($du22)) == date('Y-m'))
                    ? 'align-middle text-center text-warning'
                    : 'align-middle text-center';

                echo '
                        <tr class="'.$trclass.'">
                            <td>' . $i . '</td>
                            <td>' . $du2 . '</td>
                            <td>' . number_format($zadd01, 2, ",", " ") . '</td>
                            <td>' . number_format($grafik, 2, ",", " ") . '</td>
                            <td>' . number_format($opl01, 2, ",", " ") . '</td>
                        </tr>';

                $zadd01 = ($zadd01 + $grafik) - $opl01;

                $opljami += $opl01;
                $nachjami += $grafik;
                $zaddjami  = abs($zadd01);

                $grafik = ($ksumma + $xis_foiz) / $muddat;

                $i++;

            }   // while tugadi

            // kechikkan tulovlarni ko'rsatish
            $kechTulov = 0;
            $lateTulovlar = tulovlar1::where('tulovturi', 'Шартнома')
                ->where('shartnomaid', $shartnom->id)
                ->where('xis_oyi', '>', $boshibaza)
                ->where('status', 'Актив')
                ->groupBy('xis_oyi')
                ->selectRaw('sum(umumiysumma) as umumiysumma, max(kun) as kun') // Modify as needed
                ->get();

            foreach ($lateTulovlar as $item){

                $trclass = (date('Y-m', strtotime($item->kun)) == date('Y-m'))
                    ? 'align-middle text-center text-warning'
                    : 'align-middle text-center';

                echo '
                    <tr class="'.$trclass.'">
                        <td>' . $i++ . '</td>
                        <td>' . Carbon::parse($item->kun)->locale('ru')->translatedFormat('Y-F') . '</td>
                        <td>' . number_format($zadd01, 2, ",", " ") . '</td>
                        <td>' . number_format(0, 2, ",", " ") . '</td>
                        <td>' . number_format($item->umumiysumma, 2, ",", " ") . '</td>
                    </tr>';

                $zadd01 -= $item->umumiysumma;
                $kechTulov += $item->umumiysumma;
            }

            echo '
                    <tr class="align-middle text-center fw-bold">
                        <td colspan="2">Жами:</td>
                        <td>' . number_format($zaddjami - $kechTulov, 2, ",", " ") . '</td>
                        <td>' . number_format($nachjami, 2, ",", " ") . '</td>
                        <td>' . number_format($opljami + $kechTulov, 2, ",", " ") . '</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered table-hover">
                <tr class="align-middle fw-bold text-center">
                <td colspan="1"><b>Шартнома бугунга ёпилиши</td>
                <td>' . number_format($zaddjami, 2, ",", " ") . '</td>
                <td> -</td>
                <td>' . number_format($krxiob2, 2, ",", " ") . '</td>
                <td> =</td>
                <td>' . number_format($zaddjami - $krxiob2, 2, ",", " ") . '</td>
                <td>' . date("d.m.Y") . '</td>
            </tr>
            </table>
            <br>
            <h5 class=" text-center text-uppercase" style="color: RoyalBlue;">Шартнома учун тўланган тўловлар</h5>
            <table class="table table-hover table-bordered text-center text-muted">
                <thead>
                   <tr class="text-primary">
                        <th>№</th>
                        <th>Номи</th>
                        <th>Куни</th>
                        <th>Нақд</th>
                        <th>Пластик</th>
                        <th>Х-р</th>
                        <th>Клик</th>
                        <th>Бонус</th>
                        <th>Жами</th>
                        <th>Холати</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                    $tulovlar = tulovlar1::where('shartnomaid', $shartnom->id)
                        ->whereIn('tulovturi', ['Шартнома', 'Олдиндан тўлов', 'Брон'])
                        ->orderByDesc('id')
                        ->get();

                    $i = 1;
                    $totals = [
                        'naqd' => 0,
                        'pastik' => 0,
                        'hr' => 0,
                        'click' => 0,
                        'avtot' => 0,
                    ];

                    foreach ($tulovlar as $t) {
                        $isActive = $t->status === 'Актив' && in_array($t->tulovturi, ['Шартнома', 'Олдиндан тўлов']);
                        $rowClass = $isActive ? '' : 'text-danger';

                        if ($isActive) {
                            $totals['naqd']  += $t->naqd;
                            $totals['pastik']+= $t->pastik;
                            $totals['hr']    += $t->hr;
                            $totals['click'] += $t->click;
                            $totals['avtot'] += $t->avtot;
                        }

                        $rowTotal = $t->naqd + $t->pastik + $t->hr + $t->click + $t->avtot;

                        echo "
                                <tr class='text-center align-middle {$rowClass}'>
                                    <td>{$i}</td>
                                    <td>{$t->tulovturi}</td>
                                    <td>" . date('d.m.Y', strtotime($t->kun)) . "</td>
                                    <td>" . number_format($t->naqd, 0, ',', ' ') . "</td>
                                    <td>" . number_format($t->pastik, 0, ',', ' ') . "</td>
                                    <td>" . number_format($t->hr, 0, ',', ' ') . "</td>
                                    <td>" . number_format($t->click, 0, ',', ' ') . "</td>
                                    <td>" . number_format($t->avtot, 0, ',', ' ') . "</td>
                                    <td>" . number_format($rowTotal, 0, ',', ' ') . "</td>
                                    <td>{$t->status}</td>

                                </tr>";
                        $i++;
                    }
                    echo'
                        <tr class="text-center align-middle fw-bold">
                            <td></td>
                            <td>ЖАМИ</td>
                            <td></td>
                            <td> '.number_format($totals['naqd'], 0, ',', ' ').'</td>
                            <td> '.number_format($totals['pastik'], 0, ',', ' ').' </td>
                            <td> '.number_format($totals['hr'], 0, ',', ' ').' </td>
                            <td> '.number_format($totals['click'], 0, ',', ' ').' </td>
                            <td> '.number_format($totals['avtot'], 0, ',', ' ').' </td>
                            <td> '.number_format(array_sum($totals), 0, ',', ' ').' </td>
                            <td></td>
                        </tr>
                </tbody>
            </table>
            <br>
            <h5 class=" text-center text-uppercase" style="color: RoyalBlue;">Шартномада кўрсатилган товарлар рўйхати</h5>
           ';

            $savdomodel = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->get();
            echo '
           <table class="table table-bordered table-hover">
               <thead>
                   <tr class="text-center text-bold text-primary align-middle">
                       <th>№</th>
                       <th>Модел ID</th>
                       <th>Савдо рақами</th>
                       <th>Куни</th>
                       <th>Товар номи</th>
                       <th>Суммаси</th>
                       <th>Штрих коди</th>
                   </tr>
               </thead>
               <tbody id="tab1">';
            $jami = 0;
            $i = 1;
            foreach ($savdomodel as $savdomode) {
                echo "
                   <tr class='text-center align-middle'>
                       <td>" . $i . "</td>
                       <td>" . $savdomode->tmodel_id . "</td>
                       <td>" . $savdomode->unix_id . "</td>
                       <td>" . date('d.m.Y', strtotime($savdomode->created_at)) . "</td>
                       <td>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                       <td>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                       <td>" . $savdomode->shtrix_kod . "</td>
                    </tr>";
                $jami += $savdomode->msumma;
                $i++;
            }
            echo '
                   <tr class="text-center align-middle fw-bold">
                       <td></td>
                       <td>ЖАМИ</td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td>' . number_format($jami, 0, ",", " ") . '</td>
                       <td></td>
                   </tr>
               </tbody>
           </table>

           ';
        }
        return;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        function num2str2($num)
        {
            $nul = '00';
            $ten = array(
                array('', 'бир', 'икки', 'уч', 'тўрт', 'беш', 'олти', 'етти', 'саккиз', 'тўққиз'),
                array('', 'бир', 'икки', 'уч', 'тўрт', 'беш', 'олти', 'етти', 'саккиз', 'тўққиз')
            );
            $a20 = array('ўн', 'ўн бир', 'ўн икки', 'ўн уч', 'ўн турт', 'ўн беш', 'ўн олти', 'ўн етти', 'ўн саккиз', 'ун тўққиз');
            $tens = array(2 => 'йигирма', 'ўттиз', 'қирқ', 'эллик', 'олтмиш', 'етмиш', 'саксон', 'тўқсон');
            $hundred = array('', 'бир юз', 'икки юз', 'уч юз', 'тўрт юз', 'беш юз', 'олти юз', 'етти юз', 'саккиз юз', 'тўққиз юз');
            $unit = array(
                array('тийин', 'тийин',   'тийин',     1),
                array('сўм',    'сўм',     'сўм',     0),
                array('минг',   'минг',    'минг',      1),
                array('милион',  'милион',  'милион',  0),
                array('миллиард', 'миллиард', 'миллиард', 0),
            );

            list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
            $out = array();
            if (intval($rub) > 0) {
                foreach (str_split($rub, 3) as $uk => $v) {
                    if (!intval($v)) continue;
                    $uk = sizeof($unit) - $uk - 1;
                    $gender = $unit[$uk][3];
                    list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
                    // mega-logic
                    $out[] = $hundred[$i1]; // 1xx-9xx
                    if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; // 20-99
                    else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; // 10-19 | 1-9
                    // units without rub & kop
                    if ($uk > 1) $out[] = morph2($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
                }
            } else {
                $out[] = $nul;
            }
            $out[] = morph2(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
            $out[] = $kop . ' ' . morph2($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
            return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
        }

        function morph2($n, $f1, $f2, $f5)
        {
            $n = abs(intval($n)) % 100;
            if ($n > 10 && $n < 20) return $f5;
            $n = $n % 10;
            if ($n > 1 && $n < 5) return $f2;
            if ($n == 1) return $f1;
            return $f5;
        }


        $shartnoma = shartnoma1::where('id', $id)->get();
        foreach ($shartnoma as $shartnom) {
            $oldindantulov = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('umumiysumma');
            $chegirma = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('chegirma');
            $savdosumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');
        }

        $foiz = xissobotoy::where('xis_oy', $shartnom->xis_oyi)->value('foiz');

        if($shartnom->fstatus==0){
            $foiz=0;
        }

        $filial = filial::where('id', Auth::user()->filial_id)->get();
        foreach ($filial as $filia) {
            $ytt = $filia->ytt;
            $manzil = $filia->manzil;
            $yurmanzil = $filia->yurmanzil;
            $xr = $filia->xr;
            $inn = $filia->inn;
            $bankname = $filia->bankname;
            $mfo = $filia->mfo;
            $telefon = $filia->telefon;
        };

        //йиллик фойиз
        $foiz = (($foiz / 12) * $shartnom->muddat);
        $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);

        echo '
            <div class="modal-body m-10">
                <div class="flex items-center justify-center bg-white ">
                    <div id="certificate" class="text-center p-2">
                        <h4 style="text-align: center; font-size: 20px; margin-bottom: -10px;"><b>Ш А Р Т Н О М А № ' . $shartnom->id . '</b></h4>
                        <p style="text-align:center; margin-bottom: -5px;">
                            <b>(муддатли тўлов шарти билан олди-сотди шартномаси)</b>
                        </p>
                    <div>
                    <span style="float: left;">
                            '.$manzil.'
                    </span>
                    <span style="float: right;">
                        '.date("d.m.Y", strtotime($shartnom->kun)).' йил
                    </span>
                </div>
                <br>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: -3px;">
                    Бир томондан Фаргона вилояти Богдод тумани Давлат хизматлари маркази томонидан 2021-йил 26-Апрелда 5798725-сон билан берилган гувоҳнома асосида фаолият кўрсатувчи
                    '.$ytt.' (кейинги ўринларда «Сотувчи») ва ' . $shartnom->mijozlar->tuman->name_uz . ' ' . $shartnom->mijozlar->mfy->name_uz . ' ' . $shartnom->mijozlar->manzil . 'да яшовчи фуқаро
                        ' . $shartnom->mijozlar->last_name . " " . $shartnom->mijozlar->first_name . " " . $shartnom->mijozlar->middle_name . '(Ҳужжат серия
                        ' . $shartnom->mijozlar->passport_sn . ' ' . $shartnom->mijozlar->passport_iib . ' томонидан ' . date("d.m.Y", strtotime($shartnom->mijozlar->passport_date)) . ' йилда берилган) ' . $shartnom->mijozlar->ish_joy . ' ходими лавозимида ишловчи (кейинги ўринларда «Харидор») иккинчи томондан ушбу шартномани тарафлар ўртасида ўзаро келишув асосида куйидагилар тўғрисида тузилди.
                </p>
                <h5 style="text-align: center; font-size: 14px; margin-bottom: -5px;">
                    <b>1. ШАРТНОМА МАЗМУНИ.</b>
                </h5>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal; margin-bottom: -10px; ">
                    1.1. «Сотувчи» куйидаги маҳсулотларни «Харидор»га ' . $shartnom->muddat . ' ой муддат давомида кийматини бўлиб тўлаш шарти билан сотади.
                <p>
                <table class="table-sm table-hover" style="font-size: 12px; text-align: center; width:100%; border: 1px solid black;">
                        <tr class="align-middle">
                            <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 5%;">№</th>
                            <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 10%;">ID</th>
                            <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 35%;"">Маҳсулот номи</th>
                            <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 10%;">Сони</th>
                            <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 20%;">Махсулот нархи</th>
                            <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 20%;">Маҳсулот олдиндан<br>тўлов суммаси</th>
                    </thead>
                    <tbody id="tab1">';

                    $savdomodel = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->get();

                    $i = 0;
                    $jami = 0;
                    foreach ($savdomodel as $savdomode) {
                        $i++;
                        echo "
                            <tr class='text-center align-middle m-2'>
                                <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . $i . "</td>
                                <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . $savdomode->tmodel_id . "</td>
                                <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                                <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . number_format(1, 0, ',', ' ') . "</td>
                                <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                                <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . number_format(($savdomode->msumma * 0.30), 0, ',', ' ') . "</td>
                            </tr>";
                        $jami += $savdomode->msumma;
                    };

                    echo '
                    <tr class="text-center align-middle fw-bold">
                            <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt"></td>
                            <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">ЖАМИ</td>
                            <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt"></td>
                            <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($i, 0, ",", " ") . '</td>
                            <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($jami, 0, ",", " ") . '</td>
                            <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($jami * 0.30, 0, ",", " ") . '</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <h5 style="text-align: center; font-size: 14px; margin-top: -10px; margin-bottom: -5px;">
                    <b>2. ШАРТНОМА ҚИЙМАТИ ВА ТЎЛОВ ШАРТЛАРИ.</b>
                </h5>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal; margin-bottom: -10px;">
                    2.1. Мазкур шартнома суммаси куйидаги жадвал буйича аникланди:
                <p>
                <table class="table-sm table-hover" style="font-size: 12px; text-align: center; width:100%; border: 1px solid black;">
                    <tr class="align-middle">
                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 25%;">Шартнома умумий<br>қиймати</th>
                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 25%;">Олдиндан тўлов<br>суммаси</th>
                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 25%;">Ойлик тўлов<br>суммаси (яхлит)</th>
                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 25%;">Қарздорлик<br>суммаси</th>
                    <tbody>
                    <tr class="align-middle text-muted">
                        <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($savdosumma, 2, ",", " ") . '</td>
                        <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($oldindantulov, 2, ",", " ") . '</td>
                        <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format(($savdosumma + $xis_foiz - $oldindantulov - $chegirma) / $shartnom->muddat, 2, ",", " ") . '</td>
                        <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format(($savdosumma + $xis_foiz - $oldindantulov - $chegirma), 2, ",", " ") . '</td>
                    </tr>
                    </tbody>
                </table>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: -5px;">
                        2.2. Шартноманинг умумий киймати ' . number_format($savdosumma + $xis_foiz - $oldindantulov - $chegirma, 2, ",", " ") . ' (' . num2str2($savdosumma + $xis_foiz - $oldindantulov - $chegirma) . ') ни ташкил қилади.
                </p>
                <h5 style="text-align: center; font-size: 14px; margin-bottom: -0px;">
                    <b>3. ҲИСОБ КИТОБ ТАРТИБИ.</b>
                </h5>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    «Харидор» шартнома расмийлаштириш жараёнида сотиб олинаётган маҳсулот(лар) қийматини 0,00 (нол) сўм қисмини тўлайди.
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    3.2. Сотиб олинаётган маҳсулот(лар) қийматини қолган қисмини «Харидор» мазкур шартноманинг ажралмас қисми бўлган жадвал-илова асосида ўз вақтида ихтиёрий тўлов қилади ёки харидорнинг хар қандай банк хисоб рақамидан хамда хар қандай пластик картасидан автоматик тарзда ечиб олинади.
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    3.3. Шартноманинг амал килиш муддати: ' . date("d.m.Y", strtotime($shartnom->tug_sana)) . ' йил.
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    3.4.«Харидор» сотиб олган маҳсулот(лар)нинг қийматини мазкур шартноманинг 1.1. ва 3.3-бандлари ва тўлов графигида кўрсатилган муддатдан олдин ёки кечиктириб тўлаган тақдирда хам шартнома шартлари ўзгармайди.
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                3.5. «Харидор» томонидан мазкур шартноманинг 1.1-бандида кўрсатилган маҳсулот(лар) қиймати тўлик тўланмагунига қадар «Сотувчи»нинг шахсий мулки ҳисобланади.
                </p>

                <h5 style="text-align: center; font-size: 14px; margin-bottom: 0px;">
                    <b>4. МАҲСУЛОТ(ЛАР)НИ ЕТКАЗИШ ТАРТИБИ.</b>
                </h5>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    4.1. «Харидор» томонидан сотиб олинаётган маҳсулот(лар) учун олдиндан тўлов амалга оширилгандан сўнг 15 кун муддат ичида «Сотувчи» ва «Харидор» ўртасида келишув асосида маҳсулот(лар)ни етказиб бериш тўғрисидаги тегишли ҳужжат расмийлаштирилгандан сўнг етказиб берилади.
                </p>

                <h5 style="text-align: center; font-size: 14px; margin-bottom: -5px;">
                    <b>5. ТОМОНЛАРНИНГ ҲУҚУҚ ВА МАЖБУРИЯТЛАРИ.
                </b></h5>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    5.1. «Сотувчи»нинг ҳуқуқлари:
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - «Харидор»дан сотиб олинган маҳсулот(лар) тўловини ўз вақтида тўланишини талаб қилиб бориш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - икки ёки ундан ортиқ маротаба тўловларни амалга оширмаган тақдирда, иш жойи раҳбариятига қарздорликни ундириш юзасидан амалий ёрдам бериш ҳақида ёзма мурожаат қилиш.
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - «Харидор» тўловларни икки ёки ундан ортиқ маротаба кечиктирган тақдирда, сотиб олинган маҳсулот(лар)ни мазкур шартноманинг 3.5-бандига асосан қайтариб олиш чораларини кўради ва маҳсулот(лар) учун тўланган тўловларни ижара тўлови (эскириш қиймати) ҳисобига қабул қилиш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                - «Харидор» томонидан шартнома ва иловада белгиланган тўлов кунидан 60 кундан ортиқ муддатда тўлов амалга оширилмаган ёки шартноманинг бошқа шартлари бузилган тақдирда қарздорликни муддатидан аввал ундириш юзасидан суд органларига хамда нотариал идораларига мурожаат килиш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    5.2. «Сотувчи»нинг мажбуриятлари:
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - шартнома тузилаётган вақтда сотиб олинаётган маҳсулот(лар) нархи ўзгариши тўғрисида «Харидор»ни хабардор килиш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - шартнома тузилгандан сўнг 15 кун ичида «Харидор»га маҳсулот(лар)ни тўлиқ ва бут ҳолатда (кафолат таллони тўлдирилган ҳолда) ҳужжатлари билан топшириш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - сотиб олинган маҳсулот(лар) учун ҳисоб-фактура тақдим этиш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - носоз, сифатсиз маҳсулот(лар)ни («Харидор»нинг айби билан юзага келган носозликлар бундан мустасно) 3 кун ичида алмаштириб бериш;
                </p>

                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    5.3. «Харидор»нинг ҳуқуқлари:
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - «Сотувчи»дан маҳсулот(лар)ни тўлиқ ва бутлигини текшириш ҳамда 15 кун ичида қабул қилиб олиш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - агарда сотиб олинган маҳсулот(лар) носоз ва сифатсиз бўлган тақдирда 3 кун ичида қайтариб бериш ва бошқасига алмаштириш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - маҳсулот(лар)нинг сертификати, кафолат таллони ва тўлов жадвалини талаб қилиб олиш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - маҳсулот(лар) қабул қилинганидан сўнг, кафолат муддати давомида вужудга келган носозликлар («Харидор»нинг айби билан юзага келган носозликлар бундан мустасно) юзасидан ушбу кафолат таллонида кўрсатилган ишлаб чиқарувчининг сервис хизматларига мурожаат қилиш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    5.4. «Харидор»нинг мажбуриятлари:
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - сотиб олинган маҳсулот(лар) учун тўловларни ўз вақтида амалга ошириш;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    - тўловлар тўлиқ тўланмагунига қадар мазкур шартноманинг 1.1-бандида кўрсатилган маҳсулот(лар)ни белгиланган тартибда сақлаш, бутлигини таъминлаш ҳамда қатъиян бошқа шахсларга сотмаслик, ўзаро келишувларни умуман амалга оширмаслик;
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: -5px;">
                    - иш жойи, яшаш манзили ва бошқа маълумотлари ўзгарган тақдирда, 5 кун муддат ичида «Сотувчи»га хабар бериш;
                </p>

                <h5 style="text-align: center; font-size: 14px; margin-bottom: -0px;">
                    <b>6. ТОМОНЛАРНИНГ ЖАВОБГАРЛИГИ.</b>
                </h5>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: -0px;">
                    6.1. Томонлар ушбу шартнома шартларини бажармаслик ва белгиланган шартларга риоя қилмасдан бажарган ҳолда амалдаги қонунчилик олдида жавобгардирлар.
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: -5px;">
                    6.2. Ушбу шартнома шартларини бажарилмаслиги оқибатида юзага келган низоли вазиятлар Ўзбекистон Республикасининг амалдаги қонун ҳужжатларига амал қилган ҳолда музокаралар йўли билан ҳал қиладилар. Томонларнинг келишувига эришилмаган тақдирда низолар суд органлари ва нотариал идоралари томонидан ҳал қилинади.
                </p>

                <h5 style="text-align: center; font-size: 14px; margin-bottom: -0px;">
                    <b>7. БОШҚА ШАРТЛАР.</b>
                </h5>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: -0px;">
                    7.1. Ушбу шартномага барча ўзгартириш ва қўшимчалар томонлар имзолаган қўшимча битим тарзида ёзма шаклда расмийлаштирилади.
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: -0px;">
                    7.2. Ушбу шартномада кўзда тутилмаган ҳолатлар юзасидан томонлар амалдаги қонунчиликка асосан иш кўрадилар.
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: -0px;">
                    7.3. «Харидор» томонидан маҳсулот(лар)ни қайтарилган тақдирда, 3 кун муддат ичида таққослаш далолатномаси асосида ҳисоб-китоб қилинади ва 30 кун давомида қарздорлик бартараф этилмаса, ушбу маҳсулот(лар) ҳисобидан натура шаклида қарздорлик қопланади.
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 0px;">
                    7.4. Ушбу шартнома «Харидор» томонидан маҳсулот(лар) тўлиқ қабул қилиб олинган вақтдан бошлаб кучга киради ва ушбу шартнома бўйича мажбуриятлар тўлиқ бажарилгунига қадар амал қилади.
                </p>
                <p style="text-align:justify; font-size: 12px; text-indent: 30px; line-height: normal;  margin-bottom: 5px;">
                    7.5. Ушбу шартнома ҳар қайси томон учун бир хил юридик кучга эга бўлган 2 нусхада тузилди.
                </p>

                <h5 style="text-align: center; font-size: 14x; margin-bottom: 0px;">
                    <b>8. ТОМОНЛАРНИНГ МАНЗИЛЛАРИ.</b>
                </h5>
                <table style="width:100%; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt; " >
                <tr class="align-middle">
                    <th style="padding: 0.6375rem 0.325rem; border: 1px solid #e6e6e6; width: 50%; border-collapse: collapse; font-size: 8pt; text-align: center;">«СОТУВЧИ»</th>
                    <th style="padding: 0.6375rem 0.325rem; border: 1px solid #e6e6e6; width: 50%; border-collapse: collapse; font-size: 8pt; text-align: center;">«ХАРИДОР»</th>
                </tr>
                <tr class="align-middle" style="text-align: left">
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">' . $ytt . '</th>
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Ф.И.Ш.: ' . $shartnom->mijozlar->last_name . " " . $shartnom->mijozlar->first_name . " " . $shartnom->mijozlar->middle_name . '</th>
                </tr>
                <tr class="align-middle" style="text-align: left">
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Фаолият манзили: ' . $manzil . '</th>
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Манзил: ' . $shartnom->mijozlar->tuman->name_uz . ' ' . $shartnom->mijozlar->mfy->name_uz . ' ' . $shartnom->mijozlar->manzil . '</th>
                </tr>
                <tr class="align-middle" style="text-align: left">
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Юридик манзил: ' . $yurmanzil . '</th>
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Паспорт маълумотлари:</th>
                </tr>

                <tr class="align-middle" style="text-align: left">
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Банк: ' . $bankname . '</th>
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Паспорт №: ' . $shartnom->mijozlar->passport_sn . '</th>
                </tr>
                <tr class="align-middle" style="text-align: left">
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Ҳ/р: ' . $xr . '</th>
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Қачон берилган: ' . date("d.m.Y", strtotime($shartnom->mijozlar->passport_date)) . '</th>
                </tr>
                <tr class="align-middle" style="text-align: left">
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">МФО: ' . $mfo . '</th>
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Ким томонидан берилган: ' . $shartnom->mijozlar->passport_iib . '</th>
                </tr>
                <tr class="align-middle" style="text-align: left">
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">СТИР: ' . $inn . '</th>
                </tr>
                <tr class="align-middle" style="text-align: left">
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Тел: ' . $telefon . '</th>
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Тел: ' . $shartnom->mijozlar->phone . '</th>
                </tr>
                <tr class="align-middle" style="text-align: left">
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Имзо:</th>
                    <th style="padding: 0.2375rem 0.125rem; border: 1px solid #e6e6e6; border-collapse: collapse; font-size: 8pt">Имзо:</th>
                </tr>
                </table>

            </div>
        ';

        return ;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shartnoma = shartnoma1::where('id', $id)->get();

            $filial = filial::where('id', Auth::user()->filial_id)->get();
            foreach ($filial as $filia) {
                $ytt = $filia->ytt;
            };

        foreach ($shartnoma as $shartnom) {
            echo '
                <div class="modal-body">
                    <div class="flex items-center justify-center bg-white p-5">
                        <div id="certificate" class="text-center p-2">
                                <h3 style="text-align: center; font-size: 40px;">
                                    <img src="images/favicon.png" style="width: 150px; text-align: center;">
                                </h3>

                                <br>
                                <h3 class="fw-bold" style="text-align: center; font-size: 40px;">'.$ytt.'</h3>
                                <br>
                                <br>
                                <h4 style="text-align: center; font-size: 35px;">'.$shartnom->mijozlar->last_name." ".$shartnom->mijozlar->first_name.'<br>'.$shartnom->mijozlar->middle_name.'</h4>
                                <br>
                                <h4 style="text-align: center; font-size: 30px;">Шартнома № '.$shartnom->id.'</h4>
                                <h4 style="text-align: center; font-size: 30px;">Куни '.date("d.m.Y",strtotime($shartnom->kun)).' йил</h4>
                                <h4 style="text-align: center; font-size: 30px;">Муддати '.$shartnom->muddat.' ойга</h4>
                            <br>
                                <br>
                                <br>
                                <br>
                                <h6 style="text-align: center; font-size: 30px;">'. $shartnom->User->name.'</h6>
                                <h6 style="text-align: center; font-size: 30px;">'. $shartnom->kun .' йил</h6>
                        </div>
                    </div>
                </div>
            ';
        }
        return;
    }

    /**
     * Шарнома график.
     */
    public function destroy(string $id)
    {

        $filial = filial::where('id', Auth::user()->filial_id)->get();
        foreach ($filial as $filia) {
            $ytt = $filia->ytt;
            $manzil = $filia->manzil;
            $yurmanzil = $filia->yurmanzil;
            $xr = $filia->xr;
            $inn = $filia->inn;
            $bankname = $filia->bankname;
            $mfo = $filia->mfo;
            $telefon = $filia->telefon;
        };

        $shartnoma = shartnoma1::where('id', $id)->get();
        foreach ($shartnoma as $shartnom) {
            $kun = date('d.m.Y', strtotime($shartnom->kun));
            $muddat = number_format($shartnom->muddat, 0, ',', ' ');

            $oldindantulov = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('umumiysumma');
            $chegirma = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('chegirma');
            $savdosumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');

            $foiz = xissobotoy::where('xis_oy', $shartnom->xis_oyi)->value('foiz');

            if($shartnom->fstatus==0){
                $foiz=0;
            }


            //йиллик фойиз
            $foiz = (($foiz / 12) * $shartnom->muddat);
            $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);



            echo'
                <div class="modal-body m-0">
                    <div class="flex items-center justify-center bg-white ">
                        <div id="certificate" class="text-center p-2">
                            <h4 style="text-align:center; margin-bottom: -5px;">ЮК ХАТИ № ' . $shartnom->id . '</h4>
                            <div>
                                <p style="text-align:center; margin-bottom: 0px; margin-left: 10%; margin-right: 10%;">' . $kun . ' йилдаги № '. $shartnom->id .'-сонли муддатли тўлов шарти билан тузилган олди-сотди шартномасига асосан</p>
                            </div>
                            <table style="width:100%; border: 1px solid black; border-collapse: collapse; font-size:12px;">
                                <tr id="#jadst">
                                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;">« С О Т У В Ч И »</th>
                                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;">« Х А Р И Д О Р »</th>
                                </tr>
                                <td id="manst" style="border-right: 1px solid black; width: 50%;">
                                    <strong>
                                        <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                            '. $ytt .'
                                        </p>
                                    </strong>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                    Манзил: <strong>'. $manzil .'</strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                    Банк: <strong>'. $bankname .'</strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                    Ҳ/р: <strong>'. $xr .'</strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                    МФО: <strong>'. $mfo .'</strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                    ИНН: <strong> '. $inn .' </strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                    Тел: <strong> '. $telefon .' </strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                        <strong> </strong>
                                    </p>
                                </td>
                                <td id="manst">
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                        Ф.И.Ш: <strong>'. $shartnom->mijozlar->last_name . " " . $shartnom->mijozlar->first_name . " " . $shartnom->mijozlar->middle_name .'</strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                        Манзил: <strong>'. $shartnom->mijozlar->tuman->name_uz . ' ' . $shartnom->mijozlar->mfy->name_uz . ' ' . $shartnom->mijozlar->manzil .'</strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                        Паспорт маълумотлари:
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                        Серияси: <strong>'. $shartnom->mijozlar->passport_sn .'</strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                        Қачон берилган: <strong>'. date("d.m.Y", strtotime($shartnom->mijozlar->passport_date)) .'</strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                        Ким томонидан берилган: <strong>'. $shartnom->mijozlar->passport_iib .'</strong>
                                    </p>
                                    <p style="text-align: left; padding-right: 30px; margin-bottom: -5px;">
                                        Тел: <strong>'. $shartnom->mijozlar->phone .'</strong>
                                    </p>
                                    <br />
                                </td>
                            </table>
                            <br />
                            <table style="width:100%; border: 1px solid black; border-collapse: collapse; font-size:14px; " id="tovarjad">
                                <tr>
                                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 5%;">№</th>
                                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 7%;">Модел ИД</th>
                                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 38%;">Маҳсулот номи</th>
                                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 10%;">Сони</th>
                                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 20%;">Маҳсулот<br>нархи</th>
                                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 20%;">Умумий<br>қиймати</th>
                                </tr>';

                                $savdomodel = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->get();
                                $i = 0;
                                $jami = 0;
                                foreach ($savdomodel as $savdomode) {
                                    $i++;
                                    echo "
                                        <tr class='text-center align-middle m-2'>
                                            <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . $i . "</td>
                                            <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . $savdomode->tmodel_id . "</td>
                                            <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                                            <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . number_format(1, 0, ',', ' ') . "</td>
                                            <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                                            <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . number_format($savdomode->msumma , 0, ',', ' ') . "</td>
                                        </tr>";
                                    $jami += $savdomode->msumma;
                                };

                                echo '
                                   <tr class="text-center align-middle fw-bold">
                                        <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt"></td>
                                        <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">ЖАМИ</td>
                                        <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt"></td>
                                        <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($i, 0, ",", " ") . '</td>
                                        <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($jami, 0, ",", " ") . '</td>
                                        <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($jami, 0, ",", " ") . '</td>
                                    </tr>
                            </table>
                            <br />
                            <table style="font-size:12px;">
                                <tr>
                                    <td id="manst" style="width: 60%; text-align: center; padding-top: 10px;">
                                        <span>Раҳбар: ________________</span><br /><br />
                                    </td>
                                    <td id="manst" style="width: 40%; text-align: center;">
                                        <span> _________________________________________</span><br />
                                        <span style="margin-top: -10px; font-size: 14px;"> (Харидорнинг ёки ваколатли шахснинг имзоси)</span>
                                    </td>
                                </tr>
                                <td id="manst" style="width: 60%; text-align: center; padding-top: 10px;">
                                    <span>Маҳсулот(лар)ни бериб юбордим ___________________ </span><br /><br />
                                </td>
                                <td id="manst" style="width: 40%; text-align: center;">
                                    <span> _________________________________________</span><br />
                                    <span style="margin-top: 0px; font-size: 14px;"> (Олувчининг Ф.И.Ш.) </span>
                                </td>
                                </tr>
                            </table>

                        <br />
                        <h5 style="text-align:center; margin-bottom: -15px;">ТЎЛОВ ЖАДВАЛИ</h5>
                        <p style="margin-bottom: -15px; text-align: right;">
                            Шартнома № '. $shartnom->id .' га илова
                        </p>
                        <br />
                        <table style="width:100%; border: 1px solid black; border-collapse: collapse; font-size:14px; " id="tovarjad">
                            <tr>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 33%;"">Маҳсулот(лар) нархи</th>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 33%;"">Олдиндан тўлов суммаси</th>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 34%;"">Қарздорлик суммаси</th>
                            </tr>
                            <tr>
                                <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($savdosumma, 2, ",", " ") . '</td>
                                <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($oldindantulov, 2, ",", " ") . '</td>
                                <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format((round($savdosumma + $xis_foiz - $oldindantulov - $chegirma,-2)), 2, ",", " ") . '</td>
                            </tr>

                        </table>
                        <br />
                        <table style="width:100%; border: 1px solid black; border-collapse: collapse; font-size:14px;" id="tovarjad">
                            <tr>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 7%;">№</th>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 26%;">Тўлов санаси</th>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 33%;">Ойлик тўлови</th>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 34%;">Изоҳ</th>
                            </tr>';




                            $i = 1;
                            $koyi = $shartnom->muddat;
                            $kkuni = $shartnom->kun;
                            $du22 = $shartnom->kun;
                            $shsumma=$savdosumma - $oldindantulov - $chegirma;

                            //йиллик фойиз
                            $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);

                            $i = 0;
                            $nachjami = 0;
                            while ($i <= $koyi) {

                                $tekshuzgar2 = strtotime(+$i . " month", strtotime($kkuni));
                                $tekshtuga2 = strtotime('last day of +' . $i . ' month', strtotime($kkuni));
                                if ($tekshuzgar2 >= $tekshtuga2) {
                                    $du2 = date('Y', strtotime('last day of' . +$i . ' month', strtotime($kkuni)));
                                } else {
                                    $du2 = date("Y", strtotime(+$i . "month", strtotime($kkuni)));
                                }

                                $boshioy = date("m", strtotime($du22));
                                if ($boshioy == 01) {
                                    $du2 = "Январь " . $du2 . " й";
                                } elseif ($boshioy == 2) {
                                    $du2 = "Февраль " . $du2 . " й";
                                } elseif ($boshioy == 3) {
                                    $du2 = "Март " . $du2 . " й";
                                } elseif ($boshioy == 4) {
                                    $du2 = "Апрель " . $du2 . " й";
                                } elseif ($boshioy == 5) {
                                    $du2 = "Май " . $du2 . " й";
                                } elseif ($boshioy == 6) {
                                    $du2 = "Июнь " . $du2 . " й";
                                } elseif ($boshioy == 7) {
                                    $du2 = "Июль " . $du2 . " й";
                                } elseif ($boshioy == 8) {
                                    $du2 = "Август " . $du2 . " й";
                                } elseif ($boshioy == 9) {
                                    $du2 = "Сентябрь " . $du2 . " й";
                                } elseif ($boshioy == 10) {
                                    $du2 = "Октябрь " . $du2 . " й";
                                } elseif ($boshioy == 11) {
                                    $du2 = "Ноябрь " . $du2 . " й";
                                } elseif ($boshioy == 12) {
                                    $du2 = "Декабрь " . $du2 . " й";
                                }


                                $tkun = date("d", strtotime($du22));

                                $datetkun = new DateTime($du22);
                                $datetkun->modify('last day of this month');
                                $date2tkun = $datetkun->format('d');
                                if($tkun>$date2tkun){
                                    $tkun = $date2tkun;
                                }


                                echo '<tr class="align-middle text-center">
                                    <td style="text-align: center; border: 1px solid black; border-collapse: collapse;">' . $i . '</td>
                                    <td style="text-align: center; border: 1px solid black; border-collapse: collapse;">' .$tkun.'-'.$du2 . '</td>';
                                    if($i==0){
                                        echo '<td style="text-align: center; border: 1px solid black; border-collapse: collapse;"></td>';
                                    }else{
                                        echo '<td style="text-align: center; border: 1px solid black; border-collapse: collapse;">' . number_format(round(($shsumma+$xis_foiz)/$muddat,-2), 0, ",", " ") . '</td>';
                                        $nachjami+=(($shsumma+$xis_foiz)/$muddat);
                                    }

                                    echo '<td style="text-align: center; border: 1px solid black; border-collapse: collapse;"></td>
                                    ';
                                $i++;


                                $tekshuzgar = strtotime(+$i . " month", strtotime($kkuni));
                                $tekshtuga = strtotime('last day of +' . $i . ' month', strtotime($kkuni));
                                if ($tekshuzgar >= $tekshtuga) {
                                    $du22 = date('d.m.Y', strtotime('last day of' . +$i . ' month', strtotime($kkuni)));
                                } else {
                                    $du22 = date("d.m.Y", strtotime(+$i . "month", strtotime($kkuni)));
                                }
                            };


                            echo '
                                        <tr class="align-middle text-center fw-bold" >
                                            <td colspan="2" style="text-align: center; border: 1px solid black; border-collapse: collapse;">Жами:</td>
                                            <td style="text-align: center; border: 1px solid black; border-collapse: collapse;">' . number_format(round($nachjami,-2), 0, ",", " ") . '</td>
                                            <td style="text-align: center; border: 1px solid black; border-collapse: collapse;"></td>
                                        </tr>





                        </table>
                       </div>
                    </div>
                </div>
                ';
        }

        return ;
    }

}
