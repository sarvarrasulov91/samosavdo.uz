<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\shartnoma1;
use App\Models\tulovlar1;
use App\Models\savdo1;
use App\Models\mijozlar;
use App\Models\xodimlar;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;

use DateTime;


class PortfelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');

    return view('shartnoma.Portfel', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        echo'
            <h5 class="bc-title text-primary">
            </h5>
            <table class="table table-bordered text-center align-middle table-hover"
                style="font-size: 14px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>Мижоз <br> ID</th>
                        <th>ФИО</th>
                        <th>Шартнома</th>
                        <th>Паспорт</th>
                        <th>ИНПС</th>
                        <th>Туман</th>
                        <th>МФЙ</th>
                        <th>Манзили</th>
                        <th>Телефон 1</th>
                        <th>Телефон 2</th>
                        <th>Иш жойи</th>
                        <th>Ташриф</th>
                        <th>Ходим</th>
                        <th>Олинган<br>сана</th>
                        <th>Тугаш<br>санаси</th>
                        <th>Муддати</th>
                        <th>Шартнома<br> суммаси</th>
                        <th>Бошланғич<br> тўлов</th>
                        <th>Чегирма</th>
                        <th>Хисобланди</th>
                        <th>Умумий<br> сумма</th>
                        <th>График<br> Тўлов</th>
                        <th>Тўланган<br> умумий</th>
                        <th>Асосий<br> қарз</th>
                        <th>Қарздорлик<br> сумма</th>
                        <th>Танидан</th>
                        <th>Фоиздан</th>
                        <th>Қарздорлик<br> ойи </th>
                        <th>Охирги<br> тўлов</th>
                        <th>Тўлов <br> санаси</th>
                        <th>Просрочка</th>
                        <th>Изох</th>
                        <th>Товар<br> куриш</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                    $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

                    $shartnoma = shartnoma1::where('status', 'Актив')->orderBy('id', 'desc')->get();
                    $shsumma = 0;
                    $shotulov = 0;
                    $shchegirma = 0;
                    $shxisfoiz = 0;
                    $shjjamisumma = 0;
                    $shgrtulov = 0;
                    $shgtulov = 0;
                    $shasosqarz = 0;
                    $shjoriykunqarz = 0;
                    $ujotaniqarz = 0;
                    $ujofoizqarz = 0;
                    $uProsrochka = 0;

                    foreach ($shartnoma as $shartnom){
                        $shJamiSumma = 0;
                        $joqarz = 0;
                        $joqarzm = 0;
                        
                        $foiz = xissobotoy::where('xis_oy', $shartnom->xis_oyi)->value('foiz');
                        
                        $savdosumma = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');
                        $oldindantulov = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('umumiysumma');
                        $chegirma = tulovlar1::where('tulovturi', 'Олдиндан тўлов')->where('status', 'Актив')->where('shartnomaid', $shartnom->id)->sum('chegirma');
                        $tulov = tulovlar1::where('tulovturi', 'Шартнома')->where('shartnomaid', $shartnom->id)->where('status', 'Актив')->sum('umumiysumma');
                        $tulovinfo = tulovlar1::where('tulovturi', 'Шартнома')->where('shartnomaid', $shartnom->id)->where('status', 'Актив')->orderBy('id', 'desc')->first();

                        $tsumma = 0;
                        $tsumma = $tulovinfo->umumiysumma ?? 0;
                        if( $tsumma > 0 ){
                            $tkunpros = date('d.m.Y', strtotime($tulovinfo->kun));
                        }else{
                            $tkunpros = '';
                        }

                        if($shartnom->fstatus == 0){
                            $foiz = 0;
                        }

                        //йиллик фойиз
                        $foiz = (($foiz / 12) * $shartnom->muddat);
                        $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);

                        $shJamiSumma = $savdosumma + $xis_foiz - $oldindantulov - $chegirma;
                        
                        $shkun = $shartnom->kun;
                        $shtug_sana = $shartnom->tug_sana;

                        $date1 = new DateTime($shkun);
                        $date2 = new DateTime($shtug_sana);
                        $interval = $date1->diff($date2);
                        $dukun = $interval->days;
                        $birkunlikfoiz = $xis_foiz / $dukun;
                        $birkunliktani = ($savdosumma - $oldindantulov - $chegirma) / $dukun;
                        
                        if(($tulov - $xis_foiz) >= 0){
                            $jofoizqarz =  0;
                            $jotaniqarz = ($savdosumma - $oldindantulov - $chegirma) - ($tulov - $xis_foiz);
                        }else{
                            $jofoizqarz =  $xis_foiz - $tulov;
                            $jotaniqarz = $savdosumma - $oldindantulov - $chegirma;
                        }

                        if($shtug_sana > date("Y-m-d")){
                            $jodate1 = new DateTime(date("Y-m-d"));
                            $jodate2 = new DateTime($shkun);
                            $jointerval = $jodate1->diff($jodate2);
                            $jokun = $jointerval->days;
                            
                            $currentMonth = date('m'); 
                            $yearDiff = date('Y') - date('Y', strtotime($shkun));
                            $contractMonth = date('m', strtotime($shkun));
                            $months = $currentMonth + ($yearDiff * 12) - $contractMonth;
                            
                            //$months = ($jointerval->y * 12) + $jointerval->m;

                            $joqarz = ($birkunlikfoiz + $birkunliktani) * $jokun - $tulov;
                            
                            $joqarzm = ($shJamiSumma / $shartnom->muddat) * $months - $tulov;
                            
                            $tkun = date('Y-m', strtotime($xis_oyi)) . '-' . date('d', strtotime($shartnom->kun));
                            if ($tkun >= date("Y-m-d")) {
                                $joqarzm -= ($shJamiSumma / $shartnom->muddat);
                            }

                            if($tulov > ($birkunlikfoiz * $jokun)){
                                $jotaniqarz = ($birkunlikfoiz + $birkunliktani) * $jokun - $tulov;
                                $jofoizqarz = 0;
                            }else{
                                $jotaniqarz = $birkunliktani * $jokun;
                                $jofoizqarz = $birkunlikfoiz * $jokun - $tulov;
                            }

                        }else{
                            $joqarz = $joqarzm = $shJamiSumma - $tulov;
                        }
                        
                        if ($joqarzm < 1000) {
                            $joqarzm = 0;
                        }

                        if($joqarz < 0){
                            $joqarz = 0;
                        }

                        $hodimlar_fio = "";
                        $hodimlar = xodimlar::where('id',  $shartnom->mijozlar->mfy->xodimlar_id)->get();
                        foreach ($hodimlar as $hodimla) {
                            $hodimlar_fio = $hodimla->fio;
                        }
                        
                        $trrang = "";
                        if (date("Y-m-d") > $shtug_sana) {
                            $trrang = "align-middle text-danger";
                        }

                        if($shJamiSumma > 0){
                            echo'
                            <tr class="' . $trrang . '">
                                <td>' . $shartnom->mijozlar_id . '</td>
                                <td>' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name . '
                                <td>' . $shartnom->id . '</td>
                                <td>' . $shartnom->mijozlar->passport_sn . '</td>
                                <td>' . $shartnom->mijozlar->pinfl . '</td>
                                <td>' . $shartnom->mijozlar->tuman->name_uz . '</td>
                                <td>' . $shartnom->mijozlar->mfy->name_uz . '</td>
                                <td>' . $shartnom->mijozlar->manzil . '</td>
                                <td>' . $shartnom->mijozlar->phone . '</td>
                                <td>' . $shartnom->mijozlar->extra_phone . '</td>
                                <td>' . $shartnom->mijozlar->ish_joy . '</td>
                                <td>' . $shartnom->tashrif->tashrif_name . '</td>
                                <td>' . $hodimlar_fio . '</td>
                                <td>' . date('d.m.Y', strtotime($shartnom->kun)) . '</td>
                                <td>' . date('d.m.Y', strtotime($shartnom->tug_sana)) . '</td>
                                <td>' . $shartnom->muddat . '</td>
                                <td>' . number_format($savdosumma, 0, ',', ' ') . '</td>
                                <td>' . number_format($oldindantulov, 0, ',', ' ') . '</td>
                                <td>' . number_format($chegirma, 0, ',', ' ') . '</td>
                                <td>' . number_format($xis_foiz, 0, ',', ' ') . '</td>
                                <td>' . number_format($shJamiSumma, 0, ',', ' ') . '</td>
                                <td>' . number_format($shJamiSumma / $shartnom->muddat, 0, ',', ' ') . '</td>
                                <td>' . number_format($tulov, 0, ',', ' ') . '</td>
                                <td>' . number_format($shJamiSumma - $tulov, 0, ',', ' ') . '</td>
                                <td>' . number_format($joqarz, 0, ',', ' ') . '</td>
                                <td>' . number_format($jotaniqarz, 0, ',', ' ') . '</td>
                                <td>' . number_format($jofoizqarz, 0, ',', ' ') . '</td>
                                <td>' . number_format($joqarz * $shartnom->muddat / $shJamiSumma, 1, ',', ' ') . '</td>
                                <td>' . number_format($tsumma, 0, ',', ' ') . '</td>
                                <td>' . $tkunpros . '</td>
                                <td>' . number_format($joqarzm, 0, ',', ' ') . '</td>
                                <td>' . $shartnom->izox . '</td>
                                <td>
                                    <button id="kivitpechat" data-id="' . $shartnom->id .'" data-fio="' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name .'"
                                    class="btn btn-outline-primary btn-sm me-2 " data-bs-toggle="modal"
                                    data-bs-target="#pechat"><i class="flaticon-381-search-1"></i></button>
                                </td>

                            </tr>';
                        }

                        $shsumma += $savdosumma;
                        $shotulov += $oldindantulov;
                        $shchegirma += $chegirma;
                        $shjjamisumma += $shJamiSumma;
                        $shgrtulov += $shJamiSumma / $shartnom->muddat;
                        $shxisfoiz += $xis_foiz;
                        $shgtulov += $tulov;
                        $shasosqarz += $shJamiSumma - $tulov;
                        $shjoriykunqarz += $joqarz;
                        $ujotaniqarz += $jotaniqarz;
                        $ujofoizqarz += $jofoizqarz;
                        $uProsrochka += $joqarzm;
                    }
                    echo'
                    <tr class="align-middle text-bold">
                        <td></td>
                        <td class="fw-bold">Жами</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="fw-bold">' . number_format($shsumma, 0, ',', ' ') . '</td>
                        <td class="fw-bold">' . number_format($shotulov, 0, ',', ' ') . '</td>
                        <td class="fw-bold">' . number_format($shchegirma, 0, ',', ' ') . '</td>
                        <td class="fw-bold">' . number_format($shxisfoiz, 0, ',', ' ') . '</td>
                        <td class="fw-bold">' . number_format($shjjamisumma, 0, ',', ' ') . '</td>
                        <td class="fw-bold">' . number_format($shgrtulov, 0, ',', ' ') . '</td>
                        <td class="fw-bold">' . number_format($shgtulov, 0, ',', ' ') . '</td>
                        <td class="fw-bold">' . number_format($shasosqarz, 0, ',', ' ') . '</td>
                        <td class="fw-bold">' . number_format($shjoriykunqarz, 0, ',', ' ') . '</td>
                        <td class="fw-bold">' . number_format($ujotaniqarz, 0, ',', ' ') . '</td>
                        <td class="fw-bold">' . number_format($ujofoizqarz, 0, ',', ' ') . '</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="fw-bold">' . number_format($uProsrochka, 0, ',', ' ') . '</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        ';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        echo '
            <h3 class="text-center text-primary">Товарлар руйхати</h3>
            <table class="table-sm table-hover" style="font-size: 12px; text-align: center; width:100%; border: 1px solid black;">
                <thead>
                    <tr class="align-middle text-center">
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">№</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Модел<br>ID</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Маҳсулот номи</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Штрих-рақами</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Холати</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Сони</th>
                        <th style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Суммаси</th>
                    </tr>
                </thead>

                <tbody id="tab1">';

                    $savdomodel = savdo1::where('status', 'Шартнома')->where('shartnoma_id', $id)->get();
                    $i = 1;
                    $jami = 0;
                    foreach ($savdomodel as $savdomode) {
                        echo "
                            <tr class='text-center align-middle m-2'>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . $i . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . $savdomode->tmodel_id . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . $savdomode->shtrix_kod . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . $savdomode->status . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . number_format(1, 0, ',', ' ') . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse; padding: 5px;'>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                            </tr>";
                        $i++;
                        $jami += $savdomode->msumma;
                    };

                    echo '
                        <tr class="text-center align-middle fw-bold">
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"></td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"></td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">ЖАМИ</td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"></td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;"></td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">' . number_format($i-1, 0, ",", " ") . '</td>
                            <td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">' . number_format($jami, 0, ",", " ") . '</td>
                        </tr>
                    </tbody>
            </table>';
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
