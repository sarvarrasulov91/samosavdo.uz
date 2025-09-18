<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\shartnoma1;
use App\Models\tulovlar1;
use App\Models\savdo1;
use App\Models\ktovar1;
use App\Models\tmqaytarish;
use App\Models\xissobotoy;
use App\Models\filial;



use DateTime;

class SHartnomaOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {

            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();

            return view('shartnoma.OfficeSHartnoma', ['filial' => $filial ]);

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
        $id = $request->id;
        $filial = $request->filial;
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

        $model = new shartnoma1($filial);
        $shartnoma = $model->where('id', $id)->get();
        foreach ($shartnoma as $shartnom) {

            $savdo = new savdo1($filial);
            $savdosumma = $savdo->where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');

            $oldindantulovinfo = new tulovlar1($filial);
            $oldindantulov = $oldindantulovinfo->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('umumiysumma');
            $chegirma = $oldindantulovinfo->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('chegirma');
            $tulov = $oldindantulovinfo->where('tulovturi', 'Шартнома')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('umumiysumma');


            $tsana = date('d.m.Y', strtotime($shartnom->t_sana));
            $kun = date('d.m.Y', strtotime($shartnom->kun));
            $muddat = number_format($shartnom->muddat, 0, ',', ' ');
            $shsana = number_format($savdosumma, 2, ',', ' ');
            $oldintulov2 = number_format($oldindantulov, 2, ',', ' ');
            $chegirm = number_format($chegirma, 2, ',', ' ');
            $tani = number_format($savdosumma-$oldindantulov-$chegirma, 2, ',', ' ');
            $tulovshart = number_format($tulov, 2, ',', ' ');

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
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
                $date11 = new DateTime($shartnom->kun);
                $date22 = new DateTime(date("Y-m-d"));
                $interval1 = $date11->diff($date22);
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
                                        <button id="shyopish" data-shid="'.$shartnom->id.'" type="button" class="btn btn-danger light text-center">Шартномани муддатидан олдин ёпиш </button>
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
                                <span class="text-muted">Шартнома суммаси</span> <span class="badge-pill text-primary">' . $shsana . '</span>
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

                $tulovlari = new tulovlar1($filial);

                $opl01 = $tulovlari->where('tulovturi', 'Шартнома')
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
            $lateTulovlar = $tulovlari->where('tulovturi', 'Шартнома')
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
            		   		<th>Платик</th>
                            <th>Х-р</th>
                            <th>Клик</th>
                            <th>Бонус</th>
            		   		<th>Жами</th>
                            <th>Холати</th>
                            <th></th>
                    	</tr>
                	</thead>
                  	<tbody id="tab1">';


                        $tulovlar = new tulovlar1($filial);
                        $tulovlar = $tulovlar->where('shartnomaid', $shartnom->id)
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
                                <td>
                                    <button
                                        id='tulov_uchrish'
                                        data-tulovid='{$t->id}'
                                        data-filial='{$filial}'
                                        data-shid='{$shartnom->id}'
                                        type='button'
                                        class='btn btn-outline-danger btn-sm ms-2'>
                                        <i class='flaticon-381-substract-1'></i>
                                    </button>
                                </td>
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
                                <td></td>
                            </tr>
                    </tbody>
                </table>
                <br>
                <h5 class=" text-center text-uppercase" style="color: RoyalBlue;">Шартномада кўрсатилган товарлар рўйхати</h5>';

            $savdo = new savdo1($filial);
            $savdomodel = $savdo->where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->get();
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
                        <th>
                            <button
                                id="tovar_qushish"
                                data-shid="'. $shartnom->id .'"
                                type="button" class="btn btn-outline-primary btn-sm ms-2">
                                <i class="flaticon-381-plus"></i>
                            </button>
                        </th>

                   </tr>
               </thead>
               <tbody id="tab1">';
            $jami = 0;
            $i = 1;
            foreach ($savdomodel as $savdomode) {
                echo "
                   <tr class='text-center align-middle'>
                       <td>" . $i++ . "</td>
                       <td>" . $savdomode->tmodel_id . "</td>
                       <td>" . $savdomode->unix_id . "</td>
                       <td>" . date('d.m.Y', strtotime($savdomode->created_at)) . "</td>
                       <td>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                       <td>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                       <td>" . $savdomode->shtrix_kod . "</td>
                       <td> <button id='tovar_uchrish' data-stid='".$savdomode->id."' data-shid='".$shartnom->id."' type='button' class='btn btn-outline-danger btn-sm ms-2'><i class='flaticon-381-substract-1'></i></button> </td>
                    </tr>";
                $jami += $savdomode->msumma;
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
         echo'
            <table
                class="table table-bordered text-center align-middle table-hover"
                style="font-size: 13px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Манзили</th>
                        <th>Телефон<br>рақами</th>
                        <th>Шартнома<br>санаси</th>
                        <th>Шартнома<br>муддати</th>
                        <th>Шартнома<br>суммаси</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody id="tab1">';


                    $jami = 0;
                    $model = new shartnoma1($id);
                    $shartnoma = $model->whereIn('status', ['Актив', 'Ёпилган'])->orderBy('id', 'desc')->get();
                    foreach ($shartnoma as $shartnom){

                        $trClass = ($shartnom->status == 'Ёпилган') ? 'align-middle text-success' : 'align-middle';

                        $savdo = new savdo1($id);
                        $savdosummasi=$savdo->where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');

                        $oldindantulov = new tulovlar1($id);
                        $oldindantulovsummasi = $oldindantulov->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('umumiysumma');
                        $otulovchegirmasummasi = $oldindantulov->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('chegirma');

                        echo'
                        <tr id="modalshartshow" data-id="'.$shartnom->id.'" data-fio="'.addslashes($shartnom->mijozlar->last_name) . ' ' . addslashes($shartnom->mijozlar->first_name) . ' ' . addslashes($shartnom->mijozlar->middle_name).'"  class="'.$trClass.'">
                            <td>' . $shartnom->id . '</td>
                            <td style="white-space: pre-wrap;">' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name . '
                            </td>
                            <td style="white-space: pre-wrap;">' . $shartnom->mijozlar->tuman->name_uz .' '. $shartnom->mijozlar->mfy->name_uz . ' ' . $shartnom->mijozlar->manzil . '
                            </td>
                            <td>' . $shartnom->mijozlar->phone . '</td>
                            <td>' . date('d.m.Y', strtotime($shartnom->kun)) . '</td>
                            <td>' . $shartnom->muddat . '</td>
                            <td>' . number_format($savdosummasi-$otulovchegirmasummasi, 2, ",", " ") . '</td>
                            <td>' . $shartnom->status . '</td>
                        </tr>
                        ';
                            $jami += $savdosummasi-$otulovchegirmasummasi;
                    }
                    echo '
                            <tr class="align-middle text-bold">
                                <td></td>
                                <td>Жами</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>' . number_format($jami, 2, ",", " ") . '</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    ';

                    return;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $shartnoma = shartnoma1::where('id', $id)->get();
        foreach ($shartnoma as $shartnom) {
            $kun = date('d.m.Y', strtotime($shartnom->kun));
            $muddat = number_format($shartnom->muddat, 0, ',', ' ');

            $oldindantulov = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('umumiysumma');
            $chegirma = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('chegirma');
            $savdosumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');


            $foiz = xissobotoy::where('xis_oy', $shartnom->xis_oyi)->value('foiz');
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            if($shartnom->fstatus == 0){
                $foiz = 0;
            }


            //йиллик фойиз
            $foiz = (($foiz / 12) * $shartnom->muddat);
            $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);

                echo'<div class="modal-body m-0">
                    <div class="flex items-center justify-center bg-white ">
                        <div id="certificate" class="text-center p-2">
                            <h5 style="text-align:center; margin-bottom: -5px;">Муддатли тўлов шарти билан маҳсулот(лар) сотиб олиш учун</h5>
                            <div>
                                <h3 style="text-align:center; margin-bottom: -2px;">АРИЗА</h3>
                                <h4 style="text-align:left; ">'.date("d.m.Y").' йил.</h4>
                            </div>
                        </div>
                        <div>
                            <table style="width:100%; border: 1px solid black; border-collapse: collapse; font-size:14px; " id="tovarjad">
                                <tr id="#jadst">
                                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;">Ф.И.Ш.</th>
                                    <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;" colspan="4">' . $shartnom->mijozlar->last_name . " " . $shartnom->mijozlar->first_name . " " . $shartnom->mijozlar->middle_name. '</th>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;">Туғилган йил</td>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-weight: bold;" colspan="4">' . date("d.m.Y", strtotime($shartnom->mijozlar->t_sana)) . '</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;" rowspan="2">Ҳужжат</td>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;">Паспорт рақами</td>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;">Берилган вақти</td>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;">Ким томонидан берилган</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;">' . $shartnom->mijozlar->passport_sn . '</td>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;">' . date("d.m.Y", strtotime($shartnom->mijozlar->passport_date)) . '</td>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;">' . $shartnom->mijozlar->passport_iib . '</td>
                                </tr>
                                <tr id="#jadst">
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;" id="#jadst">Манзил</td>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-weight: bold;" colspan="4" id="#jadst">' . $shartnom->mijozlar->tuman->name_uz . ' ' . $shartnom->mijozlar->mfy->name_uz . ' ' . $shartnom->mijozlar->manzil . '</td>
                                </tr>
                                <tr id="#jadst">
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;" id="#jadst">Телефон</td>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-weight: bold;" colspan="4" id="#jadst">' .$shartnom->mijozlar->phone . '</td>
                                </tr>
                                <tr id="#jadst">
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center;" id="#jadst">Иш жойи</td>
                                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-weight: bold;" colspan="4" id="#jadst">' . $shartnom->mijozlar->ish_joy . '</td>
                                </tr>
                            </table>


                            <table style="width:100%; border: 1px solid black; border-collapse: collapse; font-size:14px; " id="tovarjad">
                            <tr>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 5%;">№</th>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 7%;">Модел ИД</th>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 38%;">Маҳсулот номи</th>
                                <th style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; width: 10%;">Сони</th>
                            </tr> <br />';

                            $savdomodel = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->get();
                            $i = 0;
                            foreach ($savdomodel as $savdomode) {
                                $i++;
                                echo "
                                    <tr class='text-center align-middle m-2'>
                                        <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . $i . "</td>
                                        <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . $savdomode->tmodel_id . "</td>
                                        <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                                        <td style='text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt'>" . number_format(1, 0, ',', ' ') . "</td>
                                    </tr>";
                            };

                            echo '
                               <tr class="text-center align-middle fw-bold">
                                    <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt"></td>
                                    <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">ЖАМИ</td>
                                    <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt"></td>
                                    <td style="text-align: center; border: 1px solid black; border-collapse: collapse; font-size: 8pt">' . number_format($i, 0, ",", " ") . '</td>
                                </tr>
                        </table>

                        </table>
                        <br />
                        <table style="width:100%; border: 1px solid black; border-collapse: collapse; font-size:14px;" id="tovarjad">
                            <tr id="#jadst">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: left; width: 40%;">Келажакда нималар сотиб олмокчисиз</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: left;"></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: left; width: 40%;">Сизниннг таклифларингиз</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: left;"></td>
                            </tr>
                        </table>
                        <p style="text-align:justify font-size:14px; ">
                            Менинг берган аризамни ўрганиш жараёнида мен хақимдаги малумотларни иш хақи, бошқа  ташкилотлардан қарздорлигим бор йўқлигини, бошқа молиявий холатимни текширишингизга, шунингдек шартнома бўйича мажбуриятимни, график бўйича тўловларни ўз вақтида қайтарилиши учун менинг хар қандай банк хисоб рақамимдан ёки хар қандай пластик картамдан автоматик тарзда ечиб олинишига розиман.<br>
                            Аризага қуйидагиларни тақдим этаман.<br><br>
                            1)  Шахсимни тасдиқловчи хужжат нусхаси,<br><br>
                            2)  Ўзим тўғримдаги керакли малумотларни тўлиқ.<br>

                        </p>
                        <h4 style="text-align:left; margin-bottom: -15px;">' . $shartnom->mijozlar->last_name . " " . $shartnom->mijozlar->first_name . " " . $shartnom->mijozlar->middle_name.  '  ______________________________________</h4>

                        </div>
                    </div>
                </div>
            ';
        }
        return ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            if($request->status == 'tqushish'){

                $savdo = new savdo1($request->filial);
                $savdounix_id = $savdo->where('status', 'Актив')->where('unix_id', $request->savdo_id)->count();
                if ($savdounix_id >= 1) {
                    $savdoadd = new savdo1($request->filial);
                    $tur = $savdoadd->where('unix_id', $request->savdo_id)->where('status', 'Актив')->
                    update([
                        'status' => "Шартнома",
                        'status2' => "Шартнома",
                        'shartnoma_id' => $request->id,
                        'q_user_id' => Auth::user()->id,
                        'q_kun' => date('Y-m-d H:i:s'),
                        'q_xis_oyi' => $xis_oyi,
                    ]);
                    return response()->json(['message' => 'Товар қўшилди .'], 200);
                }else{
                    return response()->json(['message' => $request->savdo_id . "<br> Хатолик!!! Савдо рақами топилмади."], 200);
                }

            }elseif($request->status == 'tuchirish'){

                if (!empty($request->stid)) {

                    $shtrix_kod = 0;

                    $savdo = new savdo1($request->filial);
                    $savdosumma = $savdo->where('status', 'Шартнома')->where('id', $request->stid)->first();

                    if ($savdosumma) {
                        $shtrix_kod = $savdosumma->shtrix_kod;
                    } else {
                        // Handle the case where no record is found
                        $shtrix_kod = 0;
                    }

                    if($shtrix_kod > 0){

                        $Counttovar = new ktovar1($request->filial);
                        $Counttovar1 = $Counttovar->where('status', 'Шартнома')->where('shtrix_kod', $shtrix_kod)->count();

                        if ($Counttovar1 > 0) {

                            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
                            $ReadK = new ktovar1($request->filial);
                            $ReadKt = $ReadK->where('status', 'Шартнома')->where('shtrix_kod', $shtrix_kod)->get();

                            foreach ($ReadKt as $ReadKtovar) {
                                if ($xis_oyi == $ReadKtovar->ch_xis_oyi) {
                                    try {
                                        DB::beginTransaction();

                                        $ktovar = new ktovar1($request->filial);
                                        $ktovarUpdated = $ktovar->where('status', 'Шартнома')
                                            ->where('shtrix_kod', $shtrix_kod)
                                            ->where('ch_xis_oyi', $xis_oyi)
                                            ->limit(1)
                                            ->update([
                                                'status' => "Сотилмаган",
                                                'ch_kun' => null,
                                                'ch_user_id' => 0,
                                                'ch_xis_oyi' => null,
                                                'shatnomaid' => 0,
                                            ]);

                                            $savdorem = new savdo1($request->filial);
                                            $savdUpdated = $savdorem->where('id', $request->stid)
                                            ->where('status', 'Шартнома')
                                            ->limit(1)
                                            ->update([
                                                'status' => "Удалит",
                                                'del_user_id' => Auth::id(),
                                                'del_kun' => now(),
                                                'del_xis_oyi' => $xis_oyi,
                                            ]);

                                        if ($savdUpdated && $ktovarUpdated) {
                                            DB::commit();
                                            return response()->json(['message' => "Шартномага бириктирилган товар омборга қайтарилди."], 200);
                                        } else {
                                            DB::rollBack();
                                            return response()->json(['message' => "Маълумот ўчиришда хатолик."], 200);
                                        }
                                    } catch (\Exception $e) {
                                        DB::rollBack();
                                        return response()->json(['message' => "Маълумот ўчиришда хатолик2."], 200);
                                    }

                                } else {

                                    $soninar = 0;
                                    $KtovarBarkod = new ktovar1($request->filial);
                                    $ktovarbarkods = $KtovarBarkod->where('tmodel_id', $ReadKtovar->tmodel_id)->orderBy('soni', 'desc')->limit(1)->get();

                                    foreach ($ktovarbarkods as $ktovarbarkodsoni) {
                                        $soninar = $ktovarbarkodsoni->soni;
                                    }

                                    $soninar++;

                                    $turid2 = str_pad($ReadKtovar->tur_id, 4, "0", STR_PAD_LEFT);
                                    $brendid2 = str_pad($ReadKtovar->brend_id, 4, "0", STR_PAD_LEFT);
                                    $model2 = str_pad($ReadKtovar->tmodel_id, 5, "0", STR_PAD_LEFT);
                                    $soninar2 = str_pad($soninar, 4, "0", STR_PAD_LEFT);

                                    $shtr_kod = $turid2 . $brendid2 . $model2 . $soninar2;

                                    try {
                                        DB::beginTransaction();
                                        $ktovarzapis = new ktovar1($request->filial);
                                        $ktovarzapis->kun = date('Y-m-d');
                                        $ktovarzapis->tur_id = $ReadKtovar->tur_id;
                                        $ktovarzapis->brend_id = $ReadKtovar->brend_id;
                                        $ktovarzapis->tmodel_id = $ReadKtovar->tmodel_id;
                                        $ktovarzapis->shtrix_kod = $shtr_kod;
                                        $ktovarzapis->soni = $soninar;
                                        $ktovarzapis->valyuta_id = $ReadKtovar->valyuta_id;
                                        $ktovarzapis->narhi = $ReadKtovar->narhi;
                                        $ktovarzapis->snarhi = $ReadKtovar->snarhi;
                                        $ktovarzapis->valyuta_narhi = $ReadKtovar->valyuta_narhi;
                                        $ktovarzapis->tannarhi = $ReadKtovar->tannarhi;
                                        $ktovarzapis->pastavshik_id = $ReadKtovar->pastavshik_id;
                                        $ktovarzapis->pastavshik2_id = $ReadKtovar->pastavshik2_id;
                                        $ktovarzapis->filial_id = $request->filial;
                                        $ktovarzapis->xis_oyi = $xis_oyi;
                                        $ktovarzapis->user_id = Auth::user()->id;
                                        $ktovarzapis->save();
                                        $insid = $ktovarzapis->id;

                                        $CreateTqaytarish = new tmqaytarish;
                                        $CreateTqaytarish->savdo_turi = $ReadKtovar->status;
                                        $CreateTqaytarish->shartnoma_id = $ReadKtovar->shatnomaid;
                                        $CreateTqaytarish->kun = $ReadKtovar->kun;
                                        $CreateTqaytarish->tur_id = $ReadKtovar->tur_id;
                                        $CreateTqaytarish->brend_id = $ReadKtovar->brend_id;
                                        $CreateTqaytarish->tmodel_id = $ReadKtovar->tmodel_id;
                                        $CreateTqaytarish->shtrix_kod = $ReadKtovar->shtrix_kod;
                                        $CreateTqaytarish->valyuta_id = $ReadKtovar->valyuta_id;
                                        $CreateTqaytarish->narhi = $ReadKtovar->narhi;
                                        $CreateTqaytarish->snarhi = $ReadKtovar->snarhi;
                                        $CreateTqaytarish->valyuta_narhi = $ReadKtovar->valyuta_narhi;
                                        $CreateTqaytarish->tannarhi = $ReadKtovar->tannarhi;
                                        $CreateTqaytarish->pastavshik_id = $ReadKtovar->pastavshik2_id;
                                        $CreateTqaytarish->xis_oyi = $xis_oyi;
                                        $CreateTqaytarish->filial_id = $request->filial;
                                        $CreateTqaytarish->user_id = Auth::user()->id;
                                        $CreateTqaytarish->kirim_id = $insid;
                                        $CreateTqaytarish->shtrix_kod_yangi = $shtr_kod;
                                        $CreateTqaytarish->save();

                                        $savdorem = new savdo1($request->filial);
                                        $savdUpdated = $savdorem->where('id', $request->stid)->where('status', 'Шартнома')->limit(1)
                                        ->update([
                                            'status' => "Удалит",
                                            'del_user_id' => Auth::user()->id,
                                            'del_kun' => date('Y-m-d H:i:s'),
                                            'del_xis_oyi' => $xis_oyi,
                                        ]);

                                        if ($ktovarzapis && $CreateTqaytarish && $savdUpdated) {
                                            DB::commit();
                                            return response()->json(['message' => "Шартномага бириктирилган товар янги".$shtr_kod." рақами блан омборга қайтарилди."], 200);
                                        } else {
                                            DB::rollBack();
                                            return response()->json(['message' => "Нақд савдони ўчиришда хатолик."]);
                                        }
                                    } catch (\Exception $e) {
                                        DB::rollBack();
                                        return response()->json(['message' => "Нақд савдони ўчиришда хатолик.2"]);
                                    }
                                }
                            }
                        }

                    }else{

                        $savdorem = new savdo1($request->filial);
                        $savdUpdated = $savdorem->where('id', $request->stid)->where('status', 'Шартнома')->limit(1)
                            ->update([
                                'status' => "Удалит",
                                'del_user_id' => Auth::id(),
                                'del_kun' => now(),
                                'del_xis_oyi' => $xis_oyi,
                        ]);

                        if ($savdUpdated) {
                            return response()->json(['message' => "Маълумот ўчирилди."], 200);
                        } else {
                            return response()->json(['message' => "Маълумот ўчиришда хатолик."], 200);
                        }
                    }
                }

            } elseif ($request->status == 'tulovuchrish'){

                $tulovlar = new tulovlar1($request->filial);
                $tulov = $tulovlar
                    ->where('id', $request->tulovid)
                    ->where('shartnomaid', $request->id)
                    ->where('status', 'Актив')
                    ->first();

                if (date('Y-m-d', strtotime($tulov->kun)) == date("Y-m-d")) {

                     $tulov->update([
                        'status' => "Удалит",
                        'del_user_id' => Auth::id(),
                        'del_kun' => now(),
                    ]);

                    return response()->json(['message' => 'Тўлов учирилди.'], 200);

                } else {

                   $tulov->update([
                        'tulovturi' => "Брон",
                        'bron_user_id' => Auth::id(),
                        'bron_kun' => now(),
                        'bron_xis_oyi' => $xis_oyi,
                    ]);

                    return response()->json(['message' => 'Тўлов бронга олинди.'], 200);
                }

                return response()->json(['message' => "Tulovni o'chirishda xatolik"], 200);

            } else {

                return response()->json(['message' => "Yuborilgan statusda xatolik"], 200);
            }
        } else {
            return response()->json(['message' => "Хатолик!!! <br> Админга мурожат қилинг."], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $oldindantulov = 0;
            $tulov = 0;
            $savdosumma = 0;
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $tulovlar = new tulovlar1(1);
            $oldindantulov = $tulovlar->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('umumiysumma');
            $tulov = $tulovlar->where('tulovturi', 'Шартнома')->where('status', 'Актив')->where('shartnomaid', $id)->sum('umumiysumma');

            $savdolar = new savdo1(1);
            $savdosumma = $savdolar->where('status', 'Шартнома')->where('status', 'Актив')->where('shartnoma_id', $id)->sum('msumma');

            if($oldindantulov == 0 && $tulov == 0 && $savdosumma == 0){
                $shartnoma = new shartnoma1(1);
                $shartnoma1 = $shartnoma->where('id', $id)->where('status', 'Актив')
                ->update([
                    'izox' => "Шартнома мажбурий шакилда ёпилди",
                    'status' => 'Удалит',
                    'yo_sana' => now(),
                    'yo_xis_oyi' => $xis_oyi,
                    'yo_user_id' => Auth::user()->id,
                    'skidka' => 0,
                ]);

                if ($shartnoma1) {
                    $message = 'Шартнома мажбурий шакилда ёпилди.';
                } else {

                    $message = "Шартномани ёпишда хатолик. $shartnoma";
                }

            }else{
                $message = "Шартномани ёпишдан олдин товарларни ва туловларни кайтаринг.";
            }

            return response()->json(['message' => $message], 200);
        }else{
            return response()->json(['message' => "Хатолик!!! <br> Шартномани ўчириш учун админга мурожат қилинг."], 200);
        }

    }
}
