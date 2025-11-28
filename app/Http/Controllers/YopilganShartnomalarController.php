<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shartnoma;
use App\Models\Savdo;
use App\Models\Tulovlar;
use App\Models\xissobotoy;
use App\Models\filial;
use Illuminate\Support\Facades\Auth;
use DateTime;


class YopilganShartnomalarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {

            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();

            return view('shartnoma.yopilganshartnomalar', ['filial' => $filial ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        echo'
            <table
                class="table table-bordered table-responsive-sm text-center align-middle table-hover"
                style="font-size: 12px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Cана</th>
                        <th>Муд.</th>
                        <th>Ёпилган<br>сана</th>
                        <th>Товар<br>суммаси</th>
                        <th>Шартнома<br>суммаси</th>
                        <th>Олдиндан<br>тўлови</th>
                        <th>Чегирма</th>
                        <th>Хисобланди</th>
                        <th>Жами<br>тўлови</th>
                        <th>Муд.олд.ёпган<br>чегирма</th>
                        <th>Фарқи</th>
                    </tr>
                </thead>
                <tbody id="tab1">';


                    $jamifarq = 0;
                    $chjami = 0;

                    $shartnoma = Shartnoma::where('filial_id', $id)
                        ->whereIn('status', ['Ёпилган', 'Удалит'])
                        ->orderBy('id', 'desc')->get();

                    foreach ($shartnoma as $shartnom){

                        $foiz = xissobotoy::where('xis_oy', $shartnom->xis_oyi)->value('foiz');

                        if($shartnom->fstatus == 0){
                            $foiz = 0;
                        }

                        $savdosumma = Savdo::where('filial_id', $id)->where('status', 'Шартнома')->where('shartnoma_id', $shartnom->id)->sum('msumma');

                        $tulovInfo = Tulovlar::where('filial_id', $id)
                            ->where('tulovturi', 'Олдиндан тўлов')
                            ->where('status', 'Актив')
                            ->where('shartnomaid', $shartnom->id)
                            ->first();

                        $oldindantulov = $tulovInfo->umumiysumma ?? 0;
                        $chegirma = $tulovInfo->chegirma ?? 0;

                        $tulov = Tulovlar::where('filial_id', $id)
                            ->where('tulovturi', 'Шартнома')
                            ->where('status', 'Актив')
                            ->where('shartnomaid', $shartnom->id)
                            ->sum('umumiysumma');

                        //йиллик фойиз
                        $foiz = (($foiz / 12) * $shartnom->muddat);
                        $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);

                        $date1 = new DateTime($shartnom->kun);
                        $date2 = new DateTime($shartnom->tug_sana);
                        $interval = $date1->diff($date2);
                        $dukun = $interval->days;
                        $birkunlikfoiz = $xis_foiz / $dukun;

                        $krxiob22 = 0;
                        $date22 = new DateTime(date('Y-m-d', strtotime($shartnom->yo_sana)));
                        $interval1 = $date1->diff($date22);
                        $dukun22 = $interval1->days;

                        $krxiob22 = ($birkunlikfoiz * $dukun22);

                        if ((($savdosumma - $oldindantulov - $chegirma + $xis_foiz)-($oldindantulov+$tulov+$shartnom->skidka))>0){
                            echo'
                                <tr class="align-middle table-danger">
                            ';
                        }else{
                            echo'
                                <tr class="align-middle">
                            ';
                        }

                        echo'
                            <td>' . $shartnom->id . '</td>
                            <td style="white-space: pre-wrap;">' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name . '
                            </td>
                            <td>' . date('d.m.Y', strtotime($shartnom->kun)) . '</td>
                            <td>' . $shartnom->muddat . '</td>
                            <td>' . date('d.m.Y', strtotime($shartnom->yo_sana)) . '</td>
                            <td>' . number_format($savdosumma, 2, ",", " ") . '</td>
                            <td>' . number_format(($savdosumma - $oldindantulov - $chegirma)+$xis_foiz , 2, ",", " ") . '</td>
                            <td>' . number_format($oldindantulov, 2, ",", " ") . '</td>
                            <td>' . number_format($chegirma, 2, ",", " ") . '</td>
                            <td>' . number_format($krxiob22, 2, ",", " ") . '</td>
                            <td>' . number_format($tulov, 2, ",", " ") . '</td>
                            <td>' . number_format($shartnom->skidka, 2, ",", " ") . '</td>
                            <td>' . number_format(($savdosumma - $chegirma+$xis_foiz)-($oldindantulov+$tulov+$shartnom->skidka), 2, ",", " ") . '</td>
                        </tr>
                        ';
                            $chjami += $shartnom->skidka;
                            $jamifarq += ($savdosumma - $oldindantulov - $chegirma + $xis_foiz)-($oldindantulov+$tulov+$shartnom->skidka);
                    }
                    echo '
                            <tr class="align-middle text-bold">
                                <td></td>
                                <td>ЖАМИ</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>' . number_format($chjami, 2, ",", " ") . '</td>
                                <td>' . number_format($jamifarq, 2, ",", " ") . '</td>
                            </tr>
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
