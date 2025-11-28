<?php

namespace App\Http\Controllers;

use App\Models\filial;
use App\Models\Mijozlar;
use App\Models\Savdo;
use App\Models\Shartnoma;
use App\Models\Tulovlar;
use App\Models\xissobotoy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShartnomaIdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->filial_id == 10 && Auth::user()->status == 'Актив') {
            $filial = filial::where('status', 'Актив')->whereNotIn('id',[7, 10])->get();
        } else {
            $filial = filial::where('status', 'Актив')->where('id', Auth::user()->filial_id)->get();
        }
        return view('shartnoma.ShartnomaId', ['filial' => $filial]);
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
        $id = $request->id;
        $filial = $request->filial;

        echo '
            <table
                class="table table-bordered table-responsive-sm text-center align-middle table-hover"
                style="font-size: 12px;">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th style="width: 200px">ФИО</th>
                        <th>Манзили</th>
                        <th>Телефон<br>рақами</th>
                        <th>Шартнома<br>санаси</th>
                        <th>Шартнома<br>муддати</th>
                        <th>Товар<br>суммаси</th>
                        <th>Шартнома<br>суммаси</th>
                        <th>Шартнома<br>статуси</th>
                    </tr>
                </thead>
                <tbody id="tab1">';

        if($request->radioButton == 'mijoz'){
            $clients = Mijozlar::where(function($query) use ($id) {
                $query->whereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$id}%"])
                    ->orWhere('phone', 'LIKE', "%{$id}%")
                    ->orWhere('passport_sn', 'LIKE', "%{$id}%")
                    ->orWhere('pinfl', 'LIKE', "%{$id}%");
            })->get();

            $clientIds = $clients->pluck('id')->toArray();
            $shartnomalar = Shartnoma::where('filial_id', $filial)->whereIn('mijozlar_id', $clientIds)->get();
        }else{
            $shartnomalar = Shartnoma::where('filial_id', $filial)->where('shid', $id)->get();
        }

        if ($shartnomalar->isNotEmpty()){

            foreach($shartnomalar as $shartnoma){

                $savdosummasi = Savdo::where('filial_id', $filial)->where('status', 'Шартнома')->where('shartnoma_id', $shartnoma->id)->sum('msumma');
                $tulovInfo = Tulovlar::where('filial_id', $filial)->where('tulovturi', 'Олдиндан тўлов')
                    ->where('status', 'Актив')
                    ->where('shartnoma_id', $shartnoma->id)
                    ->first();

                $oldindantulovsummasi = $tulovInfo->umumiysumma ?? 0;
                $otulovchegirmasummasi = $tulovInfo->chegirma ?? 0;

                $foiz = xissobotoy::where('xis_oy', $shartnoma->xis_oyi)->value('foiz');

                if ($shartnoma->fstatus == 0) {
                    $foiz = 0;
                }

                //йиллик фойиз
                $foiz = (($foiz / 12) * $shartnoma->muddat);

                if ($shartnoma->kun < "2023-12-05") {
                    $xis_foiz = ((($savdosummasi - $oldindantulovsummasi - $otulovchegirmasummasi) * $foiz) / 100);
                } else {
                    $xis_foiz = ((($savdosummasi - $otulovchegirmasummasi) * $foiz) / 100);
                }

                $trrang="align-middle";

                if($shartnoma->status == 'Ёпилган'){
                    $trrang = "align-middle text-success";
                }

                if($shartnoma->status == 'Кутиш'){
                    $trrang = "align-middle text-warning";
                }

                if($shartnoma->status == 'Удалит'){
                    $trrang = "align-middle text-danger";
                }

                echo '
                        <tr id="modalshartshow" data-id="' . $shartnoma->id . '" data-fio="' . addslashes($shartnoma->mijozlar->last_name) . ' ' . addslashes($shartnoma->mijozlar->first_name) . ' ' . addslashes($shartnoma->mijozlar->middle_name) . '"  class="'.$trrang.'">
                            <td>' . $shartnoma->id . '</td>
                            <td style="white-space: pre-wrap;">' . $shartnoma->mijozlar->last_name . ' ' . $shartnoma->mijozlar->first_name . ' ' . $shartnoma->mijozlar->middle_name . '</td>
                            <td style="white-space: pre-wrap">' . $shartnoma->mijozlar->tuman->name_uz . ' ' . $shartnoma->mijozlar->mfy->name_uz . ' ' . $shartnoma->mijozlar->manzil . '</td>
                            <td>' . $shartnoma->mijozlar->phone . '</td>
                            <td>' . date('d.m.Y', strtotime($shartnoma->kun)) . '</td>
                            <td>' . $shartnoma->muddat . '</td>
                            <td>' . number_format($savdosummasi, 2, ",", " ") . '</td>
                            <td>' . number_format($savdosummasi - $oldindantulovsummasi - $otulovchegirmasummasi + $xis_foiz, 2, ",", " ") . '</td>
                            <td>' . $shartnoma->status . '</td>
                        </tr>';


            } // endforeach

        }else{
            echo '
                <tr>
                    <td colspan="10"> Shartnoma topilmadi!</td>
                </tr>';
        } // endif

        echo'
            </tbody></table>';

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
