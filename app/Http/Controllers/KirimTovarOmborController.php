<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ktovar1;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;




class KirimTovarOmborController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $model = ktovar1::
            whereNotIn('status', ['Актив', 'Удалит'])
            ->where('xis_oyi', $xis_oyi)
            ->orderBy('id', 'desc')->get();

            return view('tovarlar.omborkirim', [
                'xis_oyi' => $xis_oyi,
                'model' => $model
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
        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            $message='';
            $krimt = $request->krimt;
            $model = ktovar1::where('shtrix_kod', $krimt)->where('status', 'Актив')->count();
            if ($model == 1) {
                $result = ktovar1::where('shtrix_kod', $krimt)
                ->limit(1)
                ->update([
                    'status' => 'Сотилмаган',
                    'k_kun' => now(),
                    'k_user_id' => Auth::user()->id
                ]);

                if ($result) {
                    $message = $krimt . "<br> Товар омборга кирим қилиб олинди.";
                } else {
                    $message = $krimt . "<br> Товар омборга кирим қилишда хатолик.";
                }

            } elseif ($model != 1) {
                $message = $krimt . "<br> Хатолик!!! Товар топилмади ёки илгари кирим қилиб олинган бўлиши мумкин.";
            }

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $model = ktovar1::
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
            select('id','kun','narhi','tur_id','brend_id','tmodel_id','shtrix_kod','status','pastavshik_id','filial_id')
            ->whereNotIn('status', ['Актив', 'Удалит'])->where('xis_oyi', $xis_oyi)->orderBy('id', 'desc')->get();
            return response()->json(['message'=>$message, 'model'=>$model], 200);

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
        //
    }
}
