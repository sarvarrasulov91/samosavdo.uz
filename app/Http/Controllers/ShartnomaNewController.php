<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\shartnoma1;
use App\Models\tulovlar1;
use App\Models\savdo1;
use App\Models\mijozlar;
use App\Models\tashrif;
use App\Models\xissobotoy;
use App\Models\filial;


use DateTime;
use Illuminate\Support\Facades\Validator;

class ShartnomaNewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tashrif = tashrif::all();

        $savdounix_id = savdo1::select('unix_id')->where('status', 'Актив')->orderBy('unix_id', 'desc')->groupBy('unix_id')->get();
        
        $mijozlar = mijozlar::where('status', '1')->where('m_type', '1')->get();
        
        return view('shartnoma.ShartnomaNew', [
            
            'savdounix_id' => $savdounix_id, 
            'mijozlar' => $mijozlar, 
            'tashrif'=>$tashrif
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

                    $shartnoma = shartnoma1::whereIn('status', ['Актив', 'Ёпилган'])->where('kun', date('Y-m-d'))->orderBy('id', 'desc')->get();
                    
                    $jami = 0;
                    $trrang = '';
                    
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
        $rules = [
            'yangikun' => 'required',
            'mijoz' => 'required',
            'tashrif' => 'required',
            'savdounix_id' => 'required',
            'muddat' => 'required',
            'oldintulovnaqd' => 'required',
            'oldintulovplastik' => 'required',
            'chegirma' => 'required',
            'izox' => 'required',
        ];

        $messages = [
            'yangikun.required' => 'Сана киритилмади.',
            'mijoz.required' => 'Мижозни танланг.',
            'tashrif.required' => 'Ташрифни танланг.',
            'muddat.required' => 'Шартнома муддатини танланг.',
            'savdounix_id.required' => 'Савдо-раками танланг.',
            'oldintulovnaqd.required' => 'Олдиндан туловини киритинг.',
            'oldintulovplastik.required' => 'Олдиндан туловини киритинг.',
            'chegirma.required' => 'Чегирмани киритинг.',
            'izox.required' => 'Изохни киритинг.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }else{
            $msumma = savdo1::where('status', 'Актив')->where('unix_id', $request->savdounix_id)->sum('msumma');
            if($msumma > 0){

                $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

                if (date("Y-m", strtotime($xis_oyi)) < date("Y-m")) {
                    return response()->json(['message' => "Xatolik! <br> Dasturni yangi oyga o'tkazing."], 200);
                }else{

                    $naqd = floatval(preg_replace('/[^\d.]/', '', $request->oldintulovnaqd));
                    $plastik = floatval(preg_replace('/[^\d.]/', '', $request->oldintulovplastik));
                    $chegirma = floatval(preg_replace('/[^\d.]/', '', $request->chegirma));

                    $kkuni = $request->yangikun;
                    $tekshuzgar = strtotime(+$request->muddat . " month", strtotime($kkuni));
                    $tekshtuga = strtotime('last day of +' . $request->muddat . ' month', strtotime($kkuni));
                    if ($tekshuzgar >= $tekshtuga) {
                        $du2 = date('Y.m.d', strtotime('last day of' . +$request->muddat . ' month', strtotime($kkuni)));
                    } else {
                        $du2 = date("Y.m.d", strtotime(+$request->muddat . "month", strtotime($kkuni)));
                    }
                    
                    // smartfonlar uchun oldindan tulov 20 foiz olish
                    
                    $tulov = 0;
                    $turIds = savdo1::where('status', 'Актив')->where('unix_id', $request->savdounix_id)->get();
                    foreach ($turIds as $tur){
                        if ($tur->tur_id == 1 || $tur->tur_id == 46 || $tur->tur_id == 47){
                            $tulov += $tur->msumma / 5;
                        }else{
                            $tulov += $tur->msumma / 10;
                        }
                    }
                    
                     if ($tulov > ($naqd + $plastik)){
                        return response()->json(['message' => "$tulov so'm oldindan tulov qiling."], 200);
                    }


                    try {
                        DB::beginTransaction();

                        $shartnoma = new shartnoma1;
                        $shartnoma->mijozlar_id = $request->mijoz;
                        $shartnoma->tashrif_id = $request->tashrif;
                        $shartnoma->fstatus = $request->fstatus;
                        $shartnoma->kun = $kkuni;
                        $shartnoma->tug_sana = $du2;
                        $shartnoma->savdo_id = $request->savdounix_id;
                        $shartnoma->muddat = $request->muddat;
                        $shartnoma->izox =  $request->izox;
                        $shartnoma->xis_oyi = $xis_oyi;
                        $shartnoma->user_id = Auth::user()->id;
                        $shartnoma->save();
                        $insid = $shartnoma->id;

                        $savdo1Updated = savdo1::where('unix_id', $request->savdounix_id)
                        ->where('status', 'Актив')
                        ->update([
                            'status' => "Шартнома",
                            'status2' => "Шартнома",
                            'shartnoma_id' => $insid,
                        ]);

                        $tulovlar = new tulovlar1;
                        $tulovlar->kun = $kkuni;
                        $tulovlar->tulovturi = 'Олдиндан тўлов';
                        $tulovlar->shartnomaid = $insid;
                        $tulovlar->xis_oyi = $xis_oyi;
                        $tulovlar->naqd =  $naqd;
                        $tulovlar->pastik =  $plastik;
                        $tulovlar->chegirma =  $chegirma;
                        $tulovlar->umumiysumma =  ($naqd + $plastik);
                        $tulovlar->user_id = Auth::user()->id;
                        $tulovlar->save();

                        if ($shartnoma && $savdo1Updated && $tulovlar) {
                            DB::commit();
                            $message="Шартнома " . $insid . " ИД рақами билан сақланди.";
                        } else {
                            DB::rollBack();
                            $message="Маълумот сақлашда хатолик.";
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $message="Маълумот сақлашда хатолик.";
                        // throw $e;
                    }
                    return response()->json(['message' => $message], 200);
                }

            }else{
                return response()->json(['message' => 'Бошқа савдо рақами танланг.'], 200);
            }
        }
    }

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

}
