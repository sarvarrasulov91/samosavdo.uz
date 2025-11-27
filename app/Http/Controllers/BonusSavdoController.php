<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Shartnoma;
use App\Models\Tulovlar;
use App\Models\Savdo;
use App\Models\KirimTovar;
use App\Models\BonusSavdo;
use App\Models\tmodel;
use App\Models\xissobotoy;


class BonusSavdoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bonus.bonussavdo');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        echo'
            <table class="table table-bordered text-center align-middle table-hover"
                style="font-size: 14px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Шартнома<br>санаси</th>
                        <th>Шартнома<br> суммаси</th>
                        <th>Бонус<br>суммаси</th>
                        <th>Товар<br>суммаси</th>
                        <th>Тўлов<br>суммаси</th>
                        <th>Чегирма</th>
                        <th>Фарқи</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                    $shartnoma = Shartnoma::where('filial_id', Auth::user()->filial_id)
                        ->where('status', 'Актив')
                        ->where('xis_oyi', date('Y-m-01'))
                        ->orderBy('id', 'desc')
                        ->get();

                    $shjamish = 0;
                    $bjamish = 0;
                    $tovarjami = 0;
                    $tulovjami = 0;
                    $chegirmajami = 0;

                    foreach ($shartnoma as $shartnom){

                        $savdoSumma = Savdo::where('filial_id', Auth::user()->filial_id)
                            ->where('status', 'Шартнома')
                            ->where('shartnoma_id', $shartnom->id)
                            ->get();

                        $savdosumma = $savdoSumma->sum('msumma');
                        $bonussumma = $savdoSumma->sum('bonus');

                        $bonusTulov = Tulovlar::where('tulovturi', 'Бонус')
                            ->where('status', 'Актив')
                            ->where('filial_id', Auth::user()->filial_id)
                            ->where('shartnoma_id', $shartnom->id)
                            ->get();

                        $bonustulov = $bonusTulov->sum('umumiysumma');
                        $bonuschegirma = $bonusTulov->sum('chegirma');

                        $tovarsumma = BonusSavdo::where('status', 'Актив')
                            ->where('shartnoma_id', $shartnom->id)
                            ->where('filial_id', Auth::user()->filial_id)
                            ->sum('msumma');

                        $trrang="";
                        if(($bonussumma+$bonustulov+$bonuschegirma-$tovarsumma)<0){
                            $trrang="align-middle text-danger";
                        }

                        echo'
                        <tr id="modalbonusshow" data-id="'.$shartnom->id.'" data-shid="'.$shartnom->shid.'" data-fio="'.addslashes($shartnom->mijozlar->last_name) . ' ' . addslashes($shartnom->mijozlar->first_name) . ' ' . addslashes($shartnom->mijozlar->middle_name).'"  class="'.$trrang.'">
                            <td>' . $shartnom->shid . '</td>
                            <td>' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . '<br>' . $shartnom->mijozlar->middle_name . '
                            </td>
                            <td>' . date('d.m.Y', strtotime($shartnom->kun)) . '</td>
                            <td>' . number_format($savdosumma, 2, ',', ' ') . '</td>
                            <td>' . number_format($bonussumma, 2, ',', ' ') . '</td>
                            <td>' . number_format($tovarsumma, 2, ',', ' ') . '</td>
                            <td>' . number_format($bonustulov, 2, ',', ' ') . '</td>
                            <td>' . number_format($bonuschegirma, 2, ',', ' ') . '</td>
                            <td>' . number_format($bonussumma+$bonustulov+$bonuschegirma-$tovarsumma, 2, ',', ' ') . '</td>

                        </tr>';
                            $shjamish += $savdosumma;
                            $bjamish += $bonussumma;
                            $tovarjami += $tovarsumma;
                            $tulovjami += $bonustulov;
                            $chegirmajami += $bonuschegirma;

                    }
                    echo'
                    <tr class="align-middle text-bold">
                        <td></td>
                        <td>Жами</td>
                        <td></td>
                        <td>' . number_format($shjamish, 2, ',', ' ') . '</td>
                        <td>' . number_format($bjamish, 2, ',', ' ') . '</td>
                        <td>' . number_format($tovarjami, 2, ',', ' ') . '</td>
                        <td>' . number_format($tulovjami, 2, ',', ' ') . '</td>
                        <td>' . number_format($chegirmajami, 2, ',', ' ') . '</td>
                        <td>' . number_format($bjamish+$tulovjami+$chegirmajami-$tovarjami, 2, ',', ' ') . '</td>
                    </tr>
                </tbody>
            </table>';

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $shid = $request->shid;
        $barkod = $request->krimt;
        $status = $request->status;

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

        if($status == 't_qushish'){

            if(!empty($barkod) && !empty($id) && !empty($status) ){

                $ktovar = KirimTovar::where('shtrix_kod', $barkod)
                    ->where('filial_id', Auth::user()->filial_id)
                    ->where('status', 'Сотилмаган')
                    ->first();

                if ($ktovar){

                    $modelsumma = round(($ktovar->snarhi * $ktovar->valyuta->valyuta_narhi * ($ktovar->tur->natsenka->natsen_miqdori + $ktovar->tur->transport->tars_har + 100) / 100), -3);

                    $tmodel = tmodel::where('id', $ktovar->tmodel_id)->first();

                    $chegirma = $tmodel->aksiya / 100;

                    if($chegirma > 0){
                        $chegirmamiqdor = round(($modelsumma * $chegirma),-3);
                    }else{
                        $chegirmamiqdor = 0;
                    }


                    try {
                        DB::beginTransaction();

                        $zaqis = new BonusSavdo;
                        $zaqis->kun = date('Y-m-d');
                        $zaqis->filial_id = Auth::user()->filial_id;
                        $zaqis->shid = $shid;
                        $zaqis->tur_id = $ktovar->tur_id;
                        $zaqis->brend_id = $ktovar->brend_id;
                        $zaqis->tmodel_id = $ktovar->tmodel_id;
                        $zaqis->shartnoma_id = $id;
                        $zaqis->sotuvnarhi = $modelsumma;
                        $zaqis->msumma = $modelsumma-$chegirmamiqdor;
                        $zaqis->chegirma = $chegirmamiqdor;
                        $zaqis->xis_oyi = $xis_oyi;
                        $zaqis->user_id = Auth::user()->id;
                        $zaqis->shtrix_kod = $barkod;
                        $zaqis->save();

                        $ktovarUpdated = KirimTovar::where('status', 'Сотилмаган')
                            ->where('shtrix_kod', $barkod)
                            ->where('filial_id', Auth::user()->filial_id)
                            ->limit(1)->
                            update([
                                'status' => 'Бонус',
                                'shartnoma_id' => $id,
                                'shid' => $shid,
                                'ch_kun' => date('Y-m-d'),
                                'ch_xis_oyi' => $xis_oyi,
                                'ch_user_id' => Auth::user()->id,
                            ]);

                        if ($zaqis && $ktovarUpdated) {
                            DB::commit();
                            $message="Товар қўшилди.";
                        } else {
                            DB::rollBack();
                            $message="Товар қўшишда хатолик.";
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $message="Хатолик.";
                        // throw $e;
                    }
                    return response()->json(['message' => $message], 200);
                }
                    return response()->json(['message' => $request->krimt . "<br> Хатолик!!! товар топилмади."], 200);
            }else{
                return response()->json(['message' => $request->krimt . "<br> Хатолик!!! Маълумот етарли эмас."], 200);
            }
        }else{

                $naqd = floatval(preg_replace('/[^\d.]/', '', $request->naqd));
                $plastik = floatval(preg_replace('/[^\d.]/', '', $request->plastik));
                $hr = floatval(preg_replace('/[^\d.]/', '', $request->hr));
                $click = floatval(preg_replace('/[^\d.]/', '', $request->click));
                $chegirma = floatval(preg_replace('/[^\d.]/', '', $request->chegirma));

                $tulovlar = new Tulovlar;
                $tulovlar->kun = date('Y-m-d');
                $tulovlar->tulovturi = 'Бонус';
                $tulovlar->filial_id = Auth::user()->filial_id;
                $tulovlar->shartnoma_id = $id;
                $tulovlar->shid = $shid;
                $tulovlar->xis_oyi = $xis_oyi;
                $tulovlar->naqd =  $naqd;
                $tulovlar->pastik =  $plastik;
                $tulovlar->hr =  $hr;
                $tulovlar->click =  $click;
                $tulovlar->chegirma =  $chegirma;
                $tulovlar->umumiysumma =  ($naqd + $plastik+$hr + $click );
                $tulovlar->user_id = Auth::user()->id;
                $tulovlar->save();
                $savedFondId = $tulovlar->id;

                $checktulovlar1 = Tulovlar::find($savedFondId);
                if ($checktulovlar1) {
                    return response()->json(['message' => 'Тўлов қўшилди. Фонд ID: ' . $savedFondId], 200);
                } else {
                    return response()->json(['message' => 'Хатолик юз берди, маълумот сақланмади.'], 500);
                }

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $shid = $request->shid;

        echo'
            <h3 class=" text-center text-primary"><b> ШАРТНОМА </b></h3>
            <table class="table table-bordered text-center align-middle table-hover"
                style="font-size: 14px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Шартнома<br>санаси</th>
                        <th>Шартнома<br> суммаси</th>
                        <th>Бонус<br>суммаси</th>
                        <th>Товар<br>суммаси</th>
                        <th>Тўлов<br>суммаси</th>
                        <th>Чегирма</th>
                        <th>Фарқи</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                    $shartnom = Shartnoma::where('id', $id)->where('shid', $shid)->where('filial_id', Auth::user()->filial_id)->first();

                    $savdoSumma = Savdo::where('status', 'Шартнома')
                        ->where('shartnoma_id', $shartnom->id)
                        ->where('shid', $shid)
                        ->where('filial_id', Auth::user()->filial_id)
                        ->get();

                    $savdosumma = $savdoSumma->sum('msumma');
                    $bonussumma = $savdoSumma->sum('bonus');

                    $bonusTulov = Tulovlar::where('tulovturi', 'Бонус')
                        ->where('status', 'Актив')
                        ->where('shartnoma_id', $shartnom->id)
                        ->where('shid', $shid)
                        ->where('filial_id', Auth::user()->filial_id)
                        ->get();

                    $bonustulov = $bonusTulov->sum('umumiysumma');
                    $bonuschegirma = $bonusTulov->sum('chegirma');

                    $tovarsumma = BonusSavdo::where('status', 'Актив')->where('shartnoma_id', $id)->where('shid', $shid)->sum('msumma');

                    $trrang="";

                    if(($bonussumma+$bonustulov+$bonuschegirma-$tovarsumma)<0){
                        $trrang="align-middle text-danger";
                    }

                    echo'
                    <tr class="'.$trrang.'">
                        <td>' . $shartnom->shid . '</td>
                        <td>' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name . '
                        </td>
                        <td>' . date('d.m.Y', strtotime($shartnom->kun)) . '</td>
                        <td>' . number_format($savdosumma, 2, ',', ' ') . '</td>
                        <td>' . number_format($bonussumma, 2, ',', ' ') . '</td>
                        <td>' . number_format($tovarsumma, 2, ',', ' ') . '</td>
                        <td>' . number_format($bonustulov, 2, ',', ' ') . '</td>
                        <td>' . number_format($bonuschegirma, 2, ',', ' ') . '</td>
                        <td>' . number_format($bonussumma+$bonustulov+$bonuschegirma-$tovarsumma, 2, ',', ' ') . '</td>
                    </tr>';

                    echo'
                </tbody>
            </table>

            <br>

           <h4 class=" text-center text-primary"><b> БОНУС УЧУН ТАНЛАНГАН ТОВАРЛАР </b></h4>
           <table class="table table-bordered table-hover">
               <thead>
                   <tr class="text-center text-bold text-primary align-middle">
                       <th>№</th>
                       <th>Модел ID</th>
                       <th>Куни</th>
                       <th>Товар номи</th>
                       <th>Суммаси</th>
                       <th>Штрих коди</th>
                       <th>
                            <button
                                id="tovar_qushish"
                                data-id="'. $id .'"
                                data-shid="'. $shid .'"
                                type="button"
                                class="btn btn-outline-primary btn-sm ms-2">
                                <i class="flaticon-381-plus"></i>
                            </button>
                       </th>
                   </tr>
               </thead>
               <tbody id="tab1">';
            $jami = 0;
            $i = 1;
            $savdomodel = BonusSavdo::where('status', 'Актив')->where('shartnoma_id', $id)->where('shid', $shid)->where('filial_id', Auth::user()->filial_id)->get();
            foreach ($savdomodel as $savdomode) {
                echo "
                   <tr class='text-center align-middle'>
                       <td>" . $i . "</td>
                       <td>" . $savdomode->tmodel_id . "</td>
                       <td>" . date('d.m.Y', strtotime($savdomode->created_at)) . "</td>
                       <td>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                       <td>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                       <td>" . $savdomode->shtrix_kod . "</td>
                       <td>
                        <button
                            id='tovar_uchirish'
                            data-id='".$id."'
                            data-shid='".$shid."'
                            data-shtrix_kod='".$savdomode->shtrix_kod."'
                            type='button'
                            class='btn btn-outline-danger btn-sm ms-2'>
                            <i class='flaticon-381-substract-1'></i>
                            </button>
                        </td>
                    </tr>";
                $jami += $savdomode->msumma;
                $i++;
            }
            echo '
                   <tr class="text-center align-middle fw-bold">
                        <td></td>
                        <td>ЖАМИ</td>
                        <td></td>
                        <td></td>
                        <td>' . number_format($jami, 0, ",", " ") . '</td>
                        <td></td>
                        <td></td>
                   </tr>
               </tbody>
           </table>
            <br>
           ';

        echo'
            <h4 class=" text-center text-primary"><b> БОНУС ФАРҚИ УЧУН ТЎЛАНГАН ТЎЛОВЛАР </b></h4>
                <table class="table table-hover table-bordered text-center text-muted">
                  	<thead>
                 	   <tr class="text-primary">
            	   	  		<th>№</th>
                            <th>Номи</th>
            		   		<th>Куни</th>
            		   		<th>Нақд</th>
            		   		<th>Платик</th>
                            <th>Х-р</th>
                            <th>Клик</th>
            		   		<th>Жами</th>
                            <th>Чегирма</th>
                            <th>Холати</th>
                            <th>

                            </th>
                    	</tr>
                	</thead>
                  	<tbody id="tab1">';

                        $tulovlarshj = Tulovlar::where('tulovturi', 'Бонус')
                            ->where('shartnoma_id', $shartnom->id)
                            ->where('filial_id', Auth::user()->filial_id)
                            ->orderBy('id', 'desc')
                            ->get();

                        $i = 1;
                        $jnaqd = $jpastik = $jhr = $jclick = $chegirma = 0;

                        foreach ($tulovlarshj as $tulovlarsh) {

                            if($tulovlarsh->status == 'Актив' && $tulovlarsh->tulovturi == 'Бонус'){
                                $colorqator = " ";
                                $jnaqd += $tulovlarsh->naqd;
                                $jpastik += $tulovlarsh->pastik;
                                $jhr += $tulovlarsh->hr;
                                $jclick += $tulovlarsh->click;
                                $chegirma += $tulovlarsh->chegirma;
                            }else{
                                $colorqator = "text-danger";
                            }

                            echo "
                                        <tr class='text-center align-middle $colorqator'>
                                            <td>" . $i . "</td>
                                            <td>" . $tulovlarsh->tulovturi . "</td>
                                            <td>" . date('d.m.Y', strtotime($tulovlarsh->kun)) . "</td>
                                            <td>" . number_format($tulovlarsh->naqd, 0, ',', ' ') . "</td>
                                            <td>" . number_format($tulovlarsh->pastik, 0, ',', ' ') . "</td>
                                            <td>" . number_format($tulovlarsh->hr, 0, ',', ' ') . "</td>
                                            <td>" . number_format($tulovlarsh->click, 0, ',', ' ') . "</td>
                                            <td>" . number_format($tulovlarsh->naqd + $tulovlarsh->pastik+$tulovlarsh->hr+$tulovlarsh->click+$tulovlarsh->avtot, 0, ',', ' ') . "</td>
                                            <td>" . number_format($tulovlarsh->chegirma, 0, ',', ' ') . "</td>
                                            <td>" . $tulovlarsh->status . "</td>
                                            <td>
                                                <button
                                                    id='tulov_uchrish'
                                                    data-tulovid='".$tulovlarsh->id."'
                                                    data-id='".$shartnom->id."'
                                                    data-shid='".$shartnom->shid."'
                                                    type='button'
                                                    class='btn btn-outline-danger btn-sm ms-2'>
                                                    <i class='flaticon-381-substract-1'></i>
                                                    </button>
                                                </td>
                                        </tr>";
                            $i++;
                        }
                        echo '
                        <tr class="text-center align-middle fw-bold">
                            <td></td>
                            <td>ЖАМИ</td>
                            <td></td>
                            <td>' . number_format($jnaqd, 0, ",", " ") . '</td>
                            <td>' . number_format($jpastik, 0, ",", " ") . '</td>
                            <td>' . number_format($jhr, 0, ",", " ") . '</td>
                            <td>' . number_format($jclick, 0, ",", " ") . '</td>
                            <td>' . number_format($jnaqd+$jpastik+$jhr+$jclick, 0, ",", " ") . '</td>
                            <td class="text-danger">' . number_format($chegirma, 0, ",", " ") . '</td>
                            <td></td>
                            <td></td>
                        </tr>
                   	</tbody>
                </table>
                     <br>
                    <h4 class=" text-center text-primary"><b> БОНУС ФАРҚИ УЧУН ЯНГИ ТЎЛОВ ҚЎШИШ </b></h4>
                    <form method="POST" id="add_tulov">
                            <input type="text" name="id" id="id" class="form-control form-control-sm text-center" value="'.$shartnom->id.'" readonly hidden required>
                            <input type="text" name="shid" id="shid" class="form-control form-control-sm text-center" value="'.$shartnom->shid.'" readonly hidden required>
                            <table class="table table-hover text-center text-muted">
                                <tr class="text-center align-middle fw-bold">
                                    <td>
                                        <input type="text" name="naqd" id="naqd" class="form-control form-control-sm text-center"
                                            placeholder="Накд..." maxlength="11" required>
                                    </td>
                                    <td>
                                        <input type="text" name="plastik" id="plastik" class="form-control form-control-sm text-center"
                                                placeholder="Пластик..." maxlength="11" required>
                                    </td>
                                    <td>
                                        <input type="text" name="hr" id="hr" class="form-control form-control-sm text-center"
                                                placeholder="Хисоб-рақам..." maxlength="11" required>
                                    </td>
                                    <td>
                                        <input type="text" name="click" id="click" class="form-control form-control-sm text-center"
                                            placeholder="click..." maxlength="11" required>
                                    </td>
                                    <td>
                                        <input type="text" name="chegirma" id="chegirma" class="form-control form-control-sm text-center"
                                            placeholder="Чегирма..." maxlength="11" required>
                                    </td>
                                    <td>
                                        <div >
                                            <button id="addtulov" type="button" class="btn btn-outline-primary btn-sm ms-2"><i class="flaticon-381-plus"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    ';
        return ;
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

        if(!empty($request->id) && !empty($request->status && $request->status == "tovar-delete") ){

            $bor_tovar_exists = KirimTovar::where('shtrix_kod', $request->krimt)
                ->where('status', 'Бонус')
                ->where('filial_id', Auth::user()->filial_id)
                ->where('shartnoma_id', $request->id)
                ->exists();

            if ($bor_tovar_exists) {

                try {
                    DB::beginTransaction();

                    $KtovarUpdated = KirimTovar::where('shtrix_kod', $request->krimt)
                        ->where('status', 'Бонус')
                        ->where('shartnoma_id', $request->id)
                        ->where('filial_id', Auth::user()->filial_id)
                        ->update([
                            'status' => "Сотилмаган",
                            'ch_kun' => null,
                            'ch_user_id' => 0,
                            'ch_xis_oyi' => null,
                            'shartnoma_id' => 0,
                        ]);

                    $SavdobonusUpdated = BonusSavdo::where('shartnoma_id', $request->id)
                        ->where('shtrix_kod', $request->krimt)
                        ->where('status', 'Актив')
                        ->where('filial_id', Auth::user()->filial_id)
                        ->update([
                            'status' => 'Удалит',
                            'del_kun' => now(),
                            'del_user_id' => Auth::id(),
                        ]);

                    DB::commit();

                    $message = $request->krimt . "<br> Товар ўчирилди.";


                } catch (\Exception $e) {
                    DB::rollBack();
                    $message="Маълумот ўчиришда хатолик.";
                    // throw $e;
                }

            } else {
                $message = $request->krimt . "<br> Хатолик!!! Маълумот етарли эмас.";
            }

            return response()->json(['message' => $message]);
        }

        return response()->json(['message' => 'Xatolik yuz berdi.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        if(!empty($request->id) && !empty($request->tulovid && $request->status == 'tulov_delete') ){

            $tulovlarFind = Tulovlar::where('id', $request->tulovid)
                ->where('filial_id', Auth::user()->filial_id)
                ->where('shid', $request->shid)
                ->where('shartnoma_id', $request->id)
                ->where('tulovturi', 'Бонус')
                ->first();

            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $TulovKun = date("d-m-Y", strtotime($tulovlarFind->created_at));

            $BugungiKun = date("d-m-Y");

            if($tulovlarFind){

                if($TulovKun == $BugungiKun){

                    $tulovlarFind->update([
                        'status' => 'Удалит',
                        'del_user_id' => Auth::user()->id,
                        'del_kun' => date("Y-m-d"),
                    ]);

                    return response()->json(['message' => 'Тўлов ўчирилди.'], 200);

                }else{

                    $tulovlarFind->update([
                        'tulovturi' => 'Брон',
                        'bron_kun' => now(),
                        'bron_xis_oyi' => $xis_oyi,
                        'bron_user_id' => Auth::user()->id,
                    ]);

                    return response()->json(['message' => 'Тўлов бронга олинди.'], 200);
                }

            }else{
                return response()->json(['message' => 'Хатолик: '.$id.' ИД даги тўлов топилмади.'], 200);
            }

        }
        return response()->json(['message' => 'Xatolik yuz berdi.'], 200);
    }
}
