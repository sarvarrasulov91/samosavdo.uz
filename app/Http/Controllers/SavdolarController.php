<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\savdo1;
use App\Models\ktovar1;
use App\Models\tmodel;
use App\Models\xissobotoy;
use App\Models\filial;
use App\Models\tur;

class SavdolarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $model = ktovar1::where('status', 'Сотилмаган')->get();
        
        $latestSavdo = Savdo1::where('status', '!=', 'Удалит')->max('unix_id');
        
        if ($latestSavdo !== null) { // Buni tekshirish kerak
            $unix_id = ($latestSavdo * 1) + 1;
        } else {
            $unix_id = 1;
        }
        
        return view('savdo.savdolar', ['model'=>$model, 'unix_id' => $unix_id]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $savdounix_id = savdo1::select('unix_id')->where('status', 'Актив')->orderBy('unix_id', 'desc')->groupBy('unix_id')->get();
        
        $savdomodel = savdo1::where('status', 'Актив')
        ->with(['tur' => function ($query) {
            $query->select('id','tur_name');
        }])
        ->with(['brend' => function ($query) {
            $query->select('id','brend_name');
        }])
        ->with(['tmodel' => function ($query) {
            $query->select('id','model_name');
        }])
        ->select('id','tur_id','brend_id','tmodel_id','unix_id','msumma','qushimch','created_at')
        ->get();

        return response()->json(['savdounix_id' => $savdounix_id, 'savdomodel' => $savdomodel ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = savdo1::where('unix_id', $request->unix_id)->where('status', '!=', 'Актив')->where('status', '!=', 'Удалит')->count();
        if ($model >= 1) {
            return response()->json(['message' => ' Хурматли: ' . Auth::user()->name  .'  ' . $request->unix_id.'- савдо рақам ишлатилганлиги сабабли янги савдо рақамини олишингизни тавсия этамиз.'], 200);
        }else{

            $chegirma=0;
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $chegirma = tmodel::where('id', $request->model_id)->value('aksiya');
            if($chegirma>0){
                $chegirmamiqdor=round(($request->modelsumma*($chegirma/100)),-3);
            }else{
                $chegirmamiqdor=0;
            }

            $bonusTur = 0;
            $bonusFilial = 0;

            $bonusTur = tur::where('id', $request->tur_id)->value('aksiya');
            $bonusFilial = filial::where('id', Auth::user()->filial_id)->value('bonus_daraja');
            
            if($bonusTur > $bonusFilial){
                $bonussumma = round((($request->modelsumma - $chegirmamiqdor) * ($bonusTur / 100)), -3);
            }else{
                $bonussumma = round((($request->modelsumma - $chegirmamiqdor) * ($bonusFilial / 100)), -3);
            }

            $zaqis = new Savdo1;
            $zaqis->unix_id = $request->unix_id;
            $zaqis->tur_id = $request->tur_id;
            $zaqis->brend_id = $request->brend_id;
            $zaqis->tmodel_id = $request->model_id;
            $zaqis->sotuvnarhi = $request->modelsumma;
            $zaqis->msumma = $request->modelsumma - $chegirmamiqdor;
            $zaqis->chegirma = $chegirmamiqdor;
            $zaqis->bonus = $bonussumma;
            $zaqis->xis_oyi = $xis_oyi;
            $zaqis->user_id = Auth::user()->id;
            $zaqis->save();

            if ($zaqis->id) {
                return response()->json(['message' => 'Товар шартнома тузиш учун кўшилди.'], 200);
            } else {
                return response()->json(['message' => 'Маълумотни ёзишда хатолик.'], 500);
            }

        }
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

        $id = $request->id;
        $savdo = Savdo1::where('id', $id)->first();
        if (!$savdo) {
            return response()->json(['message' => 'Хатолик. Товар шартномага бириктирилган.'], 500);
        }

        $sotuvnarhi = $savdo->sotuvnarhi;
        $chegirma = $savdo->chegirma;
        $qushimch = $request->qushmchsumma;

        $result = Savdo1::where('id', $id)
            ->where('status', 'Актив')
            ->limit(1)
            ->update([
                'msumma' => $sotuvnarhi + $qushimch - $chegirma,
                'qushimch' => $qushimch,
            ]);

        if ($result == 1) {
            return response()->json(['message' => 'Қўшимча сумма қўшилди.'], 200);
        } else {
            return response()->json(['message' => 'Хатолик. Товар шартномага бириктирилган.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $savdo = Savdo1::where('id', $id)->first();
        if (!$savdo) {
            return response()->json(['message' => 'Хатолик. Товар шартномага бириктирилган.'], 500);
        }

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $result = Savdo1::where('id', $id)
            ->where('status', 'Актив')
            ->limit(1)
            ->update([
                'status' => "Удалит",
                'del_kun' => now(), // O'zgarishi kerak bo'lgan qatnashchilarning vaqtini hisoblash uchun 'now()' funksiyasidan foydalanamiz
                'del_xis_oyi' => $xis_oyi,
                'del_user_id' => Auth::user()->id,
            ]);

        if ($result == 1) {
            return response()->json(['message' => 'Товар ўчирилди.'], 200);
        } else {
            return response()->json(['message' => 'Товарни ўчиришда хатолик. Товар шартномага бириктирилган.'], 500);
        }
    }
}
