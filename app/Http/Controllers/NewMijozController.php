<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\mijozlar;
use App\Models\mfy;
use App\Models\tuman;
use App\Models\filial;
use App\Models\fond1;
use App\Models\naqdsavdo1;
use App\Models\shartnoma1;
use App\Models\savdo1;



use Illuminate\Support\Facades\Validator;



class NewMijozController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mfy = mfy::get();
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $tuman = tuman::get();

        return view('mijoz.newmijoz', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'tuman' => $tuman, 'mfy'=>$mfy]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        echo'
            <table class="table table-bordered table-striped table-responsive-sm text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Сана</th>
                        <th>ФИО</th>
                        <th>Туғилган<br>сана</th>
                        <th>Паспорт<br>С.Р.</th>
                        <th>Мобиле<br>номер</th>
                        <th>ЖШШИР</th>
                        <th>Иш жойи</th>
                        <th>Ташкилот номи</th>
                        <th>Касби</th>
                        <th>Филиал</th>
                        <th>Таҳрирлаш</th>
                    </tr>
                </thead>
                <tbody id="tab1">
                ';
                   if (Auth::user()->filial_id == 10){
                    $mijozlar = mijozlar::where('status', 1)->orderBy('id', 'desc')->get();
                    }else{
                        $mijozlar = mijozlar::where('status', 1)->where('filial_id', Auth::user()->filial_id)->orderBy('id', 'desc')->get();
                    }
                    foreach ($mijozlar as $mijozla){
                        echo'
                        <tr>
                            <td>' . $mijozla->id . '</td>
                            <td>' . date('d.m.Y', strtotime($mijozla->created_at)) . '</td>
                            <td>' . $mijozla->last_name . ' ' . $mijozla->first_name . '<br>' . $mijozla->middle_name . '
                            </td>
                            <td>' . date('d.m.Y', strtotime($mijozla->t_sana)) . '</td>
                            <td>' . $mijozla->passport_sn . '</td>
                            <td>' . $mijozla->phone . '</td>
                            <td>' . $mijozla->pinfl . '</td>
                            <td>' . $mijozla->ish_joy . '</td>
                            <td>' . $mijozla->ish_tashkiloti . '</td>
                            <td>' . $mijozla->kasb . '</td>
                            <td>' . $mijozla->filial->fil_name . '</td>
                            <td>';
                            if (Auth::user()->lavozim_id == 100){
                                echo'
                                <button id="fondedit" class="btn btn-primary btn-sm me-2"
                                    data-id="' . $mijozla->id . '"
                                    data-last_name="' . $mijozla->last_name . '"
                                    data-first_name="' . $mijozla->first_name . '"
                                    data-middle_name="' . $mijozla->middle_name . '"
                                    data-t_sana="' . $mijozla->t_sana . '"
                                    data-passport_sn="' . $mijozla->passport_sn . '"
                                    data-passport_iib="' . $mijozla->passport_iib . '"
                                    data-passport_date="' . $mijozla->passport_date . '"
                                    data-pinfl="' . $mijozla->pinfl . '"
                                    data-tuman_id="' . $mijozla->tuman_id . '"
                                    data-mfy_id="' . $mijozla->mfy_id . '"
                                    data-manzil="' . $mijozla->manzil . '"
                                    data-phone="' . $mijozla->phone . '"
                                    data-extra_phone="' . $mijozla->extra_phone . '"
                                    data-ish_tumanid="' . $mijozla->ish_tumanid . '"
                                    data-ish_joy="' . $mijozla->ish_joy . '"
                                    data-ish_tashkiloti="' . $mijozla->ish_tashkiloti . '"
                                    data-kasb="' . $mijozla->kasb . '"
                                    data-maosh="' . $mijozla->maosh . '" data-bs-toggle="modal"
                                    data-bs-target="#edit"><i class="flaticon-381-notepad"></i></button>';
                            }
                            echo'
                            </td>
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $region = tuman::where('id', $request->tuman)->get();
        foreach ($region as $regio) {
            $viloyat = $regio->viloyat_id;
        }

        $mijozlar = new mijozlar;
        $mijozlar->last_name = ucfirst(strtolower($request->famil));
        $mijozlar->first_name = ucfirst(strtolower($request->ism));
        $mijozlar->middle_name = ucfirst(strtolower($request->sharif));
        $mijozlar->t_sana = $request->t_sana;
        $mijozlar->passport_sn = $request->p_seriya . $request->p_nomer;
        $mijozlar->passport_iib = $request->p_iib;
        $mijozlar->passport_date = $request->p_sana;
        $mijozlar->pinfl =  $request->jshshir;
        $mijozlar->viloyat_id = $regio->viloyat_id;
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

        return redirect()->route('newmijoz.index')->with('message', 'Маълумот сақланди.');
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
        $i=1;
        $mijozlar = mijozlar::where('id', $id)->get();

        foreach ($mijozlar as $mijozla) {
            echo '<h3 class=" text-center text-primary ">' . $mijozla->last_name . ' ' . $mijozla->first_name . ' ' . $mijozla->middle_name . '</h3>
            <table class="table table-bordered table-hover" style="font-size: 12px;">
                <thead>
                    <tr class="text-center text-bold text-primary align-middle">
                        <th>№</th>
                        <th>Филиал</th>
                        <th>ФИО</th>
                        <th>Савдо<br>тури</th>
                        <th>Шартнома<br>рақами</th>
                        <th>Шартнома<br>Санаси</th>
                        <th>Шартнома<br>Суммаси</th>
                        <th>Шартнома<br>холати</th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                    $filial = filial::where('status', 'Актив')->get();
                    foreach ($filial as $filialinfo){
                        $shartnoma = new shartnoma1($filialinfo->id);
                        $shartnom=$shartnoma->where('mijozlar_id',$mijozla->id)->get();
                        foreach ($shartnom as $shartnom){
                            $savdosumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');
                            echo'
                            <tr class="text-center align-middle">
                                <td>' . $i . '</td>
                                <td>' . $filialinfo->fil_name . '</td>
                                <td>' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name . '
                                </td>
                                <td>Шартнома</td>
                                <td>' . $shartnom->id . '</td>
                                <td>' . date('d.m.Y', strtotime($shartnom->kun)) . '</td>
                                <td>' . number_format($savdosumma, 2, ',', ' ') . '</td>
                                <td>' . $shartnom->status . '</td>
                            </tr>';
                            $i++;
                        }

                        $shartnoma = new naqdsavdo1($filialinfo->id);
                        $shartnom=$shartnoma->where('mijozlar_id',$mijozla->id)->where('status','Актив')->get();
                        foreach ($shartnom as $shartnom){
                            $savdosumma = savdo1::where('status', 'Нақд')->where('shartnoma_id', $shartnom->id)->sum('msumma');
                            echo'
                            <tr class="text-center align-middle">
                                <td>' . $i . '</td>
                                <td>' . $filialinfo->fil_name . '</td>
                                <td>' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name . '
                                </td>
                                <td>Нақд</td>
                                <td>' . $shartnom->id . '</td>
                                <td>' . date('d.m.Y', strtotime($shartnom->kun)) . '</td>
                                <td>' . number_format($savdosumma, 2, ',', ' ') . '</td>
                                <td>' . $shartnom->status . '</td>
                            </tr>';
                            $i++;
                        }
                        $shartnoma = new fond1($filialinfo->id);
                        $shartnom=$shartnoma->where('mijozlar_id',$mijozla->id)->where('status','Актив')->get();
                        foreach ($shartnom as $shartnom){
                            $savdosumma = savdo1::where('status', 'Фонд')->where('shartnoma_id', $shartnom->id)->sum('msumma');
                            echo'
                            <tr class="text-center align-middle">
                                <td>' . $i . '</td>
                                <td>' . $filialinfo->fil_name . '</td>
                                <td>' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name . '
                                </td>
                                <td>Фонд</td>
                                <td>' . $shartnom->id . '</td>
                                <td>' . date('d.m.Y', strtotime($shartnom->kun)) . '</td>
                                <td>' . number_format($savdosumma, 2, ',', ' ') . '</td>
                                <td>' . $shartnom->status . '</td>
                            </tr>';
                            $i++;
                        }

                    }
        }


        return;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $region = tuman::where('id', $request->tuman)->get();
        foreach ($region as $regio) {
            $viloyat = $regio->viloyat_id;
        }
        $mijozlar = mijozlar::where('id', $id)->update([
            'last_name'=>ucfirst(strtolower($request->famil)),
            'first_name'=>ucfirst(strtolower($request->ism)),
            'middle_name'=>ucfirst(strtolower($request->sharif)),
            't_sana'=>$request->t_sana,
            'passport_sn'=>$request->passport_sn,
            'passport_iib'=>$request->p_iib,
            'passport_date'=>$request->p_sana,
            'pinfl'=> $request->jshshir,
            'viloyat_id'=>$regio->viloyat_id,
            'tuman_id'=>$request->tuman,
            'mfy_id'=>$request->mfy,
            'manzil'=>$request->manzil,
            'phone'=>$request->mobile_nomer,
            'extra_phone'=>$request->qoshimcha_nomer,
            'ish_tumanid'=>$request->ish_tuman,
            'ish_joy'=>$request->ish_joy,
            'ish_tashkiloti'=>$request->ish_tashkiloti,
            'kasb'=>$request->kasb,
            'maosh'=>$request->oylik,
            'user_id'=>Auth::user()->id,
           ]);

           return response()->json(['message' => 'Малумот ўзгартирилди.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
