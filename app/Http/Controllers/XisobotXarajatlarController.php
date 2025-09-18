<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\boshqaharajat1;
use App\Models\turharajat;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;

class XisobotXarajatlarController extends Controller
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

            if(Auth::user()->lavozim_id==1){
                $filial = filial::where('status', 'Актив')->get();
            }else{
                $filial = filial::where('status', 'Актив')->where('id',Auth::user()->filial_id)->get();
            }
            
            return view('xisobotlar.KunlikXarajatlar', ['filial'=>$filial, 'filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi]);
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

            $turharajat = turharajat::get();
            foreach ($turharajat as $turharaja) {
                $boshqaharajat1 = new boshqaharajat1($request->filial);
                $boshqaharajat=$boshqaharajat1
                ->where('turharajat_id', $turharaja->id)
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

                foreach ($boshqaharajat as $boshqaharaja) {
                    $naqd+=$boshqaharaja->naqd;
                    $pastik+=$boshqaharaja->pastik;
                    $hr+=$boshqaharaja->hr;
                    $click+=$boshqaharaja->click;
                    $avtot+=$boshqaharaja->avtot;
                    $jsummasi+=$boshqaharaja->summasi;
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

                        $unaqd+=$naqd;
                        $upastik+=$pastik;
                        $uhr+=$hr;
                        $uclick+=$click;
                        $uavtot+=$avtot;
                        $ujsummasi+=$jsummasi;
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

            // $turharajat = turharajat::with(['boshqaharajat1' => function ($query) {
            //     global $request;
            //     $query->selectRaw('
            //         turharajat_id,
            //         SUM(naqd) as total_naqd,
            //         SUM(pastik) as total_pastik,
            //         SUM(hr) as total_hr,
            //         SUM(click) as total_click,
            //         SUM(avtot) as total_avtot,
            //         SUM(summasi) as total_summasi
            //     ')
            //     ->where('status', 'Актив')
            //     ->where('kun', '>=', $request->boshkun)
            //     ->where('kun', '<=', $request->yakunkun)
            //     ->groupBy('turharajat_id');
            // }])
            // ->select('id', 'har_name')
            // ->orderBy('id', 'desc')
            // ->get();
