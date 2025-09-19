<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\naqdsavdo1;
use App\Models\tulovlar1;
use App\Models\savdo1;
use App\Models\ktovar1;
use App\Models\tmqaytarish;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;


class NaqdSavdoOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();
            return view('kassa.NaqdSavdoOffice', ['filial' => $filial ]);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $savdo = new savdo1($request->filial);
        $savdomodel = $savdo->where('status', 'Нақд')->where('unix_id', $request->savdoid)->get();
        echo '<h3 class=" text-center text-primary ">' . $request->id . '</h3>
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="text-center text-bold text-primary align-middle">
                    <th>№</th>
                    <th>Куни</th>
                    <th>Товар номи</th>
                    <th>Суммаси</th>
                    <th>Штрих Коди</th>
                </tr>
            </thead>
            <tbody id="tab1">';
        $jami = 0;
        $i = 1;
        foreach ($savdomodel as $savdomode) {
            echo "
                <tr class='text-center align-middle'>
                    <td>" . $i . "</td>
                    <td>" . date('d.m.Y', strtotime($savdomode->created_at)) . "</td>
                    <td>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                    <td>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                    <td>" . $savdomode->shtrix_kod . "</td>
                </tr>";
            $jami += $savdomode->msumma;
            $i++;
        }
        echo "
                <tr class='text-center align-middle'>
                    <td></td>
                    <td></td>
                    <td>ЖАМИ</td>
                    <td>" . number_format($jami, 0, ',', ' ') . "</td>
                    <td></td>
                </tr>
            </tbody>
        </table>";
        return;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $filial=$id;
        echo '
            <table class="table table-bordered text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Куни</th>
                        <th>ФИО</th>
                        <th>Савдо<br>рақами </th>
                        <th>Товар<br>суммаси </th>
                        <th>Нақд </th>
                        <th>Пластик </th>
                        <th>Чегирма</th>
                        <th>Жами<br>суммаси</th>
                        <th>Фарқи</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tab1">
            ';

                $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

                $naqdsavdo = new naqdsavdo1($filial);
                $naqdsavdojami = $naqdsavdo->where('status', 'Актив')->orderBy('id', 'desc')->get();
                foreach ($naqdsavdojami as $naqdsavdojam){

                    $id=$naqdsavdojam->id;
                    $savdoid=$naqdsavdojam->savdoraqami_id;
                    $savdo = new savdo1($filial);
                    $savdosumma = $savdo->where('status', 'Нақд')->where('unix_id', $savdoid)->where('shartnoma_id', $id)->sum('msumma');

                    $jnaqd = 0;
                    $jplastik =0;
                    $jhr = 0;
                    $jClick = 0;
                    $jchegirma = 0;
                    $tulovlar = new tulovlar1($filial);
                    $tulovlar = $tulovlar->where('tulovturi', 'Нақд')->where('shartnomaid', $id)->where('status', 'Актив')->get();
                    foreach ($tulovlar as $tulovla) {
                        $jnaqd += $tulovla->naqd;
                        $jplastik += $tulovla->pastik;
                        $jhr += $tulovla->hr;
                        $jClick += $tulovla->click;
                        $jchegirma += $tulovla->chegirma;
                    }

                    if ($savdosumma != ($jnaqd + $jplastik + $jhr + $jClick + $jchegirma)){
                        echo'
                            <tr class="align-middle text-danger">
                        ';
                    }else{
                        echo'
                            <tr class="align-middle">
                        ';
                    }


                    echo'
                        <td>' . $naqdsavdojam->id .' </td>
                        <td>' . date("d.m.Y", strtotime($naqdsavdojam->kun)) . '</td>
                        <td>' . $naqdsavdojam->mijozlar->last_name . ' ' . $naqdsavdojam->mijozlar->first_name . ' ' . $naqdsavdojam->mijozlar->middle_name . '</td>
                        <td>' . $naqdsavdojam->savdoraqami_id . '</td>
                        <td>' . number_format($savdosumma, 2, ",", " ") . '</td>
                        <td>' . number_format($jnaqd, 2, ",", " ") . '</td>
                        <td>' . number_format($jplastik, 2, ",", " ") . '</td>
                        <td>' . number_format($jchegirma, 2, ",", " ") . '</td>
                        <td>' . number_format($jnaqd + $jplastik + $jhr + $jClick + $jchegirma, 2, ",", " ") . '
                        </td>
                        <td>' . number_format(($jnaqd + $jplastik + $jhr + $jClick + $jchegirma) -$savdosumma, 2, ",", " ") . '
                        </td>
                        <td>
                            <button id="kivitpechat" data-id="' . $naqdsavdojam->id .'" data-savdoid="' . $naqdsavdojam->savdoraqami_id .'" data-fio="' . $naqdsavdojam->mijozlar->last_name . ' ' . $naqdsavdojam->mijozlar->first_name . ' ' . $naqdsavdojam->mijozlar->middle_name .'"
                            class="btn btn-outline-primary btn-sm me-2 " data-bs-toggle="modal"
                            data-bs-target="#pechat"><i class="flaticon-381-search-1"></i></button>

                            <button id="tovarudalit" data-id="' . $naqdsavdojam->id .'" data-savdoid="' . $naqdsavdojam->savdoraqami_id .'"
                            class="btn btn-outline-danger btn-sm me-2"><i class="flaticon-381-trash-1"></i></button>
                        </td>
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

        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            if ($request->savdoid>0 && $request->id &&  $request->filial){
                $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
                $Counttovar = new ktovar1($request->filial);
                $Counttovar1 = $Counttovar->where('status', 'Нақд')->where('shatnomaid', $request->id)->count();
                if ($Counttovar1 > 0) {

                    $ReadK = new ktovar1($request->filial);
                    $ReadKt = $ReadK->where('status', 'Нақд')->where('shatnomaid', $request->id)->get();

                    foreach ($ReadKt as $ReadKtovar) {

                        if ($xis_oyi == $ReadKtovar->ch_xis_oyi) {

                            $ktovar = new ktovar1($request->filial);
                            $ktovarUpdated = $ktovar->where('status', 'Нақд')
                                ->where('shatnomaid', $request->id)
                                ->where('ch_xis_oyi', $xis_oyi)
                                ->limit(1)
                                ->update([
                                    'status' => "Сотилмаган",
                                    'ch_kun' => null,
                                    'ch_user_id' => 0,
                                    'ch_xis_oyi' => null,
                                    'shatnomaid' => 0,
                                ]);

                        } else {

                            $soninar = 0;
                            $KtovarBarkod = new ktovar1($request->filial);
                            $ktovarbarkods = $KtovarBarkod->where('tmodel_id', $ReadKtovar->tmodel_id)->orderBy('soni', 'desc')->limit(1)->get();

                            foreach ($ktovarbarkods as $ktovarbarkodsoni) {
                                $soninar = $ktovarbarkodsoni->soni;
                            }

                            $soninar++;

                            // O'zgaruvchilarni qisqartirish uchun switchCaseFormat ni ishlatish mumkin.
                            $turid2 = str_pad($ReadKtovar->tur_id, 4, "0", STR_PAD_LEFT);
                            $brendid2 = str_pad($ReadKtovar->brend_id, 4, "0", STR_PAD_LEFT);
                            $model2 = str_pad($ReadKtovar->tmodel_id, 5, "0", STR_PAD_LEFT);
                            $soninar2 = str_pad($soninar, 4, "0", STR_PAD_LEFT);

                            $shtr_kod = $turid2 . $brendid2 . $model2 . $soninar2;

                            try {
                                DB::beginTransaction();

                                $ktovarzapis = new ktovar1($request->filial);
                                $ktovarzapis->kun = date('Y-m-d');
                                $ktovarzapis->tur_id = $ReadKtovar->tur_id;
                                $ktovarzapis->brend_id = $ReadKtovar->brend_id;
                                $ktovarzapis->tmodel_id = $ReadKtovar->tmodel_id;
                                $ktovarzapis->shtrix_kod = $shtr_kod;
                                $ktovarzapis->soni = $soninar;
                                $ktovarzapis->valyuta_id = $ReadKtovar->valyuta_id;
                                $ktovarzapis->narhi = $ReadKtovar->narhi;
                                $ktovarzapis->snarhi = $ReadKtovar->snarhi;
                                $ktovarzapis->valyuta_narhi = $ReadKtovar->valyuta_narhi;
                                $ktovarzapis->tannarhi = $ReadKtovar->tannarhi;
                                $ktovarzapis->pastavshik_id = 10;
                                $ktovarzapis->pastavshik2_id = $ReadKtovar->pastavshik2_id;
                                $ktovarzapis->filial_id = $request->filial;
                                $ktovarzapis->xis_oyi = $xis_oyi;
                                $ktovarzapis->user_id = Auth::user()->id;
                                $ktovarzapis->save();
                                $insid = $ktovarzapis->id;

                                $CreateTqaytarish = new tmqaytarish;
                                $CreateTqaytarish->savdo_turi = $ReadKtovar->status;
                                $CreateTqaytarish->shartnoma_id = $ReadKtovar->shatnomaid;
                                $CreateTqaytarish->kun = $ReadKtovar->kun;
                                $CreateTqaytarish->tur_id = $ReadKtovar->tur_id;
                                $CreateTqaytarish->brend_id = $ReadKtovar->brend_id;
                                $CreateTqaytarish->tmodel_id = $ReadKtovar->tmodel_id;
                                $CreateTqaytarish->shtrix_kod = $ReadKtovar->shtrix_kod;
                                $CreateTqaytarish->valyuta_id = $ReadKtovar->valyuta_id;
                                $CreateTqaytarish->narhi = $ReadKtovar->narhi;
                                $CreateTqaytarish->snarhi = $ReadKtovar->snarhi;
                                $CreateTqaytarish->valyuta_narhi = $ReadKtovar->valyuta_narhi;
                                $CreateTqaytarish->tannarhi = $ReadKtovar->tannarhi;
                                $CreateTqaytarish->pastavshik_id = $ReadKtovar->pastavshik2_id;
                                $CreateTqaytarish->xis_oyi = $xis_oyi;
                                $CreateTqaytarish->filial_id = $request->filial;
                                $CreateTqaytarish->user_id = Auth::user()->id;
                                $CreateTqaytarish->kirim_id = $insid;
                                $CreateTqaytarish->shtrix_kod_yangi = $shtr_kod;
                                $CreateTqaytarish->save();

                                if ($ktovarzapis && $CreateTqaytarish) {
                                    DB::commit();
                                } else {
                                    DB::rollBack();
                                }
                            } catch (\Exception $e) {
                                DB::rollBack();
                            }
                        }

                        try {
                            DB::beginTransaction();

                            $savdorem = new savdo1($request->filial);
                            $savdoUpdated = $savdorem->where('unix_id', $request->savdoid)
                            ->where('status','Нақд')
                            ->update([
                                'status' => "Удалит",
                                'del_user_id' => Auth::user()->id,
                                'del_kun' => date('Y-m-d H:i:s'),
                                'del_xis_oyi' => $xis_oyi,
                            ]);

                            $tulov = new tulovlar1($request->filial);
                            $tulovlarUpdated = $tulov->where('tulovturi','Нақд')
                            ->where('shartnomaid',$id)->limit(1)
                            ->update([
                                'tulovturi' => "Брон",
                                'bron_user_id' => Auth::user()->id,
                                'bron_kun' => date('Y-m-d H:i:s'),
                                'bron_xis_oyi' => $xis_oyi,
                            ]);

                            $naqdsavdo = new naqdsavdo1($request->filial);
                            $naqdsavdoUpdated = $naqdsavdo->where('id', $id)
                            ->limit(1)
                            ->update([
                                'status' => 'Удалит',
                                'user_id' => Auth::user()->id,
                            ]);

                            if ($savdoUpdated && $tulovlarUpdated && $naqdsavdoUpdated ) {
                                DB::commit();
                                return response()->json(['message' => "Нақд савдо ўчирилди. Тўлов суммаси бронга олинди. Товарлари омборга қайтарилди."]);
                            } else {
                                DB::rollBack();
                                return response()->json(['message' => "Нақд савдони ўчиришда хатолик.2"]);
                            }
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return response()->json(['message' => "Нақд савдони ўчиришда хатолик.2"]);
                            // throw $e;
                        }
                    }

                }else{

                    try {
                        DB::beginTransaction();

                        $savdorem = new savdo1($request->filial);
                        $savdoUpdated = $savdorem->where('unix_id', $request->savdoid)
                        ->where('status','Нақд')
                        ->update([
                            'status' => "Удалит",
                            'del_user_id' => Auth::user()->id,
                            'del_kun' => date('Y-m-d H:i:s'),
                            'del_xis_oyi' => $xis_oyi,
                        ]);

                        $tulov = new tulovlar1($request->filial);
                        $tulovlarUpdated = $tulov->where('tulovturi','Нақд')
                        ->where('shartnomaid',$id)->limit(1)
                        ->update([
                            'tulovturi' => "Нақд",
                            'bron_user_id' => Auth::user()->id,
                            'bron_kun' => date('Y-m-d H:i:s'),
                            'bron_xis_oyi' => $xis_oyi,
                        ]);

                        $naqdsavdo = new naqdsavdo1($request->filial);
                        $naqdsavdoUpdated = $naqdsavdo->where('id', $id)
                        ->limit(1)
                        ->update([
                            'status' => 'Удалит',
                            'user_id' => Auth::user()->id,
                        ]);

                        if ($savdoUpdated && $tulovlarUpdated && $naqdsavdoUpdated ) {
                            DB::commit();
                            return response()->json(['message' => "Нақд савдо ўчирилди. Тўлов суммаси бронга олинди."]);
                        } else {
                            DB::rollBack();
                            return response()->json(['message' => "Нақд савдони ўчиришда хатолик.2"]);
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return response()->json(['message' => "Нақд савдони ўчиришда хатолик.2"]);
                        // throw $e;
                    }

                }

            } else {
                return response()->json(['message' => "Маълумот етарли эмас."]);
            }

        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
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
