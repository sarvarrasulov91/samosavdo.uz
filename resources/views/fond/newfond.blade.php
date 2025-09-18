@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Фондларнини рўйхатга олиш бўлими
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
                                    <h5 class="bc-title text-primary">Рўйхатга олинган фондлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"
                                            data-bs-target="#pasravshik_add" title="Янги фонд қўшиш">+ Қўшиш</a>
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


        <!-- Modal pasravshik_add -->
        <div id="pasravshik_add" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Янги Фондлар қўшиш</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="pas_add" method="POST">
                            @csrf
                            <div class="p-1">
                                <label>Фондларни киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="taminotchi" class="form-control" placeholder="Фондлар...">
                                <span id="taminotchi_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Манзилини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="manzili" class="form-control" placeholder="Манзили...">
                                <span id="manzili_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Телефон рақамини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="telefoni" class="form-control" placeholder="Телефон..."
                                    maxlength="9">
                                <span id="telefoni_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Хисоб-ракамини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="xis_raqami" class="form-control" placeholder="Хисоб-раками..."
                                    maxlength="20">
                                <span id="xis_raqami_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Иннни киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="inn" class="form-control" placeholder="ИНН..."
                                    maxlength="9">
                                <span id="inn_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>МФОни киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="mfo" class="form-control" placeholder="МФО..."
                                    value="{{ old('mfo') }}" maxlength="5">
                                <span id="mfo_error" class="text-danger error-text"></span>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" title="Таҳрирлаш"><i
                                        class="flaticon-381-save"></i> Сақлаш</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i
                                        class="flaticon-381-exit"></i> Қайтиш</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="edit" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Фондлар тахрирлаш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="pas_update" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" id="editid" name="editid" class="form-control" readonly hidden>
                            <div class="p-1">
                                <label>Фондларни киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edittaminotchi" name="edittaminotchi" class="form-control"
                                    placeholder="Фондлар...">
                                <span id="edittaminotchi_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Манзилини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="editmanzili" name="editmanzili" class="form-control"
                                    placeholder="Манзили...">
                                <span id="editmanzili_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Телефон рақамини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edittelefoni" name="edittelefoni" class="form-control"
                                    placeholder="Телефон..." maxlength="9">
                                <span id="edittelefoni_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Хисоб-ракамини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="editxis_raqami" name="editxis_raqami" class="form-control"
                                    placeholder="Хисоб-раками..." maxlength="20">
                                <span id="editxis_raqami_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Иннни киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="editinn" name="editinn" class="form-control"
                                    placeholder="ИНН..." maxlength="9">
                                <span id="editinn_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>МФОни киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="editmfo" name="editmfo" class="form-control"
                                    placeholder="МФО..." maxlength="5">
                                <span id="editmfo_error" class="text-danger error-text"></span>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="saqlash"><i
                                        class="flaticon-381-notepad"></i> Таҳрирлаш</button>
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
            function tabyuklash() {
                $.ajax({
                    url: "{{ route('newfond.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
                    }
                });
            }

            $(document).ready(function() {
                tabyuklash();
                $('#pas_add').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('newfond.store') }}",
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
                                $('#pasravshik_add').modal('show');
                            }
                        }
                    });
                });


                $('#pas_update').on('submit', function(e) {
                    e.preventDefault();
                    var id = $('#editid').val();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('newfond.index') }}/" + id,
                        type: 'PUT',
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
                                $('#edit').modal('show');
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


            $(document).on('click', '#fondedit', function() {
                var id = $(this).data('id');
                var edittaminotchi = $(this).data('pastav_name');
                var editmanzili = $(this).data('manzili');
                var edittelefoni = $(this).data('telefoni');
                var editxis_raqami = $(this).data('xis_raqami');
                var editinn = $(this).data('inn');
                var editmfo = $(this).data('mfo');
                $('#editid').val(id);
                $('#edittaminotchi').val(edittaminotchi);
                $('#editmanzili').val(editmanzili);
                $('#edittelefoni').val(edittelefoni);
                $('#editxis_raqami').val(editxis_raqami);
                $('#editinn').val(editinn);
                $('#editmfo').val(editmfo);
            });
        </script>
    @endsection
