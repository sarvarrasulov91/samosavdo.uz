@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Таъминотчига тўлонган тўловларни рўйхатга олиш
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
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Тўловлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"
                                            data-bs-target="#chiqim_taminot">+ Қўшиш</a>
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


        <!-- Modal chiqim_add -->
        <div id="chiqim_taminot" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Янги тўлов қўшиш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="chiqim_add_taminot" method="POST">
                            @csrf
                            <div class="p-1">
                                <label>Куни
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="chkun" id="chkun"
                                    class="form-control form-control-sm text-center">
                                <span id="kun_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1" id="tshow">
                                <label>Таъминотчини танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="pastav_id" name="pastav_id"
                                    class="multi-select form-control text-center">
                                    <option value=""> Таъминотчи...</option>
                                    @foreach ($pastavshik as $pastavshi)
                                        <option value="{{ $pastavshi->id }}">{{ $pastavshi->pastav_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="pastav_id_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Валютани танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="pul_id" name="pul_id"
                                    class="multi-select form-control text-center">
                                    @foreach ($valyuta as $valyut)
                                        <option value="{{ $valyut->id }}">{{ $valyut->valyuta__nomi }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="pul_id_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Суммани киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="naqd" id="naqd" class="form-control" placeholder="Нақд..." maxlength="13">
                                <span id="naqd_error" class="text-danger error-text"></span>
                                <input type="text" name="plastik" id="plastik" class="form-control" placeholder="Пластик..." maxlength="13">
                                <span id="plastik_error" class="text-danger error-text"></span>
                                <input type="text" name="hr" id="hr" class="form-control" placeholder="Хисоб-рақам..." maxlength="13">
                                <span id="hr_error" class="text-danger error-text"></span>
                                <input type="text" name="click" id="click" class="form-control" placeholder="Сlick..." maxlength="13">
                                <span id="click_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Изохини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea name="chizox" class="form-control" class="form-control" placeholder="Изох" style="height: 100px"></textarea>
                                <span id="chizox_error" class="text-danger error-text"></span>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="saqlash"><i
                                        class="flaticon-381-save"></i> Сақлаш</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash() {
                $.ajax({
                    url: "{{ route('officekassachiqtamin.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
                    }
                });
            }


            $(document).ready(function() {
                $("#qidirish").keyup(function() {
                var value = $(this).val().toLowerCase();
                $("#tab1 tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                })
            })
            
                tabyuklash();
                $("#chkun").val(new Date().toISOString().substring(0, 10));

                $('#chiqim_add_taminot').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('officekassachiqtamin.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash();
                        },
                        error: function(response) {
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                                $('#chiqim_taminot').modal('show');
                            }
                        }
                    });
                });


                $("#pul_id").select2({
                    dropdownParent: $('#chiqim_taminot')
                });

                $("#pastav_id").select2({
                    dropdownParent: $('#chiqim_taminot')
                });

                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })

            $(document).on('click', '#chqimtaminotudalit', function() {
                var id = $(this).data('id');
                var kun = $(this).data('kun');
                var uzid = confirm(id + ' ИД ракамли ' + kun +
                    ' кунги тўлов ўчирилмокда.\n ТАСДИҚЛАНГ !!!')
                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('officekassachiqtamin.index') }}/" + id,
                        method: "DELETE",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash();
                        }
                    })
                }
            });

            $(document).ready(function() {
                $("#pul_id").change(function() {
                    var id = $('#pul_id').val();
                    if(id==2){
                        $('#plastik').val(0);
                        $('#hr').val(0);
                        $('#click').val(0);
                        $("#plastik").attr("readonly", true);
                        $("#hr").attr("readonly", true);
                        $("#click").attr("readonly", true);
                    }else{
                        $('#plastik').val("");
                        $('#hr').val("");
                        $('#click').val("");
                        $("#plastik").attr("readonly", false);
                        $("#hr").attr("readonly", false);
                        $("#click").attr("readonly", false);
                    }
                })
            })

            function digits_float(target) {
                let val = $(target).val().replace(/[^0-9\.]/g, '');
                if (val.indexOf(".") !== -1) {
                    val = val.substring(0, val.indexOf(".") + 3);
                }
                val = val.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                $(target).val(val);
            }

            $(function($) {
                const inputSelectors = ['#naqd', '#plastik', '#click', '#hr'];
                $('body').on('input', inputSelectors.join(', '), function(e) {
                    digits_float(this);
                });
                inputSelectors.forEach(function(selector) {
                    digits_float(selector);
                });
            });
        </script>
    @endsection
