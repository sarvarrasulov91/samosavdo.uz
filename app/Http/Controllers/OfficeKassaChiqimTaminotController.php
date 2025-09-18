<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\chiqim_taminot;
use App\Models\valyuta;
use App\Models\pastavshik;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;

use Illuminate\Support\Facades\Validator;

class OfficeKassaChiqimTaminotController extends Controller
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
            $valyuta = valyuta::get();
            $pastavshik=pastavshik::where('status', 'Актив')->get();
            return view('kassa.OfficeKassaCHiqTamin', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'valyuta' => $valyuta, 'pastavshik'=>$pastavshik]);
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

        echo'
            <table class="table table-bordered table-responsive-sm text-center align-middle ">
            <thead>
                <tr class="text-bold text-primary">
                    <th>ID</th>
                    <th>Куни</th>
                    <th>Таъминотчи</th>
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
            $chiqimtaminot = chiqim_taminot::where('status', 'Актив')->orderBy('id', 'desc')->get();

            foreach ($chiqimtaminot as $chiqimtam){
                echo'
                <tr>
                    <td>' . $chiqimtam->id . '</td>
                    <td>' . date('d.m.Y', strtotime($chiqimtam->kun)) . '</td>
                    <td>' . $chiqimtam->pastavshik->pastav_name . '</td>
                    <td>' . $chiqimtam->valyuta->valyuta__nomi . '</td>
                    <td>' . number_format($chiqimtam->naqd, 2, ",", " ") . '</td>
                    <td>' . number_format($chiqimtam->pastik, 2, ",", " ") . '</td>
                    <td>' . number_format($chiqimtam->hr, 2, ",", " ") . '</td>
                    <td>' . number_format($chiqimtam->click, 2, ",", " ") . '</td>
                    <td>' . number_format($chiqimtam->rsumma, 2, ",", " ") . '
                    </td>
                    <td>' . $chiqimtam->izox . '</td>
                    <td>
                        <a id="chqimtaminotudalit" href="#"
                            class="btn btn-outline-danger btn-sm me-2"
                            data-id="' . $chiqimtam->id . '"
                            data-kun="' . $chiqimtam->kun . '"><i
                                class="flaticon-381-trash-1"></i></a>
                    </td>
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

        $rules = [
            'chkun' => 'required',
            'pastav_id' => 'required',
            'pul_id' => 'required',
            'naqd' => 'required',
            'plastik' => 'required',
            'hr' => 'required',
            'click' => 'required',
            'chizox' => 'required',
        ];

        $messages = [
            'chkun.required' => 'Сана киритилмади.',
            'pastav_id.required' => 'Таъминотчини танланг.',
            'pul_id.required' => 'Валютани танланг.',
            'naqd.required' => 'Сумма киритилмади.',
            'plastik.required' => 'Сумма киритилмади.',
            'hr.required' => 'Сумма киритилмади.',
            'click.required' => 'Сумма киритилмади.',
            'chizox.required' => 'Изох киритилмади.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

        $naqd = floatval(preg_replace('/[^\d.]/', '', $request->naqd));
        $plastik = floatval(preg_replace('/[^\d.]/', '', $request->plastik));
        $hr = floatval(preg_replace('/[^\d.]/', '', $request->hr));
        $click = floatval(preg_replace('/[^\d.]/', '', $request->click));

        $chiqim = new chiqim_taminot;
        $chiqim->kun = $request->chkun;
        $chiqim->pastavshik_id = $request->pastav_id;
        $chiqim->valyuta_id = $request->pul_id;
        $chiqim->naqd = $naqd;
        $chiqim->pastik = $plastik;
        $chiqim->hr = $hr;
        $chiqim->click = $click;
        $chiqim->avtot = 0;
        $chiqim->rsumma = $naqd+$plastik+$hr+$click;
        $chiqim->izox = $request->chizox;
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
            $kirim = chiqim_taminot::where('id', $id)->update([
                'status' => "Удалит",
                'user_id' => Auth::user()->filial_id,
            ]);
            return response()->json(['message' => 'Маълумот ўчирилди.'], 200);
        }
    }
}
