<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\tulovlar1;
use App\Models\valyuta;
use App\Models\filial;
use App\Models\mijozlar;
use App\Models\lavozim;
use App\Models\shartnoma1;
use App\Models\tashrif;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ShartnomaEditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $valyuta = valyuta::get();
        $mijozlar = mijozlar::orderBy('id', 'desc')->get();
        $tashrif = tashrif::get();
        $shartnoma = shartnoma1::get();
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $filial = filial::where('status', 'Актив')->whereNotIn('id', [10])->get();
        return view('shartnoma.ShartnomaEdit', [
            'xis_oyi' => $xis_oyi, 
            'filial' => $filial, 
            'valyuta' => $valyuta, 
            'mijozlar' => $mijozlar, 
            'tashrif' => $tashrif,
            'shartnoma' => $shartnoma,
            'filial_name' => $filial_name,
            'lavozim_name' => $lavozim_name,
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

        $id = $request->id;
        $filial = $request->filial;

        $shartnoma = new shartnoma1($filial);
        $shartnom = $shartnoma->where('id', $id)->first();

        
        echo '
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="text-center text-bold text-primary align-middle">
                    <th>№</th>
                    <th>Мижоз</th>
                    <th>Фоиз</th>
                    <th>Шартном сана</th>
                    <th>Муддати</th>
                    <th>Савдо ракам</th>
                    <th>Холати</th>
                    <th>Изох</th>
                    <th>Тахрир</th>
                </tr>
            </thead>';

            if ($shartnom){
                echo'

            <tbody id="tab1">
                <tr class="text-center align-middle">
                    <td>' . $shartnom->id . '</td>
                    <td>' . $shartnom->mijozlar->last_name.' '.$shartnom->mijozlar->first_name.' '.$shartnom->mijozlar->middle_name . '</td>
                    <td>' . $shartnom->fstatus . '</td>
                    <td>' . date('d.m.Y', strtotime($shartnom->kun)) . '</td>
                    <td>' . $shartnom->muddat . '</td>
                    <td>' . $shartnom->savdo_id . '</td>
                    <td>' . $shartnom->status . '</td>
                    <td>' . $shartnom->izox . '</td>
                    <td>
                        <button id="tulovedit" class="btn btn-outline-primary btn-sm me-2 "
                        data-filial2="' . $filial . '" 
                        data-shid="' . $shartnom->id . '" 
                        data-mijoz="' . $shartnom->mijozlar_id . '"
                        data-kafil="' . $shartnom->kafil_id . '"
                        data-tashrif="' . $shartnom->tashrif_id . '"
                        data-fstatus="' . $shartnom->fstatus . '"
                        data-kun="' . $shartnom->kun . '"
                        data-savdo_id="' . $shartnom->savdo_id . '" 
                        data-muddat="' . $shartnom->muddat . '"
                        data-status="' . $shartnom->status . '"
                        data-izox="' . $shartnom->izox . '"
                        data-bs-toggle="modal"
                        data-bs-target="#edit"><i class="flaticon-381-notepad"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>';

        }else{
            
            echo'
            <tr class="text-center align-middle">
                <td colspan="10"> Shartnoma topilmadi!</td> 
            <tr>';
        }
        return;
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

        $rules = [
            'mijoz' => 'required',
            'tashrif' => 'required',
            'fstatus' => 'required',
            'kun' => 'required',
            'savdo_id' => 'required',
            'muddat' => 'required',
            'status' => 'required',
            'izox' => 'required',
        ];

        $messages = [
            'mijoz.required' => 'Сана киритилмади.',
            'tashrif.required' => 'Валюта танланмади.',
            'fstatus.required' => 'Сумма киритилмади.',
            'kun.required' => 'Сумма киритилмади.',
            'savdo_id.required' => 'Сумма киритилмади.',
            'muddat.required' => 'Сумма киритилмади.',
            'status.required' => 'Сумма киритилмади.',
            'izox.required' => 'Сумма киритилмади.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $branchId = $request->integer('filial2');
        $contracts = new shartnoma1($branchId);

        $user = Auth::user();
        $contract = $contracts->where('id', $id)->first();

        if (!$contract) {
            return response()->json(['message' => "Shartnoma topilmadi!"]);
        }
        if ($contract->status == 'Удалит') {
            return response()->json(['message' => "Statusi 'Удалит' shartnoma bilan amal bajarib bo'lmaydi!"]);
        }

        if ($user->lavozim_id == 1 && $user->status == 'Актив') {
            $muddat = $request->muddat;
            $date1 = strtotime('+' .$muddat. 'month', strtotime($request->kun));
            $date2 = strtotime('last day of +' .$muddat. 'month', strtotime($request->kun));
            if ($date1 >= $date2) {
            $tugSana = date('Y-m-d', strtotime('last day of +' .$muddat. 'month', strtotime($request->kun)));
            } else {
            $tugSana = date("Y-m-d", strtotime('+' .$muddat . 'month', strtotime($request->kun)));
            }

            $contract->mijozlar_id = $request->mijoz;
            $contract->tashrif_id = $request->tashrif;
            $contract->fstatus = $request->fstatus;
            $contract->kun = $request->kun;
            $contract->tug_sana = $tugSana;
            $contract->savdo_id = $request->savdo_id;
            $contract->muddat = $muddat;
            $contract->izox = $request->izox;
            $contract->status = $request->status;

            $contract->save();

            return response()->json(['message' => 'Shartnoma o\'zgartirildi']);
        } else {
            return response()->json(['message' => "Xatolik. Adminga murojaat qiling."]);
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
