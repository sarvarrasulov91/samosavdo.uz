<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\chiqim_boshqa;
use App\Models\valyuta;
use App\Models\turharajat;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;

use Illuminate\Support\Facades\Validator;


class OfficeKassaChiqimBoshController extends Controller
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
            $turharajat=turharajat::get();
            return view('kassa.OfficeKassaCHiqBoshqa', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'valyuta' => $valyuta, 'turharajat'=>$turharajat]);
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
                    <th>Харажатлар номи</th>
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
            <tbody id="chiqim_taminot_show">
        ';

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $chiqimtaminot = chiqim_boshqa::where('status', 'Актив')->where('xis_oyi', $xis_oyi)->orderBy('id', 'desc')->get();

            foreach ($chiqimtaminot as $chiqimtam){
                echo'
                <tr>
                    <td>' . $chiqimtam->id . '</td>
                    <td>' . date('d.m.Y', strtotime($chiqimtam->kun)) . '</td>
                    <td>' . $chiqimtam->turharajat->har_name . '</td>
                    <td>' . $chiqimtam->valyuta->valyuta__nomi . '</td>
                    <td>' . number_format($chiqimtam->naqd, 2, ",", " ") . '</td>
                    <td>' . number_format($chiqimtam->pastik, 2, ",", " ") . '</td>
                    <td>' . number_format($chiqimtam->hr, 2, ",", " ") . '</td>
                    <td>' . number_format($chiqimtam->click, 2, ",", " ") . '</td>

                    <td>' . number_format($chiqimtam->rsumma, 2, ",", " ") . '</td>
                    <td>' . $chiqimtam->izox . '</td>
                    <td>
                        <a id="chqimboshqaudalit" href="#"
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
            'bochkun' => 'required',
            'boshharajat_id' => 'required',
            'boshchpul_id' => 'required',
            'boshchnaqd' => 'required',
            'boshchpastik' => 'required',
            'boshchhr' => 'required',
            'boshchclick' => 'required',
            'boshchizox' => 'required',
        ];

        $messages = [
            'bochkun.required' => 'Сана киритилмади.',
            'boshharajat_id.required' => 'Харажатни танланг.',
            'boshchpul_id.required' => 'Валютани танланг.',
            'boshchnaqd.required' => 'Сумма киритилмади.',
            'boshchpastik.required' => 'Сумма киритилмади.',
            'boshchhr.required' => 'Сумма киритилмади.',
            'boshchclick.required' => 'Сумма киритилмади.',
            'boshchizox.required' => 'Изох киритилмади.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $naqd = floatval(preg_replace('/[^\d.]/', '', $request->boshchnaqd));
        $plastik = floatval(preg_replace('/[^\d.]/', '', $request->boshchpastik));
        $hr = floatval(preg_replace('/[^\d.]/', '', $request->boshchhr));
        $click = floatval(preg_replace('/[^\d.]/', '', $request->boshchclick));

        $chiqim = new chiqim_boshqa;
        $chiqim->kun = $request->bochkun;
        $chiqim->turharajat_id = $request->boshharajat_id;
        $chiqim->valyuta_id = $request->boshchpul_id;
        $chiqim->naqd = $naqd;
        $chiqim->pastik = $plastik;
        $chiqim->hr = $hr;
        $chiqim->click = $click;
        $chiqim->avtot = 0;
        $chiqim->rsumma = $naqd+$plastik+$hr+$click;
        $chiqim->izox = $request->boshchizox;
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
            $kirim = chiqim_boshqa::where('id', $id)->update([
                'status' => "Удалит",
                'user_id' => Auth::user()->filial_id,
            ]);

            return response()->json(['message' => 'Маълумот ўчирилди.'], 200);
        }
    }
}
