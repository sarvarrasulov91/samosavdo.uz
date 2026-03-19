<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\NaqdSavdo;
use App\Models\Tulovlar;
use App\Models\Savdo;
use App\Models\KirimTovar;
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
        $savdoId = $request->savdo_id;
        $filialId = $request->filial_id;
        $id = $request->id;

        $savdomodel = Savdo::where('status', 'Нақд')
            ->where('unix_id', $savdoId)
            ->where('filial_id', $filialId)
            ->where('shartnoma_id', $id)
            ->get();

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
                    <td style='white-space: pre-wrap'>" . $savdomode->tmodel->full_name . "</td>
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
    public function show(Request $request, string $id)
    {
        $filial = $id;
        $boshkun = $request->boshkun;
        $yakunkun = $request->yakunkun;

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

                $naqdsavdojami = NaqdSavdo::where('status', 'Актив')
                    ->whereBetween('kun', [$boshkun, $yakunkun])
                    ->where('filial_id', $filial)
                    ->orderBy('id', 'desc')
                    ->get();

                foreach ($naqdsavdojami as $naqdsavdojam){

                    $id = $naqdsavdojam->id;
                    $savdoid = $naqdsavdojam->savdoraqami_id;

                    $savdosumma = Savdo::where('status', 'Нақд')
                        ->where('unix_id', $savdoid)
                        ->where('shartnoma_id', $id)
                        ->where('filial_id', $filial)
                        ->sum('msumma');

                    $jnaqd = 0;
                    $jplastik = 0;
                    $jhr = 0;
                    $jClick = 0;
                    $jchegirma = 0;

                    $tulovlar = Tulovlar::where('tulovturi', 'Нақд')
                        ->where('filial_id', $filial)
                        ->where('shartnoma_id', $id)
                        ->where('status', 'Актив')
                        ->get();

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
                        <td style="white-space: pre-wrap">' . $naqdsavdojam->mijozlar->full_name . '</td>
                        <td>' . $naqdsavdojam->savdoraqami_id . '</td>
                        <td>' . number_format($savdosumma, 0, ",", " ") . '</td>
                        <td>' . number_format($jnaqd, 0, ",", " ") . '</td>
                        <td>' . number_format($jplastik, 0, ",", " ") . '</td>
                        <td>' . number_format($jchegirma, 0, ",", " ") . '</td>
                        <td>' . number_format($jnaqd + $jplastik + $jhr + $jClick + $jchegirma, 2, ",", " ") . '
                        </td>
                        <td>' . number_format(($jnaqd + $jplastik + $jhr + $jClick + $jchegirma) -$savdosumma, 0, ",", " ") . '
                        </td>
                        <td>
                            <button id="kivitpechat"
                                data-id="' . $naqdsavdojam->id .'"
                                data-savdoid="' . $naqdsavdojam->savdoraqami_id .'"
                                data-fio="' . $naqdsavdojam->mijozlar->full_name .'"
                                class="btn btn-outline-primary btn-sm me-2 "
                                data-bs-toggle="modal"
                                data-bs-target="#pechat">
                                <i class="flaticon-381-search-1"></i>
                            </button>

                            <button id="tovarudalit"
                                data-id="' . $naqdsavdojam->id .'"
                                data-savdoid="' . $naqdsavdojam->savdoraqami_id .'"
                                class="btn btn-outline-danger btn-sm me-2">
                                <i class="flaticon-381-trash-1"></i>
                            </button>
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

                $Counttovar1 = KirimTovar::where('status', 'Нақд')
                    ->where('shatnomaid', $request->id)
                    ->wwhere('filial_id', $request->filial)
                    ->count();

                if ($Counttovar1 > 0) {

                    $ReadKt = KirimTovar::where('status', 'Нақд')
                        ->where('shatnomaid', $request->id)
                        ->where('filial_id', $request->filial)
                        ->get();

                    foreach ($ReadKt as $ReadKtovar) {

                        if ($xis_oyi == $ReadKtovar->ch_xis_oyi) {

                            KirimTovar::where('status', 'Нақд')
                                ->where('shatnomaid', $request->id)
                                ->where('filial_id', $request->filial)
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

                            // ❗ Max soni olish - tez ishlaydi
                            $soninar = KirimTovar::where('tmodel_id', $ReadKt->tmodel_id)
                                ->where('filial_id', $request->filial)
                                ->max('soni') ?? 0;

                            $soninar++;

                            // ✔ Shtrix kod generatsiyasi (eng optimal)
                            $shtr_kod = $this->makeBarcode(
                                $request->filial,
                                $ReadKtovar->tur_id,
                                $ReadKtovar->brend_id,
                                $ReadKtovar->tmodel_id,
                                $soninar
                            );

                            try {
                                DB::beginTransaction();

                                $ktovarzapis = new KirimTovar;
                                $ktovarzapis->kun = date('Y-m-d');
                                $ktovarzapis->filial_id = $request->filial;
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
                                $ktovarzapis->xis_oyi = $xis_oyi;
                                $ktovarzapis->user_id = Auth::user()->id;
                                $ktovarzapis->save();
                                $insid = $ktovarzapis->id;

                                $CreateTqaytarish = new tmqaytarish;
                                $CreateTqaytarish->savdo_turi = $ReadKtovar->status;
                                $CreateTqaytarish->filial_id = $request->filial;
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

                            $savdoUpdated = Savdo::where('unix_id', $request->savdoid)
                                ->where('status','Нақд')
                                ->where('filial_id', $request->filial)
                                ->update([
                                    'status' => "Удалит",
                                    'del_user_id' => Auth::user()->id,
                                    'del_kun' => now(),
                                    'del_xis_oyi' => $xis_oyi,
                                ]);


                            $tulovlarUpdated = Tulovlar::where('tulovturi','Нақд')
                                ->where('filial_id', $request->filial)
                                ->where('shartnomaid',$id)->limit(1)
                                ->update([
                                    'tulovturi' => "Брон",
                                    'bron_user_id' => Auth::user()->id,
                                    'bron_kun' => now(),
                                    'bron_xis_oyi' => $xis_oyi,
                                ]);


                            $naqdsavdoUpdated = NaqdSavdo::where('id', $id)
                                ->where('filial_id', $request->filial)
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

                        $savdoUpdated = Savdo::where('unix_id', $request->savdoid)
                            ->where('status','Нақд')
                            ->where('filial_id', $request->filial)
                            ->update([
                                'status' => "Удалит",
                                'del_user_id' => Auth::user()->id,
                                'del_kun' => now(),
                                'del_xis_oyi' => $xis_oyi,
                            ]);

                        $tulovlarUpdated = Tulovlar::where('tulovturi','Нақд')
                            ->where('shartnomaid',$id)
                            ->where('filial_id', $request->filial)
                            ->limit(1)
                            ->update([
                                'tulovturi' => "Нақд",
                                'bron_user_id' => Auth::user()->id,
                                'bron_kun' => date('Y-m-d H:i:s'),
                                'bron_xis_oyi' => $xis_oyi,
                            ]);

                        $naqdsavdoUpdated = NaqdSavdo::where('id', $id)
                            ->where('filial_id', $request->filial)
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

    private function makeBarcode($filial, $tur, $brend, $model, $number)
    {
        return
            str_pad($filial, 2, "0", STR_PAD_LEFT) .
            str_pad($tur, 4, "0", STR_PAD_LEFT) .
            str_pad($brend, 4, "0", STR_PAD_LEFT) .
            str_pad($model, 5, "0", STR_PAD_LEFT) .
            str_pad($number, 4, "0", STR_PAD_LEFT);
    }
}
