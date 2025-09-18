@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .removeRow {
            background: -webkit-gradient(linear, left top, left bottom, from(#D8E6F3), to(#f9f5f5));
            background: -moz-linear-gradient(top, #f2f2f2, #f0f0f0);
        }

        input.tanlash_checkbox {
            width: 20px;
            height: 20px;
        }

        input.selectall {
            width: 22px;
            height: 22px;
        }
    </style>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Жами кирим қилиб олинган
                        товарлар бўлими </h5>
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
                                    <h5 class="bc-title text-primary">Товарлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <form method="POST" action="{{ route('jamitovarlar.store') }}">
                                            @csrf
                                            <button id="barkod" name="barkod" class="btn btn-primary btn-sm ms-2"><i
                                                    class="fa fa-print"></i> Чоп этиш </button>
                                            <button id="btnExportexcel" class="btn btn-primary btn-sm ms-2"><i
                                                class="fa fa-file-excel"></i> Excel </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabpros" style="overflow: auto;">
                                <table class="table table-bordered table-responsive-sm text-center align-middle ">
                                    <thead>
                                        <tr class="text-bold text-primary">
                                            <th><input type="checkbox" id="selectall" class="selectall"></th>
                                            <th>ID</th>
                                            <th>Куни</th>
                                            <th>Модел ID</th>
                                            <th>Тури</th>
                                            <th>Бренди</th>
                                            <th>Модели</th>
                                            <th>Штрих-раками</th>
                                            <th>Кирим нарх</th>
                                            <th>Валюта</th>
                                            <th>Натсенка</th>
                                            <th>Транспорт</th>
                                            <th>Сотув нархи</th>
                                            <th>Шарт. ID</th>
                                            <th>Холати</th>
                                            <th>Таъминотчи</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($model2 as $mode)
                                             @php
                                                $valyuta = $mode->valyuta->valyuta_narhi;
                                                $kirim_narxi = $mode->snarhi;
                                                $natsenka = $mode->tur->natsenka_id;
                                                $trans_xarajat = $mode->tur->transport_id;
                                            @endphp
                                            <tr>
                                                <td><input type="checkbox" class="tanlash_checkbox" name="belginatija[]"
                                                    value="{{ $mode->id }}"></td>
                                                <td> {{ $mode->id }}</td>
                                                <td> {{ date('d.m.Y', strtotime($mode->kun)) }}</td>
                                                <td> {{ $mode->tmodel_id }}</td>
                                                <td> {{ $mode->tur->tur_name }}</td>
                                                <td> {{ $mode->brend->brend_name }}</td>
                                                <td> {{ $mode->tmodel->model_name }}</td>
                                                <td> {{ $mode->shtrix_kod }}</td>
                                                <td> {{ $kirim_narxi }}</td>
                                                <td> {{ $mode->valyuta->valyuta__nomi }}</td>
                                                <td> {{ $natsenka }}</td>
                                                <td> {{ $trans_xarajat }}</td>
                                                <td> {{ round($kirim_narxi * $valyuta * (100 + $natsenka + $trans_xarajat) / 100, -3)}}</td>
                                                <td> {{ $mode->shatnomaid }}</td>
                                                <td> {{ $mode->status }}</td>
                                                <td> {{ $mode->pastavshik->pastav_name }}</td>
                                                </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="/vendor/global/global.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $(function() {
                    $("#btnExportexcel").click(function() {
                        $("#tabpros").table2excel({
                            filename: "Tovarlar"
                        });
                    })
                });
            })

            $(document).ready(function() {
                /* Белги куйилса буяш ёки учириш */
                $('.tanlash_checkbox').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).closest('tr').addClass('removeRow');
                    } else {
                        $(this).closest('tr').removeClass('removeRow');
                    }
                    
                    toggleBarkodButton(); // Check button state after checkbox interaction
                });

                /* Хаммасини буяш ёки учириш */
                $('body').on('click', '#selectall', function() {
                    $('.tanlash_checkbox').prop('checked', this.checked);
                    
                    toggleBarkodButton(); // Check button state after checkbox interaction

                });
                
                $('body').on('click', '.tanlash_checkbox', function() {
                    if ($('.tanlash_checkbox').length == $('.tanlash_checkbox:checked').length) {
                        $('#selectall').prop('checked', true);
                    } else {
                        $("#selectall").prop('checked', false);
                    }
                    
                    toggleBarkodButton(); // Check button state after checkbox interaction

                });
                
                
                /* Function to enable/disable the #barkod button */
                function toggleBarkodButton() {
                    if ($('.tanlash_checkbox:checked').length === 0) {
                        $('#barkod').prop('disabled', true);
                    } else {
                        $('#barkod').prop('disabled', false);
                    }
                }

                // Initialize button state on page load
                toggleBarkodButton();
            })
        </script>
    @endsection
