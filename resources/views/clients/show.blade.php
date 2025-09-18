@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @if (session('message'))
        <script>
            alert("{{ session('message') }}")
        </script>
    @endif
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Мижоз ҳақида маълумотлар</h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid ">
            @if($client->m_type == 2)
                <div class="row mb-3">
                    <div class="col-xl-12">
                        <div class="bg-dark py-2">
                            <h3 class="text-danger text-center">Ushbu mijoz QORA RO'YXAT ga kiritilgan!</h3>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-xl-6">
                    <div class="card h-auto blog-card">
                        
                        <div class="card-body">
                            <div class="c-profile text-center">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/Windows_10_Default_Profile_Picture.svg" class="rounded-circle mb-2" style="width: 30%;">
                                <h3 class="text-primary"> <span>{{$client->id.'.'}}</span> {{$client->last_name.' '.$client->first_name.' '.$client->middle_name}}  </h3>
                            </div>
                            <div class="c-details" style="margin-top: -10px;">
                                <ul>
                                    <li>
                                        <span>Манзили</span>
                                        <p class="text-primary"> {{$client->viloyat->name_uz.' '.$client->tuman->name_uz.' '.$client->mfy->name_uz.' '.$client->manzil}}</p>
                                    </li>
                                    <li>
                                        <span>Тугилган йили</span>
                                        <p class="text-primary">{{$client->t_sana}}</p>
                                    </li>
                                    <li>
                                        <span>Телефон</span>
                                        <p class="text-primary"> {{$client->phone}}</p>
                                    </li>
                                    <li>
                                        <span>Қўшимча Телефон</span>
                                        <p class="text-primary"> {{$client->extra_phone}}</p>
                                    </li>
                                    <li>
                                        <span>Филиал</span>
                                        <p class="text-primary"> {{$client->filial->fil_name}}</p>
                                    </li>
                                    <div class="d-flex justify-content-around mt-2">
                                        <a href="{{route('clients.index')}}" class="btn btn-warning btn-sm ms-2"> Ортга қайтиш</a>
                                        @if (Auth::user()->lavozim_id == 1)
                                        <a href="{{route('clients.edit', ['client'=>$client->id])}}" class="btn btn-info btn-sm ms-2">Тахрирлаш</a>
                                        @endif
                                        @if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 14) && $client->m_type == 1)
                                            <a href="{{route('blackListClient', ['client'=>$client->id])}}"
                                               class="btn btn-dark btn-sm ms-2"
                                               onclick="return confirm('Shu mijozni Qora ro\'yxatga olmoqchimisiz?');">Qora ro'yxat</a>
                                        @endif
                                        @if ((Auth::user()->lavozim_id == 1 || Auth::user()->lavozim_id == 14) && $client->m_type == 2)
                                            <a href="{{route('blackListClient', ['client'=>$client->id])}}"
                                               class="btn btn-dark btn-sm ms-2"
                                               onclick="return confirm('Shu mijozni Oq ro\'yxatga olmoqchimisiz?');">Oq ro'yxat</a>
                                        @endif
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card h-auto blog-card">
                        <div class="card-body">
                            <div class="c-details" style="margin-top: -10px;">
                                <ul>
                                    <li>
                                        <span>Паспорт</span>
                                        <p class="text-primary"> {{$client->passport_sn}}</p>
                                    </li>
                                    <li>
                                        <span>Паспорт берилган жой</span>
                                        <p class="text-primary"> {{$client->passport_iib}}</p>
                                    </li>
                                    <li>
                                        <span>Паспорт берилган сана</span>
                                        <p class="text-primary">{{$client->passport_date}}</p>
                                    </li>
                                    <li>
                                        <span>ПИНФЛ</span>
                                        <p class="text-primary">{{$client->pinfl}}</p>
                                    </li>
                                    <li>
                                        <span>Иш тумани</span>
                                        <p class="text-primary">{{$ishViloyat .' '.$ishTuman}}</p>
                                    </li>
                                    <li>
                                        <span>Иш жой</span>
                                        <p class="text-primary">{{$client->ish_joy}}</p>
                                    </li>
                                    <li>
                                        <span>Иш ташкилоти</span>
                                        <p class="text-primary">{{$client->ish_tashkiloti}}</p>
                                    </li>
                                    <li>
                                        <span>Касби</span>
                                        <p class="text-primary">{{$client->kasb}}</p>
                                    </li>
                                    <li>
                                        <span>Маоши</span>
                                        <p class="text-primary">{{$client->maosh}}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

       
        <script src="/vendor/global/global.min.js"></script>
        
    @endsection
