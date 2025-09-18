@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Жами сотилмаган
                        товарлар бўлими </h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <div class="row w-100">
                                <div class="col-xl-2">
                                    <select id="filial" name="filial" class="multi-select form-control">
                                        <option value=""></option>
                                        @foreach ($filial as $filia)
                                            <option value="{{ $filia->id }}">{{ $filia->fil_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2">
                                    <button id="saqlash" class="btn btn-primary btn-xs"> Тасдиқлаш </button>
                                </div>
                                <div class="col-xl-2" role="presentation">
                                    <button id="btnExportexcel" class="btn btn-primary btn-xs"><i class="fa fa-file-excel"></i> Excel </button>
                                </div>

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
                var filial = $('#filial').val();
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                $('#tabpros').html("<div style='margin: 100px 0; 'class='text-center d-block'><div style='color: #007bff !important;' class='mx-auto spinner-border text-primary'></div></div>");
                if (!filial) {
                    alert("Filialni tanlang.!!!");
                } else {
                    $.ajax({
                        url: "{{ route('OfficeSotilmaganTovarlar.store') }}",
                        method: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            filial: filial,
                        },
                        success: function(data) {
                            $("#tabpros").html(data);
                        }
                    })
                }
            }

            $(document).ready(function() {
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $("#filial").select2({
                    placeholder: "Филиал...",
                });

                $("#yakunkun").val(new Date().toISOString().substring(0, 10));
                $("#boshkun").val(new Date().toISOString().substring(0, 8) + '01');

                $('#saqlash').on('click', function() {
                    var filial = $('#filial').val();
                    var csrf = document.querySelector('meta[name="csrf-token"]').content;
                    $('#tabpros').html("<div style='margin: 100px 0; 'class='text-center d-block'><div style='color: #007bff !important;' class='mx-auto spinner-border text-primary'></div></div>");
                    if (!filial) {
                        alert("Filialni tanlang.!!!");
                    } else {
                        $.ajax({
                            url: "{{ route('OfficeSotilmaganTovarlar.store') }}",
                            method: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                filial: filial,
                            },
                            success: function(data) {
                                $("#tabpros").html(data);
                            }
                        })
                    }
                })

                $(function() {
                    $("#btnExportexcel").click(function() {
                        $("#tabpros").table2excel({
                            filename: "JamiSotilmaganTovarlar"
                        });
                    })
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
                        url: "{{ route('OfficeSotilmaganTovarlar.index') }}/" + id,
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
