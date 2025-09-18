@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Нақд савдоларга ўзгартириш
                        киритиш
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
                                        Нақд савдолар рўйхати
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



        <div id="pechat" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 60%; font-size: 15px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title-pechat" class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="kvitpechat">
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
                        url: "{{ route('NaqdSavdoOffice.index') }}/" + id,
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
                $('#filial').select2();
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
            })



            $(document).on('click', '#kivitpechat', function() {
                var id = $(this).data('id');
                var fio = $(this).data('fio');
                var filial = $('#filial').val();
                var savdoid = $(this).data('savdoid');
                $('#modal-title-pechat').html(id + ' - ' + fio);
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                $.ajax({
                    url: "{{ route('NaqdSavdoOffice.store') }}",
                    method: "POST",
                    data: {
                        _token: csrf,
                        id: id,
                        fio: fio,
                        filial: filial,
                        savdoid: savdoid,
                    },
                    success: function(data) {
                        $("#kvitpechat").html(data);
                    }
                })
            });



            $(document).on('click', '#tovarudalit', function() {
                var id = $(this).data('id');
                var savdoid = $(this).data('savdoid');
                var filial = $('#filial').val();
                var uzid = confirm(id + ' ' + savdoid + ' ўчирилмокда. ТАСДИҚЛАНГ !!!')
                if (uzid == true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('NaqdSavdoOffice.index') }}/" + id,
                        method: "PUT",
                        data: {
                            id: id,
                            savdoid: savdoid,
                            filial: filial,
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash();
                        }
                    })
                }
            })
        </script>
    @endsection
