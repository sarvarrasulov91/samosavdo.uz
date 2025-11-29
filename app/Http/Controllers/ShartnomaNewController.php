<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Shartnoma;
use App\Models\Tulovlar;
use App\Models\Savdo;
use App\Models\Mijozlar;
use App\Models\tashrif;
use App\Models\xissobotoy;


use Illuminate\Support\Facades\Validator;

class ShartnomaNewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tashrif = tashrif::all();

        $savdounix_id = Savdo::where('filial_id', Auth::user()->filial_id)
            ->select('unix_id')
            ->where('status', 'Актив')
            ->orderBy('unix_id', 'desc')
            ->groupBy('unix_id')
            ->get();

        $mijozlar = Mijozlar::where('filial_id', Auth::user()
            ->filial_id)->where('status', '1')
            ->where('m_type', '1')
            ->get();

        return view('shartnoma.ShartnomaNew', [
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

                    $shartnoma = Shartnoma::whereIn('status', ['Актив', 'Ёпилган'])
                        ->where('filial_id', Auth::user()->filial_id)
                        ->where('kun', date('Y-m-d'))
                        ->orderBy('id', 'desc')
                        ->get();

                    foreach ($shartnoma as $shartnom){

                        if ($shartnom->status == 'Ёпилган'){
                            $trrang = 'align-middle text-success';
                        }else{
                            $trrang = 'align-middle';
                        }

                        echo'
                        <tr
                            id="modalshartshow"
                            data-id="'.$shartnom->id.'"
                            data-shid="'.$shartnom->shid.'"
                            data-filialid="'.$shartnom->filial_id.'"
                            data-fio="'.addslashes($shartnom->mijozlar->last_name) . ' ' . addslashes($shartnom->mijozlar->first_name) . ' ' . addslashes($shartnom->mijozlar->middle_name).'"
                            class="'.$trrang.'" data-bs-toggle="modal"
                            data-bs-target="#shartnoma_show">

                            <td>' . $shartnom->shid . '</td>
                            <td style="white-space: pre-wrap;">' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name . '</td>
                            <td style="white-space: pre-wrap;">' . $shartnom->mijozlar->tuman->name_uz .' '. $shartnom->mijozlar->mfy->name_uz . ' ' . $shartnom->mijozlar->manzil . '</td>
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
            'fstatus' => 'required',
            'oldintulovnaqd' => 'required',
            'oldintulovplastik' => 'required',
            'izox' => 'required',
        ];

        $messages = [
            'yangikun.required' => 'Сана киритилмади.',
            'mijoz.required' => 'Мижозни танланг.',
            'tashrif.required' => 'Ташрифни танланг.',
            'muddat.required' => 'Шартнома муддатини танланг.',
            'fstatus.required' => 'Шартнома фоизини танланг.',
            'savdounix_id.required' => 'Савдо-раками танланг.',
            'oldintulovnaqd.required' => 'Олдиндан туловини киритинг.',
            'oldintulovplastik.required' => 'Олдиндан туловини киритинг.',
            'izox.required' => 'Изохни киритинг.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $msumma = Savdo::where('status', 'Актив')
            ->where('unix_id', $request->savdounix_id)
            ->where('filial_id', Auth::user()->filial_id)
            ->sum('msumma');

        if($msumma <= 0) {
            return response()->json(['message' => 'Бошқа савдо рақами танланг.'], 200);
        }

        $xisobotoy = xissobotoy::latest('id')->first();
        $xis_oyi = $xisobotoy->xis_oy;

        if (date("Y-m", strtotime($xis_oyi)) < date("Y-m")) {
            return response()->json(['message' => "Xatolik! <br> Dasturni yangi oyga o'tkazing."], 200);
        }

        $naqd = floatval(preg_replace('/[^\d.]/', '', $request->oldintulovnaqd));
        $plastik = floatval(preg_replace('/[^\d.]/', '', $request->oldintulovplastik));

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
        $turIds = Savdo::where('status', 'Актив')
            ->where('unix_id', $request->savdounix_id)
            ->where('filial_id', Auth::user()->filial_id)
            ->get();


        foreach ($turIds as $tur){
            if ($tur->tur_id == 1 || $tur->tur_id == 46 || $tur->tur_id == 47){
                $tulov += $tur->msumma / 5;
            }
        }

         if ($tulov > ($naqd + $plastik)){
            return response()->json(['message' => "$tulov so'm oldindan tulov qiling."], 200);
        }

         // foizlarni hisoblash
        $foiz_stavka = ($request->fstatus == 1) ? $xisobotoy->foiz : 0;

        //йиллик фойиз
        $foiz = (($foiz_stavka / 12) * $request->muddat);
        $foizSumma = round($msumma * $foiz / 100, 0);

        $maxId = Shartnoma::where('filial_id', Auth::user()->filial_id)
            ->max('shid'); // bu eng yaxshi usul

        if (Auth::user()->filial_id == 2){
            $maxId = $maxId ?? 100000; // shid bo‘lmasa 100000 dan boshlanadi
        }

        $maxId = $maxId + 1;

        try {
            DB::beginTransaction();

            $shartnoma = new Shartnoma;
            $shartnoma->filial_id = Auth::user()->filial_id;
            $shartnoma->shid = $maxId;
            $shartnoma->mijozlar_id = $request->mijoz;
            $shartnoma->tashrif_id = $request->tashrif;
            $shartnoma->fstatus = $request->fstatus;
            $shartnoma->kun = $kkuni;
            $shartnoma->tug_sana = $du2;
            $shartnoma->savdo_id = $request->savdounix_id;
            $shartnoma->muddat = $request->muddat;
            $shartnoma->foiz_stavka = $foiz_stavka;
            $shartnoma->m_summa = $msumma;
            $shartnoma->old_tulov = $naqd + $plastik;
            $shartnoma->foiz_summa = $foizSumma;
            $shartnoma->izox =  $request->izox;
            $shartnoma->xis_oyi = $xis_oyi;
            $shartnoma->user_id = Auth::user()->id;
            $shartnoma->save();
            $insid = $shartnoma->id;

            Savdo::where('unix_id', $request->savdounix_id)
                ->where('status', 'Актив')
                ->where('filial_id', Auth::user()->filial_id)
                ->update([
                    'status' => "Шартнома",
                    'status2' => "Шартнома",
                    'shartnoma_id' => $insid,
                    'shid' => $maxId,
                ]);

            $tulovlar = new Tulovlar;
            $tulovlar->kun = $kkuni;
            $tulovlar->tulovturi = 'Олдиндан тўлов';
            $tulovlar->filial_id = Auth::user()->filial_id;
            $tulovlar->shartnoma_id = $insid;
            $tulovlar->shid = $maxId;
            $tulovlar->xis_oyi = $xis_oyi;
            $tulovlar->naqd =  $naqd;
            $tulovlar->pastik =  $plastik;
            $tulovlar->chegirma =  0;
            $tulovlar->umumiysumma =  ($naqd + $plastik);
            $tulovlar->user_id = Auth::user()->id;
            $tulovlar->save();

            DB::commit();

            return response()->json(['message' => "Shartnoma " . $shartnoma->shid . " ID raqam bilan saqlandi."], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            // Xatoni logga yozish
            \Log::error('Xatolik yuz berdi: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json(['message' => 'Маълумот сақлашда хатолик.'], 200);
            // throw $e;
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
