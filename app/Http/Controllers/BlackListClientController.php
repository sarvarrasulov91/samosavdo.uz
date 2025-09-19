<?php

namespace App\Http\Controllers;

use App\Models\mfy;
use App\Models\mijozlar;
use App\Models\tuman;
use App\Models\xissobotoy;
use App\Models\filial;
use App\Models\lavozim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlackListClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->query('search');

        if($search){
            $clients = mijozlar::where(function ($query) use ($search){
                $query->where('last_name', 'LIKE', "%$search%")
                    ->orWhere('first_name', 'LIKE', "%$search%")
                    ->orWhere('pinfl', 'LIKE', "%$search%")
                    ->orWhere('passport_sn', 'LIKE', "%$search%")
                    ->orWhere('phone', 'LIKE', "%$search%");
            })->where('m_type', 2)->paginate(10);

        }else{
            if (Auth::user()->filial_id == 10){
                $clients = mijozlar::query()->where('status', 1)->where('m_type', 2)->latest('id')->paginate(100);
            }else{
                $clients = mijozlar::where('status', 1)->where('m_type', 2)->where('filial_id', Auth::user()->filial_id)->latest('id')->paginate(100);
            }
        }

        $mfy = mfy::get();
        $tuman = tuman::get();

        return view('clients.BlackListClient', [
            'tuman' => $tuman,
            'mfy' => $mfy,
            'clients' => $clients
            ]);
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
        //
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
