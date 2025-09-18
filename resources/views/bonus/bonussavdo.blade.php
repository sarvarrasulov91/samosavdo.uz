@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Бонуслар билан ишлаш бўлими
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
                                        Шартномалар рўйхати
                                    </h5>
                                </li>
                            </ol>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabpros">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="bonus_show" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="true"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 75%; font-size: 14px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Рўйхатга олиш</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="people-list dz-scroll" id="modalshow">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>



        <div id="add_tovar" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="true"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Бонус товарларини киритиш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="p-1">
                            <label>Штрих кодни киритинг
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="shid" name="shid" class="form-control text-center" readonly
                                hidden />

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
            function tabyuklash() {
                $.ajax({
                    url: "{{ route('bonus.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
                    }
                });
            }


            $(document).ready(function() {
                tabyuklash();
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })

            $(document).on('click', '#modalbonusshow', function() {
                $('#bonus_show').modal('show');
                var id = $(this).data('id');
                var fio = $(this).data('fio');
                $('.modal-title').html(id + ' -> ' + fio);
                $.ajax({
                    url: "{{ route('bonus.index') }}/" + id,
                    method: "GET",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $("#modalshow").html(data);
                    }
                })
            });



            $(document).on('click', '#tovar_qushish', function() {
                $('#add_tovar').modal('show');
                var id = $(this).data('shid');
                $('#shid').val(id);
            });

            $('#krimt').on('keypress', function(e) {
                if (e.which === 13) {
                    var krimt = $('#krimt').val();
                    var id = $('#shid').val();
                    var status = 'tqushish';
                    if (krimt.length != 17) {
                        toastr.success("Хатолик!!! Маълумотларни тўлиқ киритмадингиз.");
                    } else {
                        $.ajax({
                            url: "{{ route('bonus.store') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                krimt: krimt,
                                id: id,
                                status: status
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                var id = $('#shid').val();
                                $.ajax({
                                    url: "{{ route('bonus.index') }}/" + id,
                                    method: "GET",
                                    data: {
                                        id: id,
                                    },
                                    success: function(data) {
                                        $("#modalshow").html(data);
                                    }
                                })
                                $('#krimt').val("");
                                tabyuklash();
                            }
                        });
                    }
                }
            })


            $(document).on('click', '#tovar_uchirish', function() {
                var id = $(this).data('id');
                var shtrix_kod = $(this).data('shtrix_kod');
                var status = 'tuchirish';
                var uzid = confirm(id + ' - шартномадан ' + shtrix_kod + ' товар ўчирилмокда. ТАСДИҚЛАНГ !!!')
                if (uzid == true) {
                    $.ajax({
                        url: "{{ route('bonus.index') }}/" + id,
                        method: "PUT",
                        data: {
                            _token: "{{ csrf_token() }}",
                            krimt: shtrix_kod,
                            id: id,
                            status: status
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            $.ajax({
                                url: "{{ route('bonus.index') }}/" + id,
                                method: "GET",
                                data: {
                                    id: id,
                                },
                                success: function(data) {
                                    $("#modalshow").html(data);
                                }
                            })
                            tabyuklash();
                        }
                    });
                }
            });


            $(document).on('click', '#addtulov', function() {
                var status = 'bonustulov';
                var id = $('#id').val();
                var naqd = $('#naqd').val();
                var plastik = $('#plastik').val();
                var hr = $('#hr').val();
                var click = $('#click').val();
                var chegirma = $('#chegirma').val();
                if (id === "" || naqd === "" || plastik === "" || hr === "" || click === "" || chegirma === "") {
                    toastr.success("Тўловлар тулиқ киритилмади.");
                } else {
                    $.ajax({
                        url: "{{ route('bonus.store') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            naqd: naqd,
                            plastik: plastik,
                            hr: hr,
                            click: click,
                            chegirma: chegirma,
                            status: status
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            $.ajax({
                                url: "{{ route('bonus.index') }}/" + id,
                                method: "GET",
                                data: {
                                    id: id,
                                },
                                success: function(data) {
                                    $("#modalshow").html(data);
                                }
                            })
                            tabyuklash();
                        }
                    });
                }
            })

            $(document).on('click', '#tulov_uchrish', function() {
                var id = $(this).data('id');
                var tulovid = $(this).data('tulovid');
                var uzid = confirm(id +
                    ' - шартномани бонус фарқи учун тўланган тулови бронга олинмақда. ТАСДИҚЛАНГ !!!')
                if (uzid == true) {
                    $.ajax({
                        url: "{{ route('bonus.index') }}/" + id,
                        method: "PUT",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            tulovid: tulovid
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            $.ajax({
                                url: "{{ route('bonus.index') }}/" + id,
                                method: "GET",
                                data: {
                                    id: id,
                                },
                                success: function(data) {
                                    $("#modalshow").html(data);
                                }
                            })
                            tabyuklash();
                        }
                    });
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
                const inputSelectors = ['#naqd', '#plastik', '#click', '#hr', '#chegirma' ];
                $('body').on('input', inputSelectors.join(', '), function(e) {
                    digits_float(this);
                });
                inputSelectors.forEach(function(selector) {
                    digits_float(selector);
                });
            });

        </script>
    @endsection
