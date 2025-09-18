@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .removeRow {
            background: -webkit-gradient(linear, left top, left bottom, from(#D8E6F3), to(#f9f5f5));
            background: -moz-linear-gradient(top, #f2f2f2, #f0f0f0);
        }

        input.tanlash_checkbox {
            width: 20px;
            height: 20px;
        }

        input.selectall {
            width: 22px;
            height: 22px;
        }

        input.tanlash_checkbox_del {
            width: 20px;
            height: 20px;
        }
    </style>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Товарларга чегирмалар белгилаш
                        бўлими
                    </h5>
                </li>
            </ol>
        </div>

        <div class="container-fluid ">
            <div class="row">
                <div class="col-6 md-6">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Мавжуд турлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button id="modelchadd" class="btn btn-primary btn-sm ms-2">+ Янги қўшиш</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabprosj">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 md-6">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Бонус мавжуд турлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button id="modelchdel" class="btn btn-primary btn-sm ms-2">- Олиб ташлаш</button>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabprosch">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script src="/vendor/jquery/jquery-3.7.0.min.js"></script>
        <script>
            function tabyuklashjami() {
                var id = '1';
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                if (id > 0) {
                    $.ajax({
                        url: "{{ route('BonusTur.index') }}/" + id,
                        method: "GET",
                        data: {
                            filial: id,
                            _token: csrf
                        },
                        success: function(data) {
                            $('#tabprosj').html(data);

                        }
                    })
                }
            }


            function tabyuklash() {
                $.ajax({
                    url: "{{ route('BonusTur.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabprosch').html(data);
                    }
                });
            }


            $(document).ready(function() {
                tabyuklashjami()
                tabyuklash();
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })

            $(document).ready(function() {
                /* Белги куйилса буяш ёки учириш */
                $('.tanlash_checkbox').click(function() {
                    if ($(this).is(':checked')) {
                        $(this).closest('tr').addClass('removeRow');
                    } else {
                        $(this).closest('tr').removeClass('removeRow');
                    }
                });

                /* Хаммасини буяш ёки учириш */
                $('body').on('click', '#selectall', function() {
                    $('.tanlash_checkbox').prop('checked', this.checked);

                });
                $('body').on('click', '.tanlash_checkbox', function() {
                    if ($('.tanlash_checkbox').length == $('.tanlash_checkbox:checked').length) {
                        $('#selectall').prop('checked', true);
                    } else {
                        $("#selectall").prop('checked', false);
                    }

                });
            })

            $(document).ready(function() {

                /* Хаммасини буяш ёки учириш */
                $('body').on('click', '#selectalldel', function() {
                    $('.tanlash_checkbox_del').prop('checked', this.checked);

                });
                $('body').on('click', '.tanlash_checkbox_del', function() {
                    if ($('.tanlash_checkbox_del').length == $('.tanlash_checkbox_del:checked').length) {
                        $('#selectalldel').prop('checked', true);
                    } else {
                        $("#selectalldel").prop('checked', false);
                    }

                });
            })


            $(document).on('click', '#modelchadd', function() {
                
                var tur_id = $("#find-table input:checkbox:checked").map(function() {
                    return $(this).val();
                }).toArray();
                
                if (tur_id.length === 0) {
                    toastr.warning('Tovarni tanlang!.');
                    return;
                }
                
                var bonus_miqdor = prompt("Bonus miqdorini kiriting.");
                
                if (bonus_miqdor) {
                    $.ajax({
                        url: "{{ route('BonusTur.store') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            tur_id: tur_id,
                            bonus_miqdor: bonus_miqdor,
                            status: 'qushish',
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklashjami()
                            tabyuklash();
                        },
                        
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON.message || 'Xatolik yuz berdi.');
                        }
                        
                    });
                }
            });

            $(document).on('click', '#modelchdel', function() {
                var tur_id = $("#find-table-del input:checkbox:checked").map(function() {
                    return $(this).val();
                }).toArray();
                
                if (tur_id.length === 0) {
                    toastr.warning('Tovarni tanlang.');
                    return;
                }
                
                var uzid = confirm('Siz tanlagan turlardan bonus o\'chirilmoqda. TASDIQLANG !!!')
                
                if (uzid == true) {
                    $.ajax({
                        url: "{{ route('BonusTur.store') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            tur_id: tur_id,
                            status: 'uchirish',
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklashjami()
                            tabyuklash();
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON.message || 'Xatolik yuz berdi.');
                        }
                    });
                }
            });
        </script>
        <script src="/vendor/global/global.min.js"></script>
    @endsection
