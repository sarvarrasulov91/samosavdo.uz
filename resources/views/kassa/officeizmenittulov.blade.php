@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Туловларни тахрирлаш булими
                    </h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <div class="row">
                                <div class="col-xl-3">
                                    <input type="date" name="boshkun" class="form-control form-control-sm" id="boshkun"
                                        placeholder=" ">
                                </div>
                                <div class="col-xl-3">
                                    <input type="date" name="yakunkun" class="form-control form-control-sm"
                                        id="yakunkun" placeholder=" ">
                                </div>
                                <div id="select_div" class="col-xl-3">
                                    <select id="filial" name="filial" class="multi-select form-control">
                                        <option value="">Филиал...</option>
                                        @foreach ($filial as $filia)
                                            <option value="{{ $filia->id }}">{{ $filia->fil_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button id="tasdiqlash" class="btn btn-primary btn-xs"> Тасдиқлаш </button>
                                </div>
                                <div class="col-1">
                                   <button id="btnExportexcel" class="btn btn-primary btn-xs ms-2"> Excel </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabpros" style="overflow: auto;">
                            </div>
                        </div>
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
                        <h5 class="modal-title">Тўловларни тахрирлаш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="pas_update" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="p-1">
                                <input type="text" name="filial2" id="filial2" class="form-control text-center" readonly hidden>
                                <span id="filial2_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Тулов ИД
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="id" id="id" class="form-control form-control-sm text-center">
                                <span id="id_error" class="text-danger error-text"></span>
                            </div>

                            <div class="p-1">
                                <label>Куни
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="yangikun" id="yangikun" class="form-control form-control-sm text-center">
                                <span id="yangikun_error" class="text-danger error-text"></span>
                            </div>

                            <div class="p-1">
                                <label>Тулов тури
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="tulovturi" id="tulovturi" class="form-control" placeholder="Нақд...">
                                <span id="tulovturi_error" class="text-danger error-text"></span>
                            </div>

                             <div class="p-1">
                                <label>Шартнома номери
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="shartnomaid" id="shartnomaid" class="form-control" placeholder="123...">
                                <span id="shartnomaid_error" class="text-danger error-text"></span>
                            </div>

                            <div class="p-1">
                                <label>Суммани киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="naqd" id="naqd" class="form-control" placeholder="Нақд...">
                                <span id="naqd_error" class="text-danger error-text"></span>

                                <input type="number" name="pastik" id="pastik" class="form-control" placeholder="Пластик...">
                                <span id="pastik_error" class="text-danger error-text"></span>

                                <input type="number" name="hr" id="hr" class="form-control" placeholder="Хисоб-рақам...">
                                <span id="hr_error" class="text-danger error-text"></span>

                                <input type="number" name="click" id="click" class="form-control" placeholder="Сlick...">
                                <span id="click_error" class="text-danger error-text"></span>

                                <input type="number" name="avtot" id="avtot" class="form-control" placeholder="Авто тўлов...">
                                <span id="avtot_error" class="text-danger error-text"></span>

                                <input type="number" name="chegirma" id="chegirma" class="form-control" placeholder="Чегирма...">
                                <span id="chegirma_error" class="text-danger error-text"></span>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="saqlash"><i class="flaticon-381-notepad"></i> Тахрирлаш</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="flaticon-381-exit"></i> Қайтиш</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="/vendor/global/global.min.js"></script>
        <script>

        function tabyuklash() {
                var boshkun = $('#boshkun').val();
                var yakunkun = $('#yakunkun').val();
                var filial = $('#filial').val();
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                $('#tabpros').html("<div style='margin: 100px 0; 'class='text-center d-block'><div style='color: #007bff !important;' class='mx-auto spinner-border text-primary'></div></div>");
                if (boshkun > yakunkun) {
                    alert("Кунни киритишла хатолик.!!!");
                } else {
                    $.ajax({
                        url: "{{ route('officeizmenittulov.store') }}",
                            method: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                boshkun: boshkun,
                                yakunkun: yakunkun,
                                filial: filial,
                            },
                        success: function(data) {
                            $('#tabpros').html(data);
                        }
                    });
                }
            }

        $(document).ready(function() {

                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $("#filial").select2({
                    placeholder: "Филиал...",
                });

                $("#boshkun").val(new Date().toISOString().substring(0, 8) + '01');
                $("#yakunkun").val(new Date().toISOString().substring(0, 10));

                $('#tasdiqlash').on('click', function() {
                    var boshkun = $('#boshkun').val();
                    var yakunkun = $('#yakunkun').val();
                    var filial = $('#filial').val();
                    if (boshkun > yakunkun) {
                        alert("Кунни киритишла хатолик.!!!");
                    } else {
                        $.ajax({
                            url: "{{ route('officeizmenittulov.store') }}",
                            method: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                boshkun: boshkun,
                                yakunkun: yakunkun,
                                filial: filial,
                            },
                            success: function(data) {
                                $("#tabpros").html(data);
                            }
                        })
                    }
                })

            })

            $('#pas_update').on('submit', function(e) {
                e.preventDefault();
                var id = $('#id').val();
                var filial = $('#filial').val();
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('officeizmenittulov.index') }}/" + id,
                    type: 'PUT',
                    data: formData,
                    filial: filial,

                    success: function(response) {
                        toastr.success(response.message);
                        $('#edit').modal('hide');
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

            $(document).on('click', '#tulovedit', function() {
                var filial = $(this).data('filial');
                var id = $(this).data('id');
                var kun = $(this).data('kun');
                var tulovturi = $(this).data('tulov_turi');
                var shartnomaid = $(this).data('shartnoma_id');
                var naqd = $(this).data('naqd');
                var pastik = $(this).data('pastik');
                var hr = $(this).data('hr');
                var click = $(this).data('click');
                var avtot = $(this).data('avtot');
                var chegirma = $(this).data('chegirma');
                var umumiysumma = $(this).data('umumiysumma');

                $('#filial2').val(filial);
                $('#id').val(id);
                $('#yangikun').val(kun);
                $('#tulovturi').val(tulovturi);
                $('#shartnomaid').val(shartnomaid);
                $('#naqd').val(naqd);
                $('#pastik').val(pastik);
                $('#hr').val(hr);
                $('#click').val(click);
                $('#avtot').val(avtot);
                $('#chegirma').val(chegirma);

            });

            $(document).on('click', '#tulovdelete', function() {
                var id = $(this).data('tulov_id');
                var filial = $('#filial').val();
                var status = 'tulovdelete';
                var uzid = confirm(id + ' ИД даги тулов ўчирилмокда. ТАСДИҚЛАНГ !!!');
                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('officeizmenittulov.index') }}/" + id,
                        method: "PUT",
                        data: {
                            id: id,
                            status: status,
                            filial2: filial
                        },

                        success: function(response) {
                            toastr.success(response.message);
                            var csrf = document.querySelector('meta[name="csrf-token"]').content;
                            $.ajax({
                                url: "{{ route('officeizmenittulov.store') }}",
                                type: 'POST',
                                data: {
                                    _token: csrf,
                                    id: id,
                                    filial2: filial,
                                },
                                success: function(data) {

                                    // $('#shartnoma_show').modal('hide');
                                    tabyuklash();
                                }
                            })
                        }
                    })
                }
            });

        </script>
    @endsection
