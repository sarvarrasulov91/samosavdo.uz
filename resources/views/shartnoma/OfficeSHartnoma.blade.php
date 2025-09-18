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
                            <li id="select_div" class="nav-item" role="presentation">
                                <select id="filial" name="filial" class="multi-select form-control">
                                    <option value="10">Филиал...</option>
                                    @foreach ($filial as $filia)
                                        <option value="{{ $filia->id }}">{{ $filia->fil_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </li>
                            <style>
                                #select_div {
                                    width: 150px !important;
                                }
                            </style>
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">
                                        Шартномалар рўйхати
                                    </h5>
                                </li>
                            </ol>
                            <li class="nav-item" role="presentation">
                            </li>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabprosfil">
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
                        <div class="people-list dz-scroll" id="modalshow">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>



        <script src="/vendor/global/global.min.js"></script>
        <script>

            function tabyuklash() {

                var id = $('#filial').val();
                var csrf = document.querySelector('meta[name="csrf-token"]').content;

                if (id > 0) {
                    $.ajax({
                        url: "{{ route('OfficeSHartnoma.index') }}/" + id,
                        method: "GET",
                        data: {
                            filial: id,
                            _token: csrf
                        },
                        success: function(data) {
                            $('#tabprosfil').html(data);

                        }
                    })
                }
            }


            $(document).ready(function() {

                tabyuklash();
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $("#filial").change(function() {
                    tabyuklash();
                });

                $('#filial').select2();
            })


            $(document).on('click', '#modalshartshow', function() {
                $('#shartnoma_show').modal('show');
                var id = $(this).data('id');
                var fio = $(this).data('fio');
                var filial = $('#filial').val();
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                $('#modal-title').html(id + ' -> ' + fio);
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
                var id = $(this).data('shid');
                var status = 'tqushish';
                var savdo_id = prompt("Савдо ракмини киритинг.!!!");
                var filial = $('#filial').val();
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

            $(document).on('click', '#tovar_uchrish', function() {
                var id = $(this).data('shid');
                var stid = $(this).data('stid');
                var status = 'tuchirish';
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


            $(document).on('click', '#tulov_uchrish', function() {

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


            $(document).on('click', '#shyopish', function() {
                var id = $(this).data('shid');
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
                         },
                         success: function(response) {
                             $('#shartnoma_show').modal('hide');
                             toastr.success(response.message);
                             tabyuklash();
                         }
                     })
                 }
            })
        </script>
    @endsection
