@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Мижозлар туғилган кунларини куриш бўлими
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
                                    <h5 class="bc-title text-primary">Бугунги санада туғилган мижозлар</h5>
                                </li>
                            </ol>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center text-bold text-primary align-middle">
                                            <th>ID</th>
                                            <th>ФИО</th>
                                            <th>Туғилган<br>сана</th>
                                            <th>Телефон<br>рақами</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($mijozlarkun as $mijozla)
                                            <tr class="text-center align-middle">
                                                <td>{{ $mijozla->id }}</td>
                                                <td>{{ $mijozla->last_name .' '. $mijozla->first_name }} <br> {{ $mijozla->middle_name }}</td>
                                                <td>{{ date('d.m.Y', strtotime($mijozla->t_sana)) }}</td>
                                                <td>{{ $mijozla->phone }}</td>
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
                                    <h5 class="bc-title text-primary">Шу ой туғилган мижозлар</h5>
                                </li>
                            </ol>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabpros">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center text-bold text-primary align-middle">
                                            <th>ID</th>
                                            <th>ФИО</th>
                                            <th>Туғилган<br>сана</th>
                                            <th>Телефон<br>рақами</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($mijozlaroy as $mijozla)
                                            <tr class="text-center align-middle">
                                                <td>{{ $mijozla->id }}</td>
                                                <td>{{ $mijozla->last_name .' '. $mijozla->first_name }} <br> {{ $mijozla->middle_name }}</td>
                                                <td>{{ date('d.m.Y', strtotime($mijozla->t_sana)) }}</td>
                                                <td>{{ $mijozla->phone }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script src="/vendor/jquery/jquery-3.7.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })
        </script>
        <script src="/vendor/global/global.min.js"></script>
    @endsection
