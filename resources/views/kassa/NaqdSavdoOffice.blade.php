@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">
                        Нақд савдоларга ўзгартириш киритиш бўлими
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

                                <div class="col-lg-1">
                                    <input type="date" name="boshkun" class="form-control form-control-sm" id="boshkun"
                                           placeholder=" ">
                                </div>

                                <div class="col-lg-1">
                                    <input type="date" name="yakunkun" class="form-control form-control-sm"
                                           id="yakunkun" placeholder=" ">
                                </div>

                                <div class="col-lg-2">
                                    <select id="filial" name="filial" class="multi-select form-control">
                                        <option value=""></option>
                                        @foreach ($filial as $filia)
                                            <option value="{{ $filia->id }}">{{ $filia->fil_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-2">
                                    <button id="saqlash" class="btn btn-primary btn-xs"> Тасдиқлаш </button>
                                </div>
                                <div class="col-lg-6">
                                    <h5 class="bc-title text-primary">
                                        Нақд савдолар рўйхати
                                    </h5>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabprosfil" style="overflow: auto">
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

                var boshkun = $('#boshkun').val();
                var yakunkun = $('#yakunkun').val();

                var id = $('#filial').val();

                if (boshkun > yakunkun) {
                    return toastr.error('Sanani tanlashda xatolik.');
                }

                if (!id) {
                    return toastr.error('Filialni tanlang.');
                }


                $.ajax({
                    url: "{{ route('NaqdSavdoOffice.index') }}/" + id,
                    method: "GET",
                    data: {
                        filial: id,
                        boshkun: boshkun,
                        yakunkun: yakunkun,
                    },
                    beforeSend: function() {
                        $('#tabprosfil').html(`
                            <div style="margin: 100px 0;" class="text-center d-block">
                                <div class="mx-auto spinner-border text-primary"></div>
                            </div>
                        `);
                    },
                    success: function(data) {
                        $('#tabprosfil').html(data);

                    },
                    error: function(xhr) {
                        toastr.error("Ma'lumot yuklashda xatolik: " + xhr.status);
                    }
                });

            }

            $(document).ready(function() {

                $("#boshkun").val(new Date().toISOString().substring(0, 8) + '01');
                $("#yakunkun").val(new Date().toISOString().substring(0, 10));

                $('#filial').select2();

                $("#qidirish").keyup(function() {

                    var value = $(this).val().toLowerCase();

                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $('#saqlash').on('click', function() {
                    tabyuklash();
                })
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
                        filial_id: filial,
                        savdo_id: savdoid,
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
