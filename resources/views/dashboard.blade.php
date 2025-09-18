@extends('layouts.almas_site')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="content-body">
    <div class="page-titles" style="justify-content:center !important">
        <ol class="breadcrumb">
            <li>
                <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Умумий тахлиллар </h5>
            </li>
        </ol>
    </div>
    <div class="container-fluid ">
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-sm-6 blog-card">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <i class="ti-user"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1 text-primary">Ходимлар</p>
                                <h4 class="mb-0 text-primary">{{  number_format($userlar, 0, ',', ' ') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6 blog-card">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-warning text-warning">
                                <i class="fa-sharp fa-solid fa-b"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1 text-warning">Брендлар</p>
                                <h4 class="mb-0 text-warning">{{  number_format($brend, 0, ',', ' ') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6 blog-card">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-success text-success">
                                <i class="fa-solid fa-database"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1 text-success">Товар турлари</p>
                                <h4 class="mb-0 text-success">{{  number_format($tmodel, 0, ',', ' ') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6 blog-card">
                <div class="widget-stat card">
                    <div class="card-body  p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-danger text-danger">
                                <i class="fa-solid fa-dollar-sign"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1 text-danger">Валюта</p>
                                <h4 class="mb-0 text-danger">{{  number_format($valyuta, 0, ',', ' ') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-xxl-4 col-lg-12 blog-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Мижозлар</h4>
                        <div class="row dz-scroll style-1 p-3 height370">
                            @foreach ($mfy as $mfy)
                                <div class="col-12 p-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-primary">{{ $mfy->mfy->tuman_id }} - {{ $mfy->mfy->name_uz }}</span>
                                        <h6 class="text-primary">{{ (number_format(($mfy->soni/$mijozsoni)*100, 0, ',', '')) }}%</h6>
                                    </div>
                                    <div class="progress">
                                        @if((($mijozsoni/10) < $mfy->soni))
                                            <div class="progress-bar bg-primary" style="width: {{ (number_format($mfy->soni/$mijozsoni*100, 0, ',', '')) }}%"></div>
                                        @elseif ((($mijozsoni/20) < $mfy->soni) && (($mijozsoni/10) > $mfy->soni))
                                            <div class="progress-bar bg-warning" style="width: {{ (number_format($mfy->soni/$mijozsoni*100, 0, ',', '')) }}%"></div>
                                        @else
                                            <div class="progress-bar bg-danger" style="width: {{ (number_format($mfy->soni/$mijozsoni*100, 0, ',', '')) }}%"></div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-xxl-4 col-lg-12 blog-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="">Тузилган шартномалар</h4>
                        <div class="row dz-scroll style-1 p-3 height370">
                            @foreach ($mtashrif as $mtashrif)
                                <div class="col-12 p-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-primary">{{ $mtashrif->tashrif->id . ' - ' . $mtashrif->tashrif->tashrif_name }}</span>
                                        <h6 class="text-primary">{{ (number_format(($mtashrif->soni/$shartnomasoni)*100, 0, ',', ' ')) }}%</h6>
                                    </div>
                                    <div class="progress">
                                        @if(($mtashrif->soni) > ($shartnomasoni/10))
                                            <div class="progress-bar bg-primary" style="width: {{ (number_format(($mtashrif->soni/$shartnomasoni)*100, 0, ',', '')) }}%"></div>
                                        @elseif ((($mtashrif->soni) > ($shartnomasoni/20)) && (($mtashrif->soni) < ($shartnomasoni/10)))
                                            <div class="progress-bar bg-warning" style="width: {{ (number_format(($mtashrif->soni/$shartnomasoni)*100, 0, ',', '')) }}%"></div>
                                        @else
                                            <div class="progress-bar bg-danger" style="width: {{ (number_format(($mtashrif->soni/$shartnomasoni)*100, 0, ',', '')) }}%"></div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-xxl-4 col-lg-12 blog-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="">Бугунги санада туғилган мижозлар</h4>
                        <div class="row dz-scroll style-1 p-3 height370">
                            @foreach ($mijozlarkun as $mijozla)
                                <div class="col-12 p-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-primary">{{ $mijozla->last_name .' '. $mijozla->first_name }} {{ $mijozla->middle_name }}</span>
                                        <h6 class="text-primary">{{ date('d.m.Y', strtotime($mijozla->t_sana)) }}</h6>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-primary">{{ $mijozla->tuman->name_uz.' '.$mijozla->mfy->name_uz  }} </span>
                                        <h6 class="text-primary">{{ $mijozla->phone }}</h6>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: 0%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/vendor/global/global.min.js"></script>
@endsection
