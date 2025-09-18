<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\kirim;
use App\Models\kirimtur;
use App\Models\valyuta;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;



use Illuminate\Support\Facades\Validator;

class OfficeKassaKirimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $kirimtur = kirimtur::get();
        $valyuta = valyuta::get();
        return view('kassa.OfficeKassaKirim', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi,'kirimtur'=>$kirimtur,'valyuta'=>$valyuta]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        echo '
        <table class="table table-bordered table-responsive-sm text-center align-middle ">
            <thead>
                <tr class="text-bold text-primary">
                    <th>ID</th>
                    <th>Куни</th>
                    <th>Филиал</th>
                    <th>Номи</th>
                    <th>Валюта</th>
                    <th>Нақд</th>
                    <th>Пластик</th>
                    <th>Х-р</th>
                    <th>Сlick</th>
                    <th>Жами</th>
                    <th>Изох</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tab1">
    ';

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $kirim = kirim::where('status','Актив')->orderBy('id', 'desc')->get();
        foreach ($kirim as $kirim) {
            echo '
            <tr>
                <td>' . $kirim->id . '</td>
                <td>' . date('d.m.Y', strtotime($kirim->kun)) . '</td>
                <td>' . $kirim->filial->fil_name . '</td>
                <td>' . $kirim->kirimtur->kirim_tur_name . '</td>
                <td>' . $kirim->valyuta->valyuta__nomi . '</td>
                <td>' . number_format($kirim->naqd, 2, ",", " ") . '</td>
                <td>' . number_format($kirim->pastik, 2, ",", " ") . '</td>
                <td>' . number_format($kirim->hr, 2, ",", " ") . '</td>
                <td>' . number_format($kirim->click, 2, ",", " ") . '</td>
                <td>' . number_format($kirim->umumiy, 2, ",", " ") . '</td>
                <td>' . $kirim->izoh . '</td>
                <td>
                    <a id="kirimudalit" href="#"
                        class="btn btn-outline-danger btn-sm me-2"
                        data-id="' . $kirim->id . '"
                        data-kun="' . $kirim->kun . '"
                        data-kirim_tur_name="' . $kirim->kirim_tur_name . '"><i
                        class="flaticon-381-trash-1"></i></a>
                </td>
            </tr>
            ';
        }

        echo '
                </tbody>
            </table>
        ';
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [

            'kun' => 'required',
            'naqd' => 'required',
            'plastik' => 'required',
            'hr' => 'required',
            'click' => 'required',
            'izoh' => 'required',
            'kirim_id' => 'required',
            'val_id' => 'required',

        ];

        $messages = [
            'kun.required' => 'Сана киритилмади.',
            'naqd.required' => 'Сумма киритилмади.',
            'plastik.required' => 'Сумма киритилмади.',
            'hr.required' => 'Сумма киритилмади.',
            'click.required' => 'Сумма киритилмади.',
            'izoh.required' => 'Изох киритилмади.',
            'kirim_id.required' => 'Тўлов тури танланмади.',
            'val_id.required' => 'Валюта танланмади.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $naqd = floatval(preg_replace('/[^\d.]/', '', $request->naqd));
        $plastik = floatval(preg_replace('/[^\d.]/', '', $request->plastik));
        $hr = floatval(preg_replace('/[^\d.]/', '', $request->hr));
        $click = floatval(preg_replace('/[^\d.]/', '', $request->click));

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

        $chiqim = new kirim;
        $chiqim->kun = $request->kun;
        $chiqim->filial_id = 10;
        $chiqim->kirimtur_id = $request->kirim_id;
        $chiqim->valyuta_id = $request->val_id;
        $chiqim->naqd = $naqd;
        $chiqim->pastik = $plastik;
        $chiqim->hr = $hr;
        $chiqim->click = $click;
        $chiqim->avtot = 0;
        $chiqim->umumiy = ($naqd + $plastik + $hr + $click);
        $chiqim->izoh = $request->izoh;
        $chiqim->xis_oyi = $xis_oyi;
        $chiqim->user_id = Auth::user()->id;
        $chiqim->save();

        return response()->json(['message' => 'Маълумот сақланди.'], 200);

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
        if (Auth::user()->lavozim_id == 1) {
            $kirim = kirim::where('id', $id)->update([
                'status' => "Удалит",
                'user_id' => Auth::user()->filial_id,
            ]);

            return response()->json(['message' => 'Маълумот ўчирилди.'], 200);
        }

    }
}
