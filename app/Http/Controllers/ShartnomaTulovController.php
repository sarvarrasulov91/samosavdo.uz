<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\tulovlar1;
use App\Models\shartnoma1;
use App\Models\savdo1;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;

use DateTime;

class ShartnomaTulovController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
            $shartnoma = shartnoma1::where('.status', 'Актив')->orderBy('.id', 'desc')->get();
            return view('kassa.shartnomatulov', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'shartnoma' => $shartnoma]);
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
                    <th>Шартнома<br>рақами</th>
                    <th>Ф.И.О</th>
                    <th>Телефони</th>
                    <th>Нақд</th>
                    <th>Пластик</th>
                    <th>Х-Р</th>
                    <th>Сlick</th>
                    <th>Жами тўлови</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tab1">';

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $tulovlar = tulovlar1::where('status', 'Актив')->where('tulovturi', 'Шартнома')->where('xis_oyi', $xis_oyi)->orderBy('id', 'desc')->get();
            foreach ($tulovlar as $tulovla){
                echo'
                <tr class="align-middle text-center">
                    <td>' . $tulovla->id .'</td>
                    <td>' . date("d.m.Y", strtotime($tulovla->kun)) .'</td>
                    <td>' . $tulovla->shartnomaid .'</td>
                    <td>' . $tulovla->shartnoma1->mijozlar->last_name . ' ' . $tulovla->shartnoma1->mijozlar->first_name . ' ' . $tulovla->shartnoma1->mijozlar->middle_name .'
                    </td>
                    <td>' . $tulovla->shartnoma1->mijozlar->phone .'</td>
                    <td>' . number_format($tulovla->naqd, 2, ',', ' ') .'</td>
                    <td>' . number_format($tulovla->pastik, 2, ',', ' ') .'</td>
                    <td>' . number_format($tulovla->hr, 2, ',', ' ') .'</td>
                    <td>' . number_format($tulovla->click, 2, ',', ' ') .'</td>
                    <td>' . number_format($tulovla->naqd + $tulovla->pastik + $tulovla->hr + $tulovla->click, 2, ',', ' ') .'
                    </td>
                    <td>
                        <button id="kivitpechat" data-id="' . $tulovla->id .'" data-fio="' . $tulovla->shartnoma1->mijozlar->last_name . ' ' . $tulovla->shartnoma1->mijozlar->first_name . ' ' . $tulovla->shartnoma1->mijozlar->middle_name .'"
                            class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal"
                            data-bs-target="#pechat"><i class="flaticon-381-search-1"></i></button>

                        <button id="tovarudalit" data-id="' . $tulovla->id .'" data-fio="' . $tulovla->shartnoma1->mijozlar->last_name . ' ' . $tulovla->shartnoma1->mijozlar->first_name . ' ' . $tulovla->shartnoma1->mijozlar->middle_name .'"
                            class="btn btn-outline-danger btn-sm me-2"><i class="flaticon-381-trash-1"></i></button>
                    </td>
                </tr>
                ';
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
        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            
            $rules = [
                'yangikun' => 'required',
                'mijoz' => 'required',
                'naqd' => 'required',
                'plastik' => 'required',
                'hr' => 'required',
                'click' => 'required',
            ];

            $messages = [
                'yangikun.required' => 'Сана киритилмади.',
                'mijoz.required' => 'Мижозни танланг.',
                'naqd.required' => 'Тўлов киритилмади.',
                'plastik.required' => 'Тўлов киритилмади.',
                'hr.required' => 'Тўлов киритилмади.',
                'click.required' => 'Тўлов киритилмади.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }else{

                $naqd = floatval(preg_replace('/[^\d.]/', '', $request->naqd));
                $plastik = floatval(preg_replace('/[^\d.]/', '', $request->plastik));
                $hr = floatval(preg_replace('/[^\d.]/', '', $request->hr));
                $click = floatval(preg_replace('/[^\d.]/', '', $request->click));

                $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

                $tulovlar1 = new tulovlar1;
                $tulovlar1->kun = $request->yangikun;
                $tulovlar1->tulovturi = 'Шартнома';
                $tulovlar1->shartnomaid = $request->mijoz;
                $tulovlar1->xis_oyi = $xis_oyi;
                $tulovlar1->naqd =  $naqd;
                $tulovlar1->pastik =  $plastik;
                $tulovlar1->hr =  $hr;
                $tulovlar1->click =  $click;
                $tulovlar1->avtot =  0;
                $tulovlar1->chegirma =  0;
                $tulovlar1->umumiysumma = $naqd + $plastik + $hr + $click;
                $tulovlar1->user_id = Auth::user()->id;
                $tulovlar1->save();


                $id = $request->mijoz;
                $shartnoma = shartnoma1::where('id', $id)->get();
                foreach ($shartnoma as $shartnom) {
                    
                    $foiz = xissobotoy::where('xis_oy', $shartnom->xis_oyi)->value('foiz');

                    if($shartnom->fstatus == 0){
                        $foiz = 0;
                    }

                    $oldindantulov = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('umumiysumma');
                    $chegirma = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $id)->sum('chegirma');
                    $tulov = tulovlar1::where('tulovturi', 'Шартнома')->where('status', 'Актив')->where('shartnomaid', $id)->sum('umumiysumma');
                    $savdosumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');

                    //йиллик фойиз
                    $foiz = (($foiz / 12) * $shartnom->muddat);
                    $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);
                    
                    $umumiySumma = $savdosumma + $xis_foiz - $oldindantulov - $chegirma;

                    $date111 = new DateTime($shartnom->kun);
                    $date222 = new DateTime($shartnom->tug_sana);
                    $interval = $date111->diff($date222);
                    $dukun = $interval->days;
                    $birkunlikfoiz = $xis_foiz / $dukun;

                    $krxiob22 = 0;
                    
                    if ($shartnom->tug_sana >= date("Y-m-d")) {
                        
                        $date1111 = new DateTime($shartnom->kun);
                        $date2222 = new DateTime(date("Y-m-d"));
                        $interval1 = $date1111->diff($date2222);
                        $dukun22 = $interval1->days;
                        $krxiob22 = $xis_foiz - ($birkunlikfoiz * $dukun22);
                        $joqarz = ($umumiySumma - $tulov - $krxiob22);
                        
                    } else {
                        
                        $joqarz = $umumiySumma - $tulov;
                        
                    }
                    
                    $skidka = $krxiob22;

                    if ($joqarz <= 0) {
                        $fond = shartnoma1::where('id', $id)->where('status', 'Актив')->update([
                            'status' => 'Ёпилган',
                            'izox' => 'Тўлик тўланганлиги учун',
                            'yo_user_id' => Auth::user()->id,
                            'yo_sana' => date('Y-m-d H:i:s'),
                            'yo_xis_oyi' => $xis_oyi,
                            'skidka' => $skidka,
                        ]);
                    }
                }

                return response()->json(['message' => 'Тўлов сақланди.'], 200);
            }

        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
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


        $filial = filial::where('id', Auth::user()->filial_id)->first();
        $ytt = $filial->ytt;
        $manzil = $filial->manzil;
        $xr = $filial->xr;
        $inn = $filial->inn;
        $bankname = $filial->bankname;
        $mfo = $filial->mfo;

        $tulovlar = tulovlar1::where('status', 'Актив')->where('id', $id)->get();
        foreach($tulovlar as $tulovla){
            echo'
            <div class="d-flex gap-1">
                <div style="width: 50%; padding-bottom:0;">
                    <table class="table-sm table-hover" style="font-size: 10px; text-align: center; width:100%; border: 1px solid black;">
                        <tbody>
                            <tr class="align-middle">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px; text-align: center; font-size: 8pt; text-align: center; color: RoyalBlue;" colspan="6">
                                    <h4 class="mb-0 text-success">Samo savdo markazi</h4>
                                    <b>'.$manzil.'</b>
                                </td>
                            </tr>

                            <tr class="align-middle text-muted">
                                <td colspan="2" style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.$tulovla->id.' -сонли квитансия</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Тўлов сана:</td>
                                <td colspan="3" style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.$tulovla->created_at.'</b></td>
                            </tr>
                            
                            <tr class="align-middle text-muted">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Тўловчи:</td>
                                <td colspan="5"><b>'.$tulovla->shartnoma1->mijozlar->last_name.' '.$tulovla->shartnoma1->mijozlar->first_name.' '.$tulovla->shartnoma1->mijozlar->middle_name.'</b></td>
                            </tr>
                            
                            <tr class="align-middle text-muted">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px; text-align: center; font-size: 7pt; text-align: center;" colspan="6">
                                    <b>'.date('d.m.Y', strtotime($tulovla->shartnoma1->kun)). ' кунги № ' .$tulovla->shartnomaid.' - сонли шартномага асосан</b>
                                </td>
                            </tr>
                            <tr class="align-middle text-muted">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px; text-align: center; font-size: 7pt; text-align: center; text-transform: uppercase; color: RoyalBlue;" colspan="6"><b>Тўлов тури</b></td>
                            </tr>
                            <tr class="align-middle text-muted text-center">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Нақд</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Пластик</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Хис/рақ</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Сlick</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Авто тўлов</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Жами:</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->naqd,0,","," ").'</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->pastik,0,","," ").'</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->hr,0,","," ").'</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->click,0,","," ").'</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->avtot,0,","," ").'</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->umumiysumma,0,","," ").'</b></td>
                            </tr>
                            <tr class="align-middle text-muted">
                                <td style="text-align: center; text-transform: uppercase;" colspan="7"><b>'.num2str($tulovla->umumiysumma).'</b></td>
                            </tr>
                            <tr class="align-middle text-muted">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px; font-style: italic;">Кассир:</td>
                                <td colspan="2" style="border: 1px solid black; border-collapse: collapse; padding: 1px;  font-style: italic;">имзо</td>
                                <td colspan="3" style="border: 1px solid black; border-collapse: collapse; padding: 1px;  font-style: italic;"><b>'.$tulovla->user->name.'</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="width: 1px; border:1px solid black;"></div>
                <div style="width: 50%; padding-bottom:0;">
                    <table class="table-sm table-hover" style="font-size: 10px; text-align: center; width:100%; border: 1px solid black;">
                        <tbody>
                            <tr class="align-middle">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px; text-align: center; font-size: 8pt; text-align: center; color: RoyalBlue;" colspan="6">
                                    <h4 class="mb-0 text-success">Samo savdo markazi</h4>
                                    <b>'.$manzil.'</b>
                                </td>
                            </tr>

                            <tr class="align-middle text-muted">
                                <td colspan="2" style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.$tulovla->id.' -сонли квитансия</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Тўлов сана:</td>
                                <td colspan="3" style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.$tulovla->created_at.'</b></td>
                            </tr>
                            
                            <tr class="align-middle text-muted">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Тўловчи:</td>
                                <td colspan="5"><b>'.$tulovla->shartnoma1->mijozlar->last_name.' '.$tulovla->shartnoma1->mijozlar->first_name.' '.$tulovla->shartnoma1->mijozlar->middle_name.'</b></td>
                            </tr>
                            
                            <tr class="align-middle text-muted">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px; text-align: center; font-size: 7pt; text-align: center;" colspan="6">
                                    <b>'.date('d.m.Y', strtotime($tulovla->shartnoma1->kun)). ' кунги № ' .$tulovla->shartnomaid.' - сонли шартномага асосан</b>
                                </td>
                            </tr>
                            <tr class="align-middle text-muted">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px; text-align: center; font-size: 7pt; text-align: center; text-transform: uppercase; color: RoyalBlue;" colspan="6"><b>Тўлов тури</b></td>
                            </tr>
                            <tr class="align-middle text-muted text-center">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Нақд</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Пластик</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Хис/рақ</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Сlick</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Авто тўлов</td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;">Жами:</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->naqd,0,","," ").'</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->pastik,0,","," ").'</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->hr,0,","," ").'</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->click,0,","," ").'</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->avtot,0,","," ").'</b></td>
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px;"><b>'.number_format($tulovla->umumiysumma,0,","," ").'</b></td>
                            </tr>
                            <tr class="align-middle text-muted">
                                <td style="text-align: center; text-transform: uppercase;" colspan="7"><b>'.num2str($tulovla->umumiysumma).'</b></td>
                            </tr>
                            <tr class="align-middle text-muted">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px; font-style: italic;">Кассир:</td>
                                <td colspan="2" style="border: 1px solid black; border-collapse: collapse; padding: 1px;  font-style: italic;">имзо</td>
                                <td colspan="3" style="border: 1px solid black; border-collapse: collapse; padding: 1px;  font-style: italic;"><b>'.$tulovla->user->name.'</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            ';
        };
        return ;
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
         $shid=0;
         $tulovlar = tulovlar1::where('id', $id)->where('tulovturi', 'Шартнома')->first();
         $shid = $tulovlar->shartnomaid;
         $TulovKun=date("d-m-Y", strtotime($tulovlar->created_at));
         $BugungiKun = date("d-m-Y");
         if($shid>0){
             if($TulovKun==$BugungiKun){
                 $shart = shartnoma1::where('id', $shid)
                 ->where('status', 'Ёпилган')
                 ->update([
                     'status' => 'Актив',
                     'izox' => 'Тўлов ўчирилганлиги учун қайта ёкилди.',
                     'yo_user_id' => Auth::user()->id,
                     'skidka' => 0,
                 ]);
                 $fond = tulovlar1::where('id', $id)->where('status', 'Актив')
                 ->update([
                     'status' => 'Удалит',
                     'del_user_id' => Auth::user()->id,
                     'del_kun' => date("Y-m-d"),
                 ]);
                 return response()->json(['message' => 'Тўлов ўчирилди.'], 200);
             }else{
                 return response()->json(['message' => 'Хатолик: '.$id.' ИД даги тўлов учириш учун админга мурожат қилинг.'], 200);
             }
         }else{
             return response()->json(['message' => 'Хатолик: '.$id.' ИД даги тўлов топилмади.'], 200);
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
