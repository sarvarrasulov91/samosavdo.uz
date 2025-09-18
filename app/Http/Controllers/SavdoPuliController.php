<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\kirim;
use App\Models\turharajat;
use Illuminate\Support\Facades\Validator;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;




class SavdoPuliController extends Controller
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
            $kirim = kirim::where('status','Актив')->where('xis_oyi',$xis_oyi)->where('filial_id', Auth::user()->filial_id)->orderBy('id', 'desc')->get();
            return view('kassa.savdopuli', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'kirim'=>$kirim]);
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
            $rules = [
                'yangikun' => 'required',
                'naqd' => 'required',
                'plastik' => 'required',
                'hr' => 'required',
                'click' => 'required',
                'avtot' => 'required',
                'izoh' => 'required',
            ];

            $messages = [
                'yangikun.required' => 'Сана киритилмади.',
                'naqd.required' => 'Сумма киритилмади.',
                'plastik.required' => 'Сумма киритилмади.',
                'hr.required' => 'Сумма киритилмади.',
                'click.required' => 'Сумма киритилмади.',
                'avtot.required' => 'Сумма киритилмади.',
                'izoh.required' => 'Изох киритилмади.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $naqd = floatval(preg_replace('/[^\d.]/', '', $request->naqd));
            $plastik = floatval(preg_replace('/[^\d.]/', '', $request->plastik));
            $hr = floatval(preg_replace('/[^\d.]/', '', $request->hr));
            $click = floatval(preg_replace('/[^\d.]/', '', $request->click));
            $avtot = floatval(preg_replace('/[^\d.]/', '', $request->avtot));

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $chiqim = new kirim;
            $chiqim->kun = $request->yangikun;
            $chiqim->filial_id = Auth::user()->filial_id;
            $chiqim->naqd = $naqd;
            $chiqim->pastik = $plastik;
            $chiqim->hr = $hr;
            $chiqim->click = $click;
            $chiqim->avtot = $avtot;
            $chiqim->umumiy = ($naqd+$plastik+$hr+$click+$avtot);
            $chiqim->izoh = $request->izoh;
            $chiqim->xis_oyi = $xis_oyi;
            $chiqim->user_id = Auth::user()->id;
            $chiqim->save();

            $kirim = kirim::
            with(['filial'=>function ($query) {
                $query->select('id','fil_name');
            }])->
            with(['kirimtur'=>function ($query) {
                $query->select('id','kirim_tur_name');
            }])->
            select('id','kun','filial_id','kirimtur_id','naqd','pastik','hr','click','avtot','umumiy','izoh')->
            where('status','Актив')->where('xis_oyi',$xis_oyi)->where('filial_id', Auth::user()->filial_id)->orderBy('id', 'desc')->get();

            return response()->json(['message' => 'Савдо пули сақланди.', 'kirim'=>$kirim], 200);
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

        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            $kirim = kirim::where('id', $id)->update([
                'status' => "Удалит",
                'user_id' => Auth::user()->id,
            ]);

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $kirim = kirim::
            with(['filial'=>function ($query) {
                $query->select('id','fil_name');
            }])->
            with(['kirimtur'=>function ($query) {
                $query->select('id','kirim_tur_name');
            }])->
            select('id','kun','filial_id','kirimtur_id','naqd','pastik','hr','click','avtot','umumiy','izoh')->
            where('status','Актив')->where('xis_oyi',$xis_oyi)->where('filial_id', Auth::user()->filial_id)->orderBy('id', 'desc')->get();

            return response()->json(['message' => 'Савдо пули ўчирилди.', 'kirim'=>$kirim], 200);
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }

    }
}
