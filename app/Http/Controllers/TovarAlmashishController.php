<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ktovar1;
use App\Models\talmashish;
use App\Models\valyuta;
use App\Models\xissobotoy;
use App\Models\lavozim;
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
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
            $xis_oyi = xissobotoy::pluck('xis_oy')->first();
            $filial = filial::where('status', 'Актив')->where('id','!=',Auth::user()->filial_id)->get();
            $model = talmashish::where('status', 'Актив')->where('xis_oyi', $xis_oyi)->where('filial_iddan', Auth::user()->filial_id)->orderBy('id', 'desc')->get();
            return view('tovarlar.tovaralmashish', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'filial' => $filial, 'model'=>$model]);
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


        $model = ktovar1::where('shtrix_kod', $request->krimt)->where('status', 'Сотилмаган')->count();
        if ($model == 1) {

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $ktovar = ktovar1::where('shtrix_kod', $request->krimt)->where('status', 'Сотилмаган')->first();
            $valyuta_narhi = valyuta::where('id', $ktovar->valyuta_id)->value('valyuta_narhi');

            $soninar = 0;
            $modelktovar1 = new ktovar1($request->filial);
            $soninar = $modelktovar1->where('tmodel_id', $ktovar->tmodel_id)->orderBy('soni', 'desc')->value('soni');
            $soninar++;

            $turid2 = str_pad($ktovar->tur_id, 4, "0", STR_PAD_LEFT);
            $brendid2 = str_pad($ktovar->brend_id, 4, "0", STR_PAD_LEFT);
            $model2 = str_pad($ktovar->tmodel_id, 5, "0", STR_PAD_LEFT);
            $soninar2 = str_pad($soninar, 4, "0", STR_PAD_LEFT);

            $shtr_kod = $turid2 . $brendid2 . $model2 . $soninar2;


            try {

                DB::beginTransaction();

                $ktovarzapis = new ktovar1($request->filial);
                $ktovarzapis->kun = date('Y-m-d');
                $ktovarzapis->tur_id = $ktovar->tur_id;
                $ktovarzapis->brend_id = $ktovar->brend_id;
                $ktovarzapis->tmodel_id = $ktovar->tmodel_id;
                $ktovarzapis->shtrix_kod = $shtr_kod;
                $ktovarzapis->soni = $soninar;
                $ktovarzapis->valyuta_id = $ktovar->valyuta_id;
                $ktovarzapis->narhi = $ktovar->narhi;
                $ktovarzapis->snarhi = $ktovar->snarhi;
                $ktovarzapis->valyuta_narhi = $valyuta_narhi;
                $ktovarzapis->tannarhi = ($ktovar->narhi*$valyuta_narhi);
                $ktovarzapis->pastavshik_id = 6;
                $ktovarzapis->pastavshik2_id = $ktovar->pastavshik2_id;
                $ktovarzapis->filial_id = Auth::user()->filial_id;
                $ktovarzapis->xis_oyi = $xis_oyi;
                $ktovarzapis->user_id = Auth::user()->id;
                $ktovarzapis->save();
                $insid = $ktovarzapis->id;

                $talmashishZapis = new talmashish();
                $talmashishZapis->kun = $ktovar->kun;
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
                $talmashishZapis->filial_id = $request->filial;
                $talmashishZapis->kirim_id = $insid;
                $talmashishZapis->shtrix_kod_yangi = $shtr_kod;
                $talmashishZapis->save();

                $ktovarSnarhi = new ktovar1($request->filial);
                $ktovarSnarhiUpdated = $ktovarSnarhi
                ->where('valyuta_id', $ktovar->valyuta_id)
                ->where('tmodel_id', $ktovar->tmodel_id)
                ->where('status','Сотилмаган')
                ->update([
                    'snarhi' => $ktovar->snarhi,
                ]);

                $ktovar1Updated=ktovar1::where('shtrix_kod', $request->krimt)
                ->where('status', 'Сотилмаган')
                ->limit(1)
                ->update([
                    'status' => 'Алмашиш',
                    'ch_kun' => date('Y-m-d H:i:s'),
                    'ch_xis_oyi' => $xis_oyi,
                    'ch_user_id' => Auth::user()->id,
                ]);

                if ($ktovar1Updated && $ktovarzapis && $ktovarSnarhi && $talmashishZapis) {
                    DB::commit();
                    $message=$request->krimt . "<br> Товар бошқа филиалга ўтказилди.";
                } else {
                    DB::rollBack();
                    $message=$request->krimt . "<br> Товар бошқа филиалга ўтказишда хатолик.";
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $message=$request->krimt . "<br> Товар бошқа филиалга ўтказишда хатолик2.";
                // throw $e;
            }

        }else{
            $message=$request->krimt . "<br> Хатолик!!! Товар топилмади.";
        }

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

        return response()->json(['message'=>$message, 'model'=>$model], 200);

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
