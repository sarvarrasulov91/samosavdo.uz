<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\savdo1;
use App\Models\ktovar1;
use App\Models\shartnoma1;
use Illuminate\Support\Facades\DB;

class ShartnomaChiqimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $savdo = 'savdo' . Auth::user()->filial_id;
        $shartnoma = 'shartnoma' . Auth::user()->filial_id;
        $ktovar = 'ktovar' . Auth::user()->filial_id;

        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');

        $shartnomainfo = DB::table($shartnoma)
            ->join('mijozlar', $shartnoma . '.mijoz_id', '=', 'mijozlar.id')
            ->join('region', 'mijozlar.tuman_id', '=', 'region.id')
            ->select($shartnoma . '.*', 'mijozlar.last_name', 'mijozlar.first_name', 'mijozlar.middle_name', 'mijozlar.manzil', 'region.name_uz')->where($shartnoma . '.status', 'Актив')->where($shartnoma . '.tstatus', '0')->orderBy($shartnoma . '.id', 'desc')->get();
        return view('tovarlar.chiqimshartnoma', ['xis_oyi' => $xis_oyi, 'shartnomainfo' => $shartnomainfo]);

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
        $krimt = $request->krimt;
        $shid = $request->shid;
        if (Auth::user()->filial_id == 1) {
            $tekshir = ktovar1::where('status', 'Сотилмаган')->where('shtrix_kod', $krimt)->get();
            foreach ($tekshir as $tekshi) {
                $count = $tekshi->model_id;
            }
            if (!empty($count)) {
                $tekshirsavdo = savdo1::where('status', 'Шартнома')->where('model_id', $count)->where('shtrix_kod', '0')->where('shartnoma_id', $shid)->count();
                if ($tekshirsavdo > 0) {
                    savdo1::where('status', 'Шартнома')->where('shartnoma_id', $shid)->where('model_id', $count)->where('shtrix_kod', '0')->limit(1)->update(['shtrix_kod' => $krimt]);
                    ktovar1::where('status', 'Сотилмаган')->where('model_id', $count)->where('shtrix_kod', $krimt)->limit(1)->update(['status' => 'Шартнома', 'shatnomaid' => $shid]);

                    $countid = savdo1::where('status', 'Шартнома')->where('shtrix_kod', '0')->where('shartnoma_id', $shid)->count();
                    if (empty($countid)) {
                        shartnoma1::where('id', $shid)->update(['tstatus' => 1]);
                    }
                    $aaa = 'Товар Шартномага бириктирилди.';
                } else {
                    $aaa = "Хатолик бундай товар шартномада курсатилмаган.";
                }
            } else {
                $aaa = "Хатолик бундай товар мавжуд эмас.";
            }
        }

        $id = $shid;
        $savdo = 'savdo' . Auth::user()->filial_id;
        $shartnoma = 'shartnoma' . Auth::user()->filial_id;

        $shartnom = DB::table($shartnoma)->select('savdo_id')->where('id', $id)->get();
        foreach ($shartnom as $shartno) {
            $id = $shartno->savdo_id;
        }

        $savdomodel = DB::table($savdo)->select($savdo . '.*', 'model.model_name', 'tur.tur_name', 'brend.brend_name', 'model.model_name')
            ->join('model', $savdo . '.model_id', '=', 'model.id')
            ->join('tur', 'model.tur_id', '=', 'tur.id')
            ->join('brend', 'model.brend_id', '=', 'brend.id')->where($savdo . '.status', 'Шартнома')->where($savdo . '.unix_id', $id)->get();


        echo '<h3 class=" text-center text-primary ">' . $id . '</h3>
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="text-center text-bold text-primary align-middle">
                    <th>№</th>
                    <th>Куни</th>
                    <th>Товар номи</th>
                    <th>Суммаси</th>
                    <th>Штрих-код</th>
                </tr>
            </thead>
            <tbody id="tab1">';
        $jami = 0;
        $i = 1;
        foreach ($savdomodel as $savdomode) {
            echo "
                <tr class='text-center align-middle'>
                    <td>" . $i . "</td>
                    <td>" . date('d.m.Y', strtotime($savdomode->created_at)) . "</td>
                    <td>" . $savdomode->tur_name . ' ' . $savdomode->brend_name . ' ' . $savdomode->model_name . "</td>
                    <td>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                    <td>" . $savdomode->shtrix_kod . "</td>
                </tr>";
            $jami += $savdomode->msumma;
            $i++;
        }
        echo "
                <tr class='text-center align-middle'>
                    <td></td>
                    <td>ЖАМИ</td>
                    <td></td>
                    <td>" . number_format($jami, 0, ',', ' ') . "</td>
                    <td></td>
                </tr>
            </tbody>
        </table>";
        echo '<h4 class="border p-2 text-center" style="color: RoyalBlue;">' . $aaa . '</h4>';

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $savdo = 'savdo' . Auth::user()->filial_id;
        $shartnoma = 'shartnoma' . Auth::user()->filial_id;

        $shartnom = DB::table($shartnoma)->select('savdo_id')->where('id', $id)->get();
        foreach ($shartnom as $shartno) {
            $id = $shartno->savdo_id;
        }

        $savdomodel = DB::table($savdo)->select($savdo . '.*', 'model.model_name', 'tur.tur_name', 'brend.brend_name', 'model.model_name')
            ->join('model', $savdo . '.model_id', '=', 'model.id')
            ->join('tur', 'model.tur_id', '=', 'tur.id')
            ->join('brend', 'model.brend_id', '=', 'brend.id')->where($savdo . '.status', 'Шартнома')->where($savdo . '.unix_id', $id)->get();


        echo '<h3 class=" text-center text-primary ">' . $id . '</h3>
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="text-center text-bold text-primary align-middle">
                    <th>№</th>
                    <th>Куни</th>
                    <th>Товар номи</th>
                    <th>Суммаси</th>
                    <th>Штрих-код</th>
                </tr>
            </thead>
            <tbody id="tab1">';
        $jami = 0;
        $i = 1;
        foreach ($savdomodel as $savdomode) {
            echo "
                <tr class='text-center align-middle'>
                    <td>" . $i . "</td>
                    <td>" . date('d.m.Y', strtotime($savdomode->created_at)) . "</td>
                    <td>" . $savdomode->tur_name . ' ' . $savdomode->brend_name . ' ' . $savdomode->model_name . "</td>
                    <td>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                    <td>" . $savdomode->shtrix_kod . "</td>
                </tr>";
            $jami += $savdomode->msumma;
            $i++;
        }
        echo "
                <tr class='text-center align-middle'>
                    <td></td>
                    <td>ЖАМИ</td>
                    <td></td>
                    <td>" . number_format($jami, 0, ',', ' ') . "</td>
                    <td></td>
                </tr>
            </tbody>
        </table>";
        return;
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
