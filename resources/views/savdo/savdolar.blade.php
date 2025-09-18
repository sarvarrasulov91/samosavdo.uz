@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Савдо бўлими
                    </h5>
                </li>
            </ol>
        </div>

        <div class="container-fluid ">
            <div class="row">
                <div class="col-6 md-6">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Мавжуд товарлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a title=" Янги савдо ракам олиш " href="" class="btn btn-primary btn-sm ms-2"
                                            onclick="return window.location.reload(1)">+ Янги савдо ракам олиш</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tovarlar">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center text-bold text-primary align-middle">
                                            <th>ID</th>
                                            <th>Товар номи</th>
                                            <th>Суммаси</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($model as $tmodel)
                                            @php
                                                $valyuta = $tmodel->valyuta->valyuta_narhi;
                                                $kirim_narxi = $tmodel->snarhi;
                                                $natsenka = $tmodel->tur->natsenka_id;
                                                $trans_xarajat = $tmodel->tur->transport_id;

                                                $snaxi = round($kirim_narxi * $valyuta * (100 +  $natsenka + $trans_xarajat) / 100, -3);
                                            @endphp
                                            <tr title="Кирим нархи-{{ number_format($tmodel->snarhi, 2, ',', ' ') }} * Валюта-{{ number_format($valyuta, 2, ',', ' ') }} + Тр.хар-{{ $trans_xarajat }}%  +  Наценна-{{ $natsenka }}%  =  Сотув нархи-{{ number_format($snaxi, 0, ',', ' ') }} сўм " class='text-center align-middle'>
                                                <td>{{ $tmodel->tmodel_id }}</td>
                                                <td style="white-space: wrap; width: 50%">
                                                    {{ $tmodel->tur->tur_name }} {{ $tmodel->brend->brend_name }} {{ $tmodel->tmodel->model_name }}
                                                </td>
                                                <td>{{ number_format($snaxi, 0, ',', ' ') }}</td>
                                                <td>
                                                    <button title="{{ $unix_id }}-савдо рақамига бириктириш " id='shart_yubor' data-tur_id='{{ $tmodel->tur_id }}'
                                                        data-brend_id='{{ $tmodel->brend_id }}'
                                                        data-model_id='{{ $tmodel->tmodel_id }}'
                                                        data-unix_id='{{ $unix_id }}'
                                                        data-modelsumma='{{ $snaxi }}'
                                                        class='btn btn-outline-primary btn-sm ms-2'><i class='fa fa-book-medical'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 md-6">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Шартнома учун танланган товарлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        {{ $unix_id }}
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="savdo_raqamlar">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="/vendor/jquery/jquery-3.7.0.min.js"></script>
        <script>
            function showData() {
                $.ajax({
                    url: "/savdolar/create", // Laravel routing yo'lidan foydalaning yoki kerakli URL ga o'zgartiring
                    type: 'GET',
                    data: "",
                    success: function(response) {
                        $('#savdo_raqamlar').html('');
                        response.savdounix_id.map(item_unix => {
                            var html = `<h4 class="text-center text-primary">${item_unix.unix_id}</h4>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="text-center text-bold text-primary align-middle">
                                        <th>ID</th>
                                        <th>Товар номи</th>
                                        <th>Қўшимча</th>
                                        <th>Нархи</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>`;
                            var totalSum = 0;
                            var totalQushimcha = 0;
                            response.savdomodel.map(item => {
                                if (item_unix.unix_id == item.unix_id) {
                                    totalQushimcha += parseFloat(item.qushimch);
                                    totalSum += parseFloat(item.msumma);
                                    html += `<tr class="text-center align-middle">
                                        <td>${item.tmodel_id}</td>
                                        <td style="white-space: wrap; width: 45%">${item.tur.tur_name} ${item.brend.brend_name} ${item.tmodel.model_name}</td>
                                        <td id="qushimcha" title="Қўшимча сумма қўшиш" data-id="${item.id}">${item.qushimch.toLocaleString('fr-FR').replace(/\B(?=(\d{3})+(?!\d))/g, " ")} <i class="flaticon-381-plus"></i></td>
                                        <td>${item.msumma.toLocaleString('fr-FR').replace(/\B(?=(\d{3})+(?!\d))/g, " ")}</td>
                                        <td><a onclick="uchir('${item.id}')" title="Ўчириш" class="btn btn-outline-danger btn-sm ms-2"><i class="fa fa-trash-alt"></i></a></td>
                                    </tr>`;
                                }
                            });

                            html += `<tr class="text-center align-middle fw-bold">
                                <td></td>
                                <td>ЖАМИ</td>
                                <td>${totalQushimcha.toLocaleString('fr-FR').replace(/\B(?=(\d{3})+(?!\d))/g, " ")}</td>
                                <td>${totalSum.toLocaleString('fr-FR').replace(/\B(?=(\d{3})+(?!\d))/g, " ")}</td>
                                <td></td>
                                </tr>
                                </tbody>
                                </table>`;
                            $('#savdo_raqamlar').append(html);
                        });
                    }
                });
            }

            $(document).ready(function() {
                showData();
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })

            $(document).on('click', '#shart_yubor', function() {
                var tur_id = $(this).data('tur_id');
                var brend_id = $(this).data('brend_id');
                var model_id = $(this).data('model_id');
                var unix_id = $(this).data('unix_id');
                var modelsumma = $(this).data('modelsumma');
                $.ajax({
                    url: " {{ route('savdolar.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tur_id: tur_id,
                        brend_id: brend_id,
                        model_id: model_id,
                        unix_id: unix_id,
                        modelsumma: modelsumma,
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        showData();
                    }
                })
            });

            $(document).on('click', '#qushimcha', function() {
                var id = $(this).data('id');
                var qushmchsumma = prompt("Қўшимча суммани киритинг.!!!");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if (qushmchsumma) {
                    $.ajax({
                        url: "{{ route('savdolar.index') }}/" + id,
                        method: "PUT",
                        data: {
                            id: id,
                            qushmchsumma: qushmchsumma
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            showData();
                        }
                    })
                }

            })

            function uchir(id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('savdolar.index') }}/" + id,
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        showData();
                    }
                })
            }
        </script>
        <script src="/vendor/global/global.min.js"></script>
    @endsection
