<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\tmodel;
use App\Models\lavozim;
use App\Models\filial;

class ChegirmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $model = tmodel::where('aksiya','0')->where('status', 'Актив')->orderBy('id', 'desc')->get();
        return view('qushmchalar.chegirma', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'model'=>$model]);
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
                        <th>Товар номи</th>
                        <th>Чегирма</th>
                        <th><input type="checkbox" id="selectalldel" class="selectall"></th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                    $model = tmodel::where('aksiya','>' , '0')->where('status', 'Актив')->orderBy('id', 'desc')->get();
                    foreach ($model as $tmodel){
                        echo'
                        <tr class="text-center align-middle">
                            <td>'. $tmodel->id . '</td>
                            <td>'. $tmodel->tur->tur_name . ' ' . $tmodel->brend->brend_name . ' ' . $tmodel->model_name . '
                            <td>'. $tmodel->aksiya . ' %</td>
                            <td><input type="checkbox" class="tanlash_checkbox_del" name="belgiuchir" value="'. $tmodel->id . '"></td>
                        </tr>
                        ';
                    }
                    echo'
                </tbody>
            </table>
        ';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->status=="qushish"){
            foreach ($request->model_id as $tmodelid) {
                $tur = tmodel::where('id', $tmodelid)
                ->update([
                    'aksiya' => $request->chegirma_miqdor,
                ]);
            }
            return response()->json(['message' => 'Товарлар қўшилди .'], 200);

        }elseif($request->status=="uchirish"){
            foreach ($request->model_id as $tmodelid) {
                $tur = tmodel::where('id', $tmodelid)
                ->update([
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
                        <th>Товар номи</th>
                        <th>Чегирма</th>
                        <th><input type="checkbox" id="selectall" class="selectall"></th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                    $model = tmodel::where('aksiya','0')->where('status', 'Актив')->orderBy('id', 'desc')->get();
                    foreach ($model as $tmodel){
                        echo'
                        <tr class="text-center align-middle">
                            <td>'. $tmodel->id . '</td>
                            <td>'. $tmodel->tur->tur_name . ' ' . $tmodel->brend->brend_name . ' ' . $tmodel->model_name . '
                            <td>'. $tmodel->aksiya . ' %</td>
                            <td><input type="checkbox" class="tanlash_checkbox" name="belgiuchir" value="'. $tmodel->id . '"></td>
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
