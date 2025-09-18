<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\chiqim_boshqa;
use App\Models\turharajat;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;

class XisobotOfficeXarajatlarController extends Controller
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

            return view('xisobotlar.KunlikOfficeXarajatlar', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi]);
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

        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {

            $boshkun = $request->boshkun;
            $yakunkun = $request->yakunkun;
            $unaqd=0;
            $upastik=0;
            $uhr=0;
            $uclick=0;
            $uavtot=0;
            $ujsummasi=0;

            $dunaqd=0;
            $dupastik=0;
            $duhr=0;
            $duclick=0;
            $duavtot=0;
            $dujsummasi=0;

            $turharajat = turharajat::get();
            foreach ($turharajat as $turharaja) {
                $boshqaharajat=chiqim_boshqa::where('turharajat_id', $turharaja->id)
                ->where('kun', '>=', $boshkun)
                ->where('kun', '<=', $yakunkun)
                ->where('status', 'Актив')
                ->get();

                $naqd=0;
                $pastik=0;
                $hr=0;
                $click=0;
                $avtot=0;
                $jsummasi=0;

                $dnaqd=0;
                $dpastik=0;
                $dhr=0;
                $dclick=0;
                $davtot=0;
                $djsummasi=0;

                foreach ($boshqaharajat as $boshqaharaja) {
                    if($boshqaharaja->valyuta_id ==1){
                        $naqd+=$boshqaharaja->naqd;
                        $pastik+=$boshqaharaja->pastik;
                        $hr+=$boshqaharaja->hr;
                        $click+=$boshqaharaja->click;
                        $avtot+=$boshqaharaja->avtot;
                        $jsummasi+=$boshqaharaja->rsumma;
                    }else{
                        $dnaqd+=$boshqaharaja->naqd;
                        $dpastik+=$boshqaharaja->pastik;
                        $dhr+=$boshqaharaja->hr;
                        $dclick+=$boshqaharaja->click;
                        $davtot+=$boshqaharaja->avtot;
                        $djsummasi+=$boshqaharaja->rsumma;
                    }
                }
                    echo '
                        <tr class="text-center align-middle">
                            <td>' . $turharaja->id . '</td>
                            <td>' . $turharaja->har_name . '</td>
                            <td>' . number_format($naqd, 0, ',', ' ') . '</td>
                            <td>' . number_format($dnaqd, 0, ',', ' ') . '</td>
                            <td>' . number_format($pastik, 0, ',', ' ') . '</td>
                            <td>' . number_format($dpastik, 0, ',', ' ') . '</td>
                            <td>' . number_format($hr, 0, ',', ' ') . '</td>
                            <td>' . number_format($dhr, 0, ',', ' ') . '</td>
                            <td>' . number_format($click, 0, ',', ' ') . '</td>
                            <td>' . number_format($dclick, 0, ',', ' ') . '</td>
                            <td>' . number_format($jsummasi, 0, ',', ' ') . '</td>
                            <td>' . number_format($djsummasi, 0, ',', ' ') . '</td>
                        </tr>';

                        $unaqd+=$naqd;
                        $upastik+=$pastik;
                        $uhr+=$hr;
                        $uclick+=$click;
                        $uavtot+=$avtot;
                        $ujsummasi+=$jsummasi;

                        $dunaqd+=$dnaqd;
                        $dupastik+=$dpastik;
                        $duhr+=$dhr;
                        $duclick+=$dclick;
                        $duavtot+=$davtot;
                        $dujsummasi+=$djsummasi;

            }
                echo '
                    <tr class="fw-bold">
                        <td></td>
                        <td>ЖАМИ</td>
                        <td>' . number_format($unaqd, 0, ',', ' ') . '</td>
                        <td>' . number_format($dunaqd, 0, ',', ' ') . '</td>
                        <td>' . number_format($upastik, 0, ',', ' ') . '</td>
                        <td>' . number_format($dupastik, 0, ',', ' ') . '</td>
                        <td>' . number_format($uhr, 0, ',', ' ') . '</td>
                        <td>' . number_format($duhr, 0, ',', ' ') . '</td>
                        <td>' . number_format($uclick, 0, ',', ' ') . '</td>
                        <td>' . number_format($duclick, 0, ',', ' ') . '</td>
                        <td>' . number_format($ujsummasi, 0, ',', ' ') . '</td>
                        <td>' . number_format($dujsummasi, 0, ',', ' ') . '</td>
                    </tr>
                ';


            return;
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }
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
