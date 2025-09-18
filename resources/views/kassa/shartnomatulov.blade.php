@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Шартномалар учун тўланган
                        тўловларни руйхатга олиш бўлими
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
                                        Тўловлар рўйхати
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
    </div>


    <div class="modal fade" id="fond_add" class="modal fade bd-example-modal-lg" data-bs-backdrop="static"
        data-bs-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 85%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Рўйхатга олиш</h5>
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
                                    <label>Мижозни танланг
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="mijoz" id="mijoz" class="multi-select form-control">
                                        <option value="">Мижоз номи...</option>
                                        @foreach ($shartnoma as $shartnom)
                                            <option value="{{ $shartnom->id }}">
                                                {{ $shartnom->id . ' - ' . $shartnom->mijozlar->last_name . ' ' . $shartnom->mijozlar->first_name . ' ' . $shartnom->mijozlar->middle_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span id="mijoz_error" class="text-danger error-text"></span>
                                </div>
                                <div class="p-2">
                                    <label>Тўлов суммасини киритинг
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="naqd" id="naqd" class="form-control form-control-sm text-center"
                                        placeholder="Накд..." maxlength="11">
                                    <span id="naqd_error" class="text-danger error-text"></span>
                                </div>
                                <div class="p-2">
                                    <input type="text" name="plastik" id="plastik" class="form-control form-control-sm text-center"
                                        placeholder="Пластик..." maxlength="11">
                                    <span id="plastik_error" class="text-danger error-text"></span>
                                </div>
                                <div class="p-2">
                                    <input type="text" name="hr" id="hr" class="form-control form-control-sm text-center"
                                        placeholder="Хисоб-рақам..." maxlength="11">
                                    <span id="hr_error" class="text-danger error-text"></span>
                                </div>
                                <div class="p-2">
                                    <input type="text" name="click" id="click" class="form-control form-control-sm text-center"
                                        placeholder="click..." maxlength="11">
                                    <span id="click_error" class="text-danger error-text"></span>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" id="adduser" class="btn btn-primary btn-submit">Сақлаш</button>
                                </div>
                            </form>

                        </div>
                        <div class="col-8 people-list dz-scroll">
                            <div id="tabprossh">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Қайтиш</button>
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
                    <button onclick="printcertificate2()" class="btn btn-primary"><i class="fa fa-print"></i> Чоп
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
                url: "{{ route('shartnomatulov.create') }}",
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

            $("#mijoz").select2({
                dropdownParent: $("#fond_add")
            });

            $("#mijoz").change(function() {
                var shid = $('#mijoz').val();
                $.ajax({
                    url: "{{ route('shartnomalar.index') }}/" + shid,
                    method: "GET",
                    data: {
                        shid: shid,
                    },
                    success: function(data) {
                        $('#tabprossh').html(data);
                    }
                })
            });

            $('#add_tovar').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('shartnomatulov.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        toastr.success(response.message);
                        tabyuklash();
                        var shid = $('#mijoz').val();
                        $.ajax({
                            url: "{{ route('shartnomalar.index') }}/" + shid,
                            method: "GET",
                            data: {
                                shid: shid,
                            },
                            success: function(data) {
                                $('#tabprossh').html(data);
                            }
                        })
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

        })


        $(document).on('click', '#kivitpechat', function() {
            var id = $(this).data('id');
            var fio = $(this).data('fio');
            $('#modal-title-pechat').html(id + ' - ' + fio);
            $.ajax({
                url: "{{ route('shartnomatulov.index') }}/" + id,
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


        $(document).on('click', '#tovarudalit', function() {
            var id = $(this).data('id');
            var fio = $(this).data('fio');
            var uzid = confirm(id + ' ' + fio +
                'га тегишли шартнома учун тўланган тўлови ўчирилмокда. ТАСДИҚЛАНГ !!!')
            if (uzid == true) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('shartnomatulov.index') }}/" + id,
                    method: "PUT",
                    data: {
                        id: id,
                        fio: fio
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        tabyuklash();

                    }
                })
            }
        });

        function printcertificate2() {
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
            const inputSelectors = ['#naqd', '#plastik', '#hr', '#click'];

            $('body').on('input', inputSelectors.join(', '), function(e) {
                digits_float(this);
            });

            inputSelectors.forEach(function(selector) {
                digits_float(selector);
            });
        });


    </script>
@endsection
