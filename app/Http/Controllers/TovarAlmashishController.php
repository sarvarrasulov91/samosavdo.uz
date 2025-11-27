<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\kirimTovar;
use App\Models\talmashish;
use App\Models\valyuta;
use App\Models\xissobotoy;
use App\Models\filial;

use Illuminate\Support\Facades\DB;

class TovarAlmashishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {

            $xis_oyi = xissobotoy::pluck('xis_oy')->first();
            $filial = filial::where('status', 'Актив')->where('id','!=', Auth::user()->filial_id)->get();

            $model = talmashish::where('status', 'Актив')
                ->where('xis_oyi', $xis_oyi)
                ->where('filial_iddan', Auth::user()->filial_id)
                ->orderBy('id', 'desc')->get();

            return view('tovarlar.tovaralmashish', ['filial' => $filial, 'model' => $model]);

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
        $filial2 = $request->filial;

        $model = kirimTovar::where('filial_id', Auth::user()->filial_id)
            ->where('shtrix_kod', $request->krimt)
            ->where('status', 'Сотилмаган')
            ->count();

        if ($model != 1) {
            return response()->json(['message' => "Хатолик! Товар топилмади."]);
        }

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

        $ktovar = kirimTovar::where('filial_id', Auth::user()->filial_id)
            ->where('shtrix_kod', $request->krimt)
            ->where('status', 'Сотилмаган')
            ->first();

        $valyuta_narhi = valyuta::where('id', $ktovar->valyuta_id)->value('valyuta_narhi');

        $soninar = kirimTovar::where('filial_id', $filial2)
            ->where('tmodel_id', $ktovar->tmodel_id)
            ->max('soni');

        $soninar++;
        $filialId = str_pad($filial2, 0, "0", STR_PAD_LEFT);
        $turid2 = str_pad($ktovar->tur_id, 4, "0", STR_PAD_LEFT);
        $brendid2 = str_pad($ktovar->brend_id, 4, "0", STR_PAD_LEFT);
        $model2 = str_pad($ktovar->tmodel_id, 5, "0", STR_PAD_LEFT);
        $soninar2 = str_pad($soninar, 4, "0", STR_PAD_LEFT);

        $new_shtr_kod = $filialId . $turid2 . $brendid2 . $model2 . $soninar2;


        try {

            DB::beginTransaction();

            $ktovar->update([
                'status' => 'Алмашган',
                'ch_kun' => now(),
                'ch_xis_oyi' => $xis_oyi,
                'ch_user_id' => Auth::id(),
            ]);

            $ktovarzapis = new kirimTovar;
            $ktovarzapis->kun = today();
            $ktovarzapis->filial_id = $filial2;
            $ktovarzapis->tur_id = $ktovar->tur_id;
            $ktovarzapis->brend_id = $ktovar->brend_id;
            $ktovarzapis->tmodel_id = $ktovar->tmodel_id;
            $ktovarzapis->shtrix_kod = $new_shtr_kod;
            $ktovarzapis->soni = $soninar;
            $ktovarzapis->valyuta_id = $ktovar->valyuta_id;
            $ktovarzapis->narhi = $ktovar->narhi;
            $ktovarzapis->snarhi = $ktovar->snarhi;
            $ktovarzapis->valyuta_narhi = $valyuta_narhi;
            $ktovarzapis->tannarhi = ($ktovar->narhi*$valyuta_narhi);
            $ktovarzapis->pastavshik_id = 18;
            $ktovarzapis->pastavshik2_id = $ktovar->pastavshik2_id;
            $ktovarzapis->xis_oyi = $xis_oyi;
            $ktovarzapis->user_id = Auth::user()->id;
            $ktovarzapis->save();
            $insid = $ktovarzapis->id;

            $talmashishZapis = new talmashish();
            $talmashishZapis->kun = $ktovar->kun;
            $talmashishZapis->filial_id = $filial2;
            $talmashishZapis->tur_id = $ktovar->tur_id;
            $talmashishZapis->brend_id = $ktovar->brend_id;
            $talmashishZapis->tmodel_id = $ktovar->tmodel_id;
            $talmashishZapis->shtrix_kod = $ktovar->shtrix_kod;
            $talmashishZapis->valyuta_id = $ktovar->valyuta_id;
            $talmashishZapis->narhi = $ktovar->narhi;
            $talmashishZapis->snarhi = $ktovar->snarhi;
            $talmashishZapis->valyuta_narhi = $valyuta_narhi;
            $talmashishZapis->tannarhi = ($ktovar->narhi*$valyuta_narhi);
            $talmashishZapis->pastavshik_id = $ktovar->pastavshik_id;
            $talmashishZapis->pastavshik2_id = $ktovar->pastavshik2_id;
            $talmashishZapis->xis_oyi = $xis_oyi;
            $talmashishZapis->user_id = Auth::user()->id;
            $talmashishZapis->filial_iddan = Auth::user()->filial_id;
            $talmashishZapis->kirim_id = $insid;
            $talmashishZapis->shtrix_kod_yangi = $new_shtr_kod;
            $talmashishZapis->save();

            kirimTovar::where('filial_id', $filial2)
                ->where('valyuta_id', $ktovar->valyuta_id)
                ->where('tmodel_id', $ktovar->tmodel_id)
                ->where('status','Сотилмаган')
                ->where('snarhi', '<', $ktovar->snarhi)
                ->update([
                    'snarhi' => $ktovar->snarhi,
                ]);

            DB::commit();

            $message = "Tovar boshqa filialga o'tkazildi.";

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $model = talmashish::
            with(['tur'=>function ($query) {
                $query->select('id','tur_name');
            }])->
            with(['brend'=>function ($query) {
                $query->select('id','brend_name');
            }])->
            with(['tmodel'=>function ($query) {
                $query->select('id','model_name');
            }])->
            with(['pastavshik'=>function ($query) {
                $query->select('id','pastav_name');
            }])->
            with(['filial'=>function ($query) {
                $query->select('id','fil_name');
            }])->
            select('id','kun','narhi','tur_id','brend_id','tmodel_id','shtrix_kod','pastavshik_id','filial_id','shtrix_kod_yangi')
                ->where('status', 'Актив')->where('xis_oyi', $xis_oyi)->where('filial_iddan', Auth::user()->filial_id)->orderBy('id', 'desc')->get();

            return response()->json(['message' => $message, 'model' => $model], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['message' => 'Товар бошқа филиалга ўтказишда хатолик2.'], 200);
            // throw $e;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
