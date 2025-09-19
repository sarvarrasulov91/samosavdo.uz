<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\savdobonus1;
use App\Models\savdo1;
use App\Models\ktovar1;
use App\Models\tmqaytarish;
use App\Models\filial;


class DasturNazoratiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->lavozim_id == 1 && Auth::user()->status == 'Актив') {

            $filial = filial::where('status', 'Актив')->where('id','!=','10')->get();

            return view('qushmchalar.DasturNazorati', ['filial' => $filial ]);

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        echo'<h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Яратилаётган Баркодлар тахлили</h5>';
        echo '
            <table class="table table-bordered text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Куни</th>
                        <th>Баркод</th>
                        <th>Хонаси</th>
                        <th>Сони</th>
                    </tr>
                </thead>
                <tbody id="tab1">
            ';
            $i=0;
            $ktovar1 = new ktovar1($id);
            $ktovar = $ktovar1->where('status', '!=', 'Удалит')->get();
            foreach ($ktovar as $ktovar){
                $xona=strlen($ktovar->shtrix_kod);
                $KtovarCount = new ktovar1($id);
                $KtovarCount = $KtovarCount->where('shtrix_kod', $ktovar->shtrix_kod)->count();
                if($xona!=17 || $KtovarCount>1){
                    echo'
                        <tr class="align-middle">
                            <td>'.$ktovar->id.'</td>
                            <td>' . date('d.m.Y', strtotime($ktovar->kun)) .'</td>
                            <td>'.$ktovar->shtrix_kod.'</td>
                            <td>'.$xona.'</td>
                            <td>'.$KtovarCount.'</td>
                        </tr>
                    ';
                    $i++;
                }
            }
            if($i==0){
                echo'
                    <tr class="align-middle">
                        <td colspan="5">Камчиликлар аниқланмади.</td>
                    </tr>
                ';
            }
        echo'
            </tbody>
            </table>
        ';

        echo'<h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Чиқим бўлиб кетган товарларни текшириш</h5>';
        echo'<h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Шартнома</h5>';
        echo '
            <table class="table table-bordered text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Куни</th>
                        <th>Баркод</th>
                        <th>Шартнома</th>
                        <th>Холати</th>
                        <th>Чиқим</th>
                    </tr>
                </thead>
                <tbody id="tab1">
            ';
            $i=0;
            $ktovar1 = new ktovar1($id);
            $ktovar = $ktovar1->where('status', 'Шартнома')->get();
            foreach ($ktovar as $ktovar){
                $SavdoCount = new savdo1($id);
                $SavdoCount = $SavdoCount->where('shtrix_kod', $ktovar->shtrix_kod)->where('status','Шартнома')->first();

                if(($SavdoCount->shtrix_kod ?? 0) != $ktovar->shtrix_kod){

                    $tmqaytarish = tmqaytarish::where('shtrix_kod', $ktovar->shtrix_kod)->where('status','Актив')->first();
                    if(!$tmqaytarish){
                        echo'
                        <tr class="align-middle">
                            <td style="width: 10%;">'.$ktovar->id . '</td>
                            <td style="width: 20%;">' . date('d.m.Y', strtotime($ktovar->kun)) . '</td>
                            <td style="width: 20%;">' . $ktovar->shtrix_kod . '</td>
                            <td style="width: 20%;">' . $ktovar->shatnomaid . '</td>
                            <td style="width: 15%;">' . $ktovar->status . '</td>
                            <td style="width: 15%;">' . date('d.m.Y', strtotime($ktovar->ch_kun)) . '</td>
                        </tr>
                    ';
                    $i++;
                    }
                }
            }
            if($i==0){
                echo'
                    <tr class="align-middle">
                        <td colspan="6">Камчиликлар аниқланмади.</td>
                    </tr>
                ';
            }
        echo'
            </tbody>
            </table>
        ';

        echo'<h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Нақд</h5>';
        echo '
            <table class="table table-bordered text-center align-middle ">
                <tbody id="tab1">
            ';

            $i=0;
            $ktovar1 = new ktovar1($id);
            $ktovar = $ktovar1->where('status', 'Нақд')->get();
            foreach ($ktovar as $ktovar){
                $SavdoCount = new savdo1($id);
                $SavdoCount = $SavdoCount->where('shtrix_kod', $ktovar->shtrix_kod)->where('status','Нақд')->first();
                if(($SavdoCount->shtrix_kod ?? 0) != $ktovar->shtrix_kod){
                    $tmqaytarish = tmqaytarish::where('shtrix_kod', $ktovar->shtrix_kod)->where('status','Актив')->first();
                    if(!$tmqaytarish){
                        echo'
                            <tr class="align-middle">
                                <td style="width: 10%;">'.$ktovar->id.'</td>
                                <td style="width: 20%;">' . date('d.m.Y', strtotime($ktovar->kun)) .'</td>
                                <td style="width: 20%;">'.$ktovar->shtrix_kod.'</td>
                                <td style="width: 20%;">'.$ktovar->shatnomaid.'</td>
                                <td style="width: 15%;">'.$ktovar->status.'</td>
                                <td style="width: 15%;">' . date('d.m.Y', strtotime($ktovar->ch_kun)) .'</td>
                            </tr>
                        ';
                        $i++;
                    }
                }
            }
            if($i==0){
                echo'
                    <tr class="align-middle">
                        <td colspan="6">Камчиликлар аниқланмади.</td>
                    </tr>
                ';
            }
        echo'
            </tbody>
            </table>
        ';

        echo'<h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Фонд</h5>';
        echo '
            <table class="table table-bordered text-center align-middle ">
                <tbody id="tab1">
            ';

            $i=0;
            $ktovar1 = new ktovar1($id);
            $ktovar = $ktovar1->where('status', 'Фонд')->get();
            foreach ($ktovar as $ktovar){
                $SavdoCount = new savdo1($id);
                $SavdoCount = $SavdoCount->where('shtrix_kod', $ktovar->shtrix_kod)->where('status','Фонд')->first();
                if(($SavdoCount->shtrix_kod ?? 0) != $ktovar->shtrix_kod){
                    $tmqaytarish = tmqaytarish::where('shtrix_kod', $ktovar->shtrix_kod)->where('status','Актив')->first();
                    if(!$tmqaytarish){
                        echo'
                            <tr class="align-middle">
                                <td style="width: 10%;">'.$ktovar->id.'</td>
                                <td style="width: 20%;">' . date('d.m.Y', strtotime($ktovar->kun)) .'</td>
                                <td style="width: 20%;">'.$ktovar->shtrix_kod.'</td>
                                <td style="width: 20%;">'.$ktovar->shatnomaid.'</td>
                                <td style="width: 15%;">'.$ktovar->status.'</td>
                                <td style="width: 15%;">' . date('d.m.Y', strtotime($ktovar->ch_kun)) .'</td>
                            </tr>
                        ';
                        $i++;
                    }
                }
            }
            if($i==0){
                echo'
                    <tr class="align-middle">
                        <td colspan="6">Камчиликлар аниқланмади.</td>
                    </tr>
                ';
            }
        echo'
            </tbody>
            </table>
        ';


        echo'<h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Бонус</h5>';
        echo '
            <table class="table table-bordered text-center align-middle ">
                <tbody id="tab1">
            ';
            $i=0;
            $ktovar1 = new ktovar1($id);
            $ktovar = $ktovar1->where('status', 'Бонус')->get();
            foreach ($ktovar as $ktovar){
                $savdobonusCount = new savdobonus1($id);
                $savdobonusCount = $savdobonusCount->where('shtrix_kod', $ktovar->shtrix_kod)->where('status','Актив')->first();
                if(($savdobonusCount->shtrix_kod ?? 0) != $ktovar->shtrix_kod){
                    $tmqaytarish = tmqaytarish::where('shtrix_kod', $ktovar->shtrix_kod)->where('status','Актив')->first();
                    if(!$tmqaytarish){
                        echo'
                            <tr class="align-middle">
                                <td style="width: 10%;">'.$ktovar->id.'</td>
                                <td style="width: 20%;">' . date('d.m.Y', strtotime($ktovar->kun)) .'</td>
                                <td style="width: 20%;">'.$ktovar->shtrix_kod.'</td>
                                <td style="width: 20%;">'.$ktovar->shartnoma_id.'</td>
                                <td style="width: 15%;">'.$ktovar->status.'</td>
                                <td style="width: 15%;">' . date('d.m.Y', strtotime($ktovar->ch_kun)) .'</td>
                            </tr>
                        ';
                        $i++;
                    }
                }
            }
            if($i==0){
                echo'
                    <tr class="align-middle">
                        <td colspan="6">Камчиликлар аниқланмади.</td>
                    </tr>
                ';
            }
        echo'
            </tbody>
            </table>
        ';

        echo'<h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Мижозлардан кайтган товарларга янги яратилган баркодларни текшириш</h5>';
        echo '
            <table class="table table-bordered text-center align-middle ">
                <thead>
                    <tr class="text-bold text-primary align-middle">
                        <th>ID</th>
                        <th>Куни</th>
                        <th>Баркод</th>
                        <th>Холати</th>
                        <th>Яратилган ИД</th>
                    </tr>
                </thead>
                <tbody id="tab1">
            ';
            $i=0;

            $tmqaytarish = tmqaytarish::where('status','Актив')->where('filial_id',$id)->get();
            foreach ($tmqaytarish as $tmqaytaris){
                $ktovar1 = new ktovar1($id);
                $ktovar = $ktovar1->where('shtrix_kod', $tmqaytaris->shtrix_kod_yangi)->where('status', '!=', 'Удалит')->first();
                if(!$ktovar){
                    echo'
                        <tr class="align-middle">
                            <td>'.$tmqaytaris->id.'</td>
                            <td>' . date('d.m.Y', strtotime($tmqaytaris->kun)) .'</td>
                            <td>'.$tmqaytaris->shtrix_kod_yangi.'</td>
                            <td>'.$tmqaytaris->status.'</td>
                            <td>'.$tmqaytaris->kirim_id.'</td>
                        </tr>
                    ';
                    $i++;
                }
            }
            if($i==0){
                echo'
                    <tr class="align-middle">
                        <td colspan="6">Камчиликлар аниқланмади.</td>
                    </tr>
                ';
            }
        echo'
            </tbody>
            </table>
        ';


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
