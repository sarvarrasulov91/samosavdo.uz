<?php

namespace App\Http\Controllers;

use App\Models\kirim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\filial;
use App\Models\pastavshik;
use App\Models\KirimTovar;
use App\Models\valyuta;
use App\Models\tmodel;

use Illuminate\Support\Facades\Validator;


class KirimTovarController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
    {
       if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {

            $filial = filial::where('status', 'Актив')->get();
            $pastavshik = pastavshik::where('status', 'Актив')->get();
            $valyuta = valyuta::get();
            $model = tmodel::where('status', 'Актив')->orderBy('id', 'desc')->get();

            return view('tovarlar.kirimtovar.index', [
                'model' => $model,
                'filial' => $filial,
                'pastavshik' => $pastavshik,
                'valyuta' => $valyuta
            ]);

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
        if (Auth::user()->lavozim_id != 1 && Auth::user()->status != 'Актив') {
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }

        $validator = Validator::make($request->all(), [
            'yangikun' => 'required',
            'filial' => 'required|integer',
            'pastavshik' => 'required|integer',
            'tovarmodeli' => 'required|integer',
            'valyuta' => 'required|integer',
            'tsoni' => 'required|integer|min:1|max:100',
            'tsumma' => 'required|numeric|min:0|max:15000000',
        ], [
            'yangikun.required' => 'Кунини киритилмади.',
            'filial.required' => 'Филиални танланг.',
            'pastavshik.required' => 'Таъминотчини танланг.',
            'tovarmodeli.required' => 'Товарни танланг.',
            'valyuta.required' => 'Валютани танланг.',
            'tsoni.required' => 'Товар сонини киритилмади.',
            'tsumma.required' => 'Товар суммасини киритилмади.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // === MAIN VALUES ===
        $tmodel = tmodel::find($request->tovarmodeli);
        if (!$tmodel) {
            return response()->json(['message' => 'Товар топилмади.'], 422);
        }

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $valyuta_narhi = valyuta::find($request->valyuta)->valyuta_narhi;

        $tsoni = $request->tsoni;
        $tsumma = $request->tsumma;

        // ❗ Max soni olish - tez ishlaydi
        $lastNumber = KirimTovar::where('tmodel_id', $tmodel->id)
            ->where('filial_id', $request->filial)
            ->max('soni') ?? 0;

        $data = [];

        for ($i = 1; $i <= $tsoni; $i++) {

            $lastNumber++;

            // ✔ Shtrix kod generatsiyasi (eng optimal)
            $shtr_kod = $this->makeBarcode(
                $request->filial,
                $tmodel->tur_id,
                $tmodel->brend_id,
                $tmodel->id,
                $lastNumber
            );

            $data[] = [
                'kun' => $request->yangikun,
                'filial_id' => $request->filial,
                'tur_id' => $tmodel->tur_id,
                'brend_id' => $tmodel->brend_id,
                'tmodel_id' => $tmodel->id,
                'shtrix_kod' => $shtr_kod,
                'soni' => $lastNumber,
                'valyuta_id' => $request->valyuta,
                'narhi' => $tsumma,
                'snarhi' => $tsumma,
                'valyuta_narhi' => $valyuta_narhi,
                'tannarhi' => $tsumma * $valyuta_narhi,
                'pastavshik_id' => $request->pastavshik,
                'pastavshik2_id' => $request->pastavshik,
                'xis_oyi' => $xis_oyi,
                'user_id' => Auth::id(),
                'status' => 'Актив',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk insert (super fast)
        KirimTovar::insert($data);

        //$model = new kirimTovar($request->filial);
        KirimTovar::where('filial_id', $request->filial)
            ->where('valyuta_id', $request->valyuta)
            ->where('tmodel_id', $tmodel->id)
            ->whereIn('status', ['Сотилмаган', 'Актив'])
            ->where('snarhi', '<', $tsumma)
            ->update(['snarhi' => $tsumma]);

        $datamodel = KirimTovar::select('id','kun','narhi','tur_id','brend_id','tmodel_id','valyuta_id','soni','shtrix_kod','pastavshik_id')
            ->where('status', 'Актив')
            ->where('filial_id', $request->filial)
            ->latest()
            ->get()
            ->load(['tur:id,tur_name', 'brend:id,brend_name', 'tmodel:id,model_name', 'valyuta:id,valyuta__nomi', 'pastavshik:id,pastav_name']);

        return response()->json([
            'message' => 'Маълумот сақланди.',
            'datamodel' => $datamodel
        ], 200);

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
    public function destroy(Request $request, string $id)
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив'
            && $request->id > 0 && $request->filial > 0 ) {

            KirimTovar::where('id', $request->id)
                ->where('filial_id', $request->filial)
                ->update([
                    'status' => "Удалит",
                    'del_kun' => now(),
                    'del_user_id' => Auth::id()
                ]);

            $datamodel = KirimTovar::select(['id','kun','narhi','tur_id','brend_id','tmodel_id','valyuta_id','soni','shtrix_kod','pastavshik_id'])
                ->where('status', 'Актив')
                ->where('filial_id', $request->filial)
                ->orderBy('id', 'desc')
                ->get();

            $datamodel->load(['tur:id,tur_name','brend:id,brend_name','tmodel:id,model_name','valyuta:id,valyuta__nomi','pastavshik:id,pastav_name']);

            return response()->json(['message' => 'Маълумот ўчирилди.', 'datamodel' => $datamodel], 200);

        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }
    }

    public function filbaza(Request $request)
    {
        if ($request->filial > 0) {

            $datamodel = KirimTovar::select(['id','kun','narhi','tur_id','brend_id','tmodel_id','valyuta_id','soni','shtrix_kod','pastavshik_id',])
                ->where('status', 'Актив')
                ->where('filial_id', $request->filial)
                ->orderBy('id', 'desc')
                ->get();

            $datamodel->load(['tur:id,tur_name','brend:id,brend_name','tmodel:id,model_name','valyuta:id,valyuta__nomi','pastavshik:id,pastav_name',]);

            return response()->json(['datamodel' => $datamodel], 200);

        }else{
            return response()->json(['datamodel'=>''], 200);
        }

    }

    public function sungimodel(Request $request)
    {
        $tovarmodeli = $request->tovarmodeli;

        if ($request->filial > 0 && $tovarmodeli > 0) {

            $datamodel = KirimTovar::select(['id','kun','narhi','tur_id','brend_id','tmodel_id','valyuta_id','soni','shtrix_kod','pastavshik_id',])
                ->where('tmodel_id', $tovarmodeli)
                ->where('filial_id', $request->filial)
                ->orderBy('id', 'desc')
                ->limit(1)->get();

            $datamodel->load(['tur:id,tur_name','brend:id,brend_name','tmodel:id,model_name','valyuta:id,valyuta__nomi','pastavshik:id,pastav_name',]);

            return response()->json(['data' => $datamodel], 200);

        }else{
            return response()->json(['data'=>''], 200);
        }

    }

    private function makeBarcode($filial, $tur, $brend, $model, $number)
    {
        return
            str_pad($filial, 2, "0", STR_PAD_LEFT) .
            str_pad($tur, 4, "0", STR_PAD_LEFT) .
            str_pad($brend, 4, "0", STR_PAD_LEFT) .
            str_pad($model, 5, "0", STR_PAD_LEFT) .
            str_pad($number, 4, "0", STR_PAD_LEFT);
    }
}
