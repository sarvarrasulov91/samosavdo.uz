@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Чиким булган товарлар руйхати
                    </h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <div class="row">
                                <div class="col-xl-3">
                                    <input type="date" name="boshkun" class="form-control form-control-sm" id="boshkun"
                                        placeholder=" ">
                                </div>
                                <div class="col-xl-3">
                                    <input type="date" name="yakunkun" class="form-control form-control-sm"
                                        id="yakunkun" placeholder=" ">
                                </div>
                                 <div class="col-xl-3">
                                    <select id="filial" name="filial" class="multi-select form-control">
                                        <option value="">Филиал...</option>
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
                            <div class="people-list dz-scroll" id="tabpros">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="add" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Кирим қилиб олиш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="p-1">
                            <label>Штрих кодни киритинг
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="krimt" name="krimt" class="form-control text-center"
                                maxlength="17" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>



        <script src="/vendor/global/global.min.js"></script>
        <script>
            // function tabyukla() {
            //     $.ajax({
            //         url: "{{ route('chiqimtovarombor.store') }}",
            //         type: 'GET',
            //         data: "",
            //         success: function(data) {
            //             $('#tabpros').html(data);
            //         }
            //     });
            // }


            $(document).ready(function() {
                // tabyukla();
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
                    var boshkun = $('#boshkun').val();
                    var yakunkun = $('#yakunkun').val();
                    var filial = $('#filial').val();
                    if (boshkun > yakunkun) {
                        alert("Кунни киритишла хатолик.!!!");
                    } else {
                        $.ajax({
                            url: "{{ route('chiqimtovarombor.store') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                boshkun: boshkun,
                                yakunkun: yakunkun,
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
                            filename: "ChiqimTovarlar"
                        });
                    })
                });

            })
        </script>
    @endsection
