@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">ОФИС КАССА БЎЛИМИ</h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Кассадаги қолдиғи {{ $du2 }} холатига</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="btn btn-primary btn-sm ms-2"
                                            onclick="return window.location.reload(1)"><i
                                                class="flaticon-381-home-2 me-2"></i>
                                            Янгилаш
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <table class="table table-bordered table-responsive-sm text-center align-middle table-hover"  style="font-size: 12px">
                                    <thead>
                                        <tr class="text-bold text-primary align-middle">
                                            <th rowspan="2">№</th>
                                            <th rowspan="2">Номи</th>
                                            <th colspan="2">Ой бошига</th>
                                            <th colspan="2">Кирим</th>
                                            <th colspan="2">Чиқим</th>
                                            <th colspan="2">Ой охирига</th>
                                        </tr>
                                        <tr class="text-bold text-primary align-middle">
                                            <th>Сўм</th>
                                            <th>Доллар</th>
                                            <th>Сўм</th>
                                            <th>Доллар</th>
                                            <th>Сўм</th>
                                            <th>Доллар</th>
                                            <th>Сўм</th>
                                            <th>Доллар</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                    </tbody>
                                </table>


                                <!--{{-- <div class="row items-center justify-content-md-center mt-5">-->
                                <!--    <div class="col-xl-5 ">-->
                                <!--        <div class="basic-list-group blog-card">-->
                                <!--            <ul class="list-group text-center">-->
                                <!--                <li-->
                                <!--                    class="list-group-item d-flex justify-content-between align-items-center">-->
                                <!--                    <span class="text-muted"><b>Сўм</b></span>-->
                                <!--                </li>-->
                                <!--                <li-->
                                <!--                    class="list-group-item d-flex justify-content-between align-items-center">-->
                                <!--                    <span class="text-muted">Нақд</span><span-->
                                <!--                        class="badge-pill text-primary">{{ number_format($snaqd, 2, ',', ' ') }}</span>-->
                                <!--                </li>-->
                                <!--                <li-->
                                <!--                    class="list-group-item d-flex justify-content-between align-items-center">-->
                                <!--                    <span class="text-muted">Пластик</span> <span-->
                                <!--                        class="badge-pill text-primary">{{ number_format($spastik, 2, ',', ' ') }}</span>-->
                                <!--                </li>-->
                                <!--                <li-->
                                <!--                    class="list-group-item d-flex justify-content-between align-items-center">-->
                                <!--                    <span class="text-muted">Хисоб-рақам</span> <span-->
                                <!--                        class="badge-pill text-primary">{{ number_format($shr, 2, ',', ' ') }}</span>-->
                                <!--                </li>-->
                                <!--                <li-->
                                <!--                    class="list-group-item d-flex justify-content-between align-items-center">-->
                                <!--                    <span class="text-muted">Click</span> <span-->
                                <!--                        class="badge-pill text-primary">{{ number_format($sclick, 2, ',', ' ') }}</span>-->
                                <!--                </li>-->
                                <!--                <li-->
                                <!--                    class="list-group-item d-flex justify-content-between align-items-center">-->
                                <!--                    <span class="text-muted">Авто тўлов</span> <span-->
                                <!--                        class="badge-pill text-primary">{{ number_format($savtot, 2, ',', ' ') }}</span>-->
                                <!--                </li>-->
                                <!--                <li-->
                                <!--                    class="list-group-item d-flex justify-content-between align-items-center">-->
                                <!--                    <span class="text-muted">Жами</span> <span-->
                                <!--                        class="badge-pill text-danger">{{ number_format($snaqd + $spastik + $shr + $sclick + $savtot, 2, ',', ' ') }}</span>-->
                                <!--                </li>-->
                                <!--            </ul>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--    <div class="col-xl-5 ">-->
                                <!--        <div class="basic-list-group blog-card">-->
                                <!--            <ul class="list-group text-center">-->
                                <!--                <li-->
                                <!--                    class="list-group-item d-flex justify-content-between align-items-center">-->
                                <!--                    <span class="text-muted"><b>Доллар</b></span>-->
                                <!--                </li>-->
                                <!--                <li-->
                                <!--                    class="list-group-item d-flex justify-content-between align-items-center">-->
                                <!--                    <span class="text-muted">Нақд</span><span-->
                                <!--                        class="badge-pill text-primary">{{ number_format($dnaqd, 2, ',', ' ') }}</span>-->
                                <!--                </li>-->
                                <!--                <li-->
                                <!--                    class="list-group-item d-flex justify-content-between align-items-center">-->
                                <!--                    <span class="text-muted">Жами</span> <span-->
                                <!--                        class="badge-pill text-danger">{{ number_format($dnaqd , 2, ',', ' ') }}</span>-->
                                <!--                </li>-->
                                <!--            </ul>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div> --}}-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash() {
                $.ajax({
                    url: "{{ route('officekassa.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tab1').html(data);
                    }
                });
            }
            $(document).ready(function() {
                tabyuklash();
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })

        </script>
    @endsection
