<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\mijozlar;
use App\Models\savdo1;
use App\Models\tulovlar1;
use App\Models\naqdsavdo1;
use App\Models\ktovar1;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;



use Illuminate\Support\Facades\Validator;

class NaqdSavdoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {

            $mijozlar = mijozlar::where('status', '1')->where('m_type', '1')->get();
            $savdounix_id = savdo1::select('unix_id')->where('status', 'Актив')->orderBy('unix_id', 'desc')->groupBy('unix_id')->get();
            return view('kassa.naqdsavdo', ['savdounix_id' => $savdounix_id, 'mijozlar' => $mijozlar]);
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
       echo '
            <table class="table table-bordered text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Куни</th>
                        <th>ФИО</th>
                        <th>Савдо<br>рақами </th>
                        <th>Товар<br>суммаси </th>
                        <th>Нақд </th>
                        <th>Пластик </th>
                        <th>Чегирма</th>
                        <th>Жами<br>суммаси</th>
                        <th>Фарқи</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tab1">
            ';

                $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
                $naqdsavdojami = naqdsavdo1::where('xis_oyi', $xis_oyi)->where('status', 'Актив')->orderBy('id', 'desc')->get();
                foreach ($naqdsavdojami as $naqdsavdojam){

                    $id=$naqdsavdojam->id;
                    $savdoid=$naqdsavdojam->savdoraqami_id;
                    $savdosumma = savdo1::where('status', 'Нақд')->where('unix_id', $savdoid)->where('shartnoma_id', $id)->sum('msumma');

                    $jnaqd = 0;
                    $jplastik =0;
                    $jhr = 0;
                    $jClick = 0;
                    $jchegirma = 0;
                    $tulovlar = tulovlar1::where('tulovturi', 'Нақд')
                    ->where('shartnomaid', $id)
                    ->where('status', 'Актив')
                    ->get();
                    foreach ($tulovlar as $tulovla) {
                        $jnaqd += $tulovla->naqd;
                        $jplastik += $tulovla->pastik;
                        $jhr += $tulovla->hr;
                        $jClick += $tulovla->click;
                        $jchegirma += $tulovla->chegirma;
                    }

                    if ($savdosumma != ($jnaqd + $jplastik + $jhr + $jClick + $jchegirma)){
                        echo'
                            <tr class="align-middle text-danger">
                        ';
                    }else{
                        echo'
                            <tr class="align-middle">
                        ';
                    }
                    echo'
                        <td>' . $naqdsavdojam->id .' </td>
                        <td>' . date("d.m.Y", strtotime($naqdsavdojam->kun)) . '</td>
                        <td>' . $naqdsavdojam->mijozlar->last_name . ' ' . $naqdsavdojam->mijozlar->first_name . ' ' . $naqdsavdojam->mijozlar->middle_name . '</td>
                        <td>' . $naqdsavdojam->savdoraqami_id . '</td>
                        <td>' . number_format($savdosumma, 2, ",", " ") . '</td>
                        <td>' . number_format($jnaqd, 2, ",", " ") . '</td>
                        <td>' . number_format($jplastik, 2, ",", " ") . '</td>
                        <td>' . number_format($jchegirma, 2, ",", " ") . '</td>
                        <td>' . number_format($jnaqd + $jplastik + $jhr + $jClick + $jchegirma, 2, ",", " ") . '
                        </td>
                        <td>' . number_format(($jnaqd + $jplastik + $jhr + $jClick + $jchegirma) -$savdosumma, 2, ",", " ") . '
                        </td>
                    ';
                    $tekshirtovar = savdo1::where('status', 'Нақд')->where('unix_id', $savdoid)->where('shartnoma_id', $id)->where('shtrix_kod', 0)->count();
                    if ($tekshirtovar>0){
                        echo '
                            <td>
                                <button id="kivitpechat" data-id="' . $naqdsavdojam->id .'" data-fio="' . $naqdsavdojam->mijozlar->last_name . ' ' . $naqdsavdojam->mijozlar->first_name . ' ' . $naqdsavdojam->mijozlar->middle_name .'"
                                class="btn btn-outline-primary btn-sm me-2 " data-bs-toggle="modal"
                                data-bs-target="#pechat"><i class="flaticon-381-search-1"></i></button>

                                <button id="tovarudalit" data-id="' . $naqdsavdojam->id .'" data-savdoid="' . $naqdsavdojam->savdoraqami_id .'"
                                class="btn btn-outline-danger btn-sm me-2"><i class="flaticon-381-trash-1"></i></button>
                            </td>
                        ';
                    }else{
                        echo'
                            <td>
                                <button id="kivitpechat" data-id="' . $naqdsavdojam->id .'" data-fio="' . $naqdsavdojam->mijozlar->last_name . ' ' . $naqdsavdojam->mijozlar->first_name . ' ' . $naqdsavdojam->mijozlar->middle_name .'"
                                class="btn btn-outline-primary btn-sm me-2 " data-bs-toggle="modal"
                                data-bs-target="#pechat"><i class="flaticon-381-search-1"></i></button>
                            </td>
                        ';
                    }
                    echo '</tr>';

                }
                    echo'
                        </tbody>
                        </table>
                    ';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'yangikun' => 'required',
            'mijoz' => 'required',
            'savdounix_id' => 'required',
            'naqd' => 'required',
            'plastik' => 'required',
            'click' => 'required',
            'chegirma' => 'required',
        ];

        $messages = [
            'yangikun.required' => 'Сана киритилмади.',
            'mijoz.required' => 'Мижозни танланг.',
            'savdounix_id.required' => 'Савдо-раками танланг.',
            'naqd.required' => 'Тўлов киритилмади..',
            'plastik.required' => 'Тўлов киритилмади..',
            'click.required' => 'Тўлов киритилмади..',
            'chegirma.required' => 'Чегирмани киритинг.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $msumma = savdo1::where('status', 'Актив')->where('unix_id', $request->savdounix_id)->sum('msumma');
        if($msumma>0){

            $naqd = floatval(preg_replace('/[^\d.]/', '', $request->naqd));
            $plastik = floatval(preg_replace('/[^\d.]/', '', $request->plastik));
            $click = floatval(preg_replace('/[^\d.]/', '', $request->click));
            $chegirma = floatval(preg_replace('/[^\d.]/', '', $request->chegirma));

            if($msumma!=($naqd+$plastik+$click+$chegirma)){
                return response()->json(['message' => 'Хатолик: Товар суммаси ва Сиз киритган сумма тўғри эмас.'], 200);
            }else{
                try {
                    DB::beginTransaction();

                    $naqdsavdo = new naqdsavdo1;
                    $naqdsavdo->mijozlar_id = $request->mijoz;
                    $naqdsavdo->kun = $request->yangikun;
                    $naqdsavdo->savdoraqami_id = $request->savdounix_id;
                    $naqdsavdo->xis_oyi = $xis_oyi;
                    $naqdsavdo->user_id = Auth::user()->id;
                    $naqdsavdo->save();
                    $insid = $naqdsavdo->id;

                    $savdo1Updated = savdo1::where('unix_id', $request->savdounix_id)
                    ->where('status', 'Актив')
                    ->update([
                        'status' => "Нақд",
                        'status2' => "Нақд",
                        'shartnoma_id' => $insid,
                    ]);

                    $tulovlar = new tulovlar1;
                    $tulovlar->kun = $request->yangikun;
                    $tulovlar->tulovturi = 'Нақд';
                    $tulovlar->shartnomaid = $insid;
                    $tulovlar->xis_oyi = $xis_oyi;
                    $tulovlar->naqd =  $naqd;
                    $tulovlar->pastik =  $plastik;
                    $tulovlar->click =  $click;
                    $tulovlar->chegirma =  $chegirma;
                    $tulovlar->umumiysumma =  ($naqd + $plastik + $click);
                    $tulovlar->user_id = Auth::user()->id;
                    $tulovlar->save();

                    if ($naqdsavdo && $savdo1Updated && $tulovlar) {
                        DB::commit();
                        return response()->json(['message' => 'Маълумот сақланди.'], 200);
                    } else {
                        DB::rollBack();
                        return response()->json(['message' => 'Маълумот сақлашда хатолик.'], 200);
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['message' => 'Маълумот сақлашда хатолик2.'], 200);
                    // throw $e;
                }
            }
        }else{
            return response()->json(['message' => 'Хатолик: Свдо рақам топилмади бошқа савдо рақами танланг.'], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {


        function num2str($num)
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
                array('тийин' , 'тийин',   'тийин',     1),
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
                    if ($uk > 1) $out[] = morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
                }
            } else {
                $out[] = $nul;
            }
            $out[] = morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
            $out[] = $kop . ' ' . morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
            return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
        }

        function morph($n, $f1, $f2, $f5)
        {
            $n = abs(intval($n)) % 100;
            if ($n > 10 && $n < 20) return $f5;
            $n = $n % 10;
            if ($n > 1 && $n < 5) return $f2;
            if ($n == 1) return $f1;
            return $f5;
        }

        $id=$id;
        $filia = filial::where('id', Auth::user()->filial_id)->first();
        $ytt=$filia->ytt;
        $manzil=$filia->manzil;
        $xr=$filia->xr;
        $inn=$filia->inn;
        $bankname=$filia->bankname;
        $mfo=$filia->mfo;

        $naqdsavdojam = naqdsavdo1::where('id', $id)->where('status', 'Актив')->first();

        $tulovlar = tulovlar1::where('status', 'Актив')->where('shartnomaid', $id)->where('tulovturi', 'Нақд')->get();

       foreach($tulovlar as $tulovla){
            echo'
                <table class="table-sm table-hover" style="font-size: 12px; text-align: center; width:100%; border: 1px solid black;">
                    <tbody>
                    <tr class="align-middle">
                        <td rowspan="8" style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 8pt; width: 10%;"">
                            <span>
                                <img src="/images/favicon.png" style="width: 100px; margin-left: 5px;">
                            </span>
                        </td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 10pt; text-align: center; text-transform: uppercase; color: RoyalBlue;" colspan="6"><b>'.$ytt.'</b><br><b>'.$manzil.'</b></td></td>
                    </tr>

                    <tr class="align-middle">
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; font-size: 8pt;">Х-р:</td>
                        <td colspan="3" style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.$xr.'</b></td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">ИНН:</td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.$inn.'</b></td>
                    </tr>
                    <tr class="align-middle text-muted">
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Банк номи:</td>
                        <td colspan="3" style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b style="text-transform: uppercase;">'.$bankname.'</b></td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Банк коди:</td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.$mfo.'</b></td>
                    </tr>
                    <tr class="align-middle text-muted">
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Тўловчи:</td>
                        <td colspan="5"><b style="text-transform: uppercase;">'.$naqdsavdojam->mijozlar->last_name.' '.$naqdsavdojam->mijozlar->first_name.' '.$naqdsavdojam->mijozlar->middle_name.'</b></td>
                    </tr>
                    <tr class="align-middle text-muted">
                    <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Тўлов куни:</td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.date("d.m.Y",strtotime($tulovla->kun)).'</b></td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Тўлов вакти:</td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.date("h:i:s",strtotime($tulovla->created_at)).'</b></td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Нақд савдо №:</td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.$id.'</b></td>
                    </tr>
                    <tr class="align-middle text-muted">
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; text-align: center; font-size: 10pt; text-align: center; text-transform: uppercase; color: RoyalBlue;" colspan="6"><b>Тўлов тури</b></td>
                    </tr>
                    <tr class="align-middle text-muted text-center">
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Нақд</td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Пластик</td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Сlick</td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Х-р</td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Жами тўлов</td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; color: red;">Чегирма</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.number_format($tulovla->naqd,2,","," ").'</b></td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.number_format($tulovla->pastik,2,","," ").'</b></td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.number_format($tulovla->click,2,","," ").'</b></td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.number_format($tulovla->xr,2,","," ").'</b></td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"><b>'.number_format($tulovla->umumiysumma,2,","," ").'</b></td>
                        <td style="border: 1px solid black; border-collapse: collapse; padding: 5px; color: red;"><b>'.number_format($tulovla->chegirma,2,","," ").'</b></td>
                    </tr>
                    <tr class="align-middle text-muted">
                        <td style="text-align: center; text-transform: uppercase;" colspan="7"><b>'.num2str($tulovla->umumiysumma).'</b></td>
                    </tr>
                </tbody>
            </table>
            ';
        };

        echo '
        <br>
            <table class="table-sm table-hover" style="font-size: 12px; text-align: center; width:100%; border: 1px solid black;">
                <thead>
                    <tr class="align-middle text-center">
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">№</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Модел<br>ID</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Маҳсулот номи</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Штрих-рақами</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Холати</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Сони</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Суммаси</th>
                    </tr>
                </thead>

                <tbody id="tab1">';

                    $savdomodel = savdo1::where('status', 'Нақд')->where('shartnoma_id', $id)->get();
                    $i = 1;
                    $jami = 0;
                    foreach ($savdomodel as $savdomode) {
                        echo "
                            <tr class='text-center align-middle m-2'>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . $i . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . $savdomode->tmodel_id . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . $savdomode->shtrix_kod . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . $savdomode->status . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . number_format(1, 0, ',', ' ') . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                            </tr>";
                        $i++;
                        $jami += $savdomode->msumma;
                    };


                    echo '
                        <tr class="text-center align-middle fw-bold">
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"></td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"></td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">ЖАМИ</td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"></td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"></td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">' . number_format($i-1, 0, ",", " ") . '</td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">' . number_format($jami, 0, ",", " ") . '</td>
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
        $savdomodel = savdo1::where('status', 'Актив')->where('unix_id', $id)->get();

        echo '<h3 class=" text-center text-primary ">' . $id . '</h3>
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="text-center text-bold text-primary align-middle">
                    <th>№</th>
                    <th>Куни</th>
                    <th>Товар номи</th>
                    <th>Суммаси</th>

                </tr>
            </thead>
            <tbody id="tab1">';
        $jami = 0;
        $i = 1;
        foreach ($savdomodel as $savdomode) {
            echo "
                <tr class='text-center align-middle'>
                    <td>" . $i . "</td>
                    <td>" . date('d.m.Y', strtotime($savdomode->created_at)) . "</td>
                    <td>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                    <td>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                </tr>";
            $jami += $savdomode->msumma;
            $i++;
        }
        echo "
                <tr class='text-center align-middle'>
                    <td></td>
                    <td></td>
                    <td>ЖАМИ</td>
                    <td>" . number_format($jami, 0, ',', ' ') . "</td>
                </tr>
            </tbody>
        </table>";
        return;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $tekshirktovar1 = ktovar1::where('status', 'Нақд')->where('shatnomaid', $id)->count();
        $tekshirsavdo1 = savdo1::where('status', 'Нақд')->where('unix_id', $request->savdoid)->where('shartnoma_id', $id)->where('shtrix_kod','>', 0)->count();
        if ($tekshirktovar1==0 && $tekshirsavdo1==0){
            $Readfond1 = naqdsavdo1::where('id', $id)->where('status', 'Актив')->first();
            $FondKun=$Readfond1->kun;
            $BugungiKun = date("Y-m-d");
            if($Readfond1->id>0){
                if($FondKun==$BugungiKun){
                    try {
                        DB::beginTransaction();

                        $savdUpdated = savdo1::where('unix_id', $request->savdoid)
                            ->where('status', 'Нақд')
                            ->update([
                                'status' => "Удалит",
                                'del_kun' => date('Y-m-d H:i:s'),
                                'del_user_id' => Auth::user()->id,
                            ]);

                        $tulovUpdated = tulovlar1::where('tulovturi', 'Нақд')
                            ->where('shartnomaid', $id)
                            ->limit(1)
                            ->update([
                                'status' => 'Удалит',
                                'del_kun' => date('Y-m-d'),
                                'del_user_id' => Auth::user()->id,
                            ]);
                        $naqdsavdoUpdated = naqdsavdo1::where('id', $id)
                            ->limit(1)
                            ->update([
                                'status' => 'Удалит',
                                'user_id' => Auth::user()->id,
                            ]);

                            if ($savdUpdated && $tulovUpdated && $naqdsavdoUpdated) {
                            DB::commit();
                            return response()->json(['message' => 'Маълумот ўчирилди.'], 200);
                        } else {
                            DB::rollBack();
                            return response()->json(['message' => 'Маълумот ўчиришда хатолик.'], 200);
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return response()->json(['message' => 'Маълумот ўчиришда хатолик2.'], 200);
                        // throw $e;
                    }
                }else{
                    return response()->json(['message' => 'Хатолик: ИД '.$id.' даги Нақд савдони ўчириш учун админга мурожат қилинг.'], 200);
                }
            }else{
                return response()->json(['message' => 'Хатолик: '.$id.' фонд савдо топилмади.'], 200);
            }

        }else{
            return response()->json(['message' => 'Маълумоти ўчириб бўлмайди ўчириш учун товарини омборга қайтаринг.'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
