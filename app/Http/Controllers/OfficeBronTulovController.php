<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\tulovlar1;
use App\Models\filial;
use App\Models\User;
use App\Models\lavozim;


class OfficeBronTulovController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $xis_oy = xissobotoy::all();
        if((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 12 || Auth::user()->lavozim_id == 13 || Auth::user()->lavozim_id == 14) && Auth::user()->status == 'Актив'){
            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();
        }else{
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }
        return view('kassa.officebrontulov', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name,'filial' => $filial, 'xis_oyi' => $xis_oyi]);
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
                    <th>Брон кун</th>
                    <th>Брон ходим</th>
                    
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
                $model=$tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('tulovturi', 'Брон')->orderBy('id', 'desc')->get();
                foreach ($model as $mode){
                    $kirimUser=User::where('id', $mode->user_id)->value('name');
                    $delUser=User::where('id', $mode->del_user_id)->value('name');
                    echo'
                    <tr>
                        <td>' . $i++ . '</td>
                        <td>' . date('d.m.Y', strtotime($mode->kun)) . '</td>
                        <td>' . $mode->tulovturi . '</td>
                        <td>' . $mode->shartnomaid . '</td>
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
                    
                    $naqd+=$mode->naqd;
                    $plastik+=$mode->pastik;
                    $hr+=$mode->hr;
                    $click+=$mode->click;
                    $avtot+=$mode->avtot;
                    $chegirma+=$mode->chegirma;
                    $jami+=$mode->umumiysumma;
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
