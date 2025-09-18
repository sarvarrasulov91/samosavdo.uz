<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\tmodel;
use App\Models\tur;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;
use App\Models\brend;
use Illuminate\Support\Facades\Validator;

class TovarModelController extends Controller
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
            $tur = tur::where('status', 'Актив')->get();
            $brend = brend::where('status', 'Актив')->get();
            $model = tmodel::where('status', 'Актив')->orderBy('id', 'desc')->get();
            return view('tovarlar.model.index', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'brend' => $brend, 'xis_oyi' => $xis_oyi, 'tur' => $tur,'model'=>$model]);

        } else {
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
                'tur' => 'required',
                'brend' => 'required',
                'model' => 'required',
            ];

            $messages = [
                'tur.required' => 'Товар турини танланг.',
                'brend.required' => 'Товар брендини танланг.',
                'model.required' => 'Товар модели киритилмади.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $sql = tmodel::where('status', 'Актив')->where('tur_id', $request->tur)->where('brend_id', $request->brend)->where('model_name', $request->model)->count();
            if ($sql>0) {
                return response()->json(['message' => 'Модел базада мвжуд.'], 200);
            }else{
                $brend = new tmodel;
                $brend->tur_id = $request->tur;
                $brend->brend_id = $request->brend;
                $brend->model_name = $request->model;
                $brend->user_id = Auth::user()->id;
                $brend->save();

                $model = tmodel::
                with(['tur'=>function ($query) {
                    $query->select('id','tur_name');
                }])->

                with(['brend'=>function ($query) {
                    $query->select('id','brend_name');
                }])->
                select('id','tur_id','brend_id','model_name','created_at')->where('status', 'Актив')->orderBy('id', 'desc')->get();
                return response()->json(['message' => 'Маълумот сақланди.', 'model'=>$model], 200);
            }

        } else {
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

        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $rules = [
                'edittur' => 'required',
                'editbrend' => 'required',
                'editmodel' => 'required',
            ];

            $messages = [
                'edittur.required' => 'Товар турини танланг.',
                'editbrend.required' => 'Товар брендини танланг.',
                'editmodel.required' => 'Товар модели киритилмади.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $tur = tmodel::where('id', $id)->update([
                'tur_id' => $request->edittur,
                'brend_id' => $request->editbrend,
                'model_name' => $request->editmodel,
                'user_id' => Auth::user()->id,
            ]);

            $model = tmodel::
                with(['tur'=>function ($query) {
                    $query->select('id','tur_name');
                }])->

                with(['brend'=>function ($query) {
                    $query->select('id','brend_name');
                }])->
                select('id','tur_id','brend_id','model_name','created_at')->where('status', 'Актив')->orderBy('id', 'desc')->get();
            return response()->json(['message' => 'Маълумот ўзгартирилди.', 'model'=>$model], 200);

        } else {
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
