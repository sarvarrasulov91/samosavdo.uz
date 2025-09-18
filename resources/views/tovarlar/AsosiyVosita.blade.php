@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Товарларни Асосий воситага олиш бўлими
                    </h5>
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
                                        <a href="" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"
                                            data-bs-target="#add">+ Қўшиш</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button id="btnExportexcel" class="btn btn-primary btn-sm ms-2"><i
                                                class="fa fa-file-excel"></i> Excel </button>
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
            function tabyukla() {
                $.ajax({
                    url: "{{ route('AsosiyVosita.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
                    }
                });
            }


            $(document).ready(function() {
                tabyukla();
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $(function() {
                    $("#btnExportexcel").click(function() {
                        $("#tabpros").table2excel({
                            filename: "AsosiyVositaTovarlar"
                        });
                    })
                });


                $('#krimt').on('keypress', function(e) {
                    if (e.which === 13) {
                        var krimt = $('#krimt').val();
                        if (krimt.length != 17) {
                            toastr.success("Хатолик!!! Маълумотларни тўлиқ киритмадингиз.");
                        } else {
                            $.ajax({
                                url: "{{ route('AsosiyVosita.store') }}",
                                method: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    krimt: krimt
                                },
                                success: function(response) {
                                    toastr.success(response.message);
                                    $('#krimt').val("");
                                    tabyukla();
                                }
                            });
                        }
                    }
                });
            })
        </script>
    @endsection
