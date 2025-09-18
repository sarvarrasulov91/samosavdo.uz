<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\pastavshik;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;
use Illuminate\Support\Facades\Validator;

class PastavshikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $foiz = xissobotoy::where('xis_oy', $shartnom->xis_oyi)->value('foiz');
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
            $pastavshik = pastavshik::where('status', 'Актив')->orderBy('id', 'desc')->get();
            return view('tovarlar.pastavshik.index', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'pastavshik'=>$pastavshik]);
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
            $rules = [
                'taminotchi' => 'required',
                'manzili' => 'required',
                'telefoni' => 'required|digits:9',
                'xis_raqami' => 'required|digits:20',
                'inn' => 'required|digits:9',
                'mfo' => 'required|digits:5',
            ];

            $messages = [
                'taminotchi.required' => 'Таъминотчи номи киритилмади.',
                'manzili.required' => 'Таъминотчи манзили киритилмади.',
                'telefoni.required' => 'Телефон рақами киритилмади.',
                'xis_raqami.required' => 'Хисоб-раками киритилмади.',
                'inn.required' => 'ИНН киритилмади.',
                'mfo.required' => 'МФО киритилмади.',
                'telefoni.digits' => 'Телефон 9 хонали рақам бўлиши керак.',
                'xis_raqami.digits' => 'Хисоб-раками 20 хонали рақам бўлиши керак.',
                'inn.digits' => 'ИНН 9 хонали рақам бўлиши керак.',
                'mfo.digits' => 'МФО 5 хонали рақам бўлиши керак.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $fond = pastavshik::where('status', 'Актив')->where('pastav_name', $request->taminotchi)->count();
            if ($fond>0) {
                return response()->json(['message' => 'Таъминотчи базада мвжуд.'], 200);
            }else{
                $pastavshik = new pastavshik;
                $pastavshik->pastav_name = $request->taminotchi;
                $pastavshik->manzili = $request->manzili;
                $pastavshik->telefoni = $request->telefoni;
                $pastavshik->xis_raqami = $request->xis_raqami;
                $pastavshik->inn = $request->inn;
                $pastavshik->mfo = $request->mfo;
                $pastavshik->user_id = Auth::user()->id;
                $pastavshik->save();
            }

            $pastavshik = pastavshik::where('status', 'Актив')->orderBy('id', 'desc')->get();
            return response()->json(['message' => 'Маълумот сақланди.', 'pastavshik'=>$pastavshik], 200);
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
            $rules = [
                'edittaminotchi' => 'required',
                'editmanzili' => 'required',
                'edittelefoni' => 'required',
                'editxis_raqami' => 'required|digits:20',
                'editinn' => 'required|digits:9',
                'editmfo' => 'required|digits:5',
            ];

            $messages = [
                'edittaminotchi.required' => 'Таъминотчи номи киритилмади.',
                'editmanzili.required' => 'Таъминотчи манзили киритилмади.',
                'edittelefoni.required' => 'Телефон рақами киритилмади.',
                'editxis_raqami.required' => 'Хисоб-раками киритилмади.',
                'editinn.required' => 'ИНН киритилмади.',
                'editmfo.required' => 'МФО киритилмади.',
                'edittelefoni.digits' => 'Телефон 9 хонали рақам бўлиши керак.',
                'editxis_raqami.digits' => 'Хисоб-раками 20 хонали рақам бўлиши керак.',
                'editinn.digits' => 'ИНН 9 хонали рақам бўлиши керак.',
                'editmfo.digits' => 'МФО 5 хонали рақам бўлиши керак.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $tur = pastavshik::where('id', $id)->update([
                'pastav_name' => $request->edittaminotchi,
                'manzili' => $request->editmanzili,
                'telefoni' => $request->edittelefoni,
                'xis_raqami' => $request->editxis_raqami,
                'inn' => $request->editinn,
                'mfo' => $request->editmfo,
                'user_id' => Auth::user()->id,
            ]);

            $pastavshik = pastavshik::where('status', 'Актив')->orderBy('id', 'desc')->get();
            return response()->json(['message' => 'Маълумот ўзгартирилди.','pastavshik'=>$pastavshik], 200);
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
