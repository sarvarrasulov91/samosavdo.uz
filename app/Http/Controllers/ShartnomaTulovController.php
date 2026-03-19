<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Tulovlar;
use App\Models\Shartnoma;
use App\Models\Savdo;
use Illuminate\Support\Facades\Validator;
use App\Models\xissobotoy;
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

            $shartnoma = Shartnoma::where('filial_id', Auth::user()->filial_id)
                ->where('status', 'Актив')
                ->orderBy('id', 'desc')
                ->get();

            return view('kassa.shartnomatulov', ['shartnoma' => $shartnoma]);
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
                    <th>Квитанция</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="tab1">';

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $tulovlar = Tulovlar::where('filial_id', Auth::user()->filial_id)
                ->where('status', 'Актив')
                ->where('tulovturi', 'Шартнома')
                ->where('xis_oyi', $xis_oyi)
                ->orderBy('id', 'desc')
                ->get();

            foreach ($tulovlar as $tulovla){
                echo'
                <tr class="align-middle text-center">
                    <td>' . $tulovla->id .'</td>
                    <td>' . date("d.m.Y", strtotime($tulovla->kun)) .'</td>
                    <td>' . $tulovla->shid .'</td>
                    <td>' . $tulovla->shartnoma->mijozlar->last_name . ' ' . $tulovla->shartnoma->mijozlar->first_name . ' ' . $tulovla->shartnoma->mijozlar->middle_name .'
                    </td>
                    <td>' . $tulovla->shartnoma->mijozlar->phone .'</td>
                    <td>' . number_format($tulovla->naqd, 2, ',', ' ') .'</td>
                    <td>' . number_format($tulovla->pastik, 2, ',', ' ') .'</td>
                    <td>' . number_format($tulovla->hr, 2, ',', ' ') .'</td>
                    <td>' . number_format($tulovla->click, 2, ',', ' ') .'</td>
                    <td>' . number_format($tulovla->naqd + $tulovla->pastik + $tulovla->hr + $tulovla->click, 2, ',', ' ') .'
                    </td>
                    <td>
                        <button id="kivitpechat" data-id="' . $tulovla->id .'" data-fio="' . $tulovla->shartnoma->mijozlar->last_name . ' ' . $tulovla->shartnoma->mijozlar->first_name . ' ' . $tulovla->shartnoma->mijozlar->middle_name .'"
                            class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal"
                            data-bs-target="#pechat"><i class="flaticon-381-search-1"></i></button>
                    </td>
                    <td>';
                        if (Auth::user()->lavozim_id == 2){
                            echo'
                            <button id="tovarudalit" data-id="' . $tulovla->id .'" data-fio="' . $tulovla->shartnoma->mijozlar->last_name . ' ' . $tulovla->shartnoma->mijozlar->first_name . ' ' . $tulovla->shartnoma->mijozlar->middle_name .'"
                            class="btn btn-outline-danger btn-sm me-2"><i class="flaticon-381-trash-1"></i></button>';
                        }
                        echo'
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

                $shartnoma = Shartnoma::find($request->mijoz);

                $tulovlar1 = new Tulovlar;
                $tulovlar1->kun = $request->yangikun;
                $tulovlar1->tulovturi = 'Шартнома';
                $tulovlar1->filial_id = Auth::user()->filial_id;
                $tulovlar1->shartnoma_id = $request->mijoz;
                $tulovlar1->shid = $shartnoma->shid;
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


                $foiz = xissobotoy::where('xis_oy', $shartnoma->xis_oyi)->value('foiz');

                if($shartnoma->fstatus == 0){
                    $foiz = 0;
                }

                $tulovlar = Tulovlar::where('filial_id', Auth::user()->filial_id)
                    ->where('status', 'Актив')
                    ->where('shartnoma_id', $shartnoma->id)
                    ->selectRaw("
                        SUM(CASE WHEN tulovturi = 'Олдиндан тўлов' THEN umumiysumma ELSE 0 END) as oldindan_summa,
                        SUM(CASE WHEN tulovturi = 'Олдиндан тўлов' THEN chegirma ELSE 0 END) as oldindan_chegirma,
                        SUM(CASE WHEN tulovturi = 'Шартнома' THEN umumiysumma ELSE 0 END) as shartnoma_summa
                    ")
                    ->first();

                $oldindantulov = $tulovlar->oldindan_summa;
                $chegirma      = $tulovlar->oldindan_chegirma;
                $tulov         = $tulovlar->shartnoma_summa;

                $savdosumma = Savdo::where('filial_id', Auth::user()->filial_id)->where('status', 'Шартнома')->where('shartnoma_id', $shartnoma->id)->sum('msumma');

                //йиллик фойиз
                $foiz = (($foiz / 12) * $shartnoma->muddat);
                $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);

                $umumiySumma = $savdosumma + $xis_foiz - $oldindantulov - $chegirma;

                $date1 = new DateTime($shartnoma->kun);
                $date2 = new DateTime($shartnoma->tug_sana);
                $interval = $date1->diff($date2);
                $dukun = $interval->days;
                $birkunlikfoiz = $xis_foiz / $dukun;

                $krxiob22 = 0;
                $joqarz = $umumiySumma - $tulov;

                if ( date("Y-m-d") <= $shartnoma->tug_sana) {
                    $date22 = new DateTime(date("Y-m-d"));
                    $interval1 = $date1->diff($date22);
                    $dukun22 = $interval1->days;
                    $krxiob22 = $xis_foiz - ($birkunlikfoiz * $dukun22);
                    $joqarz = ($umumiySumma - $tulov - $krxiob22);
                }

                $skidka = $umumiySumma - $tulov;

                if ($joqarz <= 0) {
                    $shartnoma->update([
                        'status' => 'Ёпилган',
                        'izox' => 'Тўлик тўланганлиги учун',
                        'yo_user_id' => Auth::user()->id,
                        'yo_sana' => now(),
                        'yo_xis_oyi' => $xis_oyi,
                        'skidka' => $skidka,
                    ]);
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
        $filial = filial::where('id', Auth::user()->filial_id)->first();
        $manzil = $filial->manzil;

        $tulovlar = Tulovlar::where('filial_id', Auth::user()->filial_id)->where('status', 'Актив')->where('id', $id)->get();
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
                                <td colspan="5"><b>'.$tulovla->shartnoma->mijozlar->last_name.' '.$tulovla->shartnoma->mijozlar->first_name.' '.$tulovla->shartnoma->mijozlar->middle_name.'</b></td>
                            </tr>

                            <tr class="align-middle text-muted">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px; text-align: center; font-size: 7pt; text-align: center;" colspan="6">
                                    <b>'.date('d.m.Y', strtotime($tulovla->shartnoma->kun)). ' кунги № ' .$tulovla->shid.' - сонли шартномага асосан</b>
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
                                <td style="text-align: center; text-transform: uppercase;" colspan="7"><b>'.numToStr($tulovla->umumiysumma).'</b></td>
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
                                <td colspan="5"><b>'.$tulovla->shartnoma->mijozlar->last_name.' '.$tulovla->shartnoma->mijozlar->first_name.' '.$tulovla->shartnoma->mijozlar->middle_name.'</b></td>
                            </tr>

                            <tr class="align-middle text-muted">
                                <td style="border: 1px solid black; border-collapse: collapse; padding: 1px; text-align: center; font-size: 7pt; text-align: center;" colspan="6">
                                    <b>'.date('d.m.Y', strtotime($tulovla->shartnoma->kun)). ' кунги № ' .$tulovla->shid.' - сонли шартномага асосан</b>
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
                                <td style="text-align: center; text-transform: uppercase;" colspan="7"><b>'.numToStr($tulovla->umumiysumma).'</b></td>
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
         //
     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tulovlar = Tulovlar::where('id', $id)
            ->where('filial_id', Auth::user()->filial_id)
            ->where('tulovturi', 'Шартнома')
            ->first();

        $shid = $tulovlar->shartnoma_id;

        if($shid > 0){

            if($tulovlar->kun == date('Y-m-d')){

                $shart = Shartnoma::where('id', $shid)
                    ->where('filial_id', Auth::user()->filial_id)
                    ->where('status', 'Ёпилган')
                    ->update([
                        'status' => 'Актив',
                        'izox' => 'Тўлов ўчирилганлиги учун қайта ёкилди.',
                        'yo_user_id' => Auth::user()->id,
                        'skidka' => 0,
                    ]);

                $tulovlar->update([
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
}
