@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Шартномаларга ўзгартириш киритиш
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
                            <div class="row w-100">
                                <div class="col-xl-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radioButton" id="radioId" value="shartnoma" checked>
                                        <label class="form-check-label" for="radioId" style="padding: 0; margin: 5px; font-size: x-small;">
                                            Shartnoma ID
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radioButton" id="radioMijoz" value="mijoz">
                                        <label class="form-check-label" for="radioMijoz" style="padding: 0; margin: 5px; font-size: x-small;">
                                            Mijoz Familiyasi va ismi / Telefoni / Passport / Pinfl
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <input type="text" name="id" class="form-control form-control-sm" id="id"
                                           placeholder=" ">
                                </div>
                                <div class="col-xl-2">
                                    <select id="filial" name="filial" class="multi-select form-control">
                                        <option value=""></option>
                                        @foreach ($filial as $filia)
                                            <option value="{{ $filia->id }}">{{ $filia->fil_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2">
                                    <button type="button" id="saqlash" class="btn btn-primary btn-xs"> Тасдиқлаш </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabprosfil" style="overflow: auto;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="shartnoma_show" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 75%; font-size: 14px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title" class="modal-title">Шартнома таҳрирлаш бўлими</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="people-list dz-scroll" id="modalshow" style="overflow: auto;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="talabnomaPechatModal" class="modal fade bd-example-modal-lg" data-bs-backdrop="static"
             data-bs-keyboard="false" tabindex="-2" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 85%; font-size: 13px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title-filial" class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="people-list dz-scroll" style="background: #cccc;">
                            <div id="talabnomaPechat">

                            </div>
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

        <!-- shartnomaga karta biriktirish -->
        <div id="contractCardAddModal" class="modal fade bd-example-modal-lg" data-bs-backdrop="static"
             data-bs-keyboard="false" tabindex="-3" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 85%; font-size: 13px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title-cards" class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="people-list dz-scroll">
                            <div id="contractShowCards">

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal"> Ortga </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="/vendor/global/global.min.js"></script>
        <script>

            function tabyuklash() {

                let id = $('#id').val();
                let filial = $('#filial').val();

                let csrf = document.querySelector('meta[name="csrf-token"]').content;

                $('#tabprosfil').html(`
                    <div style='margin: 20vh 30vw;' class='text-center d-block'>
                        <div style='color: #007bff !important;' class='mx-auto spinner-border text-primary'></div>
                    </div>
                `);

                if (!id || !filial) {

                    alert("Shartnoma raqamini kiriting Yoki filialni tanlang.!!!");

                } else {

                    $.ajax({
                        url: "{{ route('ShartnomaId.store') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            filial: filial,
                        },
                        success: function(data) {
                            $("#tabprosfil").html(data);
                        }
                    })
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

            })

            $(document).on('click', '#saqlash', function() {

                let id = $('#id').val();
                let filial = $('#filial').val();
                let radioButtonValue = $('input[name="radioButton"]:checked').val();

                let csrf = document.querySelector('meta[name="csrf-token"]').content;

                $('#tabprosfil').html(
                    "<div style='margin: 100px 0;' class='text-center d-block'>" +
                    "<div style='color: #007bff !important;' class='mx-auto spinner-border text-primary'></div>" +
                    "</div>"
                );

                if (!id || !filial) {
                    alert("Shartnoma raqamini kiriting yoki filialni tanlang.!!!");
                } else {
                    $.ajax({
                        url: "{{ route('ShartnomaId.store') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            filial: filial,
                            radioButton: radioButtonValue
                        },
                        success: function(data) {
                            $("#tabprosfil").html(data);
                        }
                    });
                }
            });


            $(document).on('click', '#modalshartshow', function() {

                $('#shartnoma_show').modal('show');

                let id = $(this).data('id');
                let shid = $(this).data('shid');
                let fio = $(this).data('fio');
                let filial = $('#filial').val();
                let csrf = document.querySelector('meta[name="csrf-token"]').content;

                $('#modal-title').html(shid + ' -> ' + fio);

                $.ajax({

                    url: "{{ route('OfficeSHartnoma.store') }}",
                    type: 'POST',
                    data: {
                        _token: csrf,
                        id: id,
                        filial: filial,
                    },
                    success: function(data) {
                        $("#modalshow").html(data);
                    }
                })
            });

            $(document).on('click', '#tovar_qushish', function() {
                var id = $(this).data('id');
                var shid = $(this).data('shid');
                var status = 'tovarqushish';
                var filial = $('#filial').val();

                var savdo_id = prompt("Савдо ракмини киритинг.!!!");

                if (savdo_id) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('OfficeSHartnoma.index') }}/" + id,
                        method: "PUT",
                        data: {
                            id: id,
                            shid: shid,
                            savdo_id: savdo_id,
                            status: status,
                            filial: filial
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            var csrf = document.querySelector('meta[name="csrf-token"]').content;
                            $.ajax({
                                url: "{{ route('OfficeSHartnoma.store') }}",
                                type: 'POST',
                                data: {
                                    _token: csrf,
                                    id: id,
                                    filial: filial,
                                },
                                success: function(data) {
                                    $("#modalshow").html(data);
                                    tabyuklash();
                                }
                            })
                        }
                    })
                }
            });

            $(document).on('click', '#tovar_delete', function() {
                var id = $(this).data('shid');
                var stid = $(this).data('stid');
                var status = 'tovaruchirish';
                var filial = $('#filial').val();
                var uzid = confirm(stid + ' ИД даги товар ўчирилмокда. ТАСДИҚЛАНГ !!!');
                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('OfficeSHartnoma.index') }}/" + id,
                        method: "PUT",
                        data: {
                            id: id,
                            stid: stid,
                            status: status,
                            filial: filial
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            var csrf = document.querySelector('meta[name="csrf-token"]').content;
                            $.ajax({
                                url: "{{ route('OfficeSHartnoma.store') }}",
                                type: 'POST',
                                data: {
                                    _token: csrf,
                                    id: id,
                                    filial: filial,
                                },
                                success: function(data) {
                                    $("#modalshow").html(data);
                                    tabyuklash();
                                }
                            })
                        }
                    })
                }
            });




            $(document).on('click', '#tulov_delete', function() {

                var id = $(this).data('shid');
                var tulovid = $(this).data('tulovid');
                var filial = $(this).data('filial');
                var status = 'tulovuchrish';

                var uzid = confirm(tulovid + ' ИД даги тўлов ўчирилмокда. ТАСДИҚЛАНГ !!!');

                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('OfficeSHartnoma.index') }}/" + id,
                        method: "PUT",
                        data: {
                            id: id,
                            tulovid: tulovid,
                            status: status,
                            filial: filial
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            var csrf = document.querySelector('meta[name="csrf-token"]').content;
                            $.ajax({
                                url: "{{ route('OfficeSHartnoma.store') }}",
                                type: 'POST',
                                data: {
                                    _token: csrf,
                                    id: id,
                                    filial: filial,
                                },
                                success: function(data) {
                                    $("#modalshow").html(data);
                                    tabyuklash();
                                }
                            })
                        }
                    })
                }
            });


            $(document).on('click', '#shartnoma_delete', function() {

                var id = $(this).data('shid');
                var filial = $('#filial').val();
                var shStatus = 'shartnoma_delete';

                var uzid = confirm(id + ' ИД даги шартнома ўчирилмокда. ТАСДИҚЛАНГ !!!');

                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('OfficeSHartnoma.index') }}/" + id,
                        method: "DELETE",
                        data: {
                            id: id,
                            filial: filial,
                            shStatus: shStatus
                        },
                        success: function(response) {
                            $('#shartnoma_show').modal('hide');
                            toastr.success(response.message);
                            tabyuklash();
                        }
                    })
                }
            })

            $(document).on('click', '#shartnoma_yopish', function() {

                var id = $(this).data('shid');
                var filial = $('#filial').val();
                var shStatus = 'shartnoma_yopish';

                var uzid = confirm(id + ' ИД даги шартнома ўчирилмокда. ТАСДИҚЛАНГ !!!');

                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('OfficeSHartnoma.index') }}/" + id,
                        method: "DELETE",
                        data: {
                            id: id,
                            filial: filial,
                            shStatus: shStatus
                        },
                        success: function(response) {
                            $('#shartnoma_show').modal('hide');
                            toastr.success(response.message);
                            tabyuklash();
                        }
                    })
                }
            })

            $(document).on('click', '#shtalabnoma', function() {

                var id = $(this).data('shid');
                var filial = $('#filial').val();

                $('#talabnomaPechatModal').modal('show');

                $.ajax({
                    url: "{{ route('ShartnomaId.index') }}/" + id,
                    method: "GET",
                    data: {
                        id: id,
                        filial: filial
                    },
                    success: function(data) {
                        $("#talabnomaPechat").html(data);
                    },
                    error: function(err) {
                        console.error("Error fetching data for shtalabnoma: ", err);
                    }
                })
            });

            function printCertificate() {
                // Check if the element exists
                if ($("#talabnomaPechat").length === 0) {
                    console.error("Element with ID 'pechat' not found.");
                    return;
                }

                var options = {
                    mode: 'iframe', // Change to 'popup' if needed
                    popClose: false // Set to true if you are using popup mode
                };

                $("#talabnomaPechat").printArea(options);
            }

        </script>
@endsection
