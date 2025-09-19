<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tur;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;
use App\Models\transport;
use App\Models\natsenka;
use Illuminate\Support\Facades\Auth;

class TovarTurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {

            $transport = transport::get();
            $natsenka1 = natsenka::get();
            $tur = tur::where('status', 'Актив')->orderBy('id', 'desc')->get();

            return view('tovarlar.tur.index', ['tur' => $tur,
                'transport' => $transport,
                'natsenka1' => $natsenka1
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
    public function store(request $request)
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $request->validate([
                'trans_harajatid' => 'required',
                'natsenka' => 'required',
                'tovarturi' => 'required|min:3|max:30',
            ]);

            $tur = new tur;
            $tur->tur_name = $request->tovarturi;
            $tur->transport_id = $request->trans_harajatid;
            $tur->natsenka_id = $request->natsenka;
            $tur->user_id = Auth::user()->id;
            $tur->save();
            return redirect()->route('tur.index')->with('message','Маълумот сақланди.');
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
            $request->validate([
                'edittrans_harajatid' => 'required',
                'editnatsenka' => 'required',
                'edittovarturi' => 'required|min:3|max:30',
            ]);

            $tur = tur::where('id', $id)->update([
                'tur_name' => $request->edittovarturi,
                'transport_id' => $request->edittrans_harajatid,
                'natsenka_id' => $request->editnatsenka,
                'user_id' => Auth::user()->id,
            ]);
            return redirect()->route('tur.index')->with('message','Маълумот ўзгартирилди.');
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
