<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\shartnoma1;
use App\Models\tulovlar1;
use App\Models\savdo1;
use App\Models\ktovar1;
use App\Models\savdobonus1;
use App\Models\tmodel;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;



class BonusSavdoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        return view('bonus.bonussavdo', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi]);
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

                    $shartnoma = shartnoma1::where('status', 'Актив')->orderBy('id', 'desc')->get();
                    $shjamish = 0;
                    $bjamish = 0;
                    $tovarjami = 0;
                    $tulovjami = 0;
                    $chegirmajami = 0;
                    foreach ($shartnoma as $shartnom){

                        $savdosumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');
                        $bonussumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('bonus');
                        $bonustulov = tulovlar1::where('tulovturi', 'Бонус')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('umumiysumma');
                        $bonuschegirma = tulovlar1::where('tulovturi', 'Бонус')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('chegirma');
                        $tovarsumma = savdobonus1::where('status', 'Актив')->where('shartnoma_id', $shartnom->id)->sum('msumma');

                        $trrang="";
                        if(($bonussumma+$bonustulov+$bonuschegirma-$tovarsumma)<0){
                            $trrang="align-middle text-danger";
                        }

                        echo'
                        <tr id="modalbonusshow" data-id="'.$shartnom->id.'" data-fio="'.addslashes($shartnom->mijozlar->last_name) . ' ' . addslashes($shartnom->mijozlar->first_name) . ' ' . addslashes($shartnom->mijozlar->middle_name).'"  class="'.$trrang.'">
                            <td>' . $shartnom->id . '</td>
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

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

        if($request->status=='tqushish'){
            if(!empty($request->krimt) && !empty($request->id) && !empty($request->status) ){
                $modelsumma=0;
                $ktovart = ktovar1::where('shtrix_kod', $request->krimt)->where('status', 'Сотилмаган')->get();
                foreach ($ktovart as $ktovar){
                    $modelsumma=round(($ktovar->snarhi * $ktovar->valyuta->valyuta_narhi * $ktovar->brend->natsenka->natsen_miqdori) / 100 + ($ktovar->snarhi * $ktovar->valyuta->valyuta_narhi), -3);

                    $chegirma=0;
                    $tmodel = tmodel::where('id', $request->model_id)->get();
                    foreach ($tmodel as $chegirm) {
                        $chegirma=$chegirm->aksiya/100;
                    }

                    if($chegirma>0){
                        $chegirmamiqdor=round(($modelsumma*$chegirma),-3);
                    }else{
                        $chegirmamiqdor=0;
                    }


                    try {
                        DB::beginTransaction();

                        $zaqis = new savdobonus1;
                        $zaqis->kun = date('Y-m-d');
                        $zaqis->tur_id = $ktovar->tur_id;
                        $zaqis->brend_id = $ktovar->brend_id;
                        $zaqis->tmodel_id = $ktovar->tmodel_id;
                        $zaqis->shartnoma_id = $request->id;
                        $zaqis->sotuvnarhi = $modelsumma;
                        $zaqis->msumma = $modelsumma-$chegirmamiqdor;
                        $zaqis->chegirma = $chegirmamiqdor;
                        $zaqis->xis_oyi = $xis_oyi;
                        $zaqis->user_id = Auth::user()->id;
                        $zaqis->shtrix_kod = $ktovar->shtrix_kod;
                        $zaqis->save();

                        $ktovarUpdated = ktovar1::where('status', 'Сотилмаган')
                        ->where('shtrix_kod', $request->krimt)
                        ->limit(1)->
                        update([
                            'status' => 'Бонус',
                            'shatnomaid' => $request->id,
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

                $tulovlar = new tulovlar1;
                $tulovlar->kun = date('Y-m-d');
                $tulovlar->tulovturi = 'Бонус';
                $tulovlar->shartnomaid = $request->id;
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

                $checktulovlar1 = tulovlar1::find($savedFondId);
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
    public function show(string $id)
    {

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

                    $shartnoma = shartnoma1::where('id', $id)->get();
                    foreach ($shartnoma as $shartnom){

                        $savdosumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');
                        $bonussumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('bonus');
                        $bonustulov = tulovlar1::where('tulovturi', 'Бонус')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('umumiysumma');
                        $bonuschegirma = tulovlar1::where('tulovturi', 'Бонус')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('chegirma');
                        $tovarsumma = savdobonus1::where('status', 'Актив')->where('shartnoma_id', $shartnom->id)->sum('msumma');
                        $trrang="";
                        if(($bonussumma+$bonustulov+$bonuschegirma-$tovarsumma)<0){
                            $trrang="align-middle text-danger";
                        }

                        echo'
                        <tr id="modalbonusshow" data-id="'.$shartnom->id.'" data-fio="'.addslashes($shartnom->mijozlar->last_name) . ' ' . addslashes($shartnom->mijozlar->first_name) . ' ' . addslashes($shartnom->mijozlar->middle_name).'"  class="'.$trrang.'" data-bs-toggle="modal"
                            data-bs-target="#shartnoma_show">
                            <td>' . $shartnom->id . '</td>
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
                   }
                    echo'
                </tbody>
            </table>
            <br>
            ';

             $savdomodel = savdobonus1::where('status', 'Актив')->where('shartnoma_id', $id)->get();
            echo '
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
                            <button id="tovar_qushish" data-shid="'. $shartnom->id .'" type="button" class="btn btn-outline-primary btn-sm ms-2"><i class="flaticon-381-plus"></i></button>
                       </th>
                   </tr>
               </thead>
               <tbody id="tab1">';
            $jami = 0;
            $i = 1;
            foreach ($savdomodel as $savdomode) {
                echo "
                   <tr class='text-center align-middle'>
                       <td>" . $i . "</td>
                       <td>" . $savdomode->tmodel_id . "</td>
                       <td>" . date('d.m.Y', strtotime($savdomode->created_at)) . "</td>
                       <td>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                       <td>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                       <td>" . $savdomode->shtrix_kod . "</td>
                       <td> <button id='tovar_uchirish' data-id='".$shartnom->id."' data-shtrix_kod='".$savdomode->shtrix_kod."' type='button' class='btn btn-outline-danger btn-sm ms-2'><i class='flaticon-381-substract-1'></i></button> </td>
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
                        $tulovlarshj = tulovlar1::where('tulovturi', 'Бонус')->where('shartnomaid', $shartnom->id)->orderBy('id', 'desc')->get();
                        $i = 1;
                        $jnaqd = 0;
                        $jpastik = 0;
                        $jhr = 0;
                        $jclick = 0;
                        $chegirma = 0;
                        $colorqator = " ";

                        foreach ($tulovlarshj as $tulovlarsh) {

                            if($tulovlarsh->status=='Актив' && $tulovlarsh->tulovturi=='Бонус'){
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
                                            <td> <button id='tulov_uchrish' data-tulovid='".$tulovlarsh->id."' data-id='".$shartnom->id."' type='button' class='btn btn-outline-danger btn-sm ms-2'><i class='flaticon-381-substract-1'></i></button> </td>
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
        $xis_oyi = xissobotoy::value('xis_oy');

        if(!empty($request->id) && !empty($request->status && $request->status=="tuchirish") ){

            $bor_tovar_exists = ktovar1::where('shtrix_kod', $request->krimt)
            ->where('status', 'Бонус')
            ->where('shatnomaid', $request->id)
            ->exists();

            if ($bor_tovar_exists) {

                try {
                    DB::beginTransaction();

                    $KtovarUpdated = ktovar1::where('shtrix_kod', $request->krimt)
                    ->where('status', 'Бонус')
                    ->where('shatnomaid', $request->id)
                    ->update([
                        'status' => "Сотилмаган",
                        'ch_kun' => null,
                        'ch_user_id' => 0,
                        'ch_xis_oyi' => null,
                        'shatnomaid' => 0,
                    ]);

                    $SavdobonusUpdated = savdobonus1::where('shartnoma_id', $request->id)
                    ->where('shtrix_kod', $request->krimt)
                    ->where('status', 'Актив')
                    ->update([
                        'status' => "Удалит",
                        'del_kun' => date('Y-m-d'),
                        'del_user_id' => Auth::user()->id,
                    ]);

                    if ($KtovarUpdated && $SavdobonusUpdated) {
                        DB::commit();
                        $message=$request->krimt . "<br> Товар ўчирилди.";
                    } else {
                        DB::rollBack();
                        $message="Товарни ўчиришда хатолик.";
                    }

                } catch (\Exception $e) {
                    DB::rollBack();
                    $message="Маълумот ўчиришда хатолик.";
                    // throw $e;
                }

            } else {
                $message=$request->krimt . "<br> Хатолик!!! Маълумот етарли эмас.";
            }

            return response()->json(['message' => $message]);
        }

        if(!empty($request->id) && !empty($request->tulovid) ){
            $shid=0;
            $tulovlarfind = tulovlar1::where('id', $request->tulovid)->where('tulovturi', 'Бонус')->first();
            $shid = $tulovlarfind->shartnomaid;
            $TulovKun=date("d-m-Y", strtotime($tulovlarfind->created_at));
            $BugungiKun = date("d-m-Y");
            if($shid>0){
                if($TulovKun==$BugungiKun){
                    $tulovlar = tulovlar1::where('tulovturi','Бонус')
                    ->where('id',$request->tulovid)
                    ->where('shartnomaid',$id)
                    ->limit(1)
                    ->update([
                        'status' => 'Удалит',
                        'del_user_id' => Auth::user()->id,
                        'del_kun' => date("Y-m-d"),
                    ]);
                    return response()->json(['message' => 'Тўлов ўчирилди.'], 200);
                }else{
                    $tulovlar = tulovlar1::where('tulovturi','Бонус')
                    ->where('id',$request->tulovid)
                    ->where('shartnomaid',$id)
                    ->limit(1)
                    ->update([
                        'tulovturi' => 'Брон',
                        'bron_kun' => date('Y-m-d H:i:s'),
                        'bron_xis_oyi' => $xis_oyi,
                        'bron_user_id' => Auth::user()->id,
                    ]);
                    return response()->json(['message' => 'Тўлов бронга олинди.'], 200);
                }
            }else{
                return response()->json(['message' => 'Хатолик: '.$id.' ИД даги тўлов топилмади.'], 200);
            }

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
