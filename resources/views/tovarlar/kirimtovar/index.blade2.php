@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Товарларни рўйхатга олиш бўлими
                    </h5>
                </li>
            </ol>
        </div>

        <div class="container-fluid ">
            <div class="row">
                <div class="col-3">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h4 class="bc-title text-primary">Товар киритиш ойнаси </h4>
                                </li>
                            </ol>
                        </div>
                        <div class="card-body">
                            <div class="row people-list dz-scroll">
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
                                        <label>Филиални танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="row align-items-center">
                                            <div class="col-10">
                                                <select id="filial" name="filial" class="multi-select form-control">
                                                    <option value="">Филиал...</option>
                                                    @foreach ($filial as $filia)
                                                        <option value="{{ $filia->id }}">{{ $filia->fil_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-2">
                                                <a href="#" class="btn btn-primary btn-sm me-2 filialadd">+</a>
                                            </div>
                                        </div>
                                        <span id="filial_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label>Таъминотчини танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="row align-items-center">
                                            <div class="col-10">
                                                <select id="pastavshik" name="pastavshik" class="multi-select form-control">
                                                    <option value="">Таъминотчи...</option>
                                                    @foreach ($pastavshik as $pastavshi)
                                                        <option value="{{ $pastavshi->id }}">{{ $pastavshi->pastav_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-2">
                                                <a href="{{ route('pastavshik.index') }}"
                                                    class="btn btn-primary btn-sm me-2 pastavshikadd">+</a>
                                            </div>
                                        </div>
                                        <span id="pastavshik_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label>Товарни номини танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="row align-items-center">
                                            <div class="col-10">
                                                <select id="tovarmodeli" name="tovarmodeli"
                                                    class="multi-select form-control">
                                                    <option value="">Товар номи...</option>
                                                    @foreach ($model as $mode)
                                                        <option value="{{ $mode->id }}">
                                                            {{ $mode->id . ' - ' . $mode->tur->tur_name . ' ' . $mode->brend->brend_name . ' ' . $mode->model_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-2">
                                                <a href="{{ route('model.index') }}"
                                                    class="btn btn-primary btn-sm me-2 pastavshikadd">+</a>
                                            </div>
                                        </div>
                                        <span id="tovarmodeli_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label>Валютани танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="valyuta" name="valyuta" class="multi-select form-control text-center">
                                            @foreach ($valyuta as $valyut)
                                                <option value="{{ $valyut->id }}">{{ $valyut->valyuta__nomi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span id="valyuta_error" class="text-danger error-text"></span>
                                    <div class="p-2">
                                        <label>Товар сонини киритинг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="tsoni" id="tsoni"
                                            class="form-control form-control-sm text-center" placeholder="Товор сони..." maxlength="3">
                                        <span id="tsoni_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label>Товар суммаси киритинг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="txet" name="tsumma" id="tsumma"
                                            class="form-control form-control-sm text-center" placeholder="Товор суммаси..." maxlength="12">
                                        <span id="tsumma_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" id="adduser"
                                            class="btn btn-primary btn-submit">Сақлаш</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">
                                        Умумий товарлар рўйхати
                                    </h5>
                                </li>
                            </ol>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <div>
                                    <table class="table table-bordered text-center align-middle ">
                                        <thead>
                                            <tr class="text-bold text-primary">
                                                <th>№</th>
                                                <th>ID</th>
                                                <th>Куни</th>
                                                <th>Товар номи</th>
                                                <th>Пул бр.</th>
                                                <th>Нархи</th>
                                                <th>Таъминотчи</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabprosmodel">
                                        </tbody>
                                    </table>
                                </div>
                                <div id="tabprosfil">
                                    <table class="table table-bordered text-center align-middle ">
                                        <thead>
                                            <tr class="text-bold text-primary">
                                                <th>ID</th>
                                                <th>Куни</th>
                                                <th>Тур</th>
                                                <th>Бренд</th>
                                                <th>Модел</th>
                                                <th>Штрих коди</th>
                                                <th>Пул бр.</th>
                                                <th>Нархи</th>
                                                <th style="width: 100px;">Таъминотчи</th>
                                                <th>Ўчириш</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tab1">

                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash(model) {
                var filial = $('#filial').val();
                $('#tab1').html(' ');
                var html = '';
                if(model.length>0){
                     model.map(item => {
                        let tr =`<tr>
                                    <td>${item.id}</td>
                                    <td>${new Date(item.kun).toLocaleDateString()}</td>
                                    <td>${item.tur.tur_name}</td>
                                    <td>${item.brend.brend_name}</td>
                                    <td>${item.tmodel.model_name}</td>
                                    <td>${item.shtrix_kod}</td>
                                    <td>${item.valyuta.valyuta__nomi}</td>
                                    <td>${item.narhi}</td>
                                    <td>${item.pastavshik.pastav_name}</td>
                                    <td>
                                        <a onclick="tovarudalit('${item.id}','${filial}')" class="btn btn-outline-danger btn-xxs"><i class="flaticon-381-trash-1"></i></a>
                                    </td>

                                </tr>`
                        return  $('#tab1').append(tr);
                    });
                }
            }

            $(document).ready(function() {
                $("#yangikun").val(new Date().toISOString().substring(0, 10));

                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $("#filial").change(function() {
                    var filial = $('#filial').val();
                    var csrf = document.querySelector('meta[name="csrf-token"]').content;
                    $.ajax({
                        url: "{{ route('filbaza') }}",
                        method: "POST",
                        data: {
                            filial: filial,
                            _token: csrf
                        },
                        success: function(response) {
                            tabyuklash(response.datamodel);
                        }
                    })
                });

                $("#tovarmodeli").change(function() {
                    var filial = $('#filial').val();
                    var tovarmodeli = $('#tovarmodeli').val();
                    var csrf = document.querySelector('meta[name="csrf-token"]').content;
                    $.ajax({
                        url: "{{ route('sungimodel') }}",
                        method: "POST",
                        data: {
                            filial: filial,
                            tovarmodeli: tovarmodeli,
                            _token: csrf
                        },
                        success: function(response) {
                            $('#tabprosmodel').html(' ');
                            var html = '';
                            if(response.data.length>0){
                                response.data.map(item => {
                                    let tr =`<tr>
                                                <td>1</td>
                                                <td>${item.id}</td>
                                                <td>${new Date(item.kun).toLocaleDateString()}</td>
                                                <td>${item.tur.tur_name+' '+item.brend.brend_name+' '+item.tmodel.model_name}</td>
                                                <td>${item.valyuta.valyuta__nomi}</td>
                                                <td>${item.narhi}</td>
                                                <td>${item.pastavshik.pastav_name}</td>
                                            </tr>`
                                    return  $('#tabprosmodel').append(tr);
                                });
                            }
                        }
                    })
                });

                $('#add_tovar').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    var filial = $('#filial').val();
                    var csrf = document.querySelector('meta[name="csrf-token"]').content;
                    $.ajax({
                        url: "{{ route('kirimtovar.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash(response.datamodel);
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

            function tovarudalit(id,filial) {
                var uzid = confirm(id + ' ' + filial + ' ўчирилмокда. ТАСДИҚЛАНГ !!!')
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('kirimtovar.index') }}/" + id,
                        method: "PUT",
                        data: {
                            id: id,
                            filial: filial
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash(response.datamodel);
                        }
                    })
                }
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
                const inputSelectors = ['#tsoni', '#tsumma'];
                $('body').on('input', inputSelectors.join(', '), function(e) {
                    digits_float(this);
                });
                inputSelectors.forEach(function(selector) {
                    digits_float(selector);
                });
            });
        </script>
    @endsection
