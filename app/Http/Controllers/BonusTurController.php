<?php

namespace App\Http\Controllers;

use App\Models\tur;
use App\Models\xissobotoy;
use App\Models\filial;
use App\Models\lavozim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BonusTurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $tur = tur::where('aksiya','0')->where('status', 'Актив')->get();
        return view('qushmchalar.BonusTur', ['xis_oyi' => $xis_oyi, 'tur'=>$tur, 'filial_name' => $filial_name, 'lavozim_name' => $lavozim_name]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        echo'
            <table class="table table-bordered table-hover" id="find-table-del">
                <thead>
                    <tr class="text-center text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Тур номи</th>
                        <th>Бонус</th>
                        <th><input type="checkbox" id="selectalldel" class="selectall"></th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                    $turlar = tur::where('aksiya','>' , '0')->where('status', 'Актив')->get();
                    foreach ($turlar as $tur){
                        echo'
                        <tr class="text-center align-middle">
                            <td>'. $tur->id . '</td>
                            <td>'. $tur->tur_name. '
                            <td>'. $tur->aksiya . ' %</td>
                            <td><input type="checkbox" class="tanlash_checkbox_del" name="belgiuchir" value="'. $tur->id . '"></td>
                        </tr>
                        ';
                    }
                    echo'
                </tbody>
            </table>
        ';
        return;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->status=="qushish"){
            foreach ($request->tur_id as $turid) {
                if((int)$turid <= 0){
                    continue;
                }
                $tur = tur::where('id', $turid)->update([
                    'aksiya' => $request->bonus_miqdor,
                ]);
            }
            return response()->json(['message' => 'Товарлар қўшилди .'], 200);

        }elseif($request->status=="uchirish"){
            foreach ($request->tur_id as $turid) {
                if((int)$turid <= 0){
                    continue;
                }
                $tur = tur::where('id', $turid)->update([
                    'aksiya' => 0,
                ]);
            }
            return response()->json(['message' => 'Товарлар ўчирилди .'], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        echo'
            <table class="table table-bordered table-hover" id="find-table">
                <thead>
                    <tr class="text-center text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Тур номи</th>
                        <th>Бонус</th>
                        <th><input type="checkbox" id="selectall" class="selectall"></th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                    $turlar = tur::where('aksiya','0')->where('status', 'Актив')->get();
                    foreach ($turlar as $tur){
                        echo'
                        <tr class="text-center align-middle">
                            <td>'. $tur->id . '</td>
                            <td>'. $tur->tur_name . '</td>
                            <td>'. $tur->aksiya . ' %</td>
                            <td><input type="checkbox" class="tanlash_checkbox" name="belgiuchir" value="'. $tur->id . '"></td>
                        </tr>
                        ';
                    }
                    echo'
                </tbody>
            </table>
        ';
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
