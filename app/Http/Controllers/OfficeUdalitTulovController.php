<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\tulovlar;
use App\Models\filial;
use App\Models\User;

class OfficeUdalitTulovController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->filial_id == 10){
            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();
        }else{
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }
        return view('kassa.officeudalittulov', ['filial' => $filial]);
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

                    <th>Тулов тури</th>
                    <th>Шарт-№</th>
                    <th>Статус</th>
                    <th>Накд</th>
                    <th>Пластик</th>
                    <th>ХР</th>
                    <th>Клик</th>
                    <th>Авто тулов</th>
                    <th>Чегирма</th>
                    <th>Жами</th>
                    <th>Масъул ходим</th>
                    <th>Учирган кун</th>
                    <th>Учирган ходим</th>

                </tr>
            </thead>
            <tbody id="tab1">';

                $i=1;
                $naqd = $plastik = $hr = $click = $avtot = $chegirma = $jami = 0;

                $unaqd = $uplastik = $uhr = $uclick = $uavtot = $uchegirma = $ujami = 0;

                $model = tulovlar::whereBetween('kun', [$boshkun, $yakunkun])
                    ->where('status', 'Удалит')
                    ->where('filial_id', $request->filial)
                    ->orderBy('id', 'desc')
                    ->get();

                foreach ($model as $mode){
                    $kirimUser = User::where('id', $mode->user_id)->value('name');
                    $delUser = User::where('id', $mode->del_user_id)->value('name');
                    echo'
                    <tr>
                        <td>' . $i++ . '</td>
                        <td>' . date('d.m.Y', strtotime($mode->kun)) . '</td>
                        <td>' . $mode->tulovturi . '</td>
                        <td>' . $mode->shartnoma_id . '</td>
                        <td>' . $mode->status . '</td>
                        <td>' . number_format($mode->naqd, 0, ',', ' ') . '</td>
                        <td>' . number_format($mode->pastik, 0, ',', ' ') . '</td>
                        <td>' . number_format($mode->hr, 0, ',', ' ') . '</td>
                        <td>' . number_format($mode->click, 0, ',', ' ') . '</td>
                        <td>' . number_format($mode->avtot, 0, ',', ' ') . '</td>
                        <td>' . number_format($mode->chegirma, 0, ',', ' ') . '</td>
                        <td>' . number_format($mode->umumiysumma, 0, ',', ' ') . '</td>
                        <td style="white-space: wrap; width: 10%;">' . $kirimUser . '</td>
                        <td>' . date('d.m.Y', strtotime($mode->del_kun)) . '</td>
                        <td style="white-space: wrap; width: 10%;">' . $delUser . '</td>
                    </tr>
                    ';

                    $naqd += $mode->naqd;
                    $plastik += $mode->pastik;
                    $hr += $mode->hr;
                    $click += $mode->click;
                    $avtot += $mode->avtot;
                    $chegirma += $mode->chegirma;
                    $jami += $mode->umumiysumma;
                }
                $unaqd += $naqd;
                $uplastik += $plastik;
                $uhr += $hr;
                $uclick += $click;
                $uavtot += $avtot;
                $uchegirma += $chegirma;
                $ujami += $jami;

                echo'
                    <tr class="fw-bold">
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
