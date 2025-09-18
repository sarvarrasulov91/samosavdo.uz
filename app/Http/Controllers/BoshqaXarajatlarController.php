<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\turharajat;
use App\Models\valyuta;
use App\Models\boshqaharajat1;
use Illuminate\Support\Facades\Validator;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;



class BoshqaXarajatlarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
            $turharajat = turharajat::get();
            $valyuta = valyuta::where('id', '1')->get();
            $chiqim =  boshqaharajat1::where('.status', 'Актив')->where('xis_oy', $xis_oyi)->orderBy('id', 'desc')->get();
            return view('kassa.boshqaxarajat', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'turharajat' => $turharajat, 'valyuta' => $valyuta, 'chiqim'=>$chiqim]);
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

        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {
            $rules = [

                'kun' => 'required',
                'naqd' => 'required',
                'plastik' => 'required',
                'hr' => 'required',
                'click' => 'required',
                'izox' => 'required',
                'turharajat_id' => 'required',
                'valyuta_id' => 'required',
            ];

            $messages = [
                'kun.required' => 'Сана киритилмади.',
                'naqd.required' => 'Сумма киритилмади.',
                'plastik.required' => 'Сумма киритилмади.',
                'hr.required' => 'Сумма киритилмади.',
                'click.required' => 'Сумма киритилмади.',
                'izox.required' => 'Изох киритилмади.',
                'turharajat_id.required' => 'Тўлов тури танланмади.',
                'valyuta_id.required' => 'Валюта танланмади.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $naqd = floatval(preg_replace('/[^\d.]/', '', $request->naqd));
            $plastik = floatval(preg_replace('/[^\d.]/', '', $request->plastik));
            $click = floatval(preg_replace('/[^\d.]/', '', $request->click));
            $hr = floatval(preg_replace('/[^\d.]/', '', $request->hr));

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $chiqim = new boshqaharajat1;
            $chiqim->kun = $request->kun;
            $chiqim->turharajat_id = $request->turharajat_id;
            $chiqim->valyuta_id = $request->valyuta_id;
            $chiqim->naqd = $naqd;
            $chiqim->pastik = $plastik;
            $chiqim->hr = $hr;
            $chiqim->click = $click;
            $chiqim->summasi = $naqd + $plastik + $hr + $click;
            $chiqim->izoh = $request->izox;
            $chiqim->xis_oy = $xis_oyi;
            $chiqim->user_id = Auth::user()->id;
            $chiqim->save();
            if ($chiqim->id) {
                $message = 'Маълумот сақланди.';
            } else {
                $message = 'Маълумот сақлашда хатолик.!!!';
            }

            $chiqim =  boshqaharajat1::
            with(['turharajat'=>function ($query) {
                $query->select('id','har_name');
            }])->
            with(['valyuta'=>function ($query) {
                $query->select('id','valyuta__nomi');
            }])->

            select('id','kun','turharajat_id','valyuta_id','naqd','pastik','hr','click','avtot','summasi','izoh')->
            where('.status', 'Актив')->where('.xis_oy', $xis_oyi)->orderBy('id', 'desc')->get();

            return response()->json(['message' => $message ,'chiqim'=>$chiqim], 200);
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
        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {
            $boshqaharajat = boshqaharajat1::where('id', $id)->where('status', 'Актив')->first();
            $boshqaharajatKun = date("d.m.Y", strtotime($boshqaharajat->created_at));
            $BugungiKun = date("d.m.Y");

            if($boshqaharajatKun == $BugungiKun){
                $kirim = boshqaharajat1::where('id', $id)->update([
                    'status' => "Удалит",
                    'user_id' => Auth::user()->filial_id,
                ]);
                $message = "Маълумот ўчирилди.";
            }else{
                $message = 'Хатолик: '.$id.' ИД даги тўлов учириш учун админга мурожат қилинг.';
            }

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $chiqim =  boshqaharajat1::
            with(['turharajat'=>function ($query) {
                $query->select('id','har_name');
            }])->
            with(['valyuta'=>function ($query) {
                $query->select('id','valyuta__nomi');
            }])->
            select('id','kun','turharajat_id','valyuta_id','naqd','pastik','hr','click','avtot','summasi','izoh')->
            where('.status', 'Актив')->where('.xis_oy', $xis_oyi)->orderBy('id', 'desc')->get();

            return response()->json(['message' => $message , 'chiqim'=>$chiqim], 200);

        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }

    }
}
