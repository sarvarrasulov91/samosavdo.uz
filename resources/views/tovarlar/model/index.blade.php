@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Моделларни рўйхатга олиш бўлими
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
                                    <h5 class="bc-title text-primary">Моделлар рўйхати</h5>
                                </li>
                            </ol>

                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"
                                            data-bs-target="#model_add">+ Модел</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="{{ route('tur.index') }}" class="btn btn-primary btn-sm ms-2">+ Тур</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="{{ route('brend.index') }}" class="btn btn-primary btn-sm ms-2">+ Бренд</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="{{ route('kirimtovar.index') }}"
                                            class="btn btn-danger btn-sm ms-2 text-right">
                                            <-Қайтиш </a>
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
                                            <th>Тур</th>
                                            <th>Бренд</th>
                                            <th>Модел</th>
                                            <th>Таҳрирлаш</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($model as $model1)
                                            <tr>
                                                <td>{{ $model1->id }}</td>
                                                <td>{{ date("d.m.Y", strtotime($model1->created_at)) }}</td>
                                                <td>{{ $model1->tur->tur_name }}</td>
                                                <td>{{ $model1->brend->brend_name }}</td>
                                                <td>{{ $model1->model_name }}</td>
                                                <td>
                                                    <buttom id="madeledit" class="btn btn-outline-primary btn-sm me-2"
                                                        data-id="{{ $model1->id }}" data-tur="{{ $model1->tur_id }}"
                                                        data-brend="{{ $model1->brend_id }}"
                                                        data-model="{{ $model1->model_name }}" data-bs-toggle="modal"
                                                        data-bs-target="#modeledit"><i class="flaticon-381-notepad"></i></buttom>
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


        <!-- Modal model_add -->
        <div id="model_add" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="true"
        tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Янги модел қўшиш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="add_model" method="POST">
                            @csrf
                            <div class="p-2">
                                <label>Турни танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="row align-items-center">
                                    <div class="col-10">
                                        <select id="tur" name="tur" class="multi-select form-control">
                                            <option value="">Тур...</option>
                                            @foreach ($tur as $tur1)
                                                <option value="{{ $tur1->id }}">{{ $tur1->tur_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <a href="{{ route('tur.index') }}" class="btn btn-primary btn-sm me-2 turedit">+</a>
                                    </div>
                                </div>
                                <span id="tur_error" class="text-danger error-text"></span>

                            </div>
                            <div class="p-2">
                                <label>Бренд танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="row align-items-center">
                                    <div class="col-10">
                                        <select id="brend" name="brend" class="multi-select">
                                            <option value="">Бренд...</option>
                                            @foreach ($brend as $drend1)
                                                <option value="{{ $drend1->id }}">{{ $drend1->brend_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2 ">
                                        <a href="{{ route('brend.index') }}"
                                            class="btn btn-primary btn-sm me-2 turedit">+</a>
                                    </div>
                                </div>
                                <span id="brend_error" class="text-danger error-text"></span>

                            </div>
                            <div class="p-2">
                                <label>Товар модел киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="model" class="form-control" placeholder="Товар модели...">
                                <span id="model_error" class="text-danger error-text"></span>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" id="adduser" class="btn btn-primary btn-submit">Сақлаш</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>






        <!-- Modal -->
        <div id="modeledit" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="true"
        tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Товар моделини тахрирлаш</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="modelupdate" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" id="editid" name="editid" class="form-control" readonly hidden>
                            <div class="p-2">
                                <label>Турни танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="edittur" name="edittur" class="form-control">
                                    @foreach ($tur as $tur1)
                                        <option value="{{ $tur1->id }}">{{ $tur1->tur_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="edittur_error" class="text-danger error-text"></span>
                            </div>


                            <div class="p-2">
                                <label>Бренд танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="editbrend" name="editbrend" class="form-control">
                                    @foreach ($brend as $drend1)
                                        <option value="{{ $drend1->id }}">{{ $drend1->brend_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="editbrend_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-2">
                                <label>Товар модел киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="editmodel" name="editmodel" class="form-control"
                                    placeholder="Товар модели...">
                                <span id="editmodel_error" class="text-danger error-text"></span>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="saqlash">Сақлаш</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Қайтиш</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash(model) {
                $('#tab1').html(' ');
                var html = '';
                if(model.length>0){
                     model.map(item => {
                        let tr =`<tr>
                                    <td>${item.id}</td>
                                    <td>${new Date(item.created_at).toLocaleDateString()}</td>
                                    <td>${item.tur.tur_name}</td>
                                    <td>${item.brend.brend_name}</td>
                                    <td>${item.model_name}</td>
                                    <td> <button id="madeledit" class="btn btn-outline-primary btn-sm me-2"
                                        data-id="${item.id}" data-tur="${item.tur_id}" data-brend="${item.brend_id}"
                                        data-model="${item.model_name}"data-bs-toggle="modal" data-bs-target="#modeledit">
                                        <i class="flaticon-381-notepad"></i></button></td>\</tr>
                                </tr>`
                        return  $('#tab1').append(tr);
                    });
                }
            }

            $(document).ready(function() {
                $('#add_model').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('model.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash(response.model);
                        },
                        error: function(response) {
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                                $('#pasravshik_add').modal('show');
                            }
                        }
                    });
                });


                $('#modelupdate').on('submit', function(e) {
                    e.preventDefault();
                    var id = $('#editid').val();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('model.index') }}/" + id,
                        type: 'PUT',
                        data: formData,
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash(response.model);
                        },
                        error: function(response) {
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('#' + key + '_error').text(value[0]);
                                });
                                $('#modeledit').modal('show');
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


                $("#brend").select2({
                    dropdownParent: $("#brend").parent(),
                    placeholder: "Бренд...",
                    allowClear: true
                });

                $("#tur").select2({
                    dropdownParent: $("#tur").parent(),
                    placeholder: "Тур...",
                    allowClear: true
                });

                $("#editbrend").select2({
                    dropdownParent: $("#editbrend").parent()
                });

                $("#edittur").select2({
                    dropdownParent: $("#edittur").parent()
                });
            })


            $(document).on('click', '#madeledit', function() {
                var id = $(this).data('id');
                var tur = $(this).data('tur');
                var brend = $(this).data('brend');
                var model = $(this).data('model');
                var url = $(this).data('url');
                $('#editid').val(id);
                $('#edittur').val(tur).trigger('change.select2');
                $('#editbrend').val(brend).trigger('change.select2');
                $('#editmodel').val(model);
            });
        </script>
    @endsection
