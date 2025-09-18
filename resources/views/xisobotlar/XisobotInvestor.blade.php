@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Хисобот тахлили
                    </h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <div class="row w-75">
                                <div class="col-xl-2">
                                    <input type="date" name="boshkun" class="form-control form-control-sm text-center" id="boshkun"
                                        placeholder=" ">
                                </div>
                                <div class="col-xl-2">
                                    <input type="date" name="yakunkun" class="form-control form-control-sm text-center"
                                        id="yakunkun" placeholder=" ">
                                </div>
                                <div class="col-xl-3">
                                    <select id="filial" name="filial" class="multi-select form-control">
                                        <option value="0">Жами...</option>
                                        @foreach ($filial as $filia)
                                            <option value="{{ $filia->id }}">{{ $filia->fil_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button id="saqlash" class="btn btn-primary btn-xs"> Тасдиқлаш </button>
                                </div>
                                <div class="col-1">
                                    <button id="btnExportexcel" class="btn btn-primary btn-xs ms-2"> Excel </button>
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
                            filename: "InvestorXisoboti"
                        });
                    })
                });

                $("#filial").select2({
                    placeholder: "Филиал...",
                });

                $("#yakunkun").val(new Date().toISOString().substring(0, 10));
                $("#boshkun").val(new Date().toISOString().substring(0, 8) + '01');

                $('#saqlash').on('click', function() {
                    var boshkun = $('#boshkun').val();
                    var yakunkun = $('#yakunkun').val();
                    var filial = $('#filial').val();
                    $('#tabpros').html(" <div style='margin: 100px 0;' class='text-center  d-block'><div style='color: #007bff !important;' class='mx-auto spinner-border text-primary'></div></div>");
                    if (boshkun > yakunkun) {
                        alert("Kunni kiritishda xatolik !!!");
                    } else {
                        $.ajax({
                            url: "{{ route('XisobotInvestor.store') }}",
                            method: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                boshkun: boshkun,
                                yakunkun: yakunkun,
                                filial: filial,
                            },
                            success: function(data) {
                                $('#tabpros').html(data);
                            }
                        })
                    }
                })
            })
        </script>
    @endsection
