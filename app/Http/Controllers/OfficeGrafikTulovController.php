<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tulovlar;
use App\Models\filial;

class OfficeGrafikTulovController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->filial_id == 10 && Auth::user()->status == 'Актив'){

            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();

        }else{

            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();

        }

        return view('kassa.officegrafiktulov', ['filial' => $filial]);
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
        $filial = $request->filial;
        $boshkun = $request->boshkun;
        $yakunkun = $request->yakunkun;

        $model = Tulovlar::with(['shartnoma.mijozlar', 'user'])
            ->whereBetween('kun', [$boshkun, $yakunkun])
            ->where('status', 'Актив')
            ->where('tulovturi', 'Шартнома')
            ->where('filial_id', $filial)
            ->orderBy('id', 'desc')
            ->get();

        // Summalar
        $naqd     = $model->sum('naqd');
        $plastik  = $model->sum('pastik');
        $hr       = $model->sum('hr');
        $click    = $model->sum('click');
        $avtot    = $model->sum('avtot');
        $chegirma = $model->sum('chegirma');
        $jami     = $model->sum('umumiysumma');

        $i = 1;

        echo '
        <table class="table table-bordered table-responsive-sm text-center align-middle ">
            <thead>
                <tr class="text-bold text-primary">
                    <th>ID</th>
                    <th>Куни</th>
                    <th>Мижоз ФИО</th>
                    <th>Тулов тури</th>
                    <th>Шарт-№</th>
                    <th>Накд</th>
                    <th>Пластик</th>
                    <th>ХР</th>
                    <th>Клик</th>
                    <th>Авто тулов</th>
                    <th>Чегирма</th>
                    <th>Жами</th>
                    <th>Масъул ходим</th>
                    <th>Тулов</th>
                </tr>

            </thead>
            <tbody id="tab1">';

        foreach ($model as $mode) {
            $mijoz = $mode->shartnoma->mijozlar;
            echo '
                <tr>
                    <td>' . $i++ . '</td>
                    <td>' . date('d.m.Y H:i:s', strtotime($mode->created_at)) . '</td>
                    <td>' . $mijoz->last_name . ' ' . $mijoz->first_name . ' ' . $mijoz->middle_name . '</td>
                    <td>' . $mode->tulovturi . '</td>
                    <td>' . $mode->shid . '</td>
                    <td>' . number_format($mode->naqd, 0, ',', ' ') . '</td>
                    <td>' . number_format($mode->pastik, 0, ',', ' ') . '</td>
                    <td>' . number_format($mode->hr, 0, ',', ' ') . '</td>
                    <td>' . number_format($mode->click, 0, ',', ' ') . '</td>
                    <td>' . number_format($mode->avtot, 0, ',', ' ') . '</td>
                    <td>' . number_format($mode->chegirma, 0, ',', ' ') . '</td>
                    <td class="fw-bold">' . number_format($mode->umumiysumma, 0, ',', ' ') . '</td>
                    <td>' . $mode->user->name . '</td>
                    <td>
                        <button
                            id="kivitpechat"
                                data-id="' . $mode->shartnoma_id .'"
                                data-shid="' . $mode->shid .'"
                                data-fio="' . $mijoz->last_name . ' ' . $mijoz->first_name . ' ' . $mijoz->middle_name .'"
                                data-bs-toggle="modal"
                                class="btn btn-outline-primary btn-sm me-2 "
                                data-bs-target="#pechat">
                                <i class="flaticon-381-search-1"></i>
                            </button>

                    </td>
                </tr>';
        }

        echo '
                <tr class="fw-bold">
                    <td colspan="5"></td>
                    <td>' . number_format($naqd, 0, ',', ' ') . '</td>
                    <td>' . number_format($plastik, 0, ',', ' ') . '</td>
                    <td>' . number_format($hr, 0, ',', ' ') . '</td>
                    <td>' . number_format($click, 0, ',', ' ') . '</td>
                    <td>' . number_format($avtot, 0, ',', ' ') . '</td>
                    <td>' . number_format($chegirma, 0, ',', ' ') . '</td>
                    <td>' . number_format($jami, 0, ',', ' ') . '</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>';
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
        echo '
        <h5 class="text-center text-uppercase" style="color: RoyalBlue;">
            Шартнома учун тўланган тўловлар
        </h5>

        <table class="table table-hover table-bordered text-center text-muted">
            <thead>
                <tr class="text-primary">
                    <th>№</th>
                    <th>Номи</th>
                    <th>Куни</th>
                    <th>Нақд</th>
                    <th>Пластик</th>
                    <th>Х-р</th>
                    <th>Клик</th>
                    <th>Авто</th>
                    <th>Чегирма</th>
                    <th>Жами</th>
                    <th>Холати</th>
                </tr>
            </thead>
            <tbody id="tab1">';

                $tulovlar = Tulovlar::where('filial_id', $request->filial)
                    ->where('shartnoma_id', $id)
                    ->whereIn('tulovturi', ['Шартнома', 'Олдиндан тўлов', 'Брон'])
                    ->orderBy('id', 'desc')
                    ->get();

                $i = 1;

                    // Summalar
                $sum = [
                    'naqd'     => 0,
                    'plastik'  => 0,
                    'hr'       => 0,
                    'click'    => 0,
                    'avtot'    => 0,
                    'chegirma' => 0,
                ];

                foreach ($tulovlar as $tl) {

                    $aktiv = ($tl->status == 'Актив')
                        && in_array($tl->tulovturi, ['Шартнома', 'Олдиндан тўлов']);

                    // Yig‘indilarga qo‘shish
                    if ($aktiv) {
                        $sum['naqd']     += $tl->naqd;
                        $sum['plastik']  += $tl->plastik;   // ✔ to‘g‘rilandi
                        $sum['hr']       += $tl->hr;
                        $sum['click']    += $tl->click;
                        $sum['avtot']    += $tl->avtot;
                        $sum['chegirma'] += $tl->chegirma;
                    }

                    $rowClass = $aktiv ? '' : 'text-danger';

                    echo "
                    <tr class='text-center align-middle $rowClass'>
                        <td>$i</td>
                        <td>{$tl->tulovturi}</td>
                        <td>" . date('d.m.Y', strtotime($tl->kun)) . "</td>
                        <td>" . number_format($tl->naqd, 0, ',', ' ') . "</td>
                        <td>" . number_format($tl->plastik, 0, ',', ' ') . "</td>
                        <td>" . number_format($tl->hr, 0, ',', ' ') . "</td>
                        <td>" . number_format($tl->click, 0, ',', ' ') . "</td>
                        <td>" . number_format($tl->avtot, 0, ',', ' ') . "</td>
                        <td>" . number_format($tl->chegirma, 0, ',', ' ') . "</td>
                        <td>" . number_format(
                                $tl->naqd +
                                $tl->plastik +
                                $tl->hr +
                                $tl->click +
                                $tl->avtot
                                , 0, ',', ' ') . "</td>
                        <td>{$tl->status}</td>
                    </tr>";

                    $i++;
                }

                // Jami
                echo "
                <tr class='fw-bold text-center align-middle'>
                    <td></td>
                    <td>ЖАМИ</td>
                    <td></td>
                    <td>" . number_format($sum['naqd'], 0, ',', ' ') . "</td>
                    <td>" . number_format($sum['plastik'], 0, ',', ' ') . "</td>
                    <td>" . number_format($sum['hr'], 0, ',', ' ') . "</td>
                    <td>" . number_format($sum['click'], 0, ',', ' ') . "</td>
                    <td>" . number_format($sum['avtot'], 0, ',', ' ') . "</td>
                    <td>" . number_format($sum['chegirma'], 0, ',', ' ') . "</td>
                    <td>" . number_format(
                            $sum['naqd'] +
                            $sum['plastik'] +
                            $sum['hr'] +
                            $sum['click'] +
                            $sum['avtot']
                            , 0, ',', ' ') . "</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        ";

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
