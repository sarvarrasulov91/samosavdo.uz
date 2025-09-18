@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Шартномаларни рўйхатга
                        олиш бўлими
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
                                    <h5 class="bc-title text-primary">
                                        Шартномалар рўйхати
                                    </h5>
                                </li>
                            </ol>
                            <li class="nav-item" role="presentation">
                                <a href="" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"
                                    data-bs-target="#shart_add">+ Қўшиш</a>
                            </li>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabpros">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="shartnoma_show" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 85%; font-size: 14px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Рўйхатга олиш</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="people-list dz-scroll" id="modalshow">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="shart_add" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="true"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 85%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Рўйхатга олиш</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 people-list dz-scroll border">
                                <form method="POST" id="add_shartnoma">
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
                                        <label>Савдо рақамини танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="savdounix_id" name="savdounix_id"
                                            class="multi-select form-control text-center">
                                            <option value="">Савдо рақами...</option>
                                            @foreach ($savdounix_id as $savdounix_i)
                                                <option value="{{ $savdounix_i->unix_id }}">
                                                    {{ $savdounix_i->unix_id }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span id="savdounix_id_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label>Мижозни танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="mijoz" id="mijoz" class="multi-select form-control">
                                            <option value="">Мижоз номи...</option>
                                            @foreach ($mijozlar as $mijozla)
                                                <option value="{{ $mijozla->id }}">
                                                    {{ $mijozla->id . ' - ' . $mijozla->passport_sn . ' - ' . $mijozla->pinfl . ' - ' . $mijozla->last_name . ' ' . $mijozla->first_name . ' ' . $mijozla->middle_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span id="mijoz_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label>Ташриф турини танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="tashrif" name="tashrif" class="multi-select form-control text-center">
                                            <option value="">Ташриф тури...</option>
                                            @foreach ($tashrif as $tashri)
                                                <option value="{{ $tashri->id }}">
                                                    {{ $tashri->tashrif_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span id="tashrif_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label> Муддатини танланг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="muddat" name="muddat"
                                            class="multi-select form-control text-center" placeholder="Муддати...">
                                            <option value="">Муддати...</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                    <span id="muddat_error" class="text-danger error-text"></span>
                                    <div class="p-2">
                                        <label> Фоиз холати
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="fstatus" name="fstatus"
                                            class="multi-select form-control text-center" placeholder="Фоизли...">
                                            <option value="1">Фоизли</option>
                                            <option value="0">Фоизсиз</option>
                                        </select>
                                    </div>
                                    <div class="p-2">
                                        <label>Олдиндан тўлов
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="oldintulovnaqd" id="naqd"
                                            class="form-control form-control-sm text-center" placeholder="Накд...">
                                        <span id="oldintulovnaqd_error" class="text-danger error-text"></span>
                                        <input type="text" name="oldintulovplastik" id="plastik"
                                            class="form-control form-control-sm text-center" placeholder="Пластик...">
                                        <span id="oldintulovplastik_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-2">
                                        <label>Чегирма суммаси киритинг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="chegirma" id="chegirma"
                                            class="form-control form-control-sm text-center" placeholder="Чегирма...">
                                        <span id="chegirma_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="p-1">
                                        <label>Изохини киритинг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="izox" class="form-control" class="form-control" placeholder=""></textarea>
                                        <span id="izox_error" class="text-danger error-text"></span>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" id="adduser"
                                            class="btn btn-primary btn-submit">Сақлаш</button>
                                    </div>
                                </form>

                            </div>
                            <div class="col-8 people-list dz-scroll border">
                                <div id="tabprosmijoz">
                                </div>
                                <div id="tabprossavdo">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Қайтиш</button>
                    </div>

                </div>
            </div>
        </div>



        <div id="shartnomapechat" class="modal fade bd-example-modal-lg" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 85%; font-size: 13px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title-filial" class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="people-list dz-scroll" style="background: #cccc;">
                            <div id="pechat">

                            </div>
                            <input type="text" readonly hidden>

                        </div>
                        <div class="modal-footer">
                            <button onclick="printCertificate()" class="btn btn-primary"><i class="fa fa-print"></i> Чоп
                                этиш</button>
                            <button onclick="exportHTML()" class="btn btn-primary"><i class="fa fa-file-word"></i>
                                Word</button>
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Қайтиш</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash() {
                $.ajax({
                    url: "{{ route('ShartnomaNew.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
                    }
                });
            }


            $(document).ready(function() {
                tabyuklash();

                $("#yangikun").val(new Date().toISOString().substring(0, 10));

                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })


                $("#mijoz").change(function() {
                    var mijoz_id = $('#mijoz').val();
                    $.ajax({
                        url: "{{ route('newmijoz.index') }}/" + mijoz_id + "/edit",
                        method: "GET",
                        data: {
                            mijoz_id: mijoz_id,
                        },
                        success: function(data) {
                            $('#tabprosmijoz').html(data);
                        }
                    })
                });


                $("#savdounix_id").change(function() {
                    var savdounix_id = $('#savdounix_id').val();
                    $.ajax({
                        url: "{{ route('naqdsavdo.index') }}/" + savdounix_id + "/edit",
                        method: "GET",
                        data: {
                            savdounix_id: savdounix_id,
                        },
                        success: function(data) {
                            $('#tabprossavdo').html(data);
                        }
                    })
                });


                $('#add_shartnoma').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    var id = '1';
                    $.ajax({
                        url: "{{ route('ShartnomaNew.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            $.ajax({
                                url: "{{ route('fondsavdo.index') }}/" + id,
                                method: "GET",
                                data: {
                                    id: id,
                                },
                                success: function(data) {
                                    $('#savdounix_id').html(data);
                                }
                            })
                            toastr.success(response.message);
                            tabyuklash();
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

                $("#mijoz").select2({
                    dropdownParent: $("#shart_add")
                });
                $("#savdounix_id").select2({
                    dropdownParent: $("#shart_add")
                });

                $("#muddat").select2({
                    dropdownParent: $("#shart_add")
                });

                $("#fstatus").select2({
                    dropdownParent: $("#shart_add")
                });

                $("#tashrif").select2({
                    dropdownParent: $("#shart_add")
                });

            })



            $(document).on('click', '#modalshartshow', function() {
                $('#shartnoma_show').modal('show');
                var id = $(this).data('id');
                var fio = $(this).data('fio');
                $('.modal-title').html(id + ' -> ' + fio);
                $.ajax({
                    url: "{{ route('shartnomalar.index') }}/" + id,
                    method: "GET",
                    data: {
                        id: id,
                        fio: fio
                    },
                    success: function(data) {
                        $("#modalshow").html(data);
                    }
                })
            });

            function shpecht(id) {
                $('#shartnomapechat').modal('show');
                $.ajax({
                    url: "{{ route('shartnomalar.index') }}/" + id + '/edit',
                    method: "GET",
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        $("#pechat").html(data);
                    }
                })
            }


            function shbetlik(id) {
                $('#shartnomapechat').modal('show');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('shartnomalar.index') }}/" + id,
                    method: "PUT",
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        $("#pechat").html(data);
                    }
                })
            }


            function shgrafik(id) {
                $('#shartnomapechat').modal('show');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('shartnomalar.index') }}/" + id,
                    method: "DELETE",
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        $("#pechat").html(data);
                    }
                })
            }




            function shariza(id) {
                $('#shartnomapechat').modal('show');
                $.ajax({
                    url: "{{ route('OfficeSHartnoma.index') }}/" + id + '/edit',
                    method: "GET",
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        $("#pechat").html(data);
                    }
                })
            }



            function printCertificate() {
                var mode = 'iframe';
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $("#pechat").printArea(options);
            }

            function exportHTML() {
                var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
                    "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                    "xmlns='http://www.w3.org/TR/REC-html40'>" +
                    "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title></head><body>";
                var footer = "</body></html>";
                var sourceHTML = header + document.getElementById("certificate").innerHTML + footer;

                var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
                var fileDownload = document.createElement("a");
                document.body.appendChild(fileDownload);
                fileDownload.href = source;
                fileDownload.download = 'Шартнома.doc';
                fileDownload.click();
                document.body.removeChild(fileDownload);
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
                const inputSelectors = ['#naqd', '#plastik', '#chegirma'];

                $('body').on('input', inputSelectors.join(', '), function(e) {
                    digits_float(this);
                });

                inputSelectors.forEach(function(selector) {
                    digits_float(selector);
                });
            });

        </script>
    @endsection
