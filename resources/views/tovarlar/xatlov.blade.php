@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Товарларни хатловдан ўтказиш
                        бўлими
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
                                    <option value="">Филиал...</option>
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
                                        <a href="" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"
                                            data-bs-target="#add">+ Хатлов</a>
                                    </li>
                                    @if (Auth::user()->lavozim_id == 1)
                                    <li class="nav-item" role="presentation">
                                        <button class="btn btn-danger btn-sm ms-2" id="clear">- Тозалаш</button>
                                    </li>
                                    @endif
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

        <div id="add" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Товарларни хатловдан ўтказиш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="p-1">
                            <label>Штрих кодни киритинг
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="krimt" name="krimt" class="form-control text-center" maxlength="17" />
                            <h4 class="border p-2 text-center border-0" id="pros" style="color: RoyalBlue;"></h4>
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

            function tabyuklash() {
                var id = $('#filial').val();
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                if (id > 0) {
                $('#tabpros').html("<div style='margin: 100px 0; 'class='text-center d-block'><div style='color: #007bff !important;' class='mx-auto spinner-border text-primary'></div></div>");
                    $.ajax({
                        url: "{{ route('xatlov.index') }}/" + id,
                        method: 'GET',
                        data: {
                                filial: id,
                                _token: csrf
                            },
                        success: function(data) {
                            $('#tabpros').html(data);
                        }
                    });
                }
            }

            $(document).ready(function() {
                $('#filial').select2();
            });

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
                    tabyuklash();
                });

                $('#krimt').on('keypress', function(e) {
                    if (e.which === 13) {
                        var krimt = $('#krimt').val();
                        var filial = $('#filial').val();

                        if (!filial) {
                            alert('Filialni tanlang');  // Show alert if no filial is selected
                            return;  // Exit the function if filial is not selected
                        }

                        if (krimt.length != 17) {
                            toastr.success("Хатолик!!! Маълумотларни тўлиқ киритилмади.");
                        } else {
                            $.ajax({
                                url: "{{ route('xatlov.store') }}",
                                method: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    krimt: krimt,
                                    filial: filial,
                                },
                                success: function(response) {
                                    $('#krimt').val("");
                                    toastr.success(response.message);
                                    tabyuklash();
                                },
                                error: function(response) {
                                    toastr.error("Xatolik yuz berdi!");  // Handle errors
                                }
                            });
                        }
                    }
                });
            })

            $(document).on('click', '#clear', function() {
                var id = $('#filial').val();  // Get the selected filial ID
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                if (!id) {  // Check if an ID was selected
                    alert("Filialni tanlang.");  // Show alert if no filial is selected
                } else {
                    if (confirm(id + ' - Базани тозалашни тасдикланг!')) {
                        $.ajax({
                            url: "{{ route('xatlov.index') }}/" + id,  // Append 'id' to the URL
                            method: "DELETE",  // Send DELETE request
                            data: {
                                _token: csrf,
                                id: id,// CSRF token for security
                            },
                            success: function(response) {
                                toastr.success(response.message);  // Show success message
                                tabyuklash();  // Call the function to refresh data
                            },
                            error: function(response) {
                                toastr.error(response.responseJSON.message || "Xatolik yuz berdi.");  // Handle errors
                            }
                        });
                    }
                }
            });

        </script>
    @endsection
