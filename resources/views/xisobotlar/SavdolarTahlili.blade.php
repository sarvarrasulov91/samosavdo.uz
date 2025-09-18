@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Сотилган товарлар тахлилини
                        кўриб олиш бўлими
                    </h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <li id="select_div" class="nav-item" role="presentation">
                                <select id="filial" name="filial" class="multi-select form-control">
                                    <option value="0">Жами...</option>
                                    @foreach ($filial as $filia)
                                        <option value="{{ $filia->id }}">{{ $filia->fil_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </li>
                            <style>
                                #select_div {
                                    width: 150px !important;
                                }
                            </style>
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Сотилган товарлар буйича тахлил</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button id="btnExportexcel" class="btn btn-primary btn-sm ms-2"><i
                                                class="fa fa-file-excel"></i> Excel </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <div id="tabpros">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash() {
                var id = '99';
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                if (id > 0) {
                    $.ajax({
                        url: "{{ route('SavdolarTahlili.index') }}/" + id,
                        method: "GET",
                        data: {
                            filial: id,
                            _token: csrf
                        },
                        success: function(data) {
                            $('#tabpros').html(data);

                        }
                    })
                }
            }

            $(document).ready(function() {
                tabyuklash();
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

                $("#filial").change(function() {
                    var id = $('#filial').val();
                    if (id > 0) {
                        $.ajax({
                            url: "{{ route('SavdolarTahlili.index') }}/" + id + "/edit",
                            method: "GET",
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                $('#tabpros').html(data);
                            }
                        })

                    } else {
                        tabyuklash();
                    }
                });

            })
        </script>
    @endsection
