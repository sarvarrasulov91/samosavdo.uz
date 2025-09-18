@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Сотилган товарларни
                        шартномаларга бириктириш бўлими</h5>
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
                                    <h5 class="bc-title text-primary">Мижозлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
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
    </div>



    <div id="shartnoma_add" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 60%; font-size: 15px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="text" name="shid" id="shid" required hidden>
                        <input type="text" name="status" id="status" required hidden>
                    </div>
                    <div id="modalshow">

                    </div>
                    <div class="row">
                        <div class="col-3">

                        </div>
                        <div class="col-6">
                            <div>
                                <label>Штрих кодни киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="krimt" name="krimt" class="form-control text-center"
                                    maxlength="17" />
                            </div>
                        </div>
                        <div class="col-3">

                        </div>
                    </div>
                </div>
                <br>
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
        function tabyuklashart() {
            $.ajax({
                url: "{{ route('tovarqarz.create') }}",
                type: 'GET',
                data: "",
                success: function(data) {
                    $('#tabpros').html(data);
                }
            });
        }


        $(document).ready(function() {
            tabyuklashart();
            $("#qidirish").keyup(function() {
                var value = $(this).val().toLowerCase();
                $("#tab1 tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                })
            })


            $('#krimt').on('keypress', function(e) {
                if (e.which === 13) {
                    var shid = $('#shid').val();
                    var krimt = $('#krimt').val();
                    var status = $('#status').val();
                    if (krimt.length != 17) {
                        toastr.success("Хатолик!!! Маълумотларни тўлиқ киритмадингиз.");
                    } else {
                        $.ajax({
                            url: "{{ route('tovarqarz.store') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                krimt: krimt,
                                shid: shid,
                                status: status
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                $('#krimt').val("");
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    }
                                });
                                $.ajax({
                                    url: "{{ route('tovarqarz.index') }}/" + shid,
                                    method: "PUT",
                                    data: {
                                        shid: shid,
                                        status: status,
                                    },
                                    success: function(data) {
                                        $("#modalshow").html(data);
                                    }
                                })
                                tabyuklashart();
                            }
                        });
                    }
                }
            })
        })



        $(document).on('click', '#modalgamurojatshart', function() {
            var id = $(this).data('id');
            var fio = $(this).data('fio');
            var status = $(this).data('status');
            $('.modal-title').html(id + ' -> ' + fio + ' -> ' + status + ' савдо');
            $('#shid').val(id);
            $('#status').val(status);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('tovarqarz.index') }}/" + id,
                method: "PUT",
                data: {
                    shid: id,
                    fio: fio,
                    status: status,
                },
                success: function(data) {
                    $("#modalshow").html(data);
                }
            })
        });

    </script>
@endsection
