@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold"> Савдо пули, Тасисчи ва омонатларни рўйхатга олиш бўлими
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
                                            data-bs-target="#kirim">+ Қўшиш</a>
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
        <div id="kirim" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Янги тўлов қўшиш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="kirim_add" method="POST">
                            @csrf
                            <div class="p-1">
                                <label>Куни
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="kun" id="kun"
                                    class="form-control form-control-sm text-center">
                                <span id="kun_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Кирим турини танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="kirim_id" name="kirim_id" class="multi-select form-control">
                                    <option value="">Кирим тур...</option>
                                    @foreach ($kirimtur as $kirimtur2)
                                        <option value="{{ $kirimtur2->id }}">{{ $kirimtur2->kirim_tur_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="kirim_id_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Валютани танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="val_id" name="val_id"
                                    class="multi-select form-control text-center">
                                    @foreach ($valyuta as $valyut)
                                        <option value="{{ $valyut->id }}">{{ $valyut->valyuta__nomi }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="val_id_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Суммани киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="naqd" id="naqd" class="form-control" placeholder="Нақд..." maxlength="14">
                                <span id="naqd_error" class="text-danger error-text"></span>
                                <input type="text" name="plastik" id="plastik" class="form-control" placeholder="Пластик..." maxlength="14">
                                <span id="plastik_error" class="text-danger error-text"></span>
                                <input type="text" name="hr" id="hr" class="form-control" placeholder="Хисоб-рақам..." maxlength="14">
                                <span id="hr_error" class="text-danger error-text"></span>
                                <input type="text" name="click" id="click" class="form-control" placeholder="Сlick..." maxlength="14">
                                <span id="click_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Изохини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea name="izoh" class="form-control" class="form-control" placeholder="Изох" style="height: 100px"></textarea>
                                <span id="izoh_error" class="text-danger error-text"></span>
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
                    url: "{{ route('OfficeKassaKirim.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
                    }
                });
            }


            $(document).ready(function() {
                tabyuklash();
                $("#kun").val(new Date().toISOString().substring(0, 10));

                $('#kirim_add').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('OfficeKassaKirim.store') }}",
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
                                $('#kirim').modal('show');
                            }
                        }
                    });
                });

                $("#val_id").select2({
                    dropdownParent: $('#kirim')
                });

                $("#kirim_id").select2({
                    dropdownParent: $('#kirim')
                });

                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })

            $(document).on('click', '#kirimudalit', function() {
                var id = $(this).data('id');
                var kun = $(this).data('kun');
                var kirim_tur_name = $(this).data('kirim_tur_name');
                var uzid = confirm(id + ' ИД ракамли ' + kirim_tur_name +
                    'дан тушган тўлов ўчирилмокда.\n ТАСДИҚЛАНГ !!!')
                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('OfficeKassaKirim.index') }}/" + id,
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
