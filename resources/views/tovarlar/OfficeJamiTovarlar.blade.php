@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
                            <li id="select_div" class="nav-item" role="presentation">
                                <select id="filial" name="filial" class="multi-select form-control">
                                    <option value="10">Филиал...</option>
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
                                    <h5 class="bc-title text-primary">Товарлар рўйхати</h5>
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
                            <div class="people-list dz-scroll" id="tabpros" style="overflow: auto;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash() {
                var id = $('#filial').val();
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                if (id > 0) {
                    $.ajax({
                        url: "{{ route('OfficeJamiTovarlar.index') }}/" + id,
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

                $(document).ready(function() {
                    $('#filial').select2();
                });


                $("#filial").change(function() {
                    tabyuklash();
                });
            })


            function tovarudalit(id) {
                var uzid = confirm(id + '-товар қайтарилмоқда. ТАСДИҚЛАНГ !!!')
                var filial = $('#filial').val();
                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('OfficeJamiTovarlar.index') }}/" + id,
                        method: "PUT",
                        data: {
                            id: id,
                            filial: filial
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash();
                        }
                    })
                }
            }
        </script>
    @endsection
