@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Таъминотчиларга пул тўлаш бўлими
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
                                    <h5 class="bc-title text-primary">Таъминотчиларга тўланган тўловлар рўйхати</h5>
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
                            <div class="people-list dz-scroll" >
                                <table class="table table-bordered table-responsive-sm text-center align-middle ">
                                    <thead>
                                        <tr class="text-bold text-primary">
                                            <th>ID</th>
                                            <th>Куни</th>
                                            <th>Таъминотчи</th>
                                            <th>Пул бр.</th>
                                            <th>Сумма</th>
                                            <th>Изох</th>
                                            <th>Таҳрирлаш</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($chiqim as $chiqim)
                                            <tr>
                                                <td>{{ $chiqim->id }}</td>
                                                <td>{{ date('d.m.Y', strtotime($chiqim->kun)) }}</td>
                                                <td>{{ $chiqim->pastav_name }}</td>
                                                <td>{{ $chiqim->valyuta__nomi }}</td>
                                                <td>{{ number_format($chiqim->rsumma, 2, ',', ' ') }}</td>
                                                <td>{{ $chiqim->izox }}</td>
                                                <td>
                                                    <a href="" class="btn btn-primary btn-sm me-2 chiqimedit"
                                                        data-id="{{ $chiqim->id }}"
                                                        data-kun="{{ $chiqim->kun }}"
                                                        data-pastav_name="{{ $chiqim->pastav_id }}"
                                                        data-valyuta="{{ $chiqim->pul_id }}"
                                                        data-rsumma="{{ $chiqim->rsumma }}"
                                                        data-izox="{{ $chiqim->izox }}" data-bs-toggle="modal"
                                                        data-bs-target="#edit">Тахрирлаш</a>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            onclick="return window.location.reload(1)">
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
                                <label>Таъминотчини танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="taminotchi" name="taminotchi" class="multi-select form-control">
                                    <option value="">Таъминотчи...</option>
                                    @foreach ($pastavshik as $pastavshi)
                                        <option value="{{ $pastavshi->id }}">{{ $pastavshi->pastav_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="taminotchi_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
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
                            <div class="p-1">
                                <label>Суммани киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="tsumma" id="tsumma" class="form-control"
                                    placeholder="Сумма...">
                                <span id="tsumma_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Иннни киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea name="izox" class="form-control" class="form-control" placeholder="" style="height: 100px"></textarea>
                                <span id="izox_error" class="text-danger error-text"></span>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="saqlash">Сақлаш</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                    onclick="return window.location.reload(1)">Қайтиш</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="edit">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Тўловларни тахрирлаш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            onclick="return window.location.reload(1)">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="pas_update" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" id="editid" name="editid" class="form-control" readonly hidden>
                            <div class="p-1">
                                <label>Куни
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="edityangikun" id="edityangikun"
                                    class="form-control form-control-sm text-center">
                                <span id="edityangikun_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Таъминотчини танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="edittaminotchi" name="edittaminotchi" class="multi-select form-control">
                                    <option value="">Таъминотчи...</option>
                                    @foreach ($pastavshik as $pastavshi)
                                        <option value="{{ $pastavshi->id }}">{{ $pastavshi->pastav_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="edittaminotchi_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Валютани танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="editvalyuta" name="editvalyuta" class="multi-select form-control text-center">
                                    @foreach ($valyuta as $valyut)
                                        <option value="{{ $valyut->id }}">{{ $valyut->valyuta__nomi }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="editvalyuta_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Суммани киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="edittsumma" id="edittsumma" class="form-control"
                                    placeholder="Сумма...">
                                <span id="edittsumma_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Иннни киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea name="editizox" id="editizox" class="form-control" class="form-control" placeholder="" style="height: 100px"></textarea>
                                <span id="editizox_error" class="text-danger error-text"></span>
                            </div>


                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="saqlash">Сақлаш</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                    onclick="return window.location.reload(1)">Қайтиш</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="/vendor/global/global.min.js"></script>
        <script>
            $(document).ready(function() {

                $("#yangikun").val(new Date().toISOString().substring(0, 10));

                $('#pas_add').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('chiqimtaminot.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            alert(response.success);
                            window.location.reload(1)
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


                $('.chiqimedit').on('click', function() {
                    var id = $(this).data('id');
                    var editkun = $(this).data('kun');
                    var edittaminotchi = $(this).data('pastav_name');
                    var editvalyuta = $(this).data('valyuta');
                    var editrsumma = $(this).data('rsumma');
                    var editizox = $(this).data('izox');

                    $('#editid').val(id);
                    $('#edityangikun').val(editkun);
                    $('#edittaminotchi').val(edittaminotchi).trigger("change");
                    $('#editvalyuta').val(editvalyuta).trigger("change");
                    $('#edittsumma').val(editrsumma);
                    $('#editizox').val(editizox);
                });



                $('#pas_update').on('submit', function(e) {
                    e.preventDefault();
                    var id = $('#editid').val();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('chiqimtaminot.index') }}/" + id,
                        type: 'PUT',
                        data: formData,
                        success: function(response) {
                            alert(response.success);
                            window.location.reload(1)
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

                $("#taminotchi").select2({
                    dropdownParent: $('#chiqim_add')
                });

                $("#valyuta").select2({
                    dropdownParent: $('#chiqim_add')
                });

                $("#edittaminotchi").select2({
                    dropdownParent: $('#edit')
                });

                $("#editvalyuta").select2({
                    dropdownParent: $('#edit')
                });


                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })
        </script>
    @endsection
