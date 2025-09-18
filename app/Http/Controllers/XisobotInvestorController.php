<?php

namespace App\Http\Controllers;

use App\Models\boshqaharajat1;
use App\Models\filial;
use App\Models\kirim;
use App\Models\kirim_old;
use App\Models\ktovar1;
use App\Models\naqdsavdo1;
use App\Models\savdo1;
use App\Models\shartnoma1;
use App\Models\tulovlar1;
use App\Models\lavozim;
use App\Models\xissobotoy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class XisobotInvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
            
            if (Auth::user()->lavozim_id == 2){
                $filial = filial::where('id', Auth::user()->filial_id)->get();   
            }else{
                $filial = filial::where('status', 'Актив')->get();
            }
            
            return view('xisobotlar.XisobotInvestor', ['xis_oyi' => $xis_oyi, 'filial' => $filial, 'lavozim_name' => $lavozim_name, 'filial_name' => $filial_name]);
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
        $boshkun = $request->boshkun;
        $yakunkun = $request->yakunkun;

        $i = 1;
        $jamiNaqd = 0;
        $jamiBank = 0;
        $jamiOld = 0;
        $jamiTulov = 0;
        $jamiXarajat = 0;
        $jamiSavdoPuli = 0;
        $ushqsumma = 0;
        $unssumma = 0;
        $uchegirmasumma = 0;
        $jamiTovarOyBoshiSumma = 0;
        $jamiTovarOyBoshiDollar = 0;
        $jamiTovarKirimSumma = 0;
        $jamiTovarKirimDollar = 0;
        $jamiTovarChiqimDollar = 0;
        $jamiTovarChiqimSumma = 0;
        $qoldiqTovarDollar = 0;
        $qoldiqTovarSumma = 0;

        if ($request->filial == 0){
            echo'
            <table class="table table-bordered table-responsive-sm text-center align-middle" style="font-size: 11px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>№</th>
                        <th>Филиал</th>
                        <th>Накд</th>
                        <th>Х/Р</th>
                        <th>Жами тушум</th>
                        <th>Харажат</th>
                        <th>Офисга СП</th>
                        <th>Портфел</th>
                        <th>Накд савдо</th>
                        <th>ОБ Товар <br> Доллар</th>
                        <th>Кирим товар <br> Доллар</th>
                        <th>Чиким товар <br> Доллар</th>
                        <th>Колдик товар <br> Доллар</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                $filials = filial::where('status', 'Актив')->get();  
                foreach ($filials as $filial){

                    // tulovlardan tushgan tushumlarni hisoblash
                    $tulovBank = 0;
                    $tulovlar = new tulovlar1($filial->id);
                    $tulovNaqd = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('naqd');
                    $tulovPlastik = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('pastik');
                    $tulovHr = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('hr');
                    $tulovClick = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('click');
                    $tulovAvtot = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('avtot');
                    $tulovJami = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('umumiysumma');
                    $tulovBank += ($tulovPlastik + $tulovHr + $tulovClick + $tulovAvtot);
                    

                    // xarajatlarni hisoblash
                    $xarajatlar = new boshqaharajat1($filial->id);
                    $xarajatSummasi = $xarajatlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->where('turharajat_id', '!=', '33')->sum('summasi');

                    // ofisga yuborilgan savdo pullari
                    $savdoPuliNaqd = kirim::whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->where('filial_id', $filial->id)->sum('naqd');

                    // shartnoma summasini hisoblash
                    $shqsumma = 0;

                    $shartnoma = new shartnoma1($filial->id);
                    $shartnoma1 = $shartnoma->whereBetween('kun', [$boshkun, $yakunkun])
                        ->where(function($query){
                            $query->where('status', 'Актив')
                                ->orWhere('status', 'Ёпилган');
                        })->get();
                    foreach ($shartnoma1 as $shart) {
                        $savdo = new savdo1($filial->id);
                        $savdosumma = $savdo->where('status', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');
                        
                        $oldindantulovinfo = new tulovlar1($filial->id);
                        $oldindantulov = $oldindantulovinfo->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shart->id)->sum('umumiysumma');
                        $chegirma = $oldindantulovinfo->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shart->id)->sum('chegirma');

                        $foiz = xissobotoy::where('xis_oy', $shart->xis_oyi)->value('foiz');
                        if($shart->fstatus == 0){
                            $foiz = 0;
                        }

                        //йиллик фойиз
                        $foiz = (($foiz / 12) * $shart->muddat);
                        if ($shart->kun < "2023-12-05"){
                            $xis_foiz = ((($savdosumma - $oldindantulov - $chegirma) * $foiz) / 100);
                        }else{
                            $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);  
                        }
                        $shqsumma += ($savdosumma - $oldindantulov - $chegirma + $xis_foiz);

                    }
                    $ushqsumma += $shqsumma;

                    // Naqd savdo taxlili
                    $nssumma = 0;
                    $chegirmasumma = 0;

                    $naqdsavdo = new naqdsavdo1($filial->id);
                    $naqdsavdo1 = $naqdsavdo->whereBetween('kun', [$boshkun, $yakunkun])->where('status','Актив')->get();
                    foreach ($naqdsavdo1 as $naqd) {
                        $savdosumma = new savdo1($filial->id);
                        $savdosumma = $savdosumma->where('status', 'Нақд')->where('shartnoma_id', $naqd->id)->sum('msumma');
                        $nssumma += $savdosumma;
                        
                        $naqdchegirma = new tulovlar1($filial->id);
                        $chegirmasum = $naqdchegirma->where('tulovturi', 'Нақд')->where('status', 'Актив')->where('shartnomaid', $naqd->id)->sum('chegirma');
                        $chegirmasumma += $chegirmasum;
                        
                    }
                        $unssumma += $nssumma;
                        $uchegirmasumma += $chegirmasumma;

                    // Oy boshiga qoldiq tovarlarni hisoblash
                    $tovarlar = new ktovar1($filial->id);
                    // $ktovarOyBoshiDollar = $tovarlar->where('valyuta_id', '2')->where('kun', '<', $boshkun)
                    //     ->where(function ($query) {
                    //         $query->where('status', 'Сотилмаган')
                    //             ->orWhere('status', 'Асосий восита');
                    //     })->sum('narhi');

                    $ktovarOyBoshiDollar = $tovarlar
                        ->where(function ($query) use ($boshkun){
                            $query->where('valyuta_id', '2')->where('kun', '<', $boshkun)->where('status', 'Сотилмаган');
                        })
                        ->orWhere(function ($query) use ($boshkun){
                            $query->where('valyuta_id', '2')->where('kun', '<', $boshkun)->where('ch_kun', '>=', $boshkun);
                        })
                        ->sum('narhi');

                    $ktovarOyBoshiSum = $tovarlar
                    ->where(function ($query) use ($boshkun){
                        $query->where('valyuta_id', '1')->where('kun', '<', $boshkun)->where('status', 'Сотилмаган');
                    })
                    ->orWhere(function ($query) use ($boshkun){
                        $query->where('valyuta_id', '1')->where('kun', '<', $boshkun)->where('ch_kun', '>=', $boshkun);
                    })->sum('narhi');
                    
                        $jamiTovarOyBoshiDollar += $ktovarOyBoshiDollar;
                        $jamiTovarOyBoshiSumma += $ktovarOyBoshiSum;

                    // Oy olingan tovarlarni hisoblash
                    $ktovarKirimDollar = $tovarlar->whereBetween('kun', [$boshkun, $yakunkun])
                        ->where('valyuta_id', '2')
                        ->where('status', '!=', 'Удалит')
                        ->where('status', '!=', 'Актив')
                        ->sum('narhi');
                    $ktovarKirimSum = $tovarlar->whereBetween('kun', [$boshkun, $yakunkun])
                        ->where('valyuta_id', '1')
                        ->where('status', '!=', 'Удалит')
                        ->where('status', '!=', 'Актив')
                        ->sum('narhi');

                    $jamiTovarKirimDollar += $ktovarKirimDollar;
                    $jamiTovarKirimSumma += $ktovarKirimSum;

                    // chiqim bo'lagn tovarlar
                    $ktovarChiqimDollar = $tovarlar->whereBetween('ch_kun', [$boshkun, $yakunkun])
                        ->where('valyuta_id', '2')
                        ->where('status', '!=', 'Удалит')
                        ->where('status', '!=', 'Актив')
                        ->sum('narhi');
                    $ktovarChiqimSum = $tovarlar->whereBetween('ch_kun', [$boshkun, $yakunkun])
                        ->where('valyuta_id', '1')
                        ->where('status', '!=', 'Удалит')
                        ->where('status', '!=', 'Актив')
                        ->sum('narhi');

                    $jamiTovarChiqimDollar += $ktovarChiqimDollar;
                    $jamiTovarChiqimSumma += $ktovarChiqimSum;

                    echo'
                    <tr>
                        <td>' . $i . '</td>
                        <td>' . $filial->fil_name . '</td>
                        <td>' . number_format($tulovNaqd, 0, ",", " ") . '</td>
                        <td>' . number_format($tulovBank, 0, ",", " ") . '</td>
                        <td>' . number_format($tulovJami, 0, ",", " ") . '</td>
                        <td>' . number_format($xarajatSummasi, 0, ",", " ") . '</td>
                        <td>' . number_format($savdoPuliNaqd, 0, ",", " ") . '</td>
                        <td>' . number_format($shqsumma, 0, ",", " ") . '</td>
                        <td>' . number_format($nssumma - $chegirmasumma, 0, ",", " ") . '</td>
                        <td>' . number_format($ktovarOyBoshiDollar+$ktovarOyBoshiSum/12700, 0, ",", " ") . '</td>
                        <td>' . number_format($ktovarKirimDollar+$ktovarKirimSum/12700, 0, ",", " ") . '</td>
                        <td>' . number_format($ktovarChiqimDollar+$ktovarChiqimSum/12700, 0, ",", " ") . '</td>
                        <td>' . number_format(($ktovarOyBoshiDollar+$ktovarKirimDollar-$ktovarChiqimDollar)+($ktovarOyBoshiSum+$ktovarKirimSum-$ktovarChiqimSum)/12700, 0, ",", " ") . '</td>
                    </tr>';

                    $i++;
                    $jamiNaqd += $tulovNaqd;
                    $jamiBank += $tulovBank;
                    $jamiTulov += $tulovJami;
                    $jamiXarajat += $xarajatSummasi;
                    $jamiSavdoPuli += $savdoPuliNaqd;
                    $qoldiqTovarDollar += ($ktovarOyBoshiDollar+$ktovarKirimDollar-$ktovarChiqimDollar);
                    $qoldiqTovarSumma += ($ktovarOyBoshiSum+$ktovarKirimSum-$ktovarChiqimSum);
                }

                echo'
                    <tr class="fw-bold">
                        <td></td>
                        <td><b>ЖАМИ</b></td>
                        <td>' . number_format($jamiNaqd, 0, ",", " ") .'</td>
                        <td>' . number_format($jamiBank, 0, ",", " ") .'</td>
                        <td>' . number_format($jamiTulov, 0, ",", " ") .'</td>
                        <td>' . number_format($jamiXarajat, 0, ",", " ") .'</td>
                        <td>' . number_format($jamiSavdoPuli, 0, ",", " ") .'</td>
                        <td>' . number_format($ushqsumma, 0, ",", " ") .'</td>
                        <td>' . number_format($unssumma - $uchegirmasumma, 0, ",", " ") . '</td>
                        <td>' . number_format($jamiTovarOyBoshiDollar + $jamiTovarOyBoshiSumma / 12700, 0, ",", " ") . '</td>
                        <td>' . number_format($jamiTovarKirimDollar + $jamiTovarKirimSumma / 12700, 0, ",", " ") . '</td>
                        <td>' . number_format($jamiTovarChiqimDollar + $jamiTovarChiqimSumma / 12700, 0, ",", " ") . '</td>
                        <td>' . number_format($qoldiqTovarDollar + $qoldiqTovarSumma / 12700, 0, ",", " ") . '</td>
                    </tr>
                </tbody>
            </table>';
            return;

        }else{
            
            $filial = $request->filial;
            $filName = filial::where('id', $filial)->value('fil_name');
            echo'
            <h4 class="text-bold text-primary text-center"><b>'.$filName.' филиали буйича кунлик хисобот</b></h4>
            <table class="table table-bordered table-responsive-sm text-center align-middle" style="font-size: 11px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>№</th>
                        <th>Накд</th>
                        <th>Х/Р</th>
                        <th>Жами тушум</th>
                        <th>Харажат</th>
                        <th>Офисга СП</th>
                        <th>Портфел</th>
                        <th>Накд савдо</th>
                        <th>ОБ Товар <br> Доллар</th>
                        <th>Олин товар <br> Доллар</th>
                        <th>Чиким товар <br> Доллар</th>
                        <th>Колдик товар <br> Доллар</th>
                    </tr>
                </thead>
                <tbody id="tab1">';


                while ($boshkun <= $yakunkun) {

                    // tulovlardan tushgan tushumlarni hisoblash
                    $tulovBank = 0;
                    $tulovlar = new tulovlar1($filial);
                    $tulovNaqd = $tulovlar->where('kun', $boshkun)->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('naqd');
                    $tulovPlastik = $tulovlar->where('kun', $boshkun)->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('pastik');
                    $tulovHr = $tulovlar->where('kun', $boshkun)->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('hr');
                    $tulovClick = $tulovlar->where('kun', $boshkun)->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('click');
                    $tulovAvtot = $tulovlar->where('kun', $boshkun)->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('avtot');
                    $tulovJami = $tulovlar->where('kun', $boshkun)->where('status', 'Актив')->where('tulovturi', '!=', 'Брон')->sum('umumiysumma');
                    $tulovBank += ($tulovPlastik + $tulovHr + $tulovClick + $tulovAvtot);

                    // xarajatlarni hisoblash
                    $xarajatlar = new boshqaharajat1($filial);
                    $xarajatSummasi = $xarajatlar->where('kun', $boshkun)->where('status', 'Актив')->where('turharajat_id', '!=', '33')->sum('summasi');

                    // ofisga yuborilgan savdo pullari
                    $savdoPuliNaqd = kirim::where('kun', $boshkun)->where('status', 'Актив')->where('filial_id', $filial)->sum('naqd');

                     // shartnoma summasini hisoblash
                     $shqsumma = 0;

                     $shartnoma = new shartnoma1($filial);
                     $shartnoma1 = $shartnoma->where('kun', $boshkun)
                        ->where(function($query){
                            $query->where('status', 'Актив')->orWhere('status', 'Ёпилган');
                        })->get();

                     foreach ($shartnoma1 as $shart) {
                         $savdo = new savdo1($filial);
                         $savdosumma = $savdo->where('status', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');
                         
                         $oldindantulovinfo = new tulovlar1($filial);
                         $oldindantulov = $oldindantulovinfo->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shart->id)->sum('umumiysumma');
                         $chegirma = $oldindantulovinfo->where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shart->id)->sum('chegirma');
 
                         $foiz = xissobotoy::where('xis_oy', $shart->xis_oyi)->value('foiz');
                         if($shart->fstatus == 0){
                             $foiz=0;
                         }
 
                         //йиллик фойиз
                         $foiz = (($foiz / 12) * $shart->muddat);
                         if ($shart->kun < "2023-12-05"){
                             $xis_foiz = ((($savdosumma - $oldindantulov - $chegirma) * $foiz) / 100);
                         }else{
                             $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);  
                         }
                         $shqsumma += ($savdosumma-$oldindantulov-$chegirma+$xis_foiz);
 
                     }
                     $ushqsumma += $shqsumma;

                     // Naqd savdo taxlili
                    $nssumma = 0;
                    $chegirmasumma = 0;

                    $naqdsavdo = new naqdsavdo1($filial);
                    $naqdsavdo1 = $naqdsavdo->where('kun', $boshkun)->where('status','Актив')->get();
                    foreach ($naqdsavdo1 as $naqd) {
                        $savdosumma = new savdo1($filial);
                        $savdosumma = $savdosumma->where('status', 'Нақд')->where('shartnoma_id', $naqd->id)->sum('msumma');
                        $nssumma += $savdosumma;
                        
                        $naqdchegirma = new tulovlar1($filial);
                        $chegirmasum = $naqdchegirma->where('tulovturi', 'Нақд')->where('status', 'Актив')->where('shartnomaid', $naqd->id)->sum('chegirma');
                        $chegirmasumma += $chegirmasum;
                        
                    }
                    $unssumma += $nssumma;
                    $uchegirmasumma += $chegirmasumma;

                    // Oy boshiga qoldiq tovarlarni hisoblash
                    $tovarlar = new ktovar1($filial);
                    $ktovarOyBoshiDollar = $tovarlar
                        ->where(function ($query) use ($boshkun){
                            $query->where('valyuta_id', '2')->where('kun', '<', $boshkun)->where('status', 'Сотилмаган');
                        })
                        ->orWhere(function ($query) use ($boshkun){
                            $query->where('valyuta_id', '2')->where('kun', '<', $boshkun)->where('ch_kun', '>=', $boshkun);
                        })
                        ->sum('narhi');

                    $ktovarOyBoshiSum = $tovarlar
                    ->where(function ($query) use ($boshkun){
                        $query->where('valyuta_id', '1')->where('kun', '<', $boshkun)->where('status', 'Сотилмаган');
                    })
                    ->orWhere(function ($query) use ($boshkun){
                        $query->where('valyuta_id', '1')->where('kun', '<', $boshkun)->where('ch_kun', '>=', $boshkun);
                    })->sum('narhi');

                    // Oy olingan tovarlarni hisoblash
                    $ktovarKirimDollar = $tovarlar->where('kun', $boshkun)
                        ->where('valyuta_id', '2')
                        ->where('status', '!=', 'Удалит')
                        ->where('status', '!=', 'Актив')
                        ->sum('narhi');
                    $ktovarKirimSum = $tovarlar->where('kun', $boshkun)
                        ->where('valyuta_id', '1')
                        ->where('status', '!=', 'Удалит')
                        ->where('status', '!=', 'Актив')
                        ->sum('narhi');

                    // chiqim bo'lagn tovarlar
                    $ktovarChiqimDollar = $tovarlar->where('ch_kun', $boshkun)
                        ->where('valyuta_id', '2')
                        ->where('status', '!=', 'Удалит')
                        ->where('status', '!=', 'Актив')
                        ->sum('narhi');
                    $ktovarChiqimSum = $tovarlar->where('ch_kun', $boshkun)
                        ->where('valyuta_id', '1')
                        ->where('status', '!=', 'Удалит')
                        ->where('status', '!=', 'Актив')
                        ->sum('narhi');

                    echo'
                    <tr class="text-bold">
                        <td>' . date('d.m.Y', strtotime($boshkun)).'</td>
                        <td>' . number_format($tulovNaqd, 0, ",", " ") . '</td>
                        <td>' . number_format($tulovBank, 0, ",", " ") . '</td>
                        <td>' . number_format($tulovJami, 0, ",", " ") . '</td>
                        <td>' . number_format($xarajatSummasi, 0, ",", " ") .'</td>
                        <td>' . number_format($savdoPuliNaqd, 0, ",", " ") .'</td>
                        <td>' . number_format($shqsumma, 0, ",", " ") . '</td>
                        <td>' . number_format($nssumma - $chegirmasumma, 0, ",", " ") . '</td>
                        <td>' . number_format($ktovarOyBoshiDollar + $ktovarOyBoshiSum / 12700, 2, ",", " ") . '</td>
                        <td>' . number_format($ktovarKirimDollar + $ktovarKirimSum / 12700, 2, ",", " ") . '</td>
                        <td>' . number_format($ktovarChiqimDollar + $ktovarChiqimSum / 12700, 2, ",", " ") . '</td>
                        <td>' . number_format(($ktovarOyBoshiDollar+$ktovarKirimDollar-$ktovarChiqimDollar)+($ktovarOyBoshiSum+$ktovarKirimSum-$ktovarChiqimSum)/12700, 2, ",", " ") . '</td>
                    </tr>';

                    $jamiNaqd += $tulovNaqd;
                    $jamiBank += $tulovBank;
                    $jamiTulov += $tulovJami;
                    $jamiXarajat += $xarajatSummasi;
                    $jamiSavdoPuli += $savdoPuliNaqd;

                    $boshkun = date('Y-m-d', strtotime($boshkun . ' +1 day'));
                } 

                echo'
                    <tr class="fw-bold">
                        <td></td>
                        <td>' . number_format($jamiNaqd, 0, ",", " ") .'</td>
                        <td>' . number_format($jamiBank, 0, ",", " ") .'</td>
                        <td>' . number_format($jamiTulov, 0, ",", " ") .'</td>
                        <td>' . number_format($jamiXarajat, 0, ",", " ") .'</td>
                        <td>' . number_format($jamiSavdoPuli, 0, ",", " ") .'</td>
                        <td>' . number_format($ushqsumma, 0, ",", " ") .'</td>
                        <td>' . number_format($unssumma - $uchegirmasumma, 0, ",", " ") . '</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>';

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
