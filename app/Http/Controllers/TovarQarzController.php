<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\savdo1;
use App\Models\ktovar1;
use App\Models\fond1;
use App\Models\shartnoma1;
use App\Models\naqdsavdo1;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;


class TovarQarzController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');

            return view('tovarlar.tovarqarz', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi]);
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
        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            echo'
            <table class="table table-bordered text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Манзили</th>
                        <th>Савдо рақами</th>
                        <th>Статус</th>
                        <th>Санаси</th>
                    </tr>
                </thead>
                <tbody id="tab1">';


                    $savdounix_id = savdo1::select('unix_id','unix_id')->where('shtrix_kod', '0')->where('status','!=','Удалит')->orderBy('unix_id','desc')->groupBy('unix_id')->get();
                    foreach ($savdounix_id as $savdounix){
                        $savdoid = savdo1::where('unix_id', $savdounix->unix_id)->where('status','!=','Удалит' )->limit(1)->get();
                        foreach ($savdoid as $savdoidall){
                            if($savdoidall->status=='Шартнома'){
                                $shartnoma1 = shartnoma1::where('id', $savdoidall->shartnoma_id)->where('status','Актив')->get();
                                foreach ($shartnoma1 as $shartnoma){
                                    echo'
                                    <tr class="align-middle" data-bs-toggle="modal"
                                        data-bs-target="#shartnoma_add"
                                        id="modalgamurojatshart" data-id="'. $shartnoma->id .'" data-status="'. $savdoidall->status .'" data-fio="'. $shartnoma->mijozlar->last_name . ' ' . $shartnoma->mijozlar->first_name . ' ' . $shartnoma->mijozlar->middle_name.'">
                                        <td>' . $shartnoma->id . '</td>
                                        <td>' . $shartnoma->mijozlar->last_name . ' ' . $shartnoma->mijozlar->first_name . ' ' . $shartnoma->mijozlar->middle_name . '</td>
                                        <td>' . $shartnoma->mijozlar->tuman->name_uz . ' ' . $shartnoma->mijozlar->manzil . '</td>
                                        <td>' . $shartnoma->savdo_id . ' </td>
                                        <td>' . $savdoidall->status . '</td>
                                        <td>' . date('d.m.Y', strtotime($shartnoma->kun)) . ' </td>
                                    </tr>';
                                }
                            }elseif($savdoidall->status=='Нақд'){
                                $naqdsavdojami = naqdsavdo1::where('id', $savdoidall->shartnoma_id)->get();
                                foreach ($naqdsavdojami as $naqdsavdojam){
                                    echo'
                                    <tr class="align-middle" data-bs-toggle="modal" data-bs-target="#shartnoma_add"
                                        id="modalgamurojatshart" data-id="'. $naqdsavdojam->id .'" data-status="'. $savdoidall->status .'" data-fio="'. $naqdsavdojam->mijozlar->last_name . ' ' . $naqdsavdojam->mijozlar->first_name . ' ' . $naqdsavdojam->mijozlar->middle_name.'">
                                        <td>' . $naqdsavdojam->id . '</td>
                                        <td>' . $naqdsavdojam->mijozlar->last_name . ' ' . $naqdsavdojam->mijozlar->first_name . ' ' . $naqdsavdojam->mijozlar->middle_name . ' </td>
                                        <td>' . $naqdsavdojam->mijozlar->tuman->name_uz . ' ' . $naqdsavdojam->mijozlar->manzil . ' </td>
                                        <td>' . $naqdsavdojam->savdoraqami_id . ' </td>
                                        <td> Нақд </td>
                                        <td>' . date('d.m.Y', strtotime($naqdsavdojam->kun)) . '
                                        </td>
                                    </tr>';
                                }
                            }elseif($savdoidall->status=='Фонд'){
                            $fondsavdojami = fond1::where('id', $savdoidall->shartnoma_id)->get();
                                foreach ($fondsavdojami as $fondsavdojam){
                                    echo '
                                    <tr class="align-middle" data-bs-toggle="modal" data-bs-target="#shartnoma_add"
                                    id="modalgamurojatshart" data-id="'. $fondsavdojam->id .'" data-status="'. $savdoidall->status .'" data-fio="'. $fondsavdojam->mijozlar->last_name . ' ' . $fondsavdojam->mijozlar->first_name . ' ' . $fondsavdojam->mijozlar->middle_name.'">
                                        <td>' . $fondsavdojam->id . '</td>
                                        <td>' . $fondsavdojam->mijozlar->last_name . ' ' . $fondsavdojam->mijozlar->first_name . ' ' . $fondsavdojam->mijozlar->middle_name . '
                                        </td>
                                        <td>' . $fondsavdojam->mijozlar->tuman->name_uz . ' ' . $fondsavdojam->mijozlar->manzil . '
                                        </td>
                                        <td>' . $fondsavdojam->savdoraqami_id . ' </td>
                                        <td> Фонд </td>
                                        <td>' . date('d.m.Y', strtotime($fondsavdojam->kun)) . '
                                        </td>
                                    </tr>
                                    ';
                                }
                            }
                        }
                    }
                    echo'
                </tbody>
            </table>';
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            $krimt = $request->krimt;
            $shid = $request->shid;
            $status = $request->status;
            if(!empty($krimt) && !empty($shid) && !empty($status) ){
                $tekshi = ktovar1::where('status', 'Сотилмаган')->where('shtrix_kod', $krimt)->first();
                if ($tekshi) {
                    $count = $tekshi->tmodel_id;
                    $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
                    $tekshirsavdo = savdo1::where('status', $status)->where('tmodel_id', $count)->where('shtrix_kod', '0')->where('shartnoma_id', $shid)->count();
                    if ($tekshirsavdo>0) {

                        try {
                            DB::beginTransaction();
                            $savdo1Updated = savdo1::where('status', $status)
                                ->where('shartnoma_id', $shid)
                                ->where('tmodel_id', $count)
                                ->where('shtrix_kod', '0')
                                ->limit(1)
                                ->update([
                                    'shtrix_kod' => $krimt,
                                    'kirimnarhi' => round($tekshi->tannarhi,-3)
                                ]);

                            $ktovar1Updated = ktovar1::where('status', 'Сотилмаган')
                            ->where('tmodel_id', $count)
                            ->where('shtrix_kod', $krimt)
                            ->limit(1)
                            ->update([
                                'status' => $status,
                                'shatnomaid' => $shid,
                                'ch_kun' => date('Y-m-d H:i:s'),
                                'ch_xis_oyi' => $xis_oyi,
                                'ch_user_id' => Auth::user()->id,
                            ]);

                            if ($savdo1Updated && $ktovar1Updated) {
                                DB::commit();
                                return response()->json(['message' => "Товар шартномага бириктирилди."], 200);
                            } else {
                                DB::rollBack();
                                return response()->json(['message' => "Товар шартномага бириктиришда хатолик."], 200);
                            }
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return response()->json(['message' => "Товар шартномага бириктиришда хатолик2"], 200);
                            // throw $e;
                        }
                    } else {
                        return response()->json(['message' => "Хатолик бундай товар шартномада курсатилмаган."], 200);
                    }

                } else {
                    return response()->json(['message' => "Хатолик. Бундай товар мавжуд эмас."], 200);
                }
                return response()->json(['message' => "Хатолик. Маълумот тўлик эмас"], 200);
            }
            return response()->json(['message' => "Хатолик. Маълумот тўлик эмас"], 200);
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
        if (Auth::user()->lavozim_id == 2 && Auth::user()->status == 'Актив') {
            $savdomodel = savdo1::where('status', $request->status)->where('shartnoma_id', $id)->get();
            echo '<h3 class=" text-center text-primary ">' . $id . '</h3>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center text-bold text-primary align-middle">
                        <th>№</th>
                        <th>Куни</th>
                        <th>Модел ID</th>
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
                            <td>" . $savdomode->tmodel_id . "</td>
                            <td>" . $savdomode->tur->tur_name . ' ' . $savdomode->brend->brend_name . ' ' . $savdomode->tmodel->model_name . "</td>
                            <td>" . number_format($savdomode->msumma, 0, ',', ' ') . "</td>
                            <td>" . $savdomode->shtrix_kod . "</td>
                        </tr>";
                    $jami += $savdomode->msumma;
                    $i++;
                }
                echo "
                    <tr class='text-center align-middle'>
                        <td></td>
                        <td></td>
                        <td>ЖАМИ</td>
                        <td></td>
                        <td>" . number_format($jami, 0, ',', ' ') . "</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            ";
            return;
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
