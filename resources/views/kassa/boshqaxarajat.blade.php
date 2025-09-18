@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Бошқа харажатларни рўйхатга олиш
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
                                    <h5 class="bc-title text-primary">Харажатлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"
                                            data-bs-target="#chiqim_add">+ Қўшиш</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabpros">
                                <table class="table table-bordered table-responsive-sm text-center align-middle ">
                                    <thead>
                                        <tr class="text-bold text-primary">
                                            <th>ID</th>
                                            <th>Куни</th>
                                            <th>Харажатлар</th>
                                            <th>Пул бр.</th>
                                            <th>Нақд</th>
                                            <th>Пластик</th>
                                            <th>Х-р</th>
                                            <th>Сlick</th>
                                            <th>Жами</th>
                                            <th>Изох</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($chiqim as $chiqim)
                                            <tr>
                                                <td> {{ $chiqim->id }}</td>
                                                <td> {{ date('d.m.Y', strtotime($chiqim->kun)) }}</td>
                                                <td> {{ $chiqim->turharajat->har_name }}</td>
                                                <td> {{ $chiqim->valyuta->valyuta__nomi }}</td>
                                                <td> {{ number_format($chiqim->naqd, 0, ",", " ") }}</td>
                                                <td> {{ number_format($chiqim->pastik, 0, ",", " ") }}</td>
                                                <td> {{ number_format($chiqim->hr, 0, ",", " ") }}</td>
                                                <td> {{ number_format($chiqim->click, 0, ",", " ") }}</td>
                                                <td> {{ number_format($chiqim->naqd + $chiqim->pastik + $chiqim->hr + $chiqim->click,0, ',', ' ') }}
                                                </td>
                                                <td> {{ $chiqim->izoh }}</td>
                                                <td>
                                                    <button id="chqimudalit" class="btn btn-outline-danger btn-sm me-2"
                                                        data-id=" {{ $chiqim->id }}" data-kun=" {{ $chiqim->kun }}"><i
                                                        class="flaticon-381-trash-1"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal chiqim_add -->
        <div id="chiqim_add" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Харажатларни рўйхатга олиш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="pas_add" method="POST">
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
                                <label>Харажат турини танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="turharajat_id" name="turharajat_id" class="multi-select form-control">
                                    <option value="">Харажат тури...</option>
                                    @foreach ($turharajat as $turharaja)
                                        <option value="{{ $turharaja->id }}">{{ $turharaja->har_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="turharajat_id_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Валютани танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="valyuta_id" name="valyuta_id" class="multi-select form-control text-center">
                                    @foreach ($valyuta as $valyut)
                                        <option value="{{ $valyut->id }}">{{ $valyut->valyuta__nomi }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="valyuta_id_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Суммани киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="naqd" id="naqd" class="form-control" placeholder="Нақд..." maxlength="11">
                                <span id="naqd_error" class="text-danger error-text"></span>
                                <input type="text" name="plastik" id="plastik" class="form-control" placeholder="Пластик..." maxlength="11">
                                <span id="plastik_error" class="text-danger error-text"></span>
                                <input type="text" name="hr" id="hr" class="form-control" placeholder="Хисоб-рақам..." maxlength="11">
                                <span id="hr_error" class="text-danger error-text"></span>
                                <input type="text" name="click" id="click" class="form-control" placeholder="Сlick..." maxlength="11">
                                <span id="click_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Изохини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea name="izox" class="form-control" class="form-control" placeholder="Изох" style="height: 100px"></textarea>
                                <span id="izox_error" class="text-danger error-text"></span>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="saqlash"><i
                                        class="flaticon-381-save"></i> Сақлаш</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i
                                        class="flaticon-381-exit"></i> Қайтиш</button>
                            </div>
                        </form>
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
                                <td>${item.turharajat.har_name}</td>
                                <td>${item.valyuta.valyuta__nomi}</td>
                                <td>${item.naqd.toLocaleString('fr-FR')}</td>
                                <td>${item.pastik.toLocaleString('fr-FR')}</td>
                                <td>${item.hr.toLocaleString('fr-FR')}</td>
                                <td>${item.click.toLocaleString('fr-FR')}</td>
                                <td>${item.summasi.toLocaleString('fr-FR')}</td>
                                <td>${item.izoh}</td>
                                <td>
                                    <button id="chqimudalit" class="btn btn-outline-danger btn-sm me-2"
                                                        data-id=" ${item.id}" data-kun=" ${new Date(item.kun).toLocaleDateString()}"><i
                                                        class="flaticon-381-trash-1"></i></button>
                                </td>

                            </tr>`
                        return  $('#tab1').append(tr);
                    });
                }
            }

            $(document).ready(function() {
                $("#kun").val(new Date().toISOString().substring(0, 10));
                $('#pas_add').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('boshqaxarajat.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash(response.chiqim);
                        },
                        error: function(response) {
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                                $('#chiqim_add').modal('show');
                            }
                        }
                    });
                });

                $("#turharajat_id").select2({
                    dropdownParent: $('#chiqim_add')
                });

                $("#valyuta_id").select2({
                    dropdownParent: $('#chiqim_add')
                });

                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })

            $(document).on('click', '#chqimudalit', function() {
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
                        url: "{{ route('boshqaxarajat.index') }}/" + id,
                        method: "DELETE",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash(response.chiqim);
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
