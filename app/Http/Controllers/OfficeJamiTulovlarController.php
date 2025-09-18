<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\tulovlar1;
use App\Models\filial;
use App\Models\lavozim;


class OfficeJamiTulovlarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        if (Auth::user()->filial_id == 10 && Auth::user()->status == 'Актив') {
            $filial = filial::where('status', 'Актив')->where('id', '!=', '10')->get();
        } else {
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }
        return view('kassa.OfficeJamiTulovlar', ['filial' => $filial, 'xis_oyi' => $xis_oyi, 'filial_name' => $filial_name, 'lavozim_name' => $lavozim_name]);
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

        echo '<br><div class="row justify-content-md-center">
                <h3 class=" text-center text-primary"><b>ЖАМИ ТУЛОВЛАР</b></h3>
                <div class="col-xl-12">
                <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Филиал</th>
                        <th>Накд</th>
                        <th>Пластик</th>
                        <th>ХР</th>
                        <th>Клик</th>
                        <th>Автотулов</th>
                        <th>Чегирма</th>
                        <th>Жами</th>
                    </tr>
                </thead>
                <tbody id="tab1">';
            $naqd = 0;
            $unaqd = 0;
            $plastik = 0;
            $uplastik = 0;
            $click = 0;
            $uclick = 0;
            $hr = 0;
            $uhr = 0;
            $avtot = 0;
            $uavtot = 0;
            $chegirma = 0;
            $uchegirma = 0;
            $jami = 0;
            $ujami = 0;

            $filialbase = filial::where('status', 'Актив')->where('id','!=','10')->get();
            foreach ($filialbase as $filia) {
                $tulovlar = new tulovlar1($filia->id); 
                $naqd = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->sum('naqd');
                $plastik = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->sum('pastik');
                $hr = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->sum('hr');
                $click = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->sum('click');
                $avtot = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->sum('avtot');
                $chegirma = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->sum('chegirma');
                $jami = $tulovlar->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->sum('umumiysumma');
                
                echo '
                    <tr class="text-center align-middle">
                        <td>' . $filia->id . '</td>
                        <td>' . $filia->fil_name . '</td>
                        <td>' . number_format($naqd, 0, ',', ' ') . '</td>
                        <td>' . number_format($plastik, 0, ',', ' ') . '</td>
                        <td>' . number_format($hr, 0, ',', ' ') . '</td>
                        <td>' . number_format($click, 0, ',', ' ') . '</td>
                        <td>' . number_format($avtot, 0, ',', ' ') . '</td>
                        <td>' . number_format($chegirma, 0, ',', ' ') . '</td>
                        <td>' . number_format($jami, 0, ',', ' ') . '</td>
                    </tr>';

                $unaqd += $naqd;
                $uplastik += $plastik;
                $uhr += $hr;
                $uclick += $click;
                $uavtot += $avtot;
                $uchegirma += $chegirma;
                $ujami += $jami;
            }

            echo '
            <tr class="text-center align-middle fw-bold">
                <td></td>
                <td>ЖАМИ</td>
                <td>' . number_format($unaqd, 0, ',', ' ') . '</td>
                <td>' . number_format($uplastik, 0, ',', ' ') . '</td>
                <td>' . number_format($uhr, 0, ',', ' ') . '</td>
                <td>' . number_format($uclick, 0, ',', ' ') . '</td>
                <td>' . number_format($uavtot, 0, ',', ' ') . '</td>
                <td>' . number_format($uchegirma, 0, ',', ' ') . '</td>
                <td>' . number_format($ujami, 0, ',', ' ') . '</td>
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
