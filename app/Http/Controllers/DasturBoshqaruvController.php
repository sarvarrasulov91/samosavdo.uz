<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\valyuta;
use App\Models\xissobotoy;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\lavozim;
use App\Models\filial;

class DasturBoshqaruvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');

            $xis_oy = DB::table('xissobotoy')
            ->join('users', 'xissobotoy.user_id', '=', 'users.id')
            ->select('xissobotoy.*', 'users.name')->orderByDesc('id')->get();
            $valyuta = DB::table('valyuta')
            ->join('users', 'valyuta.user_id', '=', 'users.id')
            ->select('valyuta.*', 'users.name')->where('valyuta.id', '2')->get();
            return view('qushmchalar.qushmcha', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'valyuta' => $valyuta, 'xis_oy' => $xis_oy]);
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
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $valyuta = Valyuta::find($request->id);
            if ($valyuta) {
                $valyuta->valyuta_narhi = $request->uzid;
                $valyuta->tovar_kurs = $request->kursid;
                $valyuta->user_id = Auth::user()->id;
                $valyuta->save();
                $izox = "Валюта курси ўзгарди.";
            } else {
                $izox = "Хатолик: Бундай валюта топилмади.";
            }
            return $izox;
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

        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $xis_oy2 = date('Y-m-d', strtotime('+1 MONTH', strtotime($request->oy)));
            $xissobotoy = xissobotoy::create([
                'xis_oy' => $xis_oy2,
                'user_id' => Auth::user()->id,
                'foiz' => $request->tarif,
            ]);

            if ($xissobotoy) {
                $izox = "Дастур янги ойга ўтказилди."; // Success message
            } else {
                $izox = "Хатолик юз берди. Дастур ўзига мос маълумотларни текшириб кўринг."; // Error message
            }
            return $izox;
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
