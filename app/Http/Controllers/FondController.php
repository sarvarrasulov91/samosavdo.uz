<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\fond;
use Illuminate\Support\Facades\Validator;
use App\Models\xissobotoy;
use App\Models\lavozim;
use App\Models\filial;



class fondController extends Controller
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
            $fond = fond::where('status', 'Актив')->orderBy('id', 'desc')->get();
            return view('fond.newfond', ['filial_name' => $filial_name, 'lavozim_name' => $lavozim_name, 'fond' => $fond, 'xis_oyi' => $xis_oyi]);
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

        echo'
            <table class="table table-bordered table-responsive-sm text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary">
                        <th>ID</th>
                        <th>Куни</th>
                        <th>Фондлар</th>
                        <th>Манзили</th>
                        <th>Телефони</th>
                        <th>Х-р</th>
                        <th>Инн</th>
                        <th>МФО</th>
                        <th>Таҳрирлаш</th>
                    </tr>
                </thead>
                <tbody id="tab1">
        ';

            $fond = fond::where('status', 'Актив')->orderBy('id', 'desc')->get();
            foreach ($fond as $fondnew){
                echo '
                <tr>
                    <td>' . $fondnew->id . '</td>
                    <td>' . date('d.m.Y', strtotime($fondnew->created_at)) . '</td>
                    <td>' . $fondnew->pastav_name . '</td>
                    <td>' . $fondnew->manzili . '</td>
                    <td>' . $fondnew->telefoni . '</td>
                    <td>' . $fondnew->xis_raqami . '</td>
                    <td>' . $fondnew->inn . '</td>
                    <td>' . $fondnew->mfo . '</td>
                    <td>
                        <button id="fondedit" class="btn btn-outline-primary btn-sm me-2"
                            title="Таҳрирлаш" data-id="' . $fondnew->id . '"
                            data-pastav_name="' . $fondnew->pastav_name . '"
                            data-manzili="' . $fondnew->manzili . '"
                            data-telefoni="' . $fondnew->telefoni . '"
                            data-xis_raqami="' . $fondnew->xis_raqami . '"
                            data-inn="' . $fondnew->inn . '" data-mfo="' . $fondnew->mfo . '"
                            data-bs-toggle="modal" data-bs-target="#edit"><i
                                class="flaticon-381-notepad"></i></button>
                    </td>
                </tr>
                ';
            }

            echo '
                </tbody>
            </table>
            ';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $rules = [
                'taminotchi' => 'required',
                'manzili' => 'required',
                'telefoni' => 'required|digits:9',
                'xis_raqami' => 'required|digits:20',
                'inn' => 'required|digits:9',
                'mfo' => 'required|digits:5',
            ];

            $messages = [
                'taminotchi.required' => 'Фонд номи киритилмади.',
                'manzili.required' => 'Фонд манзили киритилмади.',
                'telefoni.required' => 'Телефон рақами киритилмади.',
                'xis_raqami.required' => 'Хисоб-раками киритилмади.',
                'inn.required' => 'ИНН киритилмади.',
                'mfo.required' => 'МФО киритилмади.',
                'telefoni.digits' => 'Телефон 9 хонали рақам бўлиши керак.',
                'xis_raqami.digits' => 'Хисоб-раками 20 хонали рақам бўлиши керак.',
                'inn.digits' => 'ИНН 9 хонали рақам бўлиши керак.',
                'mfo.digits' => 'МФО 5 хонали рақам бўлиши керак.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $fond = fond::where('status', 'Актив')->where('pastav_name', $request->taminotchi)->count();
            if ($fond>0) {
                return response()->json(['message' => 'Фонд базада мвжуд.'], 200);
            }else{

                $fond = new fond;
                $fond->pastav_name = $request->taminotchi;
                $fond->manzili = $request->manzili;
                $fond->telefoni = $request->telefoni;
                $fond->xis_raqami = $request->xis_raqami;
                $fond->inn = $request->inn;
                $fond->mfo = $request->mfo;
                $fond->user_id = Auth::user()->id;
                $fond->save();
                $savedFondId = $fond->id;
                $checkFond = fond::find($savedFondId);
                if ($checkFond) {
                    return response()->json(['message' => 'Маълумот сақланди. Фонд ID: ' . $savedFondId], 200);
                } else {
                    return response()->json(['message' => 'Хатолик юз берди, маълумот сақланмади.'], 500);
                }
            }
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

        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {
            $rules = [
                'edittaminotchi' => 'required',
                'editmanzili' => 'required',
                'edittelefoni' => 'required',
                'editxis_raqami' => 'required|digits:20',
                'editinn' => 'required|digits:9',
                'editmfo' => 'required|digits:5',
            ];

            $messages = [
                'edittaminotchi.required' => 'Фонд номи киритилмади.',
                'editmanzili.required' => 'Фонд манзили киритилмади.',
                'edittelefoni.required' => 'Телефон рақами киритилмади.',
                'editxis_raqami.required' => 'Хисоб-раками киритилмади.',
                'editinn.required' => 'ИНН киритилмади.',
                'editmfo.required' => 'МФО киритилмади.',
                'edittelefoni.digits' => 'Телефон 9 хонали рақам бўлиши керак.',
                'editxis_raqami.digits' => 'Хисоб-раками 20 хонали рақам бўлиши керак.',
                'editinn.digits' => 'ИНН 9 хонали рақам бўлиши керак.',
                'editmfo.digits' => 'МФО 5 хонали рақам бўлиши керак.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $tur = fond::where('id', $id)->update([
                'pastav_name' => $request->edittaminotchi,
                'manzili' => $request->editmanzili,
                'telefoni' => $request->edittelefoni,
                'xis_raqami' => $request->editxis_raqami,
                'inn' => $request->editinn,
                'mfo' => $request->editmfo,
                'user_id' => Auth::user()->id,
            ]);

            if ($tur) {
                return response()->json(['message' => 'Маълумот ўзгартирилди.'], 200);
            } else {
                return response()->json(['message' => 'Хатолик рўй берди.'], 500);
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
}
