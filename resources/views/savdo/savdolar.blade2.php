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
                                        <a href="" class="btn btn-primary btn-sm ms-2"
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
                                            <th>Нархи</th>
                                            <th>Тр.хар</th>
                                            <th>Наценка</th>
                                            <th>Суммаси</th>
                                            <th>Танлаш</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">

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
                            <div class="people-list dz-scroll" id="tabpros">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script src="/vendor/jquery/jquery-3.7.0.min.js"></script>
        <script>
            function tabyuklash() {
                $.ajax({
                    url: "{{ route('savdolar.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
                    }
                })
            }

            function TovarTabYuklash() {
                var $id={{ $unix_id }};
                $.ajax({
                    url: "{{ route('savdolar.index') }}/" + $id,
                    method: "GET",
                    data: {},
                    success: function(data) {
                        $('#tab1').html(data);
                    }
                })
            }


            $(document).ready(function() {
                TovarTabYuklash();
                tabyuklash();
                
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
                        tabyuklash();
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
                            tabyuklash();
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
                        tabyuklash();
                    }
                })
            }
        </script>
        <script src="/vendor/global/global.min.js"></script>
    @endsection
