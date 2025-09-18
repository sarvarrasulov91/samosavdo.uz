<?php

namespace App\Http\Controllers;

use App\Models\filial;
use App\Models\ktovar1;
use App\Models\valyuta;
use App\Models\xissobotoy;
use App\Models\lavozim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TovarXisobotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 2) && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

            if (Auth::user()->filial_id == 10){
                $filial = filial::where('status', 'Актив')->get();
            }else{
                $filial = filial::where('id', Auth::user()->filial_id)->get();
            }
            
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');

            return view('xisobotlar.TovarXisobot', [
                'filial_name' => $filial_name, 
                'lavozim_name' => $lavozim_name, 
                'xis_oyi' => $xis_oyi, 
                'filial' => $filial
                ]);
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

        $i = 1;
        $jamiTovarOyBoshi = 0;
        $jamiTovarKirim = 0;
        $jamiTovarChiqim = 0;
        $jamiShartnoma = 0;
        $jamiNaqd = 0;
        $jamiBonus = 0;
        $jamiAlmashgan = 0;
        $jamiAsosiy = 0;
        $jamiQaytgan = 0;
        $jamiQoldiqTovar = 0;

        echo'
        <table class="table table-bordered text-center align-middle m-auto" style="font-size: 12px; width: 95%;">
            <thead>
                <tr class="text-bold text-primary align-middle">
                    <th rowspan="2"> № </th>
                    <th rowspan="2"> Филиал </th>
                    <th rowspan="2"> ОБ Товар </th>
                    <th rowspan="2"> Кирим товар </th>
                    <th colspan="6"> Чиким товарлар </th>
                    <th rowspan="2">Жами чиким</th>
                    <th rowspan="2">Колдик товар</th>
                </tr>
                <tr class="text-bold text-primary align-middle">
                    <th>Шартнома</th>
                    <th>Накд</th>
                    <th>Бонус</th>
                    <th>Алмашув</th>
                    <th>Асосий</th>
                    <th>Кайтган</th>
                </tr>
            </thead>
            <tbody id="tab1">';

        $tovarKurs = valyuta::query()->where('id', 2)->value('tovar_kurs');

        $filials = filial::where('status', 'Актив')->get();
        foreach ($filials as $filial){

            $tovarlar = new ktovar1($filial->id);

            // Calculate initial balances
            $ktovarOyBoshiDollar = $tovarlar->where(function ($query) use ($boshkun) {
                $query->where('valyuta_id', '2')
                    ->whereDate('kun', '<', $boshkun)
                    ->where(function ($subQuery) use ($boshkun) {
                        $subQuery->where('status', 'Сотилмаган')
                            ->orWhereDate('ch_kun', '>=', $boshkun);
                    });
            })->sum('narhi');

            $ktovarOyBoshiSum = $tovarlar->where(function ($query) use ($boshkun) {
                $query->where('valyuta_id', '1')
                    ->whereDate('kun', '<', $boshkun)
                    ->where(function ($subQuery) use ($boshkun) {
                        $subQuery->where('status', 'Сотилмаган')
                            ->orWhereDate('ch_kun', '>=', $boshkun);
                    });
            })->sum('narhi') / $tovarKurs;

            // Reusable function for kirim and chiqim
            $calculateSum = function ($valyutaId, $status = null, $dateField = 'ch_kun') use ($tovarlar, $boshkun, $yakunkun) {
                $query = $tovarlar->where('valyuta_id', $valyutaId)
                    ->whereBetween($dateField, [$boshkun, $yakunkun]);

                if ($status) {
                    $query->where('status', $status);
                } else {
                    $query->whereNotIn('status', ['Удалит', 'Актив']);
                }

                return $query->sum('narhi');
            };

            // Calculate other metrics
            $ktovarKirimDollar = $calculateSum('2');
            $ktovarKirimSum = $calculateSum('1') / $tovarKurs;
            $ktovarChiqimDollar = $calculateSum('2', null, 'ch_kun');
            $ktovarChiqimSum = $calculateSum('1', null, 'ch_kun') / $tovarKurs;

            $ktovarShartnomaChiqimDollar = $calculateSum('2', 'Шартнома', 'ch_kun');
            $ktovarShartnomaChiqimSum = $calculateSum('1', 'Шартнома', 'ch_kun') / $tovarKurs;
            $ktovarNaqdChiqimDollar = $calculateSum('2', 'Нақд', 'ch_kun');
            $ktovarNaqdChiqimSum = $calculateSum('1', 'Нақд', 'ch_kun') / $tovarKurs;
            $ktovarBonusChiqimDollar = $calculateSum('2', 'Бонус', 'ch_kun');
            $ktovarBonusChiqimSum = $calculateSum('1', 'Бонус', 'ch_kun') / $tovarKurs;
            $ktovarAlmashganChiqimDollar = $calculateSum('2', 'Алмашган', 'ch_kun');
            $ktovarAlmashganChiqimSum = $calculateSum('1', 'Алмашган', 'ch_kun') / $tovarKurs;
            $ktovarAsosiyChiqimDollar = $calculateSum('2', 'Асосий восита', 'ch_kun');
            $ktovarAsosiyChiqimSum = $calculateSum('1', 'Асосий восита', 'ch_kun') / $tovarKurs;
            $ktovarQaytganChiqimDollar = $calculateSum('2', 'Кайтган', 'ch_kun');
            $ktovarQaytganChiqimSum = $calculateSum('1', 'Кайтган', 'ch_kun') / $tovarKurs;

            $jamiTovarOyBoshi += ($ktovarOyBoshiDollar + $ktovarOyBoshiSum);
            $jamiTovarKirim += ($ktovarKirimDollar + $ktovarKirimSum);
            $jamiTovarChiqim += ($ktovarChiqimDollar + $ktovarChiqimSum);
            $jamiShartnoma += ($ktovarShartnomaChiqimDollar + $ktovarShartnomaChiqimSum);
            $jamiNaqd += ($ktovarNaqdChiqimDollar + $ktovarNaqdChiqimSum);
            $jamiBonus += ($ktovarBonusChiqimDollar + $ktovarBonusChiqimSum);
            $jamiAlmashgan += ($ktovarAlmashganChiqimDollar + $ktovarAlmashganChiqimSum);
            $jamiAsosiy += ($ktovarAsosiyChiqimDollar + $ktovarAsosiyChiqimSum);
            $jamiQaytgan += ($ktovarQaytganChiqimDollar + $ktovarQaytganChiqimSum);
            $jamiQoldiqTovar += ($ktovarOyBoshiDollar + $ktovarOyBoshiSum + $ktovarKirimDollar + $ktovarKirimSum)-($ktovarChiqimDollar + $ktovarChiqimSum);

            echo '
                <tr>
                    <td>' . $i++ . '</td>
                    <td>' . $filial->fil_name . '</td>
                    <td>' . number_format(($ktovarOyBoshiDollar + $ktovarOyBoshiSum), 0, ",", " ") . '</td>
                    <td>' . number_format(($ktovarKirimDollar + $ktovarKirimSum), 0, ",", " ") . '</td>
                    <td>' . number_format(($ktovarShartnomaChiqimDollar + $ktovarShartnomaChiqimSum), 0, ",", " ") . '</td>
                    <td>' . number_format(($ktovarNaqdChiqimDollar + $ktovarNaqdChiqimSum), 0, ",", " ") . '</td>
                    <td>' . number_format(($ktovarBonusChiqimDollar + $ktovarBonusChiqimSum), 0, ",", " ") . '</td>
                    <td>' . number_format(($ktovarAlmashganChiqimDollar + $ktovarAlmashganChiqimSum), 0, ",", " ") . '</td>
                    <td>' . number_format(($ktovarAsosiyChiqimDollar + $ktovarAsosiyChiqimSum), 0, ",", " ") . '</td>
                    <td>' . number_format(($ktovarQaytganChiqimDollar + $ktovarQaytganChiqimSum), 0, ",", " ") . '</td>
                    <td>' . number_format(($ktovarChiqimDollar + $ktovarChiqimSum), 0, ",", " ") . '</td>
                    <td>' . number_format((($ktovarOyBoshiDollar + $ktovarKirimDollar - $ktovarChiqimDollar) + ($ktovarOyBoshiSum + $ktovarKirimSum - $ktovarChiqimSum)), 0, ",", " ") . '</td>
                </tr>
            ';
        }

        echo '
                <tr class="fw-bold">
                    <td></td>
                    <td></td>
                    <td>' . number_format($jamiTovarOyBoshi, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiTovarKirim, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiShartnoma, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiNaqd, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiBonus, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiAlmashgan, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiAsosiy, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiQaytgan, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiTovarChiqim, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiQoldiqTovar, 0, ",", " ") . '</td>
                </tr>
            </tbody>
        </table>
        <br>';
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
        $boshkun = $request->boshkun;
        $yakunkun = $request->yakunkun;
        $filial = $request->filial;

        $i = 1;
        $jamiTovarOyBoshi = 0;
        $jamiTovarKirim = 0;
        $jamiTovarChiqim = 0;
        $jamiShartnoma = 0;
        $jamiNaqd = 0;
        $jamiBonus = 0;
        $jamiAlmashgan = 0;
        $jamiAsosiy = 0;
        $jamiQaytgan = 0;
        $jamiQoldiqTovar = 0;

        echo'
        <table class="table table-bordered text-center align-middle m-auto" style="font-size: 12px; width: 95%;">
            <thead>
                <tr class="text-bold text-primary align-middle">
                    <th rowspan="2"> № </th>
                    <th rowspan="2"> Сана </th>
                    <th rowspan="2"> ОБ Товар </th>
                    <th rowspan="2"> Кирим товар </th>
                    <th colspan="6"> Чиким товарлар </th>
                    <th rowspan="2">Жами чиким</th>
                    <th rowspan="2">Колдик товар</th>
                </tr>
                <tr class="text-bold text-primary align-middle">
                    <th>Шартнома</th>
                    <th>Накд</th>
                    <th>Бонус</th>
                    <th>Алмашув</th>
                    <th>Асосий</th>
                    <th>Кайтган</th>
                </tr>
            </thead>
            <tbody id="tab1">';

            $tovarKurs = valyuta::query()->where('id', 2)->value('tovar_kurs');

            $filName = filial::where('id', $filial)->value('fil_name');
            
            $tovarlar = new ktovar1($filial);
                
            while ($boshkun <= $yakunkun) {

                // Calculate initial balances
                $ktovarOyBoshiDollar = $tovarlar->where(function ($query) use ($boshkun) {
                    $query->where('valyuta_id', '2')
                        ->whereDate('kun', '<', $boshkun)
                        ->where(function ($subQuery) use ($boshkun) {
                            $subQuery->where('status', 'Сотилмаган')
                                ->orWhereDate('ch_kun', '>=', $boshkun);
                        });
                })->sum('narhi');

                $ktovarOyBoshiSum = $tovarlar->where(function ($query) use ($boshkun) {
                        $query->where('valyuta_id', '1')
                            ->whereDate('kun', '<', $boshkun)
                            ->where(function ($subQuery) use ($boshkun) {
                                $subQuery->where('status', 'Сотилмаган')
                                    ->orWhereDate('ch_kun', '>=', $boshkun);
                            });
                    })->sum('narhi') / $tovarKurs;

                // Reusable function for kirim and chiqim
                $calculateSum = function ($valyutaId, $status = null, $dateField = 'ch_kun') use ($tovarlar, $boshkun) {
                    $query = $tovarlar->where('valyuta_id', $valyutaId)
                        ->whereDate($dateField, $boshkun);

                    if ($status) {
                        $query->where('status', $status);
                    } else {
                        $query->whereNotIn('status', ['Удалит', 'Актив']);
                    }

                    return $query->sum('narhi');
                };

                // Calculate other metrics
                $ktovarKirimDollar = $calculateSum('2');
                $ktovarKirimSum = $calculateSum('1') / $tovarKurs;
                $ktovarChiqimDollar = $calculateSum('2', null, 'ch_kun');
                $ktovarChiqimSum = $calculateSum('1', null, 'ch_kun') / $tovarKurs;

                $ktovarShartnomaChiqimDollar = $calculateSum('2', 'Шартнома', 'ch_kun');
                $ktovarShartnomaChiqimSum = $calculateSum('1', 'Шартнома', 'ch_kun') / $tovarKurs;
                $ktovarNaqdChiqimDollar = $calculateSum('2', 'Нақд', 'ch_kun');
                $ktovarNaqdChiqimSum = $calculateSum('1', 'Нақд', 'ch_kun') / $tovarKurs;
                $ktovarBonusChiqimDollar = $calculateSum('2', 'Бонус', 'ch_kun');
                $ktovarBonusChiqimSum = $calculateSum('1', 'Бонус', 'ch_kun') / $tovarKurs;
                $ktovarAlmashganChiqimDollar = $calculateSum('2', 'Алмашган', 'ch_kun');
                $ktovarAlmashganChiqimSum = $calculateSum('1', 'Алмашган', 'ch_kun') / $tovarKurs;
                $ktovarAsosiyChiqimDollar = $calculateSum('2', 'Асосий восита', 'ch_kun');
                $ktovarAsosiyChiqimSum = $calculateSum('1', 'Асосий восита', 'ch_kun') / $tovarKurs;
                $ktovarQaytganChiqimDollar = $calculateSum('2', 'Кайтган', 'ch_kun');
                $ktovarQaytganChiqimSum = $calculateSum('1', 'Кайтган', 'ch_kun') / $tovarKurs;

                $jamiTovarKirim += ($ktovarKirimDollar + $ktovarKirimSum);
                $jamiShartnoma += ($ktovarShartnomaChiqimDollar + $ktovarShartnomaChiqimSum);
                $jamiNaqd += ($ktovarNaqdChiqimDollar + $ktovarNaqdChiqimSum);
                $jamiBonus += ($ktovarBonusChiqimDollar + $ktovarBonusChiqimSum);
                $jamiAlmashgan += ($ktovarAlmashganChiqimDollar + $ktovarAlmashganChiqimSum);
                $jamiAsosiy += ($ktovarAsosiyChiqimDollar + $ktovarAsosiyChiqimSum);
                $jamiQaytgan += ($ktovarQaytganChiqimDollar + $ktovarQaytganChiqimSum);
                $jamiTovarChiqim += ($ktovarChiqimDollar + $ktovarChiqimSum);

                echo '
                    <tr>
                        <td>' . $i++ . '</td>
                        <td>' . date('d.m.Y', strtotime($boshkun)).'</td>
                        <td>' . number_format(($ktovarOyBoshiDollar + $ktovarOyBoshiSum), 0, ",", " ") . '</td>
                        <td>' . number_format(($ktovarKirimDollar + $ktovarKirimSum), 0, ",", " ") . '</td>
                        <td>' . number_format(($ktovarShartnomaChiqimDollar + $ktovarShartnomaChiqimSum), 0, ",", " ") . '</td>
                        <td>' . number_format(($ktovarNaqdChiqimDollar + $ktovarNaqdChiqimSum), 0, ",", " ") . '</td>
                        <td>' . number_format(($ktovarBonusChiqimDollar + $ktovarBonusChiqimSum), 0, ",", " ") . '</td>
                        <td>' . number_format(($ktovarAlmashganChiqimDollar + $ktovarAlmashganChiqimSum), 0, ",", " ") . '</td>
                        <td>' . number_format(($ktovarAsosiyChiqimDollar + $ktovarAsosiyChiqimSum), 0, ",", " ") . '</td>
                        <td>' . number_format(($ktovarQaytganChiqimDollar + $ktovarQaytganChiqimSum), 0, ",", " ") . '</td>
                        <td>' . number_format(($ktovarChiqimDollar + $ktovarChiqimSum), 0, ",", " ") . '</td>
                        <td>' . number_format((($ktovarOyBoshiDollar + $ktovarKirimDollar - $ktovarChiqimDollar) + ($ktovarOyBoshiSum + $ktovarKirimSum - $ktovarChiqimSum)), 0, ",", " ") . '</td>
                    </tr>
                ';

                $boshkun = date('Y-m-d', strtotime($boshkun . ' +1 day'));
            }
            echo '
                    <tr class="fw-bold">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>' . number_format($jamiTovarKirim, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiShartnoma, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiNaqd, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiBonus, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiAlmashgan, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiAsosiy, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiQaytgan, 0, ",", " ") . '</td>
                    <td>' . number_format($jamiTovarChiqim, 0, ",", " ") . '</td>
                    <td></td>
                </tr>
            </tbody>
        </table><br>';

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
