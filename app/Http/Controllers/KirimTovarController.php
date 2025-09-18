<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\xissobotoy;
use App\Models\filial;
use App\Models\pastavshik;
use App\Models\ktovar1;
use App\Models\tur;
use App\Models\valyuta;
use App\Models\tmodel;
use App\Models\lavozim;

use Illuminate\Support\Facades\Validator;


class KirimTovarController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
    {
       if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
            $lavozim_name = lavozim::where('id', Auth::user()->lavozim_id)->value('lavozim');
            $filial_name = filial::where('id', Auth::user()->filial_id)->value('fil_name');
            $filial = filial::where('status', 'Актив')->get();
            $pastavshik = pastavshik::where('status', 'Актив')->get();
            $valyuta = valyuta::get();;
            $model = tmodel::where('status', 'Актив')->orderBy('id', 'desc')->get();

            return view('tovarlar.kirimtovar.index', [
                'filial_name' => $filial_name, 
                'lavozim_name' => $lavozim_name, 
                'model' => $model, 
                'xis_oyi' => $xis_oyi, 
                'filial' => $filial, 
                'pastavshik' => $pastavshik, 
                'valyuta' => $valyuta
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
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $rules = [
                'yangikun' => 'required',
                'filial' => 'required',
                'pastavshik' => 'required',
                'tovarmodeli' => 'required',
                'valyuta' => 'required',
                'tsoni' => 'required',
                'tsumma' => 'required',
            ];

            $messages = [
                'yangikun.required' => 'Кунини киритилмади.',
                'filial.required' => 'Филиални танланг.',
                'pastavshik.required' => 'Таъминотчини танланг.',
                'tovarmodeli.required' => 'Товарни танланг.',
                'valyuta.required' => 'Валютани танланг.',
                'tsoni.required' => 'Товар сонини киритилмади.',
                'tsumma.required' => 'Товар суммасини киритилмади.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }else{

                $xis_oyi = xissobotoy::latest('id')->value('xis_oy');
                $valyuta_narhi = valyuta::where('id', $request->valyuta)->value('valyuta_narhi');

                $tsoni = floatval(preg_replace('/[^\d.]/', '', $request->tsoni));
                $tsumma = floatval(preg_replace('/[^\d.]/', '', $request->tsumma));

                $tovarmodel = tmodel::where('id', $request->tovarmodeli)->first();
                if ($tovarmodel) {

                    $soninar = 0;
                    $model = new ktovar1($request->filial);
                    // $soninar = ktovar1::where('tmodel_id', $tovarmodel->id)->max('soni');
                     $soninar = $model->where('tmodel_id', $tovarmodel->id)->orderBy('soni', 'desc')->value('soni');

                    $i = 1;
                    while ($i <= $tsoni) {
                        $soninar++;

                        $turid2 = str_pad($tovarmodel->tur_id, 4, "0", STR_PAD_LEFT);
                        $brendid2 = str_pad($tovarmodel->brend_id, 4, "0", STR_PAD_LEFT);
                        $model2 = str_pad($tovarmodel->id, 5, "0", STR_PAD_LEFT);
                        $soninar2 = str_pad($soninar, 4, "0", STR_PAD_LEFT);

                        $shtr_kod = $turid2 . $brendid2 . $model2 . $soninar2;

                        $zaqis = new ktovar1($request->filial);
                        $zaqis->kun = $request->yangikun;
                        $zaqis->tur_id = $tovarmodel->tur_id;
                        $zaqis->brend_id = $tovarmodel->brend_id;
                        $zaqis->tmodel_id = $request->tovarmodeli;
                        $zaqis->shtrix_kod = $shtr_kod;
                        $zaqis->soni = $soninar;
                        $zaqis->valyuta_id = $request->valyuta;
                        $zaqis->narhi = $tsumma;
                        $zaqis->snarhi = $tsumma;
                        $zaqis->valyuta_narhi = $valyuta_narhi;
                        $zaqis->tannarhi = ($tsumma*$valyuta_narhi);
                        $zaqis->pastavshik_id = $request->pastavshik;
                        $zaqis->pastavshik2_id = $request->pastavshik;
                        $zaqis->filial_id = $request->filial;
                        $zaqis->xis_oyi = $xis_oyi;
                        $zaqis->user_id = Auth::user()->id;
                        $zaqis->save();
                        $i++;
                    }

                    $message = 'Маълумот сақланди.';

                } else {
                    $message = 'Товарни киритишда хатолик.';
                }

                $model = new ktovar1($request->filial);
                $modelsoni = $model->where('valyuta_id', $request->valyuta)
                ->where('tmodel_id', $request->tovarmodeli)
                ->where('status','Сотилмаган')
                ->update([
                    'snarhi' => $tsumma,
                ]);
            }

            $model = new ktovar1($request->filial);
            $datamodel = $model->select(['id','kun','narhi','tur_id','brend_id','tmodel_id','valyuta_id','soni','shtrix_kod','pastavshik_id',])
            ->where('status', 'Актив')->orderBy('id', 'desc')->get();
            $datamodel->load(['tur:id,tur_name','brend:id,brend_name','tmodel:id,model_name','valyuta:id,valyuta__nomi','pastavshik:id,pastav_name',]);

            return response()->json(['message' => $message, 'datamodel'=>$datamodel], 200);

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

       if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив' && $request->id>0 && $request->filial>0 ) {
            $model = new ktovar1($request->filial);
            $data=$model->where('id', $request->id)->
            update([
                'status' => "Удалит",
                'u_kun' => date('Y-m-d H:i:s'),
                'u_user_id' => Auth::user()->id
            ]);

            if ($request->filial > 0) {
                $model = new ktovar1($request->filial);
                $datamodel = $model->select(['id','kun','narhi','tur_id','brend_id','tmodel_id','valyuta_id','soni','shtrix_kod','pastavshik_id',])
                ->where('status', 'Актив')->orderBy('id', 'desc')->get();
                $datamodel->load(['tur:id,tur_name','brend:id,brend_name','tmodel:id,model_name','valyuta:id,valyuta__nomi','pastavshik:id,pastav_name',]);
                return response()->json(['message' => 'Маълумот ўчирилди.', 'datamodel'=>$datamodel], 200);
            }else{
                return response()->json(['datamodel'=>''], 200);
            }

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

    public function filbaza(Request $request)
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            if ($request->filial > 0) {
                $model = new ktovar1($request->filial);
                $datamodel = $model->select(['id','kun','narhi','tur_id','brend_id','tmodel_id','valyuta_id','soni','shtrix_kod','pastavshik_id',])
                ->where('status', 'Актив')->orderBy('id', 'desc')->get();
                $datamodel->load(['tur:id,tur_name','brend:id,brend_name','tmodel:id,model_name','valyuta:id,valyuta__nomi','pastavshik:id,pastav_name',]);
                return response()->json(['datamodel'=>$datamodel], 200);
            }else{
                return response()->json(['datamodel'=>''], 200);
            }
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }

    }

    public function sungimodel(Request $request)
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $tovarmodeli = $request->tovarmodeli;
            if ($request->filial > 0 && $tovarmodeli > 0) {
                $model = new ktovar1($request->filial);
                $datamodel = $model->select(['id','kun','narhi','tur_id','brend_id','tmodel_id','valyuta_id','soni','shtrix_kod','pastavshik_id',])
                ->where('tmodel_id', $tovarmodeli)->orderBy('id', 'desc')->limit(1)->get();
                $datamodel->load(['tur:id,tur_name','brend:id,brend_name','tmodel:id,model_name','valyuta:id,valyuta__nomi','pastavshik:id,pastav_name',]);
                return response()->json(['data'=>$datamodel], 200);
            }else{
                return response()->json(['data'=>''], 200);
            }
        }else{
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }
    }
}
