<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\brend;
use Illuminate\Support\Facades\Auth;


class TovarBrendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            // $natsenka1 = natsenka::get();
            $brend = brend::where('status', 'Актив')->orderBy('brend.id', 'desc')->get();

            return view('tovarlar.brend.index', ['brend' => $brend]);
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
            $request->validate([
                // 'natsenka' => 'required',
                'tovarbrendi' => 'required|min:3|max:30',
            ]);

            $brend = new brend;
            $brend->brend_name = $request->tovarbrendi;
            // $brend->natsenka_id = $request->natsenka;
            $brend->user_id = Auth::user()->id;
            $brend->save();
            return redirect()->route('brend.index')->with('message','Маълумот сақланди.');
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
                // 'editnatsenka' => 'required',
                'tovarbrendi' => 'required|min:3|max:30',
            ]);

            $tur = brend::where('id', $id)->update([
                'brend_name' => $request->tovarbrendi,
                // 'natsenka_id' => $request->editnatsenka,
                'user_id' => Auth::user()->id,
            ]);
            return redirect()->route('brend.index')->with('message','Маълумот ўзгартирилди.');
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
