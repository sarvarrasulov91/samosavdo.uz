<?php

namespace App\Http\Controllers;

use App\Models\MijozlarIshJoy;
use Illuminate\Http\Request;
use App\Models\xissobotoy;
use App\Models\mfy;
use App\Models\tuman;
use App\Models\mijozlar;
use App\Models\lavozim;
use App\Models\filial;
use App\Models\viloyat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->query('search');

        if($search){
            $clients = mijozlar::query()
                ->where('last_name', 'LIKE', "%$search%")
                ->orWhere('first_name', 'LIKE', "%$search%")
                ->orWhere('pinfl', 'LIKE', "%$search%")
                ->orWhere('passport_sn', 'LIKE', "%$search%")
                ->orWhere('phone', 'LIKE', "%$search%")
                ->paginate(10);

        }else{
            if (Auth::user()->filial_id == 10){
                $clients = mijozlar::query()->where('status', 1)->latest('id')->paginate(1000);
            }else{
                $clients = mijozlar::query()->where('status', 1)->where('filial_id', Auth::user()->filial_id)->latest('id')->paginate(100);
            }
        }

        $mfy = mfy::all();
        $tuman = tuman::all();

        return view('clients.index', [
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
        $mfy = mfy::all();
        $xis_oyi = xissobotoy::query()->latest('id')->value('xis_oy');
        $viloyat = viloyat::all();
        $tuman = tuman::all();
        $ishJoy = MijozlarIshJoy::all();
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');

        return view('clients.create', [
            'filial_name' => $filial_name,
            'lavozim_name' => $lavozim_name,
            'xis_oyi' => $xis_oyi,
            'tuman' => $tuman,
            'viloyat' => $viloyat,
            'mfy' => $mfy,
            'ishJoy' => $ishJoy
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $request->validate([
        //     't_sana' => 'required|date',
        //     'pinfl' => 'required|digits:14',
        // ]);

        // // Extract date from `t_sana`
        // $x = date("dmy", strtotime($request->t_sana)); // Format as DDMMYY

        // // Extract DDMMYY from `pinfl`
        // $pinflDate = substr($request->pinfl, 1, 6); // Extract 2nd to 7th digits

        // // Compare the dates
        // if ($x !== $pinflDate) {
        //     return back()->with('message', 'Xatolik: Tug‘ilgan sana va PINFL mos emas!');
        // }

        $tekshiruv = mijozlar::where(function ($query) use ($request) {
            $query->where('pinfl', $request->jshshir)
                ->orWhere('passport_sn', $request->p_seriya . $request->p_nomer);
        })->orderBy('id', 'desc')->first();

        if($tekshiruv){
            $vaqt = date('d.m.Y', strtotime($tekshiruv->created_at));
            return
                "Bu mijoz bazada mavjud. <br><br>
                $tekshiruv->last_name $tekshiruv->first_name $tekshiruv->middle_name <br><br>
                $vaqt kuni $tekshiruv->id nomerda ro'yhatdan o'tgan. <br><br>
                Bazadan qidirib ko'ring.";
        }else{

            $mijozlar = new mijozlar;
            $mijozlar->last_name = ucfirst(strtolower($request->last_name));
            $mijozlar->first_name = ucfirst(strtolower($request->first_name));
            $mijozlar->middle_name = ucfirst(strtolower($request->middle_name));
            $mijozlar->t_sana = $request->t_sana;
            $mijozlar->passport_sn = $request->p_seriya . $request->p_nomer;
            $mijozlar->passport_iib = $request->passport_iib;
            $mijozlar->passport_date = $request->passport_date;
            $mijozlar->pinfl =  $request->pinfl;
            $mijozlar->viloyat_id = $request->viloyat;
            $mijozlar->tuman_id = $request->tuman;
            $mijozlar->mfy_id = $request->mfy;
            $mijozlar->manzil = $request->manzil;
            $mijozlar->phone = $request->mobile_nomer;
            $mijozlar->extra_phone = $request->qoshimcha_nomer;
            $mijozlar->ish_tumanid = $request->ish_tuman;
            $mijozlar->ish_joy = $request->ish_joy;
            $mijozlar->ish_tashkiloti = $request->ish_tashkiloti;
            $mijozlar->kasb = $request->kasb;
            $mijozlar->maosh = $request->oylik;
            $mijozlar->user_id = Auth::user()->id;
            $mijozlar->filial_id = Auth::user()->filial_id;
            $mijozlar->save();

            return redirect()->route('clients.index')->with('message', "Malumot saqlandi.");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $mfy = DB::table('mfy')->where('tuman_id', $id)->where('status','>',0)->get();
        foreach ($mfy as $mfyname) {
            echo "
                <option value='" . $mfyname->id . "'>" . $mfyname->name_uz . "</option>
            ";
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $mfy = mfy::get();
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $viloyat = viloyat::get();
        $tuman = tuman::get();
        $client = mijozlar::findOrFail($id);
        $ishJoy = MijozlarIshJoy::get();
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');

        return view('clients.edit', [
            'filial_name' => $filial_name,
            'lavozim_name' => $lavozim_name,
            'client'=>$client,
            'xis_oyi' => $xis_oyi,
            'viloyat' => $viloyat,
            'tuman' => $tuman,
            'mfy'=>$mfy,
            'ishJoy' => $ishJoy
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $oldMijoz = mijozlar::findOrFail($id); // Get the original record

        // Save the old record in the mijozlar_old table
        DB::table('mijozlar_old')->insert([
            'mijoz_id' => $oldMijoz->id,
            'last_name' => $oldMijoz->last_name,
            'first_name' => $oldMijoz->first_name,
            'middle_name' => $oldMijoz->middle_name,
            't_sana' => $oldMijoz->t_sana,
            'passport_sn' => $oldMijoz->passport_sn,
            'passport_iib' => $oldMijoz->passport_iib,
            'passport_date' => $oldMijoz->passport_date,
            'pinfl' => $oldMijoz->pinfl,
            'viloyat_id' => $oldMijoz->viloyat_id,
            'tuman_id' => $oldMijoz->tuman_id,
            'mfy_id' => $oldMijoz->mfy_id,
            'manzil' => $oldMijoz->manzil,
            'phone' => $oldMijoz->phone,
            'extra_phone' => $oldMijoz->extra_phone,
            'ish_tumanid' => $oldMijoz->ish_tumanid,
            'ish_joy' => $oldMijoz->ish_joy,
            'ish_tashkiloti' => $oldMijoz->ish_tashkiloti,
            'kasb' => $oldMijoz->kasb,
            'maosh' => $oldMijoz->maosh,
            'user_id' => $oldMijoz->user_id,
            'yo_user_id' => Auth::user()->id, // Record who made the change
            'filial_id' => $oldMijoz->filial_id
        ]);

        // change original record
        $client = mijozlar::where('id', $id)->update([
            'last_name' => ucfirst(strtolower($request->last_name)),
            'first_name' => ucfirst(strtolower($request->first_name)),
            'middle_name' => ucfirst(strtolower($request->middle_name)),
            't_sana' => $request->t_sana,
            'passport_sn' => $request->p_seriya . $request->p_nomer,
            'passport_iib' => $request->passport_iib,
            'passport_date' => $request->passport_date,
            'pinfl' =>  $request->pinfl,
            'viloyat_id' => $request->viloyat,
            'tuman_id' => $request->tuman,
            'mfy_id' => $request->mfy,
            'manzil' => $request->manzil,
            'phone' => $request->mobile_nomer,
            'extra_phone' => $request->qoshimcha_nomer,
            'ish_tumanid' => $request->ish_tuman,
            'ish_joy' => $request->ish_joy,
            'ish_tashkiloti' => $request->ish_tashkiloti,
            'kasb' => $request->kasb,
            'maosh' => $request->oylik,
           ]);

            return redirect()->route('showClient', ['id' => $id])->with('message', "Malumot saqlandi.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tuman = DB::table('tuman')->where('viloyat_id', $id)->get();
        foreach ($tuman as $tumanname) {
            echo "
                <option value='" . $tumanname->id . "'>" . $tumanname->name_uz . "</option>
            ";
        }
    }


    public function showClient(string $id)
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $client = mijozlar::findOrFail($id);
        $ishTuman = tuman::where('id', $client->ish_tumanid)->value('name_uz');
        $ishViloyatId = tuman::where('id', $client->ish_tumanid)->value('viloyat_id');
        $ishViloyatName = viloyat::where('id', $ishViloyatId)->value('name_uz');
        $filialName = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $lavozimName = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');

        return view('clients.show', [
            'client' => $client,
            'xis_oyi' => $xis_oyi,
            'ishTuman' => $ishTuman,
            'ishViloyat' => $ishViloyatName,
            'filial_name' => $filialName,
            'lavozim_name' => $lavozimName
            ]);
    }

    public function blackListClient(string $id)
    {
        $client = mijozlar::find($id);
        if ($client->m_type == 1){
            $client->update([
                'm_type' => '2',
                'user_id' => Auth::user()->id,
            ]);
        }elseif ($client->m_type == 2){
            $client->update([
                'm_type' => '1',
                'user_id' => Auth::user()->id,
            ]);
        }


        return redirect()->route('showClient', ['id' => $id])->with('message', "Malumot saqlandi.");
    }
}
