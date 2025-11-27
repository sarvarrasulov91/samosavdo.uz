<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\Tulovlar;
use App\Models\filial;
use App\Models\lavozim;


class OfficeJamiTulovlarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->filial_id == 10 && Auth::user()->status == 'Актив') {
            $filial = filial::where('status', 'Актив')->where('id', '!=', '10')->get();
        } else {
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }
        return view('kassa.OfficeJamiTulovlar', ['filial' => $filial]);
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

            $unaqd = 0;
            $uplastik = 0;
            $uclick = 0;
            $uhr = 0;
            $uavtot = 0;
            $uchegirma = 0;
            $ujami = 0;

            $filialbase = filial::where('status', 'Актив')->where('id','!=','10')->get();

            foreach ($filialbase as $filia) {

                $tulovlar = Tulovlar::whereBetween('kun', [$boshkun, $yakunkun])
                    ->where('status', 'Актив')
                    ->where('filial_id', $filia->id)
                    ->get();

                $naqd = $tulovlar->sum('naqd');
                $plastik = $tulovlar->sum('pastik');
                $hr = $tulovlar->sum('hr');
                $click = $tulovlar->sum('click');
                $avtot = $tulovlar->sum('avtot');
                $chegirma = $tulovlar->sum('chegirma');
                $jami = $tulovlar->sum('umumiysumma');

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
