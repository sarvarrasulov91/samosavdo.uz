<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\tulovlar1;
use App\Models\mijozlar;
use App\Models\filial;
use App\Models\shartnoma1;
use App\Models\User;
use App\Models\lavozim;
use App\Models\savdo1;

class OfficeAvtoTulovController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        if((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 12 || Auth::user()->lavozim_id == 14) && Auth::user()->status == 'Актив'){
            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();
        }else{
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }
        return view('kassa.officeavtotulov', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name,'filial' => $filial, 'xis_oyi' => $xis_oyi]);
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
        $boshkun = $request->boshkun;
        $yakunkun = $request->yakunkun;

        echo'
            <table class="table table-bordered table-responsive-sm text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary">
                        <th>ID</th>
                        <th>Куни</th>
                        <th>Мижоз ФИО</th>
                        <th>Тулов тури</th>
                        <th>Шарт-№</th>
                        <th>График</th>
                        <th>Накд</th>
                        <th>Пластик</th>
                        <th>ХР</th>
                        <th>Клик</th>
                        <th>Авто тулов</th>
                        <th>Чегирма</th>
                        <th>Жами</th>
                        <th>Масъул ходим</th>
                        <th>Тулов</th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                    
                    $i=1;
                    $naqd=0;
                    $plastik=0;
                    $hr=0;
                    $click=0;
                    $avtot=0;
                    $chegirma=0;
                    $jami=0;

                    $unaqd=0;
                    $uplastik=0;
                    $uhr=0;
                    $uclick=0;
                    $uavtot=0;
                    $uchegirma=0;
                    $ujami=0;
                    
                    $tulovlar = new tulovlar1($request->filial);
                    $model=$tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->where('tulovturi','!=','Брон')->where('tulovturi','!=','Нақд')->where('tulovturi','!=','Бонус')->orderBy('id', 'desc')->get();
                    foreach ($model as $mode){
                        $shartnoma1 = new shartnoma1($request->filial);
                        $shartnoma=$shartnoma1->where('id', $mode->shartnomaid)->first();

                        $savdo = new savdo1($request->filial);
                        $savdosumma=$savdo->where('status', 'Шартнома')->where('shartnoma_id', $shartnoma->id)->sum('msumma');

                        $oldindantulov = new tulovlar1($request->filial);
                        $oldindantulov = $oldindantulov->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shartnoma->id)->sum('umumiysumma');

                        $otulovchegirma = new tulovlar1($request->filial);
                        $chegirma = $otulovchegirma->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shartnoma->id)->sum('chegirma');
                        


                        $xis_oy = xissobotoy::all();
                        foreach ($xis_oy as $xis_o) {
                            $foiz = $xis_o->foiz;
                        }
                        $foiz = xissobotoy::where('xis_oy', $shartnoma->xis_oyi)->value('foiz');
                        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

                        if($shartnoma->fstatus==0){
                            $foiz=0;
                        }

                        //йиллик фойиз
                        $foiz = (($foiz / 12) * $shartnoma->muddat);
                        
                        if ($shartnoma->kun < "2023-12-05"){
                            $xis_foiz = ((($savdosumma - $oldindantulov - $chegirma) * $foiz) / 100);
                        }else{
                            $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);  
                        }

                        if ($mode->avtot > 0){
                            echo'
                            <tr>
                                <td>' . $i++ . '</td>
                                <td>' . date('d.m.Y', strtotime($mode->kun)) . '</td>
                                <td style="white-space: wrap; width: 14%;">' .$shartnoma->mijozlar->last_name. ' ' .$shartnoma->mijozlar->first_name. ' ' .$shartnoma->mijozlar->middle_name . '</td>
                                <td>' . $mode->tulovturi . '</td>
                                <td>' . $mode->shartnomaid . '</td>
                                <td class="text-primary">' . number_format(($savdosumma-$oldindantulov-$chegirma+$xis_foiz)/$shartnoma->muddat, 0, ',', ' ') . '</td>
                                <td>' . number_format($mode->naqd, 0, ',', ' ') . '</td>
                                <td>' . number_format($mode->pastik, 0, ',', ' ') . '</td>
                                <td>' . number_format($mode->hr, 0, ',', ' ') . '</td>
                                <td>' . number_format($mode->click, 0, ',', ' ') . '</td>
                                <td>' . number_format($mode->avtot, 0, ',', ' ') . '</td>
                                <td>' . number_format($mode->chegirma, 0, ',', ' ') . '</td>
                                <td class="fw-bold">' . number_format($mode->umumiysumma, 0, ',', ' ') . '</td>
                                <td style="white-space: wrap; width: 10%;">' . $mode->User->name . '</td>
                                <td>
                                    <button id="kivitpechat" data-id="' . $shartnoma->id .'" data-fio="' . $shartnoma->mijozlar->last_name . ' ' . $shartnoma->mijozlar->first_name . ' ' . $shartnoma->mijozlar->middle_name .'"
                                    class="btn btn-outline-primary btn-sm me-2 " data-bs-toggle="modal"
                                    data-bs-target="#pechat"><i class="flaticon-381-search-1"></i></button>
                                </td>
                            </tr>
                            ';
                            
                            $naqd+=$mode->naqd;
                            $plastik+=$mode->pastik;
                            $hr+=$mode->hr;
                            $click+=$mode->click;
                            $avtot+=$mode->avtot;
                            $chegirma+=$mode->chegirma;
                            $jami+=$mode->umumiysumma;
                        }
                    }
                    $unaqd+=$naqd;
                    $uplastik+=$plastik;
                    $uhr+=$hr;
                    $uclick+=$click;
                    $uavtot+=$avtot;
                    $uchegirma+=$chegirma;
                    $ujami+=$jami;

                    echo'
                        <tr class="fw-bold">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>' . number_format($unaqd, 0, ',', ' ') . '</td>
                            <td>' . number_format($uplastik, 0, ',', ' ') . '</td>
                            <td>' . number_format($uhr, 0, ',', ' ') . '</td>
                            <td>' . number_format($uclick, 0, ',', ' ') . '</td>
                            <td>' . number_format($uavtot, 0, ',', ' ') . '</td>
                            <td>' . number_format($uchegirma, 0, ',', ' ') . '</td>
                            <td>' . number_format($ujami, 0, ',', ' ') . '</td>
                            <td></td>
                            <td></td>
                        </tr>
                        ';
                
                    echo'
                </tbody>
            </table>
        ';
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
        // Shartnoma uchun tulangan tulovlarni korish
        echo' 
        <h5 class=" text-center text-uppercase" style="color: RoyalBlue;">Шартнома учун тўланган тўловлар</h5>
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
                        <th>Авто</th>
                        <th>Чегирма</th>
                        <th>Жами</th>
                        <th>Холати</th>
                    </tr>
                </thead>
                <tbody id="tab1">';
                
            $tulovlar1=new tulovlar1($request->filial);
            $tulovlarshj = $tulovlar1->where('tulovturi', 'Шартнома')->where('shartnomaid', $id)->orwhere('tulovturi', 'Олдиндан тўлов')->where('shartnomaid', $id)->orwhere('tulovturi', 'Брон')->where('shartnomaid', $id)->orderBy('id', 'desc')->get();
            $i = 1;
            $jnaqd = 0;
            $jpastik = 0;
            $jhr = 0;
            $jclick = 0;
            $javtot = 0;
            $jchegirma = 0;
            $colorqator = " ";

            foreach ($tulovlarshj as $tulovlarsh) {

                if($tulovlarsh->status=='Актив' && $tulovlarsh->tulovturi=='Шартнома' OR $tulovlarsh->status=='Актив' && $tulovlarsh->tulovturi=='Олдиндан тўлов'){
                    $colorqator = " ";
                    $jnaqd += $tulovlarsh->naqd;
                    $jpastik += $tulovlarsh->pastik;
                    $jhr += $tulovlarsh->hr;
                    $jclick += $tulovlarsh->click;
                    $javtot += $tulovlarsh->avtot;
                    $jchegirma += $tulovlarsh->chegirma;
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
                                <td>" . number_format($tulovlarsh->avtot, 0, ',', ' ') . "</td>
                                <td>" . number_format($tulovlarsh->chegirma, 0, ',', ' ') . "</td>
                                <td>" . number_format($tulovlarsh->naqd + $tulovlarsh->pastik+$tulovlarsh->hr+$tulovlarsh->click+$tulovlarsh->avtot, 0, ',', ' ') . "</td>
                                <td>" . $tulovlarsh->status . "</td>
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
                            <td>' . number_format($javtot, 0, ",", " ") . '</td>
                            <td>' . number_format($jchegirma, 0, ",", " ") . '</td>
                            <td>' . number_format($jnaqd+$jpastik+$jhr+$jclick+$javtot, 0, ",", " ") . '</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <br>
            ';
        return;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
