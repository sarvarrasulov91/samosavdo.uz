<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Savdo;
use App\Models\Tulovlar;
use App\Models\Xarajat;
use App\Models\kirim;
use App\Models\FondSavdo;
use App\Models\fond;
use App\Models\Shartnoma;
use App\Models\NaqdSavdo;
use App\Models\BonusSavdo;
use App\Models\xissobotoy;
use App\Models\filial;

class XisobotKunlikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->lavozim_id == 1){
            $filial = filial::where('status', 'Актив')->whereNotIn('id', [10])->get();
        }else{
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }
        return view('xisobotlar.kunlik', [
            'filial' => $filial,
        ]);
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

        $jnaqd = 0;
        $jplastik = 0;
        $jhr = 0;
        $jClick = 0;
        $javtot = 0;
        $jchegirma = 0;
        $jumumiy = 0;

        $nnaqd = 0;
        $nplastik = 0;
        $nhr = 0;
        $nClick = 0;
        $navtot = 0;
        $nchegirma = 0;
        $numumiy = 0;

        $shnaqd = 0;
        $shplastik = 0;
        $shhr = 0;
        $shClick = 0;
        $shavtot = 0;
        $shchegirma = 0;
        $shumumiy = 0;

        $otnaqd = 0;
        $otplastik = 0;
        $othr = 0;
        $otClick = 0;
        $otavtot = 0;
        $otchegirma = 0;
        $otumumiy = 0;

        $fnaqd = 0;
        $fplastik = 0;
        $fhr = 0;
        $fClick = 0;
        $favtot = 0;
        $fchegirma = 0;
        $fumumiy = 0;

        $bnaqd = 0;
        $bplastik = 0;
        $bhr = 0;
        $bClick = 0;
        $bavtot = 0;
        $bchegirma = 0;
        $bumumiy = 0;

        $bonusnaqd = 0;
        $bonusplastik = 0;
        $bonushr = 0;
        $bonusClick = 0;
        $bonusavtot = 0;
        $bonuschegirma = 0;
        $bonusumumiy = 0;

            $tulovlar2 = Tulovlar::where('filial_id', $filial)
                ->whereBetween('kun', [$boshkun, $yakunkun])
                ->where('status', 'Актив')
                ->get();

        foreach ($tulovlar2 as $tulovla) {
                $jnaqd += $tulovla->naqd;
                $jplastik += $tulovla->pastik;
                $jhr += $tulovla->hr;
                $jClick += $tulovla->click;
                $javtot += $tulovla->avtot;
                $jchegirma += $tulovla->chegirma;

            if ($tulovla->tulovturi == 'Нақд') {
                    $nnaqd += $tulovla->naqd;
                    $nplastik += $tulovla->pastik;
                    $nhr += $tulovla->hr;
                    $nClick += $tulovla->click;
                    $navtot += $tulovla->avtot;
                    $nchegirma += $tulovla->chegirma;
            } elseif ($tulovla->tulovturi == 'Шартнома') {
                    $shnaqd += $tulovla->naqd;
                    $shplastik += $tulovla->pastik;
                    $shhr += $tulovla->hr;
                    $shClick += $tulovla->click;
                    $shavtot += $tulovla->avtot;
                    $shchegirma += $tulovla->chegirma;
            } elseif ($tulovla->tulovturi == 'Олдиндан тўлов') {
                    $otnaqd += $tulovla->naqd;
                    $otplastik += $tulovla->pastik;
                    $othr += $tulovla->hr;
                    $otClick += $tulovla->click;
                    $otavtot += $tulovla->avtot;
                    $otchegirma += $tulovla->chegirma;
            } elseif ($tulovla->tulovturi == 'Фонд') {
                    $fnaqd += $tulovla->naqd;
                    $fplastik += $tulovla->pastik;
                    $fhr += $tulovla->hr;
                    $fClick += $tulovla->click;
                    $favtot += $tulovla->avtot;
                    $fchegirma += $tulovla->chegirma;
            }elseif ($tulovla->tulovturi == 'Брон') {
                    $bnaqd += $tulovla->naqd;
                    $bplastik += $tulovla->pastik;
                    $bhr += $tulovla->hr;
                    $bClick += $tulovla->click;
                    $bavtot += $tulovla->avtot;
                    $bchegirma += $tulovla->chegirma;
            }elseif ($tulovla->tulovturi == 'Бонус') {
                    $bonusnaqd += $tulovla->naqd;
                    $bonusplastik += $tulovla->pastik;
                    $bonushr += $tulovla->hr;
                    $bonusClick += $tulovla->click;
                    $bonusavtot += $tulovla->avtot;
                    $bonuschegirma += $tulovla->chegirma;
            }
        }

        $jumumiy += ($jnaqd + $jplastik + $jhr + $jClick + $javtot);
        $numumiy += ($nnaqd + $nplastik + $nhr + $nClick + $navtot);
        $shumumiy += ($shnaqd + $shplastik + $shhr + $shClick + $shavtot);
        $otumumiy += ($otnaqd + $otplastik + $othr + $otClick + $otavtot);
        $fumumiy += ($fnaqd + $fplastik + $fhr + $fClick + $favtot);
        $bumumiy += ($bnaqd + $bplastik + $bhr + $bClick + $bavtot);
        $bonusumumiy += ($bonusnaqd + $bonusplastik + $bonushr + $bonusClick + $bonusavtot);

        echo '<div class="row justify-content-md-center">
            <h3 class=" text-center text-primary"><b> С А В Д О Л А Р </b></h3>
            <div class="col-xl-2">
                <div class="basic-list-group blog-card">
                    <ul class="list-group text-center">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"><b>Умумий савдо</b></span> <span class="badge-pill text-primary"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Жами</span> <span class="badge-pill text-primary">' . number_format($jumumiy, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Нақд</span><span class="badge-pill text-primary">' .  number_format($jnaqd, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Пластик</span> <span class="badge-pill text-primary">' .  number_format($jplastik, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Хисоб-рақам</span> <span class="badge-pill text-primary">' . number_format($jhr, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Click</span> <span class="badge-pill text-primary">' . number_format($jClick, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Авто тўлов</span> <span class="badge-pill text-primary">' . number_format($javtot, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Чегирма</span> <span class="badge-pill text-danger">' . number_format($jchegirma, 2, ",", " ") . '</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-2">
                <div class="basic-list-group blog-card">
                    <ul class="list-group text-center">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"><b>Нақд савдо</b></span> <span class="badge-pill text-primary"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($numumiy, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span><span class="badge-pill text-primary">' .  number_format($nnaqd, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-primary">' .  number_format($nplastik, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($nhr, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($nClick, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($navtot, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-danger">' . number_format($nchegirma, 2, ",", " ") . '</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-2">
                <div class="basic-list-group blog-card">
                    <ul class="list-group text-center">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"><b>График тўлов</b></span> <span class="badge-pill text-primary"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($shumumiy, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span><span class="badge-pill text-primary">' .  number_format($shnaqd, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-primary">' .  number_format($shplastik, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($shhr, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($shClick, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($shavtot, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"></span> <span class="badge-pill text-danger">' . number_format($shchegirma, 2, ",", " ") . '</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-2">
            <div class="basic-list-group blog-card">
                <ul class="list-group text-center">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"><b>Олдиндан тўлов</b></span> <span class="badge-pill text-primary"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($otumumiy, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span><span class="badge-pill text-primary">' .  number_format($otnaqd, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' .  number_format($otplastik, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($othr, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($otClick, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($otavtot, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-danger">' . number_format($otchegirma, 2, ",", " ") . '</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="basic-list-group blog-card">
                <ul class="list-group text-center">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"><b>Фонд фарқи</b></span> <span class="badge-pill text-primary"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($fumumiy, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span><span class="badge-pill text-primary">' .  number_format($fnaqd, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' .  number_format($fplastik, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($fhr, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($fClick, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($favtot, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-danger">' . number_format($fchegirma, 2, ",", " ") . '</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="basic-list-group blog-card">
                <ul class="list-group text-center">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"><b>Бонус фарқи</b></span> <span class="badge-pill text-primary"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($bonusumumiy, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span><span class="badge-pill text-primary">' .  number_format($bonusnaqd, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' .  number_format($bonusplastik, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($bonushr, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($bonusClick, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-primary">' . number_format($bonusavtot, 2, ",", " ") . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted"></span> <span class="badge-pill text-danger">' . number_format($bonuschegirma, 2, ",", " ") . '</span>
                    </li>
                </ul>
            </div>
        </div>
        </div>
        ';

        $bxharajat = 0;
        $bxnaqd = 0;
        $bxplastik = 0;
        $bxhr = 0;
        $bxclick = 0;
        $bxavtot = 0;

        $spumumiy=0;

        $bronxharajat = 0;
        $bronxnaqd = 0;
        $bronxplastik = 0;
        $bronxhr = 0;
        $bronxclick = 0;
        $bronxavtot = 0;

        $shsqltulovlar2 = Xarajat::where('filial_id', $filial)
            ->whereBetween('kun', [$boshkun, $yakunkun])
            ->where('valyuta_id', '=', 1)
            ->where('status', 'Актив')
            ->get();

        foreach ($shsqltulovlar2 as $shsqltulovlar) {

            if($shsqltulovlar->turharajat_id!=14){
                $bxharajat += $shsqltulovlar->summasi;
                $bxnaqd += $shsqltulovlar->naqd;
                $bxplastik += $shsqltulovlar->pastik;
                $bxhr += $shsqltulovlar->hr;
                $bxclick += $shsqltulovlar->click;
                $bxavtot += $shsqltulovlar->avtot;
            }else{
                $bronxharajat += $shsqltulovlar->summasi;
                $bronxnaqd += $shsqltulovlar->naqd;
                $bronxplastik += $shsqltulovlar->pastik;
                $bronxhr += $shsqltulovlar->hr;
                $bronxclick += $shsqltulovlar->click;
                $bronxavtot += $shsqltulovlar->avtot;
            }
        }

        $spnaqd = 0;
        $spplastik = 0;
        $sphr = 0;
        $spclick = 0;
        $spavtot = 0;

        $kirim2 = kirim::where('kun', '>=', $boshkun)
            ->where('kun', '<=', $yakunkun)
            ->where('kirimtur_id', 1)
            ->where('filial_id', $filial)
            ->where('status', 'Актив')
            ->get();

        foreach ($kirim2 as $kirim) {
            $spnaqd += $kirim->naqd;
            $spplastik += $kirim->pastik;
            $sphr += $kirim->hr;
            $spclick += $kirim->click;
            $spavtot += $kirim->avtot;
        }

        echo '<br><div class="row justify-content-md-center">
            <h3 class=" text-center text-primary"><b> Х А Р А Ж А Т Л А Р </b></h3>
            <div class="col-xl-4">
                <div class="basic-list-group blog-card">
                    <ul class="list-group text-center">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"><b>Савдо пули</b></span> <span class="badge-pill text-primary"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Жами</span> <span class="badge-pill text-primary">' . number_format($spnaqd+$spplastik+$sphr+$spclick+$spavtot, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Нақд</span><span class="badge-pill text-primary">' .  number_format($spnaqd, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Пластик</span> <span class="badge-pill text-primary">' .  number_format($spplastik, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Хисоб-рақам</span> <span class="badge-pill text-primary">' . number_format($sphr, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Click</span> <span class="badge-pill text-primary">' . number_format($spclick, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Авто тўлов</span> <span class="badge-pill text-primary">' . number_format($spavtot, 2, ",", " ") . '</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="basic-list-group blog-card">
                    <ul class="list-group text-center">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"><b>Бошқа харажатлар</b></span> <span class="badge-pill text-primary"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Жами</span> <span class="badge-pill text-primary">' . number_format($bxnaqd+$bxplastik+$bxhr+$bxclick+$bxavtot, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Нақд</span><span class="badge-pill text-primary">' .  number_format($bxnaqd, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Пластик</span> <span class="badge-pill text-primary">' .  number_format($bxplastik, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Хисоб-рақам</span> <span class="badge-pill text-primary">' . number_format($bxhr, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Click</span> <span class="badge-pill text-primary">' . number_format($bxclick, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Авто тўлов</span> <span class="badge-pill text-primary">' . number_format($bxavtot, 2, ",", " ") . '</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="basic-list-group blog-card">
                    <ul class="list-group text-center">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted"><b>Брон чиқим</b></span> <span class="badge-pill text-primary"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Жами</span> <span class="badge-pill text-primary">' . number_format($bronxnaqd+$bronxplastik+$bronxhr+$bronxclick+$bronxavtot, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Нақд</span><span class="badge-pill text-primary">' .  number_format($bronxnaqd, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Пластик</span> <span class="badge-pill text-primary">' .  number_format($bronxplastik, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Хисоб-рақам</span> <span class="badge-pill text-primary">' . number_format($bronxhr, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Click</span> <span class="badge-pill text-primary">' . number_format($bronxclick, 2, ",", " ") . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Авто тўлов</span> <span class="badge-pill text-primary">' . number_format($bronxavtot, 2, ",", " ") . '</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <br>
        ';

        $TOyBoshiNaqd = 0;
        $TOyBoshiPastik = 0;
        $TOyBoshiHr = 0;
        $TOyBoshiClick = 0;
        $TOyBoshiAvtot = 0;
        $TOyBoshiChegirma = 0;
        $TOyBoshiBron = 0;
        $TOyBoshiJasmi = 0;


        $TulovlarOyBoshga1 = Tulovlar::where('filial_id', $filial)->where('kun', '<', $boshkun)->where('status', 'Актив')->get();
        foreach ($TulovlarOyBoshga1 as $TulovlarOyBoshga) {
            $TOyBoshiNaqd += $TulovlarOyBoshga->naqd;
            $TOyBoshiPastik += $TulovlarOyBoshga->pastik;
            $TOyBoshiHr += $TulovlarOyBoshga->hr;
            $TOyBoshiClick += $TulovlarOyBoshga->click;
            $TOyBoshiAvtot += $TulovlarOyBoshga->avtot;
            $TOyBoshiChegirma += $TulovlarOyBoshga->chegirma;
            if($TulovlarOyBoshga->tulovturi == "Брон"){
                $TOyBoshiBron += $TulovlarOyBoshga->naqd+$TulovlarOyBoshga->pastik+$TulovlarOyBoshga->hr+$TulovlarOyBoshga->click+$TulovlarOyBoshga->avtot;
            }
            $TOyBoshiJasmi += $TulovlarOyBoshga->naqd+$TulovlarOyBoshga->pastik+$TulovlarOyBoshga->hr+$TulovlarOyBoshga->click+$TulovlarOyBoshga->avtot;
        }

        $TDavrOrasiNaqd = 0;
        $TDavrOrasiPastik = 0;
        $TDavrOrasiHr = 0;
        $TDavrOrasiClick = 0;
        $TDavrOrasiAvtot = 0;
        $TDavrOrasiChegirma = 0;
        $TDavrOrasiBron = 0;
        $TDavrOrasiJami = 0;


        $TulovlarDavrOrasi1 = Tulovlar::where('filial_id', $filial)->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->get();
        foreach ($TulovlarDavrOrasi1 as $TulovlarDavrOrasi) {
            $TDavrOrasiNaqd += $TulovlarDavrOrasi->naqd;
            $TDavrOrasiPastik += $TulovlarDavrOrasi->pastik;
            $TDavrOrasiHr += $TulovlarDavrOrasi->hr;
            $TDavrOrasiClick += $TulovlarDavrOrasi->click;
            $TDavrOrasiAvtot += $TulovlarDavrOrasi->avtot;
            $TDavrOrasiChegirma += $TulovlarDavrOrasi->chegirma;
            if($TulovlarDavrOrasi->tulovturi == "Брон"){
                $TDavrOrasiBron += $TulovlarDavrOrasi->naqd+$TulovlarDavrOrasi->pastik+$TulovlarDavrOrasi->hr+$TulovlarDavrOrasi->click+$TulovlarDavrOrasi->avtot;
            }
            $TDavrOrasiJami += $TulovlarDavrOrasi->naqd+$TulovlarDavrOrasi->pastik+$TulovlarDavrOrasi->hr+$TulovlarDavrOrasi->click+$TulovlarDavrOrasi->avtot;
        }

        $CHOyBoshiNaqd = 0;
        $CHOyBoshiPastik = 0;
        $CHOyBoshiHr = 0;
        $CHOyBoshiClick = 0;
        $CHOyBoshiAvtot = 0;
        $CHOyBoshiChegirma = 0;
        $CHOyBoshiBron = 0;
        $CHOyBoshiJami = 0;

        $CHiqimOyBoshga1 = Xarajat::where('filial_id', $filial)->where('kun', '<', $boshkun)->where('status', 'Актив')->get();
        foreach ($CHiqimOyBoshga1 as $CHiqimOyBoshga) {
            $CHOyBoshiNaqd += $CHiqimOyBoshga->naqd;
            $CHOyBoshiPastik += $CHiqimOyBoshga->pastik;
            $CHOyBoshiHr += $CHiqimOyBoshga->hr;
            $CHOyBoshiClick += $CHiqimOyBoshga->click;
            $CHOyBoshiAvtot += $CHiqimOyBoshga->avtot;
            $CHOyBoshiChegirma += $CHiqimOyBoshga->chegirma;
            if($CHiqimOyBoshga->turharajat_id == 14){
                $CHOyBoshiBron += $CHiqimOyBoshga->naqd+$CHiqimOyBoshga->pastik+$CHiqimOyBoshga->hr+$CHiqimOyBoshga->click+$CHiqimOyBoshga->avtot;
            }
            $CHOyBoshiJami += $CHiqimOyBoshga->naqd+$CHiqimOyBoshga->pastik+$CHiqimOyBoshga->hr+$CHiqimOyBoshga->click+$CHiqimOyBoshga->avtot;

        }


        $CHDavrOrasiNaqd = 0;
        $CHDavrOrasiPastik = 0;
        $CHDavrOrasiHr = 0;
        $CHDavrOrasiClick = 0;
        $CHDavrOrasiAvtot = 0;
        $CHDavrOrasiChegirma = 0;
        $CHDavrOrasiBron = 0;
        $CHDavrOrasiJami = 0;

        $CHiqimDavrOrasi1 = Xarajat::where('filial_id', $filial)->whereBetween('kun', [$boshkun, $yakunkun])->where('status', 'Актив')->get();
        foreach ($CHiqimDavrOrasi1 as $CHiqimDavrOrasi) {
            $CHDavrOrasiNaqd += $CHiqimDavrOrasi->naqd;
            $CHDavrOrasiPastik += $CHiqimDavrOrasi->pastik;
            $CHDavrOrasiHr += $CHiqimDavrOrasi->hr;
            $CHDavrOrasiClick += $CHiqimDavrOrasi->click;
            $CHDavrOrasiAvtot += $CHiqimDavrOrasi->avtot;
            $CHDavrOrasiChegirma += $CHiqimDavrOrasi->chegirma;
            if($CHiqimDavrOrasi->turharajat_id == 14){
                $CHDavrOrasiBron += $CHiqimDavrOrasi->naqd+$CHiqimDavrOrasi->pastik+$CHiqimDavrOrasi->hr+$CHiqimDavrOrasi->click+$CHiqimDavrOrasi->avtot;
            }
            $CHDavrOrasiJami += $CHiqimDavrOrasi->naqd+$CHiqimDavrOrasi->pastik+$CHiqimDavrOrasi->hr+$CHiqimDavrOrasi->click+$CHiqimDavrOrasi->avtot;
        }

        $SPOyBoshiNaqd = 0;
        $SPOyBoshiPastik = 0;
        $SPOyBoshiHr = 0;
        $SPOyBoshiClick = 0;
        $SPOyBoshiAvtot = 0;
        $SPOyBoshiJami = 0;

        $SPOyBoshga1 = kirim::where('kun', '<', $boshkun)->where('kirimtur_id','1')->where('filial_id', $filial)->where('status', 'Актив')->get();

        foreach ($SPOyBoshga1 as $SPOyBoshga) {
            $SPOyBoshiNaqd += $SPOyBoshga->naqd;
            $SPOyBoshiPastik += $SPOyBoshga->pastik;
            $SPOyBoshiHr += $SPOyBoshga->hr;
            $SPOyBoshiClick += $SPOyBoshga->click;
            $SPOyBoshiAvtot += $SPOyBoshga->avtot;
            $SPOyBoshiJami += $SPOyBoshga->naqd+$SPOyBoshga->pastik+$SPOyBoshga->hr+$SPOyBoshga->click+$SPOyBoshga->avtot;
        }

        $SPDavrOrasiNaqd = 0;
        $SPDavrOrasiPastik = 0;
        $SPDavrOrasiHr = 0;
        $SPDavrOrasiClick = 0;
        $SPDavrOrasiAvtot = 0;
        $SPDavrOrasiJami = 0;

        $SPDavrOrasi1 = kirim::where('kun', '>=', $boshkun)
            ->where('kun', '<=', $yakunkun)
            ->where('kirimtur_id', '1')
            ->where('filial_id', $filial)
            ->where('status', 'Актив')
            ->get();

        foreach ($SPDavrOrasi1 as $SPDavrOrasi) {
            $SPDavrOrasiNaqd += $SPDavrOrasi->naqd;
            $SPDavrOrasiPastik += $SPDavrOrasi->pastik;
            $SPDavrOrasiHr += $SPDavrOrasi->hr;
            $SPDavrOrasiClick += $SPDavrOrasi->click;
            $SPDavrOrasiAvtot += $SPDavrOrasi->avtot;
            $SPDavrOrasiJami += $SPDavrOrasi->naqd+$SPDavrOrasi->pastik+$SPDavrOrasi->hr+$SPDavrOrasi->click+$SPDavrOrasi->avtot;
        }

        echo '<div class="row justify-content-md-center">
            <h3 class=" text-center text-primary"><b> КАССА ХИСОБОТИ</b></h3>
            <div class="col-xl-12">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center text-bold text-primary align-middle">
                            <th style="width: 20%"><span class="text-muted "><b>Номи</b></span></th>
                            <th style="width: 20%"><span class="text-muted"><b>'.date('d.m.Y', strtotime($boshkun)).' йил<br>холатига </br></span></th>
                            <th style="width: 20%"><span class="text-muted"><b>Кирим</b></span></th>
                            <th style="width: 20%"><span class="text-muted" colspan="2"><b>Чиқим</b></span></th>
                            <th style="width: 20%"><span class="text-muted"><b>'.date('d.m.Y', strtotime($yakunkun)).' йил<br>холатига </br></span></th>
                        </tr>
                    </thead>
                    <tbody id="tab1">
                        <tr class="text-center align-middle">
                            <td><span class="text-muted">Нақд</span></td>
                            <td class="badge-pill text-primary">' . number_format($TOyBoshiNaqd-$CHOyBoshiNaqd-$SPOyBoshiNaqd, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($TDavrOrasiNaqd, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($CHDavrOrasiNaqd+$SPDavrOrasiNaqd, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($TOyBoshiNaqd+$TDavrOrasiNaqd-$CHOyBoshiNaqd-$CHDavrOrasiNaqd-$SPOyBoshiNaqd-$SPDavrOrasiNaqd, 2, ',', ' ') . '</td>
                        </tr>
                        <tr class="text-center align-middle">
                            <td><span class="text-muted">Пластик</span></td>
                            <td class="badge-pill text-primary">' . number_format($TOyBoshiPastik-$CHOyBoshiPastik-$SPOyBoshiPastik, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($TDavrOrasiPastik, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($CHDavrOrasiPastik+$SPDavrOrasiPastik, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($TOyBoshiPastik+$TDavrOrasiPastik-$CHOyBoshiPastik-$CHDavrOrasiPastik-$SPOyBoshiPastik-$SPDavrOrasiPastik, 2, ',', ' ') . '</td>
                        </tr>
                        <tr class="text-center align-middle">
                            <td><span class="text-muted">Хисоб-рақам</span></td>
                            <td class="badge-pill text-primary">' . number_format($TOyBoshiHr-$CHOyBoshiHr-$SPOyBoshiHr, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($TDavrOrasiHr, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($CHDavrOrasiHr+$SPDavrOrasiHr, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($TOyBoshiHr+$TDavrOrasiHr-$CHOyBoshiHr-$CHDavrOrasiHr-$SPOyBoshiHr-$SPDavrOrasiHr, 2, ',', ' ') . '</td>
                        </tr>
                        <tr class="text-center align-middle">
                            <td><span class="text-muted">Click</span></td>
                            <td class="badge-pill text-primary">' . number_format($TOyBoshiClick-$CHOyBoshiClick-$SPOyBoshiClick, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($TDavrOrasiClick, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($CHDavrOrasiClick+$SPDavrOrasiClick, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($TOyBoshiClick+$TDavrOrasiClick-$CHOyBoshiClick-$CHDavrOrasiClick-$SPOyBoshiClick-$SPDavrOrasiClick, 2, ',', ' ') . '</td>
                        </tr>
                        <tr class="text-center align-middle">
                            <td><span class="text-muted">Авто тўлов</span></td>
                            <td class="badge-pill text-primary">' . number_format($TOyBoshiAvtot-$CHOyBoshiAvtot-$SPOyBoshiAvtot, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($TDavrOrasiAvtot, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($CHDavrOrasiAvtot+$SPDavrOrasiAvtot, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary">' . number_format($TOyBoshiAvtot+$TDavrOrasiAvtot-$CHOyBoshiAvtot-$CHDavrOrasiAvtot-$SPOyBoshiAvtot-$SPDavrOrasiAvtot, 2, ',', ' ') . '</td>
                        </tr>
                        <tr class="text-center align-middle">
                            <td><span class="text-muted">Брон</span></td>
                            <td class="badge-pill text-danger">' . number_format($TOyBoshiBron-$CHOyBoshiBron, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-danger">' . number_format($TDavrOrasiBron, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-danger">' . number_format($CHDavrOrasiBron, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-danger">' . number_format($TOyBoshiBron+$TDavrOrasiBron-$CHOyBoshiBron-$CHDavrOrasiBron, 2, ',', ' ') . '</td>
                        </tr>

                        <tr class="text-center align-middle">
                            <td><span class="text-muted fw-bold">ЖАМИ</span></td>
                            <td class="badge-pill text-primary fw-bold">' . number_format($TOyBoshiJasmi-$CHOyBoshiJami-$SPOyBoshiJami, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary fw-bold">' . number_format($TDavrOrasiJami, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary fw-bold">' . number_format($CHDavrOrasiJami+$SPDavrOrasiJami, 2, ',', ' ') . '</td>
                            <td class="badge-pill text-primary fw-bold">' . number_format($TOyBoshiJasmi-$CHOyBoshiJami+$TDavrOrasiJami-$CHDavrOrasiJami-$SPOyBoshiJami-$SPDavrOrasiJami, 2, ',', ' ') . '</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>

            <div class="row justify-content-md-center">
                <h3 class=" text-center text-primary"><b> Ф О Н Д Л А Р</b></h3>
                <div class="col-xl-12">
                <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Фонд</th>
                        <th>Сони</th>
                        <th>Суммаси</th>
                        <th>Нақд </th>
                        <th>Пластик </th>
                        <th>Чегирма</th>
                        <th>Шартнома<br>суммаси</th>
                        </tr>
                </thead>
                <tbody id="tab1">';
            $ufsoni = 0;
            $ufsumma = 0;
            $ufotnaqd = 0;
            $ufotpalatik = 0;
            $ufchegirma = 0;
            $fond = fond::where('status', 'Актив')->get();
            foreach ($fond as $fon) {
                $fid = $fon->id;
                $fsoni = 0;
                $fsumma = 0;
                $fotnaqd = 0;
                $fotpalatik = 0;
                $fchegirma = 0;


                $fsumma = Savdo::where('filial_id', $filial)
                    ->where('fond_id', $fid)
                    ->where('status', 'Фонд')
                    ->whereDate('created_at', '>=', $boshkun)
                    ->whereDate('created_at', '<=', $yakunkun)
                    ->sum('msumma');

                $fond01 = FondSavdo::where('filial_id', $filial)
                    ->where('fond_id', $fid)
                    ->whereBetween('kun', [$boshkun, $yakunkun])
                    ->where('status', 'Актив')
                    ->get();

                foreach ($fond01 as $fond0){
                    $fsoni++;

                    $TulovfondlarDavrOra = Tulovlar::where('filial_id', $filial)
                        ->whereBetween('kun', [$boshkun, $yakunkun])
                        ->where('status', 'Актив')
                        ->where('tulovturi', 'Фонд')
                        ->where('shartnoma_id', $fond0->id)
                        ->get();

                    foreach ($TulovfondlarDavrOra as $TulovfondlarDavrOr) {
                        $fotnaqd += $TulovfondlarDavrOr->naqd;
                        $fotpalatik += $TulovfondlarDavrOr->pastik;
                        $fchegirma += $TulovfondlarDavrOr->hr;
                    }
                }

                $ufsoni += $fsoni;
                $ufsumma += $fsumma;
                $ufotnaqd += $fotnaqd;
                $ufotpalatik += $fotpalatik;
                $ufchegirma += $fchegirma;
                echo '
                        <tr class="text-center align-middle">
                            <td>' . $fid . '</td>
                            <td>' . $fon->pastav_name . '</td>
                            <td>' . number_format($fsoni, 0, ',', ' ') . '</td>
                            <td>' . number_format($fsumma, 2, ',', ' ') . '</td>
                            <td>' . number_format($fotnaqd, 2, ',', ' ') . '</td>
                            <td>' . number_format($fotpalatik, 2, ',', ' ') . '</td>
                            <td>' . number_format($fchegirma, 2, ',', ' ') . '</td>
                            <td>' . number_format($fsumma - $fotnaqd - $fotpalatik - $fchegirma, 2, ',', ' ') . '</td>
                        </tr>';
            }
                echo '
                    <tr class="text-center align-middle">
                    <td></td>
                    <td>ЖАМИ</td>
                    <td>' . number_format($ufsoni, 0, ',', ' ') . '</td>
                    <td>' . number_format($ufsumma, 2, ',', ' ') . '</td>
                    <td>' . number_format($ufotnaqd, 2, ',', ' ') . '</td>
                    <td>' . number_format($ufotpalatik, 2, ',', ' ') . '</td>
                    <td>' . number_format($ufchegirma, 2, ',', ' ') . '</td>
                    <td>' . number_format($ufsumma - $ufotnaqd - $ufotpalatik - $ufchegirma, 2, ',', ' ') . '</td>
                </tr>
                </tbody>
                        </table>
                    </div>
                    </div>

        <!----Shartnomalar taxlilini korish  --->
        <br>
        <div class="row justify-content-md-center">
                <h3 class=" text-center text-primary"><b>ШАРТНОМАЛАР</b></h3>
                <div class="col-xl-12">
                <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Филиал</th>
                        <th>Шартнома Сони</th>
                        <th>Шартнома суммаси</th>
                        <th>Қайтиш суммаси</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

            $branch = filial::where('status', 'Актив')->where('id', $filial)->first();

            $shsoni = $shtsumma = $shqsumma = 0;

            $shartnoma1 = Shartnoma::whereBetween('kun', [$boshkun, $yakunkun])
                ->where('filial_id', $filial)
                ->whereIn('status', ['Актив', 'Ёпилган'])
                ->get();

            foreach ($shartnoma1 as $shart) {

                $shsoni++;
                $savdosumma = Savdo::where('filial_id', $filial)->where('status', 'Шартнома')->where('shartnoma_id', $shart->id)->sum('msumma');

                $oldindantulovinfo = Tulovlar::where('filial_id', $filial)
                    ->where('tulovturi', 'Олдиндан тўлов')
                    ->where('status', 'Актив')
                    ->where('shartnoma_id', $shart->id)
                    ->first();

                $oldindantulov = $oldindantulovinfo->umumiysumma;
                $chegirma = $oldindantulovinfo->chegirma;

                $foiz = xissobotoy::where('xis_oy', $shart->xis_oyi)->value('foiz');

                if($shart->fstatus == 0){
                    $foiz = 0;
                }

                //йиллик фойиз
                $foiz = (($foiz / 12) * $shart->muddat);

                $xis_foiz = ((($savdosumma - $chegirma) * $foiz) / 100);

                $shtsumma += $savdosumma-$chegirma;
                $shqsumma += $savdosumma-$oldindantulov-$chegirma+$xis_foiz;
            }

            echo '
                    <tr class="text-center align-middle">
                        <td>' . $filial . '</td>
                        <td>' . $branch->fil_name . '</td>
                        <td>' . number_format($shsoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($shtsumma, 0, ',', ' ') . '</td>
                        <td>' . number_format($shqsumma, 0, ',', ' ') . '</td>
                    </tr>

            </tbody>
                    </table>
                </div>
                </div>

        <!----/*Naqda savdoni taxlilini korish*/ --->

        <br>
        <div class="row justify-content-md-center">
            <h3 class=" text-center text-primary"><b>Нақд савдолар тахлили</b></h3>
        <div class="col-xl-12">
        <table class="table table-bordered table-hover">
        <thead>
            <tr class="text-center text-bold text-primary align-middle">
                <th>ID</th>
                <th>Филиал</th>
                <th>Сони</th>
                <th>Савдо<br>суммаси</th>
            </tr>
        </thead>
        <tbody id="tab1">';

            $nssoni = $nssumma = $chegirmasumma = 0;

            $naqdsavdo1 = NaqdSavdo::where('filial_id', $filial)
                ->whereBetween('kun', [$boshkun, $yakunkun])
                ->where('status','Актив')
                ->get();

            foreach ($naqdsavdo1 as $naqd) {

                $savdosumma = Savdo::where('filial_id', $filial)
                    ->where('status', 'Нақд')
                    ->where('shartnoma_id', $naqd->id)
                    ->sum('msumma');

                $nssoni ++;
                $nssumma += $savdosumma;

                $chegirmasum = Tulovlar::where('filial_id', $filial)
                    ->where('tulovturi', 'Нақд')
                    ->where('status', 'Актив')
                    ->where('shartnoma_id', $naqd->id)
                    ->sum('chegirma');

                $chegirmasumma += $chegirmasum;

            }

            echo '
                    <tr class="text-center align-middle">
                        <td>' . $filial . '</td>
                        <td>' . $branch->fil_name . '</td>
                        <td>' . number_format($nssoni, 0, ',', ' ') . '</td>
                        <td>' . number_format($nssumma - $chegirmasumma, 0, ',', ' ') . '</td>
                    </tr>
            </tbody>
                </table>
            </div>
            </div>

             <!--Bonus savdoni taxlilini kurish-->
            <br>
            <div class="row justify-content-md-center">
                <h3 class=" text-center text-primary"><b>Бонус савдолар тахлили</b></h3>
                <div class="col-xl-12">
                <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Филиал</th>
                        <th>Товар сони</th>
                        <th>Товар<br>суммаси</th>
                        <th>Тулов<br>суммаси</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

                $bonussavdo = BonusSavdo::where('filial_id', $filial)
                    ->whereBetween('kun', [$boshkun, $yakunkun])
                    ->where('status','Актив')
                    ->get();

                $bssoni = $bonussavdo->count();
                $bssumma = $bonussavdo->sum('msumma');

                //Bonuslar  tulov summasi sonini aniqlash

                $bonusTulovSumma = Tulovlar::where('filial_id', $filial)
                    ->whereBetween('kun', [$boshkun, $yakunkun])
                    ->where('tulovturi','Бонус')
                    ->where('status','Актив')
                    ->sum('umumiysumma');

                echo '
                        <tr class="text-center align-middle">
                            <td>' . $filial . '</td>
                            <td>' . $branch->fil_name . '</td>
                            <td>' . number_format($bssoni, 0, ',', ' ') . '</td>
                            <td>' . number_format($bssumma, 0, ',', ' ') . '</td>
                            <td>' . number_format($bonusTulovSumma, 0, ',', ' ') . '</td>
                        </tr>
                </tbody>
                        </table>
                    </div>
                    </div>';

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
