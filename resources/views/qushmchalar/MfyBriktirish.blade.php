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
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Ундурув ходимларига МФЙ
                        бириктириш бўлими
                    </h5>
                </li>
            </ol>
        </div>

        <div class="container-fluid ">
            <div class="row">
                <div class="col-6 md-6">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <li id="select_div" class="nav-item" role="presentation">
                                <select class="form-control" name="tuman" id="tuman" style="width: 100%" required>
                                    <option value="0">Туман ва шахарни...</option>
                                    @foreach ($tuman as $tumanlar)
                                        <option value="{{ $tumanlar->id }}">
                                            {{ $tumanlar->viloyat->name_uz . ' - ' . $tumanlar->name_uz }}
                                        </option>
                                    @endforeach
                                </select>
                                <style>
                                    #select_div {
                                        width: 150px !important;
                                    }
                                </style>
                            </li>
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">МФЙ рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button id="modelchadd" class="btn btn-primary btn-sm ms-2">+ Янги
                                            қўшиш</button>
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
                            <li id="select_div" class="nav-item" role="presentation">
                                <select class="form-control" name="xodimlar" id="xodimlar" style="width: 100%" required>
                                    <option value="">Ходимлар...</option>
                                    @foreach ($xodimlar as $xodimlar)
                                        <option value="{{ $xodimlar->id }}">
                                            {{ $xodimlar->id . ' - ' . $xodimlar->fio }}
                                        </option>
                                    @endforeach
                                </select>
                                <style>
                                    #select_div {
                                        width: 150px !important;
                                    }
                                </style>
                            </li>
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">МФЙ рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <<button id="modelchdel" class="btn btn-primary btn-sm ms-2">- Олиб
                                            ташлаш</button>
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
                let id = $('#xodimlar').val();
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                $.ajax({
                    url: "{{ route('MfyBriktirish.index') }}/" + id + '/edit',
                    method: "GET",
                    data: {
                        id: id,
                        _token: csrf
                    },
                    success: function(data) {
                        $('#tabprosch').html(data);

                    }
                })
            }


            function tabyuklash() {
                let id = $('#tuman').val();
                $.ajax({
                    url: "{{ route('MfyBriktirish.index') }}/" + id,
                    method: "get",
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        $('#tabprosj').html(data)
                    }
                })
            }

            $(document).ready(function() {
                $('#xodimlar').select2();

                $('#xodimlar').change(function() {
                    tabyuklashjami();
                })

            });


            $(document).ready(function() {
                $('#tuman').select2();

                $('#tuman').change(function() {
                    tabyuklash();
                })

            });


            $(document).ready(function() {
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
                var mfy_id = $("#find-table input:checkbox:checked").map(function() {
                    return $(this).val();
                }).toArray();
                let xodim_id = $('#xodimlar').val();
                if (mfy_id == "" || xodim_id == "") {
                    alert("МФЙ қўшиш учун МФЙ ва Ходим танланган булиши шарт.!!!");
                } else {
                    var uzid = confirm('Сиз танлаган МФЙлар ходимга бириктириляпти. ТАСДИҚЛАНГ !!!')
                    if (uzid == true && xodim_id == true) {
                        $.ajax({
                            url: "{{ route('MfyBriktirish.store') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                mfy_id: mfy_id,
                                xodim_id: xodim_id,
                                status: 'qushish',
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                tabyuklashjami()
                                tabyuklash();
                            }
                        });
                    }
                }
            });


            $(document).on('click', '#modelchdel', function() {
                var mfy_id = $("#find-table-del input:checkbox:checked").map(function() {
                    return $(this).val();
                }).toArray();
                if (mfy_id == "") {
                    alert("МФЙларни ходимдан ўчириш учун МФЙни танланг.!!!");
                } else {
                    var uzid = confirm('Сиз танлаган МФЙ лар ўчирилмокда. ТАСДИҚЛАНГ !!!')
                    if (uzid == true && mfy_id > "") {
                        $.ajax({
                            url: "{{ route('MfyBriktirish.store') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                mfy_id: mfy_id,
                                status: 'uchirish',
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                tabyuklashjami()
                                tabyuklash();
                            }
                        });
                    }
                }
            });
        </script>
        <script src="/vendor/global/global.min.js"></script>
    @endsection
