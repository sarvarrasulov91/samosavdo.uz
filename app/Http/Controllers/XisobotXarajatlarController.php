<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Xarajat;
use App\Models\turharajat;
use App\Models\filial;

class XisobotXarajatlarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {

            if(Auth::user()->filial_id == 10){

                $filial = filial::where('status', 'Актив')->get();

            }else{

                $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();

            }

            return view('xisobotlar.KunlikXarajatlar', ['filial' => $filial]);
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
        $filial = $request->filial;

        $unaqd = $upastik = $uhr = $uclick = $uavtot = $ujsummasi = 0;

        $turharajat = turharajat::get();
        foreach ($turharajat as $turharaja) {

            $boshqaharajat = Xarajat::where('filial_id', $filial)
                ->whereBetween('kun', [$boshkun, $yakunkun])
                ->where('turharajat_id', $turharaja->id)
                ->where('status', 'Актив')
                ->get();

            $naqd = $pastik = $hr = $click = $avtot = $jsummasi = 0;

            foreach ($boshqaharajat as $boshqaharaja) {
                $naqd += $boshqaharaja->naqd;
                $pastik += $boshqaharaja->pastik;
                $hr += $boshqaharaja->hr;
                $click += $boshqaharaja->click;
                $avtot += $boshqaharaja->avtot;
                $jsummasi += $boshqaharaja->summasi;
            }

                echo '
                    <tr class="text-center align-middle">
                        <td>' . $turharaja->id . '</td>
                        <td>' . $turharaja->har_name . '</td>
                        <td>' . number_format($naqd, 0, ',', ' ') . '</td>
                        <td>' . number_format($pastik, 0, ',', ' ') . '</td>
                        <td>' . number_format($hr, 0, ',', ' ') . '</td>
                        <td>' . number_format($click, 0, ',', ' ') . '</td>
                        <td>' . number_format($avtot, 0, ',', ' ') . '</td>
                        <td>' . number_format($jsummasi, 0, ',', ' ') . '</td>
                    </tr>';

                    $unaqd += $naqd;
                    $upastik += $pastik;
                    $uhr += $hr;
                    $uclick += $click;
                    $uavtot += $avtot;
                    $ujsummasi += $jsummasi;
        }

        echo '
            <tr class="fw-bold">
                <td></td>
                <td>ЖАМИ</td>
                <td>' . number_format($unaqd, 0, ',', ' ') . '</td>
                <td>' . number_format($upastik, 0, ',', ' ') . '</td>
                <td>' . number_format($uhr, 0, ',', ' ') . '</td>
                <td>' . number_format($uclick, 0, ',', ' ') . '</td>
                <td>' . number_format($uavtot, 0, ',', ' ') . '</td>
                <td>' . number_format($ujsummasi, 0, ',', ' ') . '</td>
            </tr>
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
