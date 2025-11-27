<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KirimTovar;
use App\Models\tmodel;
use App\Models\xissobotoy;
use App\Models\filial;

class TovarQoldigiOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {

            $filial = filial::where('status', 'Актив')->get();

            return view('tovarlar.tovarlartahlili', ['filial' => $filial]);

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
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $filiallar = filial::where('status', 'Актив')->get();

        $i = 1;

        // Umumiy yig‘indi
        $totals = [
            'TOBDSoni' => 0, 'TOBDSumma' => 0,
            'TOBSSoni' => 0, 'TOBSSumma' => 0,
            'TOBKDSoni' => 0, 'TOBKDSumma' => 0,
            'TOBKSSoni' => 0, 'TOBKSSumma' => 0,
            'TOBCHDSoni' => 0, 'TOBCHDSumma' => 0,
            'TOBCHSSoni' => 0, 'TOBCHSSumma' => 0,
        ];

        echo '
            <table class="table table-bordered table-responsive-sm text-center align-middle" style="font-size: 12px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th rowspan="3">№</th>
                        <th rowspan="3">Филиал</th>
                        <th colspan="4">Ой бошига</th>
                        <th colspan="4">Кирим</th>
                        <th colspan="4">Чиқим</th>
                        <th colspan="4">Ой охирига</th>
                    </tr>
                    <tr class="text-bold text-primary align-middle">
                        <th colspan="2">Доллар</th>
                        <th colspan="2">Сўм</th>
                        <th colspan="2">Доллар</th>
                        <th colspan="2">Сўм</th>
                        <th colspan="2">Доллар</th>
                        <th colspan="2">Сўм</th>
                        <th colspan="2">Доллар</th>
                        <th colspan="2">Сўм</th>
                    </tr>
                    <tr class="text-bold text-primary align-middle">
                        <th>Сони</th><th>Суммаси</th>
                        <th>Сони</th><th>Суммаси</th>
                        <th>Сони</th><th>Суммаси</th>
                        <th>Сони</th><th>Суммаси</th>
                        <th>Сони</th><th>Суммаси</th>
                        <th>Сони</th><th>Суммаси</th>
                        <th>Сони</th><th>Суммаси</th>
                        <th>Сони</th><th>Суммаси</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

        foreach ($filiallar as $filialinfo) {

            $data = KirimTovar::where('filial_id', $filialinfo->id)
                ->selectRaw("
                        SUM(CASE WHEN valyuta_id=2 AND xis_oyi < ?
                                 AND (status='Сотилмаган' OR ch_xis_oyi >= ?) THEN 1 ELSE 0 END) as TOBDSoni,
                        SUM(CASE WHEN valyuta_id=2 AND xis_oyi < ?
                                 AND (status='Сотилмаган' OR ch_xis_oyi >= ?) THEN narhi ELSE 0 END) as TOBDSumma,
                        SUM(CASE WHEN valyuta_id=1 AND xis_oyi < ?
                                 AND (status='Сотилмаган' OR ch_xis_oyi >= ?) THEN 1 ELSE 0 END) as TOBSSoni,
                        SUM(CASE WHEN valyuta_id=1 AND xis_oyi < ?
                                 AND (status='Сотилмаган' OR ch_xis_oyi >= ?) THEN narhi ELSE 0 END) as TOBSSumma,

                        SUM(CASE WHEN valyuta_id=2 AND xis_oyi = ?
                                 AND status NOT IN ('Удалит','Актив') THEN 1 ELSE 0 END) as TOBKDSoni,
                        SUM(CASE WHEN valyuta_id=2 AND xis_oyi = ?
                                 AND status NOT IN ('Удалит','Актив') THEN narhi ELSE 0 END) as TOBKDSumma,
                        SUM(CASE WHEN valyuta_id=1 AND xis_oyi = ?
                                 AND status NOT IN ('Удалит','Актив') THEN 1 ELSE 0 END) as TOBKSSoni,
                        SUM(CASE WHEN valyuta_id=1 AND xis_oyi = ?
                                 AND status NOT IN ('Удалит','Актив') THEN narhi ELSE 0 END) as TOBKSSumma,

                        SUM(CASE WHEN valyuta_id=2 AND ch_xis_oyi = ?
                                 AND status != 'Удалит' THEN 1 ELSE 0 END) as TOBCHDSoni,
                        SUM(CASE WHEN valyuta_id=2 AND ch_xis_oyi = ?
                                 AND status != 'Удалit' THEN narhi ELSE 0 END) as TOBCHDSumma,
                        SUM(CASE WHEN valyuta_id=1 AND ch_xis_oyi = ?
                                 AND status != 'Удалit' THEN 1 ELSE 0 END) as TOBCHSSoni,
                        SUM(CASE WHEN valyuta_id=1 AND ch_xis_oyi = ?
                                 AND status != 'Удалit' THEN narhi ELSE 0 END) as TOBCHSSumma
                    ", [
                    $xis_oyi, $xis_oyi, $xis_oyi, $xis_oyi,
                    $xis_oyi, $xis_oyi, $xis_oyi, $xis_oyi,
                    $xis_oyi, $xis_oyi, $xis_oyi, $xis_oyi,
                    $xis_oyi, $xis_oyi, $xis_oyi, $xis_oyi,
                ])
                ->first();

            // Ой охирига hisoblash
            $TOBYDSoni   = $data->TOBDSoni + $data->TOBKDSoni - $data->TOBCHDSoni;
            $TOBYDSumma  = $data->TOBDSumma + $data->TOBKDSumma - $data->TOBCHDSumma;
            $TOBYSSoni   = $data->TOBSSoni + $data->TOBKSSoni - $data->TOBCHSSoni;
            $TOBYSSumma  = $data->TOBSSumma + $data->TOBKSSumma - $data->TOBCHSSumma;

            echo "<tr>
                    <td>{$i}</td>
                    <td>{$filialinfo->fil_name}</td>

                    <td>".number_format($data->TOBDSoni,0,","," ")."</td>
                    <td>".number_format($data->TOBDSumma,0,","," ")."</td>
                    <td>".number_format($data->TOBSSoni,0,","," ")."</td>
                    <td>".number_format($data->TOBSSumma,0,","," ")."</td>

                    <td>".number_format($data->TOBKDSoni,0,","," ")."</td>
                    <td>".number_format($data->TOBKDSumma,0,","," ")."</td>
                    <td>".number_format($data->TOBKSSoni,0,","," ")."</td>
                    <td>".number_format($data->TOBKSSumma,0,","," ")."</td>

                    <td>".number_format($data->TOBCHDSoni,0,","," ")."</td>
                    <td>".number_format($data->TOBCHDSumma,0,","," ")."</td>
                    <td>".number_format($data->TOBCHSSoni,0,","," ")."</td>
                    <td>".number_format($data->TOBCHSSumma,0,","," ")."</td>

                    <td>".number_format($TOBYDSoni,0,","," ")."</td>
                    <td>".number_format($TOBYDSumma,0,","," ")."</td>
                    <td>".number_format($TOBYSSoni,0,","," ")."</td>
                    <td>".number_format($TOBYSSumma,0,","," ")."</td>
                </tr>";

            $i++;

            // umumiy yig‘indi
            foreach ($totals as $k => $v) {
                $totals[$k] += $data->$k;
            }
        }

        echo "<tr class='text-bold'>
                <td></td>
                <td><b>ЖАМИ</b></td>
                <td><b>".number_format($totals['TOBDSoni'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBDSumma'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBSSoni'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBSSumma'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBKDSoni'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBKDSumma'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBKSSoni'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBKSSumma'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBCHDSoni'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBCHDSumma'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBCHSSoni'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBCHSSumma'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBDSoni']+$totals['TOBKDSoni']-$totals['TOBCHDSoni'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBDSumma']+$totals['TOBKDSumma']-$totals['TOBCHDSumma'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBSSoni']+$totals['TOBKSSoni']-$totals['TOBCHSSoni'],0,","," ")."</b></td>
                <td><b>".number_format($totals['TOBSSumma']+$totals['TOBKSSumma']-$totals['TOBCHSSumma'],0,","," ")."</b></td>
            </tr>";

        echo "</tbody></table>";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ktovar = KirimTovar::where('filial_id', $id)
            ->where('status', 'Сотилмаган')
            ->selectRaw("
                tmodel_id,
                SUM(CASE WHEN valyuta_id = 1 THEN 1 ELSE 0 END) as sum_soni,
                SUM(CASE WHEN valyuta_id = 1 THEN narhi ELSE 0 END) as sum_summasi,
                SUM(CASE WHEN valyuta_id = 2 THEN 1 ELSE 0 END) as dollar_soni,
                SUM(CASE WHEN valyuta_id = 2 THEN narhi ELSE 0 END) as dollar_summasi
            ")
            ->groupBy('tmodel_id')
            ->get();

        echo '
            <table class="table table-bordered table-responsive-sm text-center align-middle" style="font-size: 12px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th rowspan="3">№</th>
                        <th rowspan="3">Модел ID</th>
                        <th rowspan="3">Товар номи</th>
                        <th colspan="4">Товарлар қолдиғи</th>
                    </tr>
                    <tr class="text-bold text-primary align-middle">
                        <th colspan="2">Доллар</th>
                        <th colspan="2">Сўм</th>
                    </tr>
                    <tr class="text-bold text-primary align-middle">
                        <th>Сони</th>
                        <th>Суммаси</th>
                        <th>Сони</th>
                        <th>Суммаси</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                    $i = 1;
                    $TovarJamiDollarSoni = 0;
                    $TovarJamiDollasrummasi = 0;
                    $TovarJamiSumSoni = 0;
                    $TovarJamiSumsummasi = 0;

                    foreach ($ktovar as $row) {
                        $model = tmodel::with(['tur', 'brend'])->find($row->tmodel_id);
                        $tmodel_name = $model->tur->tur_name .' '. $model->brend->brend_name .' '. $model->model_name;

                        echo "
                    <tr>
                        <td>{$i}</td>
                        <td>{$row->tmodel_id}</td>
                        <td>{$tmodel_name}</td>
                        <td>" . number_format($row->dollar_soni, 0, ",", " ") . "</td>
                        <td>" . number_format($row->dollar_summasi, 0, ",", " ") . "</td>
                        <td>" . number_format($row->sum_soni, 0, ",", " ") . "</td>
                        <td>" . number_format($row->sum_summasi, 0, ",", " ") . "</td>
                    </tr>
                ";

                        $i++;
                        $TovarJamiDollarSoni += $row->dollar_soni;
                        $TovarJamiDollasrummasi += $row->dollar_summasi;
                        $TovarJamiSumSoni += $row->sum_soni;
                        $TovarJamiSumsummasi += $row->sum_summasi;
                    }

                    echo "
                <tr class='text-bold'>
                    <td></td>
                    <td><b>ЖАМИ</b></td>
                    <td></td>
                    <td><b>" . number_format($TovarJamiDollarSoni, 0, ",", " ") . "</b></td>
                    <td><b>" . number_format($TovarJamiDollasrummasi, 0, ",", " ") . "</b></td>
                    <td><b>" . number_format($TovarJamiSumSoni, 0, ",", " ") . "</b></td>
                    <td><b>" . number_format($TovarJamiSumsummasi, 0, ",", " ") . "</b></td>
                </tr>
            </tbody>
            </table>";

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
