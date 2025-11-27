<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\savdo;
use App\Models\kirimTovar;
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
        $model = kirimTovar::where('status', 'Сотилмаган')->where('filial_id', Auth::user()->filial_id)->get();

        $latestSavdo = savdo::where('status', '!=', 'Удалит')->max('unix_id');

        if ($latestSavdo !== null) { // Buni tekshirish kerak
            $unix_id = ($latestSavdo * 1) + 1;
        } else {
            $unix_id = 1;
        }

        return view('savdo.savdolar', ['model' => $model, 'unix_id' => $unix_id]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $savdounix_id = savdo::select('unix_id')->where('status', 'Актив')
            ->where('filial_id', Auth::user()->filial_id)
            ->orderBy('unix_id', 'desc')
            ->groupBy('unix_id')
            ->get();

        $savdomodel = savdo::where('status', 'Актив')
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
        ->where('filial_id', Auth::user()->filial_id)
        ->get();

        return response()->json(['savdounix_id' => $savdounix_id, 'savdomodel' => $savdomodel ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = savdo::where('unix_id', $request->unix_id)
            ->whereNotIn('status', ['Актив', 'Удалит'])
            ->where('filial_id', Auth::user()->filial_id)
            ->count();

        if ($model >= 1) {
            return response()->json(['message' => 'Boshqa savdo raqam tanlang.'], 200);
        }else{

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $chegirma = tmodel::where('id', $request->model_id)->value('aksiya');

            if($chegirma > 0){
                $chegirmamiqdor = round(($request->modelsumma * ($chegirma / 100)),-3);
            }else{
                $chegirmamiqdor = 0;
            }

            $bonusTur = tur::where('id', $request->tur_id)->value('aksiya');
            $bonusFilial = filial::where('id', Auth::user()->filial_id)->value('bonus_daraja');

            if($bonusTur > $bonusFilial){
                $bonussumma = round((($request->modelsumma - $chegirmamiqdor) * ($bonusTur / 100)), -3);
            }else{
                $bonussumma = round((($request->modelsumma - $chegirmamiqdor) * ($bonusFilial / 100)), -3);
            }

            $zaqis = new savdo;
            $zaqis->kun = today();
            $zaqis->unix_id = $request->unix_id;
            $zaqis->filial_id = Auth::user()->filial_id;
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
        $savdo = savdo::where('id', $id)->where('filial_id', Auth::user()->filial_id)->first();

        if (!$savdo) {
            return response()->json(['message' => 'Хатолик. Товар шартномага бириктирилган.'], 500);
        }

        $sotuvnarhi = $savdo->sotuvnarhi;
        $chegirma = $savdo->chegirma;
        $qushimch = $request->qushmchsumma;

        $result = savdo::where('id', $id)
            ->where('status', 'Актив')
            ->where('filial_id', Auth::user()->filial_id)
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
        $savdo = savdo::where('id', $id)
            ->where('shartnoma_id', 0)
            ->where('filial_id', Auth::user()->filial_id)
            ->where('status', 'Актив')
            ->where('status2', 'Актив')
            ->where('shtrix_kod', 0)
            ->where('fond_id', 0)
            ->first();

        if (!$savdo) {
            return response()->json(['message' => 'Хатолик. Товар шартномага бириктирилган.'], 500);
        }

        $savdo->delete();

        return response()->json(['message' => 'Товар ўчирилди.'], 200);

    }
}
