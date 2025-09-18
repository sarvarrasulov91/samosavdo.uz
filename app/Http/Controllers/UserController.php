<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\PostStoreRequest;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;


class UserController extends Controller
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
            $lavozimlar = lavozim::get();
            $filial = filial::get();
            $Userlar = DB::table('users')
                ->join('lavozim', 'users.lavozim_id', '=', 'lavozim.id')
                ->join('filial', 'users.filial_id', '=', 'filial.id')
                ->select('users.*', 'lavozim.lavozim', 'filial.fil_name')->where('users.status', 'Актив')->orderBy('users.id', 'desc')
                ->get();
            return view('users.index', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'Userlar' => $Userlar, 'lavozimlar' => $lavozimlar, 'filial' => $filial, 'xis_oyi' => $xis_oyi]);
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
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $lavozimlar = DB::table('lavozim')->get();
        $filial = DB::table('filial')->get();
        return view('users.create', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'lavozimlar' => $lavozimlar, 'filial' => $filial, 'xis_oyi' => $xis_oyi]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'filial_id' => $request->filial,
            'lavozim_id' => $request->lavozim,
            'password' => Hash::make($request->password),
        ]);
        // }
        return redirect()->route('user.index')->with('message','Маълумот сақланди.');
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
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $lavozimlar = DB::table('lavozim')->get();
        $filial = DB::table('filial')->get();
        $Userlar = DB::table('users')->where('users.status', 'Актив')->where('users.id', $id)->get();
        return view('users.edit', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'Userlar' => $Userlar, 'lavozimlar' => $lavozimlar, 'filial' => $filial, 'xis_oyi' => $xis_oyi]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostStoreRequest $request, string $id)
    {
        $user = DB::table('users')->where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'filial_id' => $request->filial,
            'lavozim_id' => $request->lavozim,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('user.index')->with('message','Маълумот ўзгартирилди.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = DB::table('users')->where('id', $id)->update([
            'status' => "ДеАктив",
        ]);
        return redirect()->route('user.index')->with('message','Маълумот ўчирилди.');
    }
}
