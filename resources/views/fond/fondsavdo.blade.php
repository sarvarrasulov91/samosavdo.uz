@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Фонд орқали савдоларни рўйхатга
                        олиш бўлими
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
                                    <h5 class="bc-title text-primary">
                                        Савдоларни рўйхати
                                    </h5>
                                </li>
                            </ol>
                            <li class="nav-item" role="presentation">
                                <a href="" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"
                                    data-bs-target="#fond_add">+ Қўшиш</a>
                            </li>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabpros">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="fond_add" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="true"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 85%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Янги фонд савдоларни рўйхатга олиш</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 people-list dz-scroll">
                                <form method="POST" id="add_tovar">
                                    @csrf
                                    <div class="p-2">
                                        <label>Куни
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="yangikun" id="yangikun"
                                            class="form-control form-control-sm text-center">
                                        <span id="yangikun_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label>Фондни танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="fond" id="fond" class="multi-select form-control">
                                            <option value="">Фонд...</option>
                                            @foreach ($fond as $fond1)
                                                <option value="{{ $fond1->id }}">{{ $fond1->pastav_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span id="fond_error" class="text-danger error-text"></span>
                                    </div>

                                    <div class="p-2">
                                        <label>Мижозни танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="mijoz" id="mijoz" class="multi-select form-control">
                                            <option value="">Мижоз номи...</option>
                                            @foreach ($mijozlar as $mijozla)
                                                <option value="{{ $mijozla->id }}">
                                                    {{ $mijozla->id . ' - ' . $mijozla->passport_sn . ' - ' . $mijozla->pinfl . ' - ' . $mijozla->last_name . ' ' . $mijozla->first_name . ' ' . $mijozla->middle_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span id="mijoz_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label>Савдо рақамини танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="savdounix_id" name="savdounix_id"
                                            class="multi-select form-control text-center">
                                            <option value="">Савдо рақами...</option>
                                            @foreach ($savdounix_id as $savdounix_i)
                                                <option value="{{ $savdounix_i->unix_id }}">{{ $savdounix_i->unix_id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span id="savdounix_id_error" class="text-danger error-text"></span>

                                    <div class="p-2">
                                        <label>Шартнома муддатини танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="muddat" name="muddat" class="multi-select form-control text-center"
                                            placeholder="Муддати...">
                                            <option value="">Муддати...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                    <span id="muddat_error" class="text-danger error-text"></span>
                                    <div class="p-2">
                                        <label>Олдиндан тўлов
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="naqd" id="naqd"
                                            class="form-control form-control-sm text-center" placeholder="Накд..." maxlength="11">
                                        <span id="naqd_error" class="text-danger error-text"></span>
                                        <input type="text" name="plastik" id="plastik"
                                            class="form-control form-control-sm text-center" placeholder="Пластик..." maxlength="11">
                                        <span id="plastik_error" class="text-danger error-text"></span>
                                        <input type="text" name="click" id="click"
                                            class="form-control form-control-sm text-center" placeholder="Click..." maxlength="11">
                                        <span id="click_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label>Чегирма суммаси киритинг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="chegirma" id="chegirma"
                                            class="form-control form-control-sm text-center" placeholder="Чегирма...">
                                        <span id="chegirma_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary"><i class="flaticon-381-save"></i>
                                            Сақлаш</button>
                                    </div>
                                </form>

                            </div>
                            <div class="col-8 people-list dz-scroll">
                                <div id="tabprossavdo">
                                </div>
                                <div id="tabprosmijoz">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal"><i
                                class="flaticon-381-exit"></i> Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="pechat" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="true"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 60%; font-size: 15px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title-pechat" class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="kvitpechat">
                    </div>
                    <div class="modal-footer">
                        <button onclick="printcertificate()" class="btn btn-primary"><i class="fa fa-print"></i>Чоп
                            этиш</button>
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>



        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash() {
                $.ajax({
                    url: "{{ route('fondsavdo.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
                    }
                });
            }

            $(document).ready(function() {
                tabyuklash();

                $("#yangikun").val(new Date().toISOString().substring(0, 10));

                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $("#savdounix_id").change(function() {
                    var savdounix_id = $('#savdounix_id').val();
                    $.ajax({
                        url: "{{ route('naqdsavdo.index') }}/" + savdounix_id + "/edit",
                        method: "GET",
                        data: {
                            savdounix_id: savdounix_id,
                        },
                        success: function(data) {
                            $('#tabprossavdo').html(data);
                        }
                    })
                });

                $('#add_tovar').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    var id = 1;
                    $.ajax({
                        url: "{{ route('fondsavdo.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            $.ajax({
                                url: "{{ route('fondsavdo.index') }}/" + id,
                                method: "GET",
                                data: {
                                    id: id,
                                },
                                success: function(data) {
                                    $('#savdounix_id').html(data);
                                }
                            })
                            toastr.success(response.message);
                            tabyuklash();
                        },
                        error: function(response) {
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                            }
                        }
                    });
                });

                $("#fond").select2({
                    dropdownParent: $("#fond_add")
                });
                $("#mijoz").select2({
                    dropdownParent: $("#fond_add")
                });
                $("#savdounix_id").select2({
                    dropdownParent: $("#fond_add")
                });
                $("#muddat").select2({
                    dropdownParent: $("#fond_add")
                });

                $("#mijoz").change(function() {
                    var mijoz_id = $('#mijoz').val();
                    $.ajax({
                        url: "{{ route('newmijoz.index') }}/" + mijoz_id + "/edit",
                        method: "GET",
                        data: {
                            mijoz_id: mijoz_id,
                        },
                        success: function(data) {
                            $('#tabprosmijoz').html(data);
                        }
                    })
                });
            })

            $(document).on('click', '#fondsavdoudalit', function() {
                var id = $(this).data('id');
                var kun = $(this).data('kun');
                var savdoid = $(this).data('savdoid');
                var uzid = confirm(id + ' ИД ракамли ' + kun +
                    ' кунги Фонд савдо ўчирилмокда.\n ТАСДИҚЛАНГ !!!')
                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('fondsavdo.index') }}/" + id,
                        method: "PUT",
                        data: {
                            id: id,
                            savdoid: savdoid
                        },
                        success: function(response) {
                            $.ajax({
                                url: "{{ route('fondsavdo.index') }}/" + id,
                                method: "GET",
                                data: {
                                    id: id,
                                },
                                success: function(data) {
                                    $('#savdounix_id').html(data);
                                }
                            })
                            toastr.success(response.message);
                            tabyuklash();
                        }
                    })
                }
            });


            $(document).on('click', '#kivitpechat', function() {
                var id = $(this).data('id');
                var fio = $(this).data('fio');
                $('#modal-title-pechat').html(id + ' - ' + fio);
                $.ajax({
                    url: "{{ route('fondsavdo.index') }}/" + id + '/edit',
                    method: "GET",
                    data: {
                        id: id,
                        fio: fio
                    },
                    success: function(data) {
                        $("#kvitpechat").html(data);
                    }
                })
            });

            function printcertificate() {
                var mode = 'iframe';
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $("#kvitpechat").printArea(options);
            }


            function digits_float(target) {
                let val = $(target).val().replace(/[^0-9\.]/g, '');

                if (val.indexOf(".") !== -1) {
                    val = val.substring(0, val.indexOf(".") + 3);
                }

                val = val.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                $(target).val(val);
            }

            $(function($) {
                const inputSelectors = ['#naqd', '#plastik', '#click', '#chegirma'];

                $('body').on('input', inputSelectors.join(', '), function(e) {
                    digits_float(this);
                });

                inputSelectors.forEach(function(selector) {
                    digits_float(selector);
                });
            });


        </script>
    @endsection
