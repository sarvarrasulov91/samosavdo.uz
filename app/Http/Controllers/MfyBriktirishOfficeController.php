<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mfy;
use App\Models\tuman;
use App\Models\xodimlar;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;
use Illuminate\Support\Facades\Auth;

class MfyBriktirishOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $tuman = tuman::get();
        $xodimlar = xodimlar::get();
        return view('qushmchalar.MfyBriktirish', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'tuman'=>$tuman, 'xodimlar'=>$xodimlar ]);
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
        if($request->status=="qushish"){
            foreach ($request->mfy_id as $mfyid) {
                $tur = mfy::where('id', $mfyid)->limit(1)->update([
                    'xodimlar_id' => $request->xodim_id,
                ]);
            }
            return response()->json(['message' => 'МФЙлар ходимга бириктирилди .'], 200);

        }elseif($request->status=="uchirish"){
            foreach ($request->mfy_id as $mfyid) {
                $tur = mfy::where('id', $mfyid)->limit(1)->update([
                    'xodimlar_id' => 0,
                ]);
            }
            return response()->json(['message' => 'МФЙлар ўчирилди .'], 200);

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
                        <th>Тумани</th>
                        <th>МФЙ номи</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                    $mfy = mfy::where('xodimlar_id',0)->where('status', 1)->where('tuman_id', $id)->orderBy('id', 'desc')->get();
                    foreach ($mfy as $mfyname){
                        echo'
                        <tr class="text-center align-middle">
                            <td>'. $mfyname->id . '</td>
                            <td>'. $mfyname->tuman->name_uz . '
                            <td>'. $mfyname->name_uz . '
                            <td><input type="checkbox" class="tanlash_checkbox" name="belgiuchir" value="'. $mfyname->id . '"></td>
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

          echo'
            <table class="table table-bordered table-hover" id="find-table-del">
                <thead>
                    <tr class="text-center text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Тумани</th>
                        <th>МФЙ номи</th>
                        <th><input type="checkbox" id="selectalldel" class="selectall"></th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                    $mfy = mfy::where('xodimlar_id',$id)->where('status', '1')->orderBy('id', 'desc')->get();
                    foreach ($mfy as $mfyname){
                        echo'
                        <tr class="text-center align-middle">
                            <td>'. $mfyname->id . '</td>
                            <td>'. $mfyname->tuman->name_uz . '
                            <td>'. $mfyname->name_uz . '
                            <td><input type="checkbox" class="tanlash_checkbox_del" name="belgiuchir" value="'. $mfyname->id . '"></td>
                        </tr>
                        ';
                    }
                    echo'
                </tbody>
            </table>
        ';
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
