<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\tulovlar1;
use App\Models\shartnoma1;
use App\Models\naqdsavdo1;
use App\Models\mijozlar;
use App\Models\valyuta;
use App\Models\lavozim;
use App\Models\filial;
use Illuminate\Support\Facades\Validator;

class OfficeIzmenitTulovController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
        $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
        $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
        $xis_oy = xissobotoy::get();
        $valyuta = valyuta::get();
        $filial = filial::where('status', 'Актив')->where('id', '!=', '10')->get();
        return view('kassa.officeizmenittulov', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'xis_oyi' => $xis_oyi, 'filial' => $filial, 'valyuta'=> $valyuta]);
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

        $tulovlar = new tulovlar1($request->filial);
        $tulovlar1 = $tulovlar->where('status', 'Актив')->whereBetween('kun', [$boshkun, $yakunkun])->orderBy('id', 'desc')->get();
        echo '
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="text-center text-bold text-primary align-middle">
                    <th>№</th>
                    <th>Сана</th>
                    <th>Мижоз ФИО</th>
                    <th>Тулов тури</th>
                    <th>Шартнома №</th>
                    <th>Накд</th>
                    <th>Пластик</th>
                    <th>Х/Р</th>
                    <th>Click</th>
                    <th>Авто тулов</th>
                    <th>Чегирма</th>
                    <th>Жами сумма</th>
                    <th>Тахрир</th>
                    <th>Удалит</th>
                </tr>
            </thead>
            <tbody id="tab1">';
            
        $jamiNaqd = 0;
        $jamiPlastik = 0;
        $jamiXR = 0;
        $jamiClick = 0;
        $jamiAvto = 0;
        $jamiChegirma = 0;
        $jamiSumma = 0;
        $filial = $request->filial;
        
        foreach ($tulovlar1 as $tulov) {
            $shartnoma1 = new shartnoma1($request->filial);
            
            if ($tulov->tulovturi == 'Нақд'){
                $shartnoma1 = new naqdsavdo1($request->filial);
            }
            
            if ($tulov->tulovturi != 'Брон'){
            
                $shartnoma = $shartnoma1->where('id', $tulov->shartnomaid)->first();
                $mijozlar = mijozlar::where('id', $shartnoma->mijozlar_id)->first();
                $mijozName = $mijozlar->last_name . ' ' . $mijozlar->first_name . ' ' . $mijozlar->middle_name;
        
            }else{
                $mijozName = "";
            }
            
            echo '
                <tr class="text-center align-middle">
                    <td>' . $tulov->id . '</td>
                    <td style="white-space: pre-wrap">' . date('d.m.Y H:i:s', strtotime($tulov->created_at)) . '</td>
                    <td style="white-space: pre-wrap">' . $mijozName . '</td>
                    <td>' . $tulov->tulovturi . '</td>
                    <td class="text-primary">' . $tulov->shartnomaid . '</td>
                    <td>' . number_format($tulov->naqd, 0, ',', ' ') . '</td>
                    <td>' . number_format($tulov->pastik, 0, ',', ' ') . '</td>
                    <td>' . number_format($tulov->hr, 0, ',', ' ') . '</td>
                    <td>' . number_format($tulov->click, 0, ',', ' ') . '</td>
                    <td>' . number_format($tulov->avtot, 0, ',', ' ') . '</td>
                    <td>' . number_format($tulov->chegirma, 0, ',', ' ') . '</td>
                    <td>' . number_format($tulov->umumiysumma, 0, ',', ' ') . '</td>
                    <td>
                        <button id="tulovedit" class="btn btn-outline-primary btn-sm me-2 "
                        data-filial="' . $filial . '" 
                        data-id="' . $tulov->id . '" 
                        data-kun="' . $tulov->kun . '"
                        data-tulov_turi="' . $tulov->tulovturi . '"
                        data-shartnoma_id="' . $tulov->shartnomaid . '"
                        data-naqd="' . $tulov->naqd . '"
                        data-pastik="' . $tulov->pastik . '" 
                        data-hr="' . $tulov->hr . '"
                        data-click="' . $tulov->click . '"
                        data-avtot="' . $tulov->avtot . '"
                        data-chegirma="' . $tulov->chegirma . '"
                        data-umumiysumma="' . $tulov->umumiysumma . '"
                        data-bs-toggle="modal"
                        data-bs-target="#edit"><i class="flaticon-381-notepad"></i></button>
                    </td>
                    <td>
                        <button id="tulovdelete" class="btn btn-outline-danger btn-sm me-2 "
                        data-filial="' . $filial . '" data-tulov_id="' . $tulov->id . '" ><i class="flaticon-381-substract-1"></i></button>
                    </td>
                </tr>';
                
            $jamiNaqd += $tulov->naqd;
            $jamiPlastik += $tulov->pastik;
            $jamiXR += $tulov->hr;
            $jamiClick += $tulov->click;
            $jamiAvto += $tulov->avtot;
            $jamiChegirma += $tulov->chegirma;
            $jamiSumma += $tulov->umumiysumma;
        }
        echo "
                    <tr class='text-center align-middle fw-bold'>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>ЖАМИ</td>
                        <td>" . number_format($jamiNaqd, 0, ',', ' ') . "</td>
                        <td>" . number_format($jamiPlastik, 0, ',', ' ') . "</td>
                        <td>" . number_format($jamiXR, 0, ',', ' ') . "</td>
                        <td>" . number_format($jamiClick, 0, ',', ' ') . "</td>
                        <td>" . number_format($jamiAvto, 0, ',', ' ') . "</td>
                        <td>" . number_format($jamiChegirma, 0, ',', ' ') . "</td>
                        <td>" . number_format($jamiSumma, 0, ',', ' ') . "</td>
                        <td></td>
                        <td></td>

                    </tr>
            </tbody>
        </table>";
        return;
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
        if ($request->status == 'tulovdelete' && Auth::user()->lavozim_id == 1){
            $tulov = new tulovlar1($request->filial2);
            $tulovkun = $tulov->where('id', $request->id)->where('status', 'Актив')->limit(1)->value('kun');

            if ($tulovkun == date('Y-m-d')) {
                $tulovlar = $tulov->where('id', $request->id)->where('status', 'Актив')
                    ->update([
                        'status' => 'Удалит',
                        'del_kun' => date('Y-m-d'),
                        'del_user_id' => Auth::user()->id,
                    ]);
                return response()->json(['message' => "To'lov o'chirildi."], 200);
            } else {
                $tulovlar = $tulov->where('id', $request->id)->where('status', 'Актив')
                    ->update([
                        'tulovturi' => 'Брон',
                        'del_kun' => date('Y-m-d'),
                        'del_user_id' => Auth::user()->id,
                    ]);
                return response()->json(['message' => "To'lov bronga olindi."], 200);
            }
           
        }else{
            $rules = [
                'filial2' => 'required',
                'yangikun' => 'required',
                'tulovturi' => 'required',
                'shartnomaid' => 'required',
                'naqd' => 'required',
                'pastik' => 'required',
                'hr' => 'required',
                'click' => 'required',
                'avtot' => 'required',
                'chegirma' => 'required',
            ];

            $messages = [
                'filial2.required' => 'Филиал танланмади.',
                'yangikun.required' => 'Сана киритилмади.',
                'tulovturi.required' => 'Харажат турини танланг .',
                'shartnomaid.required' => 'Валюта танланмади.',
                'naqd.required' => 'Сумма киритилмади.',
                'pastik.required' => 'Сумма киритилмади.',
                'hr.required' => 'Сумма киритилмади.',
                'click.required' => 'Сумма киритилмади.',
                'avtot.required' => 'Сумма киритилмади.',
                'chegirma.required' => 'Сумма киритилмади.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
                $tulovlar = new tulovlar1($request->filial2);
                $tulovlar1 = $tulovlar->where('id', $id)->where('status', 'Актив')->first();
                if ($tulovlar1){
                    $tulovlar1 = $tulovlar->where('id', $id)->where('status', 'Актив')->first()->update([
                        'kun' => $request->yangikun,
                        'tulovturi' => $request->tulovturi,
                        'shartnomaid' => $request->shartnomaid,
                        'naqd' => $request->naqd,
                        'pastik' => $request->pastik,
                        'hr' => $request->hr,
                        'click' => $request->click,
                        'avtot' => $request->avtot,
                        'chegirma' => $request->chegirma,
                        'umumiysumma' => $request->naqd + $request->pastik + $request->hr + $request->click + $request->avtot,
                        'user_id' => Auth::user()->id,
                    ]);
                    return response()->json(['message' => 'Маълумот ўзгартирилди.'], 200);
                }else{
                    return response()->json(['message' => 'Хато малумот киритилди.'], 200);
                }

                    
            }else{
                return response()->json(['message' => "Xatolik. Adminga murojaat qiling."], 200);
                
                // Auth::guard('web')->logout();
                // session()->invalidate();
                // session()->regenerateToken();
                // return redirect('/');
            } 
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
