<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\xissobotoy;
use Illuminate\Support\Facades\Auth;
use App\Models\filial;
use App\Models\mijozlar;
use App\Models\lavozim;
use App\Models\tashrif;
use App\Models\shartnoma1;

class MijozTaxlilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        if((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 12 || Auth::user()->lavozim_id == 13) && Auth::user()->status == 'Актив'){
            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();
        }else{
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }
        return view('mijoz.mijoztaxlil', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'filial' => $filial, 'xis_oyi' => $xis_oyi]);
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
        
        // Mijozlar taxlili ko'rish

        echo '
        <div class="row justify-content-md-center">
            <h3 class=" text-center text-primary"><b>Мижозлар иш жойи тахлили</b></h3>
            <div class="col-xl-12">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center text-bold text-primary align-middle">
                            <th>ID</th>
                            <th>Филиал</th>
                            <th>Жами мижоз</th>
                            <th>Мактаб</th>
                            <th>МТМ</th>
                            <th>Тиббиёт</th>
                            <th>Дав.ташкил</th>
                            <th>Пенсионер</th>
                            <th>Ижти.нафақа</th>
                            <th>Бола пули</th>
                            <th>ЯТТ</th>
                            <th>МЧЖ</th>
                            <th>Бошкалар</th>
                        </tr>
                    </thead>
                    <tbody id="tab1">';

                        $jamiMijozSoni = 0;
                        $jamiMaktab = 0;
                        $jamiMTM = 0;
                        $jamiTibbiyot = 0;
                        $jamiDavTashkilot = 0;
                        $jamiPensioner = 0;
                        $jamiIjtimoiy = 0;
                        $jamiBolaPuli = 0;
                        $jamiYATT = 0;
                        $jamiMCHJ = 0;
                        $jamiBoshqalar = 0;

                        // $filialbase = filial::where('status', 'Актив')->where('id','!=','10')->get();
                        if(Auth::user()->filial_id == 10){
                            $filialbase = filial::where('status', 'Актив')->where('id','!=','10')->get();
                        }else{
                            $filialbase = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
                        }
                        foreach ($filialbase as $filia) {
                            $mijozlar = 0;
                            $maktab = 0;
                            $MTM = 0;
                            $tibbiyot = 0;
                            $davTashkilot = 0;
                            $pensioner = 0;
                            $ijtimoiy = 0;
                            $bolaPuli = 0;
                            $yatt = 0;
                            $mchj = 0;
                            $boshqalar = 0;

                            $mijozlar = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('filial_id', $filia->id)->where('status',1)->count();
                            $maktab = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('ish_joy', 'Мактаб')->where('filial_id', $filia->id)->where('status','1')->count();
                            $MTM = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('ish_joy', 'МТМ')->where('filial_id', $filia->id)->where('status','1')->count();
                            $tibbiyot = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('ish_joy', 'Тиббиёт')->where('filial_id', $filia->id)->where('status','1')->count();
                            $davTashkilot = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('ish_joy', 'Давлат ташкилоти')->where('filial_id', $filia->id)->where('status','1')->count();
                            $pensioner = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('ish_joy', 'Пенсионер')->where('filial_id', $filia->id)->where('status','1')->count();
                            $ijtimoiy = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('ish_joy', 'Ижтимоий нафақа')->where('filial_id', $filia->id)->where('status','1')->count();
                            $bolaPuli = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('ish_joy', 'Бола пули')->where('filial_id', $filia->id)->where('status','1')->count();
                            $yatt = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('ish_joy', 'ЯТТ')->where('filial_id', $filia->id)->where('status','1')->count();
                            $mchj = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('ish_joy', 'МЧЖ')->where('filial_id', $filia->id)->where('status','1')->count();
                            $boshqalar = mijozlar::whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('ish_joy', 'Бошкалар')->where('filial_id', $filia->id)->where('status','1')->count();
                            
                            $jamiMijozSoni += $mijozlar;
                            $jamiMaktab += $maktab;
                            $jamiMTM += $MTM;
                            $jamiTibbiyot += $tibbiyot;
                            $jamiDavTashkilot += $davTashkilot;
                            $jamiPensioner += $pensioner;
                            $jamiIjtimoiy += $ijtimoiy;
                            $jamiBolaPuli += $bolaPuli;
                            $jamiYATT += $yatt;
                            $jamiMCHJ += $mchj;
                            $jamiBoshqalar += $boshqalar;
                    
                            echo '
                            <tr class="text-center align-middle">
                                <td>' . $filia->id . '</td>
                                <td>' . $filia->fil_name . '</td>
                                <td>' . number_format($mijozlar, 0, ',', ' ') . '</td>
                                <td>' . number_format($maktab, 0, ',', ' ') . '</td>
                                <td>' . number_format($MTM, 0, ',', ' ') . '</td>
                                <td>' . number_format($tibbiyot, 0, ',', ' ') . '</td>
                                <td>' . number_format($davTashkilot, 0, ',', ' ') . '</td>
                                <td>' . number_format($pensioner, 0, ',', ' ') . '</td>
                                <td>' . number_format($ijtimoiy, 0, ',', ' ') . '</td>
                                <td>' . number_format($bolaPuli, 0, ',', ' ') . '</td>
                                <td>' . number_format($yatt, 0, ',', ' ') . '</td>
                                <td>' . number_format($mchj, 0, ',', ' ') . '</td>
                                <td>' . number_format($boshqalar, 0, ',', ' ') . '</td>
                                
                            </tr>';
                        }

                        echo '
                        <tr class="text-center align-middle fw-bold">
                            <td></td>
                            <td>ЖАМИ</td>
                            <td>' . number_format($jamiMijozSoni, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiMaktab, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiMTM, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiTibbiyot, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiDavTashkilot, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiPensioner, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiIjtimoiy, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiBolaPuli, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiYATT, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiMCHJ, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiBoshqalar, 0, ',', ' ') . '</td>
                        </tr>

                        <tr class="text-center align-middle fw-bold text-danger">
                            <td></td>
                            <td>Улуши</td>
                            <td>' . number_format($jamiMijozSoni/$jamiMijozSoni*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiMaktab/$jamiMijozSoni*100, 0, ',', ' ') . '%' .  '</td>
                            <td>' . number_format($jamiMTM/$jamiMijozSoni*100, 0, ',', ' ') . '%' .  '</td>
                            <td>' . number_format($jamiTibbiyot/$jamiMijozSoni*100, 0, ',', ' ') . '%' .  '</td>
                            <td>' . number_format($jamiDavTashkilot/$jamiMijozSoni*100, 0, ',', ' ') . '%' .  '</td>
                            <td>' . number_format($jamiPensioner/$jamiMijozSoni*100, 0, ',', ' ') . '%' .  '</td>
                            <td>' . number_format($jamiIjtimoiy/$jamiMijozSoni*100, 0, ',', ' ') . '%' .  '</td>
                            <td>' . number_format($jamiBolaPuli/$jamiMijozSoni*100, 0, ',', ' ') . '%' .  '</td>
                            <td>' . number_format($jamiYATT/$jamiMijozSoni*100, 0, ',', ' ') . '%' .  '</td>
                            <td>' . number_format($jamiMCHJ/$jamiMijozSoni*100, 0, ',', ' ') . '%' .  '</td>
                            <td>' . number_format($jamiBoshqalar/$jamiMijozSoni*100, 0, ',', ' ') . '%' .  '</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>'; 

        // Tashrif taxlili ko'rish

        echo ' <br> <br>
        <div class="row justify-content-md-center">
            <h3 class=" text-center text-primary"><b>Ташриф тахлили</b></h3>
            <div class="col-xl-12">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center text-bold text-primary align-middle">
                            <th>ID</th>
                            <th>Филиал</th>
                            <th>Шартнома сони</th>
                            <th>Эски мижоз</th>
                            <th>Телевиде</th>
                            <th>Радио</th>
                            <th>Телеграм</th>
                            <th>Инстаграм</th>
                            <th>Ютубе</th>
                            <th>Флаер</th>
                            <th>Баннер</th>
                            <th>Таргибот</th>
                            <th>Ташаббускор</th>
                        </tr>
                    </thead>
                    <tbody id="tab1">';

                        $jamiMijozSoni = 0;
                        $jamiShartnoma = 0;
                        $jamiEskiMijoz = 0;
                        $jamiTV = 0;
                        $jamiRadio = 0;
                        $jamiTelegram = 0;
                        $jamiInstagram = 0;
                        $jamiYouTube = 0;
                        $jamiFlayer = 0;
                        $jamiBanner = 0;
                        $jamiTargibot = 0;
                        $jamiTashabbus = 0;
                        

                        // $filialbase = filial::where('status', 'Актив')->where('id','!=','10')->get();
                        if(Auth::user()->filial_id == 10){
                            $filialbase = filial::where('status', 'Актив')->where('id','!=','10')->get();
                        }else{
                            $filialbase = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
                        }
                        foreach ($filialbase as $filia) {
                            $ShartnomaSoni = 0;
                            $tashrifName=[];
                            $tashriflar = tashrif::where('status', 'Актив')->get();
                            foreach($tashriflar as $tashrif){
                                $shartnomalar = new shartnoma1($filia->id);
                                $ShartnomaSoni = $shartnomalar->whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('status','Актив')->count();            
                                $tashrifName[] += $shartnomalar->whereDate('created_at', '>=', $boshkun)->whereDate('created_at', '<=', $yakunkun)->where('tashrif_id', $tashrif->id)->where('status','Актив')->count();                                
                            }
                            $jamiShartnoma += $ShartnomaSoni;
                            $jamiEskiMijoz += $tashrifName[0];
                            $jamiTV += $tashrifName[1];
                            $jamiRadio += $tashrifName[2];
                            $jamiTelegram += $tashrifName[3];
                            $jamiInstagram += $tashrifName[4];
                            $jamiYouTube += $tashrifName[5];
                            $jamiFlayer += $tashrifName[6];
                            $jamiBanner += $tashrifName[7];
                            $jamiTargibot += $tashrifName[8];
                            $jamiTashabbus += $tashrifName[9];

                            echo '
                            <tr class="text-center align-middle">
                                <td>' . $filia->id . '</td>
                                <td>' . $filia->fil_name . '</td>
                                <td>' . number_format($ShartnomaSoni, 0, ',', ' ') . '</td>
                                <td>' . number_format($tashrifName[0], 0, ',', ' ') . '</td>
                                <td>' . number_format($tashrifName[1], 0, ',', ' ') . '</td>
                                <td>' . number_format($tashrifName[2], 0, ',', ' ') . '</td>
                                <td>' . number_format($tashrifName[3], 0, ',', ' ') . '</td>
                                <td>' . number_format($tashrifName[4], 0, ',', ' ') . '</td>
                                <td>' . number_format($tashrifName[5], 0, ',', ' ') . '</td>
                                <td>' . number_format($tashrifName[6], 0, ',', ' ') . '</td>
                                <td>' . number_format($tashrifName[7], 0, ',', ' ') . '</td>
                                <td>' . number_format($tashrifName[8], 0, ',', ' ') . '</td>
                                <td>' . number_format($tashrifName[9], 0, ',', ' ') . '</td>
                                
                            </tr>';
                        }

                        echo '
                        <tr class="text-center align-middle fw-bold">
                            <td></td>
                            <td>ЖАМИ</td>
                            <td>' . number_format($jamiShartnoma, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiEskiMijoz, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiTV, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiRadio, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiTelegram, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiInstagram, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiYouTube, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiFlayer, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiBanner, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiTargibot, 0, ',', ' ') . '</td>
                            <td>' . number_format($jamiTashabbus, 0, ',', ' ') . '</td>
                            
                        </tr>

                        <tr class="text-center align-middle text-danger fw-bold">
                            <td></td>
                            <td>Улуши</td>
                            <td>' . number_format($jamiShartnoma/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiEskiMijoz/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiTV/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiRadio/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiTelegram/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiInstagram/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiYouTube/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiFlayer/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiBanner/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiTargibot/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            <td>' . number_format($jamiTashabbus/$jamiShartnoma*100, 0, ',', ' ') . '%' . '</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>'; 

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
