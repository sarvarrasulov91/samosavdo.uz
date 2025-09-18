@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Офисга юборилган пулларни
                        рўйхатга олиш бўлими
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
                                    <h5 class="bc-title text-primary">Офисга юборилган савдо пуллари</h5>
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
                                            <th>Филиал</th>
                                            <th>Номи</th>
                                            <th>Нақд</th>
                                            <th>Пластик</th>
                                            <th>Х-р</th>
                                            <th>Сlick</th>
                                            <th>Автотўлов</th>
                                            <th>Жами</th>
                                            <th>Изох</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($kirim as $kirim)
                                            <tr>
                                                <td> {{ $kirim->id }}</td>
                                                <td> {{ date('d.m.Y', strtotime($kirim->kun)) }}</td>
                                                <td> {{ $kirim->filial->fil_name }}</td>
                                                <td> {{ $kirim->kirimtur->kirim_tur_name }}</td>
                                                <td> {{ number_format($kirim->naqd, 0, ',', ' ') }}</td>
                                                <td> {{ number_format($kirim->pastik, 0, ',', ' ') }}</td>
                                                <td> {{ number_format($kirim->hr, 0, ',', ' ') }}</td>
                                                <td> {{ number_format($kirim->click, 0, ',', ' ') }}</td>
                                                <td> {{ number_format($kirim->avtot, 0, ',', ' ') }}</td>
                                                <td> {{ number_format($kirim->umumiy, 0, ',', ' ') }}</td>
                                                <td> {{ $kirim->izoh }}</td>
                                                <td>
                                                    <button id="destroysavdopuli" class="btn btn-outline-danger btn-sm me-2"
                                                        data-id=" {{  $kirim->id }}" data-kun=" {{ date('d.m.Y', strtotime($kirim->kun)) }}"><i
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
        <div class="modal fade" id="chiqim_add">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Янги тўлов қўшиш ойнаси</h5>
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
                                <input type="date" name="yangikun" id="yangikun"
                                    class="form-control form-control-sm text-center">
                                <span id="yangikun_error" class="text-danger error-text"></span>
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
                                <input type="text" name="avtot" id="avtot" class="form-control" placeholder="Авто тўлов..." maxlength="13">
                                <span id="avtot_error" class="text-danger error-text"></span>
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
                                <td>${item.filial.fil_name}</td>
                                <td>${item.kirimtur.kirim_tur_name}</td>
                                <td>${item.naqd.toLocaleString('fr-FR')}</td>
                                <td>${item.pastik.toLocaleString('fr-FR')}</td>
                                <td>${item.hr.toLocaleString('fr-FR')}</td>
                                <td>${item.click.toLocaleString('fr-FR')}</td>
                                <td>${item.avtot.toLocaleString('fr-FR')}</td>
                                <td>${item.umumiy.toLocaleString('fr-FR')}</td>
                                <td>${item.izoh}</td>
                                <td>
                                    <button id="destroysavdopuli" class="btn btn-outline-danger btn-sm me-2"
                                        data-id="${item.id}" data-kun="${new Date(item.kun).toLocaleDateString()}"><i
                                        class="flaticon-381-trash-1"></i></button>
                                </td>

                            </tr>`
                        return  $('#tab1').append(tr);
                    });
                }
            }

            $(document).ready(function() {
                $("#yangikun").val(new Date().toISOString().substring(0, 10));

                $('#pas_add').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('savdopuli.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash(response.kirim);
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


                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })


            $(document).on('click', '#destroysavdopuli', function() {
                var id = $(this).data('id');
                var kun = $(this).data('kun');
                var uzid = confirm(id + ' ИД ракамли ' + kun +
                    ' кунги савдо пули ўчирилмокда.\n ТАСДИҚЛАНГ !!!')
                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('savdopuli.index') }}/" + id,
                        method: "DELETE",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash(response.kirim);
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
                const inputSelectors = ['#naqd', '#plastik', '#click', '#hr', '#avtot' ];
                $('body').on('input', inputSelectors.join(', '), function(e) {
                    digits_float(this);
                });
                inputSelectors.forEach(function(selector) {
                    digits_float(selector);
                });
            });

        </script>
    @endsection
