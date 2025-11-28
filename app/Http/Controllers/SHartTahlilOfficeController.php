<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Shartnoma;
use App\Models\Savdo;
use App\Models\xissobotoy;
use App\Models\filial;
use Illuminate\Support\Facades\Auth;

class SHartTahlilOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();

            $du2 = Carbon::parse($xis_oyi)->locale('ru')->translatedFormat('Y-F');

            return view('shartnoma.SHTahlil', ['filial' => $filial, 'du2' => $du2 ]);
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
        // --- Umumiy o‘zgaruvchilar
        $uob_shsoni = $uob_shtsumma = 0;
        $u_yo_shsoni = $u_yo_shsumma = 0;
        $u_nach_shsoni = $U_nach_shsumma = 0;
        $u_q_shsoni = $u_q_shsumma = 0;
        $u_u_shsoni = $u_u_shsumma = 0;
        $uoo_shsoni = $uoo_shtsumma = 0;

        // --- Oxirgi xisobot oyi
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

        // --- Barcha shartnomalarni 1 marta olish
        $allShartnomalar = Shartnoma::get()->groupBy('filial_id');

        // --- Barcha savdolarni 1 marta olish
        $allSavdolar = Savdo::get()->groupBy('shartnoma_id');

        // --- Barcha filiallar
        $filialbase = filial::where('status', 'Актив')->get();


        foreach ($filialbase as $filial) {

            $shsoni = $shtsumma = 0;
            $yo_shsoni = $yo_shsumma = 0;
            $nach_shsoni = $nach_shsumma = 0;
            $q_shsoni = $q_shsumma = 0;
            $u_shsoni = $u_shsumma = 0;

            // Filialga tegishli shartnomalar
            $shartnoma1 = $allShartnomalar[$filial->id] ?? collect([]);

            foreach ($shartnoma1 as $shart) {

                // Savdolar birga grouped turadi
                $savdolar = $allSavdolar[$shart->id] ?? collect([]);

                // 1) Oldingi oylardan kelganlar
                if ($shart->xis_oyi < $xis_oyi && $shart->status == 'Актив') {

                    $sum = $savdolar->where('status2', 'Шартнома')->sum('msumma');

                    if ($sum > 0) {
                        $shsoni++;
                        $shtsumma += $sum;
                    }
                }

                // 2) Oyboshida davom etayotganlar
                if ($shart->xis_oyi < $xis_oyi && $shart->yo_xis_oyi >= $xis_oyi) {

                    $sum = $savdolar->where('status', 'Шартнома')->sum('msumma');

                    $yo_shsoni++;
                    $yo_shsumma += $sum;
                }

                // 3) Shu oyda yangi ochilganlar
                if ($shart->xis_oyi == $xis_oyi) {

                    $sum = $savdolar->where('status2', 'Шартнома')->sum('msumma');

                    $nach_shsoni++;
                    $nach_shsumma += $sum;
                }

                // 4) Qo‘shimcha savdo
                $qush = $savdolar->where('q_xis_oyi', $xis_oyi)->sum('msumma');

                if ($qush > 0) {
                    $q_shsoni++;
                    $q_shsumma += $qush;
                }

                // 5) O‘chirilgan savdo
                $udal = $savdolar->where('del_xis_oyi', $xis_oyi)->sum('msumma');

                if ($udal > 0) {
                    $u_shsoni++;
                    $u_shsumma += $udal;
                }
            }

            // --- HTML qator
            echo '
                <tr class="text-center align-middle" id="modalFilYil"
                    data-filial_id="'.$filial->id.'" data-filial_name="'.$filial->fil_name.'">
                    <td>'.$filial->id.'</td>
                    <td>'.$filial->fil_name.'</td>
                    <td>'.number_format($shsoni + $yo_shsoni, 0, ',', ' ').'</td>
                    <td>'.number_format($shtsumma + $yo_shsumma, 0, ',', ' ').'</td>
                    <td>'.number_format($nach_shsoni, 0, ',', ' ').'</td>
                    <td>'.number_format($nach_shsumma, 0, ',', ' ').'</td>
                    <td>'.number_format($yo_shsoni, 0, ',', ' ').'</td>
                    <td>'.number_format($yo_shsumma, 0, ',', ' ').'</td>
                    <td>'.number_format($q_shsoni, 0, ',', ' ').'</td>
                    <td>'.number_format($q_shsumma, 0, ',', ' ').'</td>
                    <td>'.number_format($u_shsoni, 0, ',', ' ').'</td>
                    <td>'.number_format($u_shsumma, 0, ',', ' ').'</td>
                    <td>'.number_format($shsoni + $nach_shsoni - $yo_shsoni, 0, ',', ' ').'</td>
                    <td>'.number_format($shtsumma + $nach_shsumma - $u_shsumma, 0, ',', ' ').'</td>
                </tr>
            ';

            // Umumiy yig‘indilar
            $u_nach_shsoni += $nach_shsoni;
            $U_nach_shsumma += $nach_shsumma;
            $u_yo_shsoni += $yo_shsoni;
            $u_yo_shsumma += $yo_shsumma;
            $u_q_shsoni += $q_shsoni;
            $u_q_shsumma += $q_shsumma;
            $u_u_shsoni += $u_shsoni;
            $u_u_shsumma += $u_shsumma;

            $uob_shsoni += ($shsoni + $yo_shsoni);
            $uob_shtsumma += ($shtsumma + $yo_shsumma);

            $uoo_shsoni += ($shsoni + $nach_shsoni - $yo_shsoni);
            $uoo_shtsumma += ($shtsumma + $nach_shsumma - $u_shsumma);
        }


        // --- Jadval oxiri
        echo '
            <tr class="text-center align-middle fw-bold">
                <td></td><td>ЖАМИ:</td>
                <td>'.number_format($uob_shsoni).'</td>
                <td>'.number_format($uob_shtsumma).'</td>
                <td>'.number_format($u_nach_shsoni).'</td>
                <td>'.number_format($U_nach_shsumma).'</td>
                <td>'.number_format($u_yo_shsoni).'</td>
                <td>'.number_format($u_yo_shsumma).'</td>
                <td>'.number_format($u_q_shsoni).'</td>
                <td>'.number_format($u_q_shsumma).'</td>
                <td>'.number_format($u_u_shsoni).'</td>
                <td>'.number_format($u_u_shsumma).'</td>
                <td>'.number_format($uoo_shsoni).'</td>
                <td>'.number_format($uoo_shtsumma).'</td>
            </tr>
        ';


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*** Yil buyicha taxlil */

        $id = $request->id;

        // Shartnomalardan faqat yilni olish
        $shartXisYil = Shartnoma::selectRaw('YEAR(xis_oyi) as yil')
            ->where('filial_id', $id)
            ->groupBy('yil')
            ->orderBy('yil')
            ->get();

        // Barcha shartnomalar
        $shartnomalar = Shartnoma::where('filial_id', $id)->get();

        // Savdolarni shartnoma ID bo‘yicha group qilish
        $savdolar = Savdo::where('filial_id', $id)->get()->groupBy('shartnoma_id');

        // => Yillar bo‘yicha hisoblash
        foreach ($shartXisYil as $row) {

            $yil = $row->yil;

            // Yil bo‘yicha umumiy o‘zgaruvchilar
            $shsoni = $shtsumma = 0;
            $Oy_bosh_yo_soni = $Oy_bosh_yo_shsumma = 0;
            $yo_shsoni = $yo_shsumma = 0;
            $nach_shsoni = $nach_shsumma = 0;
            $q_shsoni = $q_shsumma = 0;
            $u_shsoni = $u_shsumma = 0;

            foreach ($shartnomalar as $shart) {

                $sh_yil = date('Y', strtotime($shart->xis_oyi));

                $shartSavdoList = $savdolar[$shart->id] ?? collect([]);

                // 1) Oldingi yillardan davom etayotganlar
                if ($sh_yil < $yil && $shart->status == 'Актив') {
                    $sum = $shartSavdoList->where('status2', 'Шартнома')->sum('msumma');
                    if ($sum > 0) {
                        $shsoni++;
                        $shtsumma += $sum;
                    }
                }

                // 2) Yil boshida davom etayotganlar
                if ($sh_yil < $yil && $shart->yo_xis_oyi && date('Y', strtotime($shart->yo_xis_oyi)) >= $yil) {
                    $Oy_bosh_yo_soni++;
                    $Oy_bosh_yo_shsumma += $shartSavdoList->where('status', 'Шартнома')->sum('msumma');
                }

                // 3) Shu yilda yopilganlar
                if ($shart->yo_xis_oyi && date('Y', strtotime($shart->yo_xis_oyi)) == $yil) {
                    $yo_shsoni++;
                    $yo_shsumma += $shartSavdoList->where('status', 'Шартнома')->sum('msumma');
                }

                // 4) Shu yilda ochilganlar
                if ($sh_yil == $yil) {
                    $nach_shsoni++;
                    $nach_shsumma += $shartSavdoList->where('status2', 'Шартнома')->sum('msumma');
                }

                // 5) Qo‘shimcha savdo
                $q_shsumma_yil = $shartSavdoList
                    ->where('q_xis_oyi', 'LIKE', "$yil%")
                    ->sum('msumma');

                if ($q_shsumma_yil > 0) {
                    $q_shsoni++;
                    $q_shsumma += $q_shsumma_yil;
                }

                // 6) O‘chirilgan savdo
                $u_shsumma_yil = $shartSavdoList
                    ->where('del_xis_oyi', 'LIKE', "$yil%")
                    ->sum('msumma');

                if ($u_shsumma_yil > 0) {
                    $u_shsoni++;
                    $u_shsumma += $u_shsumma_yil;
                }
            }

            $filial = filial::find($id);

            // Jadvalni chiqarish

            echo "
                <tr class='text-center align-middle' id='modalfil' data-filial_id={$id} data-xisyil={$yil}  data-filial_name={$filial->fil_name}>
                    <td>$yil</td>
                    <td>".number_format($shsoni + $Oy_bosh_yo_soni)."</td>
                    <td>".number_format($shtsumma + $Oy_bosh_yo_shsumma)."</td>
                    <td>".number_format($nach_shsoni)."</td>
                    <td>".number_format($nach_shsumma)."</td>
                    <td>".number_format($yo_shsoni)."</td>
                    <td>".number_format($yo_shsumma)."</td>
                    <td>".number_format($q_shsoni)."</td>
                    <td>".number_format($q_shsumma)."</td>
                    <td>".number_format($u_shsoni)."</td>
                    <td>".number_format($u_shsumma)."</td>
                    <td>".number_format($shsoni + $Oy_bosh_yo_soni + $nach_shsoni - $yo_shsoni)."</td>
                    <td>".number_format($shtsumma + $Oy_bosh_yo_shsumma + $nach_shsumma - $yo_shsumma - $u_shsumma)."</td>
                </tr>
            ";
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $yil = $request->yil;

        $shartXisOy = Shartnoma::select('xis_oyi')
            ->where('filial_id', $id)
            ->whereYear('xis_oyi', $yil)
            ->groupBy('xis_oyi')
            ->get();

        $shartnomalar = Shartnoma::where('filial_id', $id)->whereYear('xis_oyi', $yil)->get();
        $savdolar = Savdo::where('filial_id', $id)->whereYear('xis_oyi', $yil)->get()->groupBy('shartnoma_id');

        foreach ($shartXisOy as $xisOyiRow) {

            $xis_oyi = $xisOyiRow->xis_oyi;
            $du2 = Carbon::parse($xis_oyi)->locale('ru')->translatedFormat('Y-F');

            $shsoni = $shtsumma = 0;
            $Oy_bosh_yo_soni = $Oy_bosh_yo_shsumma = 0;

            $yo_shsoni = $yo_shsumma = 0;
            $nach_shsoni = $nach_shsumma = 0;
            $q_shsoni = $q_shsumma = 0;
            $u_shsoni = $u_shsumma = 0;

            foreach ($shartnomalar as $shart) {

                $shartSavdoList = $savdolar[$shart->id] ?? collect([]);

                // 1) Oldingi oylardan kelayotganlar
                if ($shart->xis_oyi < $xis_oyi && $shart->status == 'Актив') {
                    $sum = $shartSavdoList->where('status2', 'Шартнома')->sum('msumma');
                    if ($sum > 0) {
                        $shsoni++;
                        $shtsumma += $sum;
                    }
                }

                // 2) Oy boshida davom etayotganlar
                if ($shart->xis_oyi < $xis_oyi && $shart->yo_xis_oyi > 0 && $shart->yo_xis_oyi >= $xis_oyi) {
                    $Oy_bosh_yo_soni++;
                    $Oy_bosh_yo_shsumma += $shartSavdoList->where('status', 'Шартнома')->sum('msumma');
                }

                // 3) Shu oyda yopilganlar
                if ($shart->yo_xis_oyi == $xis_oyi) {
                    $yo_shsoni++;
                    $yo_shsumma += $shartSavdoList->where('status', 'Шартнома')->sum('msumma');
                }

                // 4) Shu oyda yangi ochilganlar
                if ($shart->xis_oyi == $xis_oyi) {
                    $nach_shsoni++;
                    $nach_shsumma += $shartSavdoList->where('status2', 'Шартnoma')->sum('msumma');
                }

                // 5) Qushimcha savdo
                $qush = $shartSavdoList->where('q_xis_oyi', $xis_oyi)->sum('msumma');
                if ($qush > 0) {
                    $q_shsoni++;
                    $q_shsumma += $qush;
                }

                // 6) Udalit savdo
                $udal = $shartSavdoList->where('del_xis_oyi', $xis_oyi)->sum('msumma');
                if ($udal > 0) {
                    $u_shsoni++;
                    $u_shsumma += $udal;
                }
            }

            echo "
                <tr class='text-center align-middle'>
                    <td>$du2</td>
                    <td>".number_format($shsoni + $Oy_bosh_yo_soni)."</td>
                    <td>".number_format($shtsumma + $Oy_bosh_yo_shsumma)."</td>
                    <td>".number_format($nach_shsoni)."</td>
                    <td>".number_format($nach_shsumma)."</td>
                    <td>".number_format($yo_shsoni)."</td>
                    <td>".number_format($yo_shsumma)."</td>
                    <td>".number_format($q_shsoni)."</td>
                    <td>".number_format($q_shsumma)."</td>
                    <td>".number_format($u_shsoni)."</td>
                    <td>".number_format($u_shsumma)."</td>
                    <td>".number_format($shsoni + $Oy_bosh_yo_soni + $nach_shsoni - $yo_shsoni)."</td>
                    <td>".number_format($shtsumma + $Oy_bosh_yo_shsumma + $nach_shsumma - $yo_shsumma - $u_shsumma)."</td>
                </tr>
            ";
        }

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
