@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Шартномаларни тахрирлаш булими
                    </h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <div class="row w-100">
                                <div class="col-xl-2">
                                    <input type="number" name="id" class="form-control form-control-sm" id="id"
                                        placeholder=" ">
                                </div>
                                <div id="select_div" class="col-xl-2">
                                    <select id="filial" name="filial" class="multi-select form-control">
                                        <option value=""></option>
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
                        <h5 class="modal-title">Шартномаларни тахрирлаш ойнаси</h5>
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
                                <label>Шартнома ИД
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="shid" id="shid" class="form-control form-control-sm text-center" readonly>
                                <span id="shid_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Мижоз
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="mijoz" id="mijoz" style="width: 100%" required>
                                    <option value=""></option>
                                    @foreach ($mijozlar as $mijoz)
                                        <option value="{{ $mijoz->id }}">
                                            {{ $mijoz->last_name.' '.$mijoz->first_name . ' ' . $mijoz->middle_name }}</option>
                                    @endforeach
                                </select>
                                <span id="mijoz_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Ташриф
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="tashrif" id="tashrif" style="width: 100%" required>
                                    <option value=""></option>
                                    @foreach ($tashrif as $tashrifName)
                                        <option value="{{ $tashrifName->id }}">
                                            {{ $tashrifName->tashrif_name}}</option>
                                    @endforeach
                                </select>
                                <span id="tashrif_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Фоиз холати
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="fstatus" id="fstatus">
                                    <option value="1">Фоизли</option>
                                    <option value="0">Фоизсиз</option>
                                </select>
                                <span id="fstatus_error" class="text-danger error-text"></span>
                            </div>

                            <div class="p-1">
                                <label>Куни
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="kun" id="kun" class="form-control form-control-sm text-center">
                                <span id="kun_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Муддати
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="muddat" id="muddat" class="form-control form-control-sm text-center">
                                <span id="muddat_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Савдо ракам
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="savdo_id" id="savdo_id" class="form-control form-control-sm text-center">
                                <span id="savdo_id_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Изох
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="izox" id="izox" class="form-control">
                                <span id="izox_error" class="text-danger error-text"></span>
                            </div>
                            <div class="p-1">
                                <label>Шартнома статуси
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="status" id="status">
                                    <option value="Актив">Актив</option>
                                    <option value="Ёпилган">Ёпилган</option>
                                    <option value="Удалит">Удалит</option>
                                </select>
                                <span id="status_error" class="text-danger error-text"></span>
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
            var id = $('#id').val();
            var filial = $('#filial').val();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            $('#tabpros').html("<div style='margin: 100px 0; 'class='text-center d-block'><div style='color: #007bff !important;' class='mx-auto spinner-border text-primary'></div></div>");
                if (!id) {
                    alert("Shartnoma raqamini kiriting.!!!");
                } else {
                    $.ajax({
                        url: "{{ route('ShartnomaEdit.store') }}",
                            method: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id,
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

                $(function() {
                    $("#btnExportexcel").click(function() {
                        $("#tabpros").table2excel({
                            filename: "Tulovlar"
                        });
                    })
                }); 

                $("#filial").select2({
                    placeholder: "Филиал...",
                });

                $('#tasdiqlash').on('click', function() {
                    var id = $('#id').val();
                    var filial = $('#filial').val();
                    var csrf = document.querySelector('meta[name="csrf-token"]').content;
                    if (!id) {
                        alert("Shartnoma raqamini kiriting.!!!");
                    } else {
                        $.ajax({
                            url: "{{ route('ShartnomaEdit.store') }}",
                            method: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id,
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
                    url: "{{ route('ShartnomaEdit.index') }}/" + id,
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
            $(document).ready(function() {
                $('#mijoz').select2({
                    dropdownParent: $('#edit'), // Keeps the dropdown within the modal
                    placeholder: 'Mijozni tanlang', // Placeholder text
                    allowClear: true // Allows clearing the selection
                });

                $('#kafil').select2({
                    dropdownParent: $('#edit'), // Keeps the dropdown within the modal
                    placeholder: 'Kafilni tanlang', // Placeholder text
                    allowClear: true // Allows clearing the selection
                });
            });

            $(document).on('click', '#tulovedit', function() {
                var filial2 = $(this).data('filial2');
                var shid = $(this).data('shid');
                var mijoz = $(this).data('mijoz');
                var tashrif = $(this).data('tashrif');
                var fstatus = $(this).data('fstatus');
                var kun = $(this).data('kun');
                var savdo_id = $(this).data('savdo_id');
                var muddat = $(this).data('muddat');
                var status = $(this).data('status');
                var izox = $(this).data('izox');

                $('#filial2').val(filial2);
                $('#shid').val(shid);
                $('#mijoz').val(mijoz).trigger('change');
                $('#tashrif').val(tashrif);
                $('#fstatus').val(fstatus);
                $('#kun').val(kun);
                $('#savdo_id').val(savdo_id);
                $('#muddat').val(muddat);
                $('#status').val(status);
                $('#izox').val(izox);
            });


        </script>
    @endsection
