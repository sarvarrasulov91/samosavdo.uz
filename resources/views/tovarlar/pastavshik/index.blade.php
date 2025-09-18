@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Таъминотчиларни рўйхатга олиш
                        бўлими</h5>
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
                                    <h5 class="bc-title text-primary">Таъминотчи рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"
                                            data-bs-target="#pasravshik_add">+ Қўшиш</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="{{ route('kirimtovar.index') }}"
                                            class="btn btn-danger btn-sm ms-2 text-right"><-Қайтиш </a>
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
                                            <th>Таъминотчи</th>
                                            <th>Манзили</th>
                                            <th>Телефони</th>
                                            <th>Х-р</th>
                                            <th>Инн</th>
                                            <th>МФО</th>
                                            <th>Таҳрирлаш</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($pastavshik as $pastavshi)
                                            <tr>
                                                <td>{{ $pastavshi->id }}</td>
                                                <td>{{ date('d.m.Y', strtotime($pastavshi->created_at)) }}</td>
                                                <td>{{ $pastavshi->pastav_name }}</td>
                                                <td>{{ $pastavshi->manzili }}</td>
                                                <td>{{ $pastavshi->telefoni }}</td>
                                                <td>{{ $pastavshi->xis_raqami }}</td>
                                                <td>{{ $pastavshi->inn }}</td>
                                                <td>{{ $pastavshi->mfo }}</td>
                                                <td>
                                                    <button id="pastavshikedit" class="btn btn-outline-primary btn-sm me-2"
                                                        data-id="{{ $pastavshi->id }}"
                                                        data-pastav_name="{{ $pastavshi->pastav_name }}"
                                                        data-manzili="{{ $pastavshi->manzili }}"
                                                        data-telefoni="{{ $pastavshi->telefoni }}"
                                                        data-xis_raqami="{{ $pastavshi->xis_raqami }}"
                                                        data-inn="{{ $pastavshi->inn }}" data-mfo="{{ $pastavshi->mfo }}"
                                                        data-bs-toggle="modal" data-bs-target="#edit"><i class="flaticon-381-notepad"></i></button>
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


        <!-- Modal pasravshik_add -->
        <div class="modal fade" id="pasravshik_add">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Янги таъминотчи қўшиш</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="pas_add" method="POST">
                            @csrf
                            <div class="p-1">
                                <label>Таъминотчини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="taminotchi" class="form-control" placeholder="Таъминотчи...">
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
                                    maxlength="5">
                                <span id="mfo_error" class="text-danger error-text"></span>
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

        <!-- Modal -->
        <div class="modal fade" id="edit">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Таъминотчи тахрирлаш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="pas_update" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" id="editid" name="editid" class="form-control" readonly hidden>
                            <div class="p-1">
                                <label>Таъминотчини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="edittaminotchi" name="edittaminotchi" class="form-control"
                                    placeholder="Таъминотчи...">
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
            function tabyuklash(pastavshik) {
                var html = '';
                if(pastavshik.length>0){
                    for(let i = 0; i < pastavshik.length; i++){
                        html +='<tr>\
                                    <td>'+pastavshik[i]['id']+'</td>\
                                    <td>'+new Date(pastavshik[i]['created_at']).toLocaleDateString()+'</td>\
                                    <td>'+pastavshik[i]['pastav_name']+'</td>\
                                    <td>'+pastavshik[i]['manzili']+'</td>\
                                    <td>'+pastavshik[i]['telefoni']+'</td>\
                                    <td>'+pastavshik[i]['xis_raqami']+'</td>\
                                    <td>'+pastavshik[i]['inn']+'</td>\
                                    <td>'+pastavshik[i]['mfo']+'</td>\
                                    <td> <button id="pastavshikedit" class="btn btn-outline-primary btn-sm me-2" data-id="'+pastavshik[i]['id']+'" data-pastav_name="'+pastavshik[i]['pastav_name']+'" data-manzili="'+pastavshik[i]['manzili']+'"data-telefoni="'+pastavshik[i]['telefoni']+'"data-xis_raqami="'+pastavshik[i]['xis_raqami']+'"data-inn="'+pastavshik[i]['inn']+'"data-mfo="'+pastavshik[i]['mfo']+'"data-bs-toggle="modal" data-bs-target="#edit"><i class="flaticon-381-notepad"></i></button></td>\</tr>';
                    }
                }else{
                    html +='<tr>\
                                <td colspan="9">Маълумот топилмади</td>\
                            </tr>';
                }
                $('#tab1').html(html);
            }

            $(document).ready(function() {
                $('#pas_add').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: "{{ route('pastavshik.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash(response.pastavshik);
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
                        url: "{{ route('pastavshik.index') }}/" + id,
                        type: 'PUT',
                        data: formData,
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash(response.pastavshik);
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


            $(document).on('click', '#pastavshikedit', function() {
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
