@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Таъминотчилар хисоботи</h5>
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
                                    <h5 class="bc-title text-primary">Таъминотчилар хисоботи {{ $du2 }} йил
                                        холатига</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <table class="table table-bordered table-responsive-sm text-center align-middle ">
                                    <thead>
                                        <tr class="text-bold text-primary align-middle">
                                            <th rowspan="2">ID</th>
                                            <th rowspan="2">Таъминотчи</th>
                                            <th colspan="2">Ой бошига</th>
                                            <th colspan="2">Кирим</th>
                                            <th colspan="2">Қайтарилди</th>
                                            <th colspan="2">Чиқим</th>
                                            <th colspan="2">Ой охирига</th>
                                        </tr>
                                        <tr class="text-bold text-primary align-middle">
                                            <th>Сўм</th>
                                            <th>Доллар</th>
                                            <th>Сўм</th>
                                            <th>Доллар</th>
                                            <th>Сўм</th>
                                            <th>Доллар</th>
                                            <th>Сўм</th>
                                            <th>Доллар</th>
                                            <th>Сўм</th>
                                            <th>Доллар</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modaloylar" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 97%; font-size: 14px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="people-list dz-scroll">
                            <table class="table table-bordered table-responsive-sm text-center align-middle ">
                                <thead>
                                    <tr class="text-bold text-primary align-middle">
                                        <th rowspan="2">ID</th>
                                        <th rowspan="2">Ойлар</th>
                                        <th colspan="2">Ой бошига</th>
                                        <th colspan="2">Кирим</th>
                                        <th colspan="2">Қайтарилди</th>
                                        <th colspan="2">Чиқим</th>
                                        <th colspan="2">Ой охирига</th>
                                    </tr>
                                    <tr class="text-bold text-primary align-middle">
                                        <th>Сўм</th>
                                        <th>Доллар</th>
                                        <th>Сўм</th>
                                        <th>Доллар</th>
                                        <th>Сўм</th>
                                        <th>Доллар</th>
                                        <th>Сўм</th>
                                        <th>Доллар</th>
                                        <th>Сўм</th>
                                        <th>Доллар</th>
                                    </tr>
                                </thead>
                                <tbody id="modalshow">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal"><i
                                class="flaticon-381-exit"></i> Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="modalfil" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 97%; font-size: 14px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title-filial" class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="people-list dz-scroll" id="modalshowfil">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal"><i
                                class="flaticon-381-exit"></i> Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="modalkunlar" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 97%; font-size: 14px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title-filial-kunlar" class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="people-list dz-scroll">
                            <table class="table table-bordered table-responsive-sm text-center align-middle ">
                                <thead>
                                    <tr class="text-bold text-primary align-middle">
                                        <th rowspan="2">ID</th>
                                        <th rowspan="2">Кунлар</th>
                                        <th colspan="2">Сўм</th>
                                        <th colspan="2">Доллар</th>
                                    </tr>
                                    <tr class="text-bold text-primary align-middle">
                                        <th>Сони</th>
                                        <th>Суммаси</th>
                                        <th>Сони</th>
                                        <th>Суммаси</th>
                                    </tr>
                                </thead>
                                <tbody id="modalshowfilkun">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal"><i
                                class="flaticon-381-exit"></i> Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="modalkunlarname" class="modal fade bd-example-modal-lg" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 97%; font-size: 14px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title-filial-kunlar-name" class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="people-list dz-scroll">
                            <table class="table table-bordered table-responsive-sm text-center align-middle ">
                                <thead>
                                    <tr class="text-bold text-primary align-middle">
                                        <th rowspan="2">ID</th>
                                        <th rowspan="2">Куни</th>
                                        <th rowspan="2">Товар номи</th>
                                        <th rowspan="2">Валюта</th>
                                        <th colspan="2">Товар</th>
                                        <th rowspan="2">Холати</th>
                                    </tr>
                                    <tr class="text-bold text-primary align-middle">
                                        <th>Сони</th>
                                        <th>Суммаси</th>
                                    </tr>
                                </thead>
                                <tbody id="modalshowfilkunmane">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal"><i
                                class="flaticon-381-exit"></i> Қайтиш</button>
                    </div>
                </div>
            </div>
        </div>




        <script src="/vendor/global/global.min.js"></script>
        <script>

            function tabyuklash() {
                $.ajax({
                    url: "{{ route('xisobottaminot.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tab1').html(data);
                    }
                });
            }

            $(document).ready(function() {
                tabyuklash();
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })

            $(document).on('click', '#modalgamurojat', function() {
                var id = $(this).data('id');
                var pas_name = $(this).data('pas_name');
                $('#modaloylar').modal('show');
                $('.modal-title').html(id + ' -> ' + pas_name);
                $.ajax({
                    url: "{{ route('xisobottaminot.index') }}/" + id,
                    method: "GET",
                    data: {
                        id: id,
                        pas_name: pas_name
                    },
                    success: function(data) {
                        $("#modalshow").html(data);
                    }
                })
            })


            $(document).on('click', '#modalgamurojatfil', function() {
                var pas_id = $(this).data('pas_id');
                var xis_oy = $(this).data('xis_oy');
                var pash_name = $(this).data('pash_name');
                var du2 = $(this).data('du2');
                $('#modalfil').modal('show');
                $('#modal-title-filial').html(pas_id + ' -> ' + pash_name + ' -> ' + du2);
                $.ajax({
                    url: "{{ route('xisobottaminot.store') }}",
                    method: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        pas_id: pas_id,
                        xis_oy: xis_oy,
                        pash_name: pash_name,
                        du2: du2,
                    },
                    success: function(data) {
                        $("#modalshowfil").html(data);
                    }
                })

            });


            $(document).on('click', '#modalkunlarfil', function() {
                var rfilia = $(this).data('rfilia');
                var fil_name = $(this).data('fil_name');
                var pas_id = $(this).data('pas_id');
                var name_pas = $(this).data('name_pas');
                var tek_oy = $(this).data('tek_oy');
                var xis_oy = $(this).data('xis_oy');

                $('#modalkunlar').modal('show');
                $('#modal-title-filial-kunlar').html(pas_id + ' -> ' + tek_oy + ' -> ' + name_pas + ' -> ' + fil_name);
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                $.ajax({
                    url: "{{ route('storekunlar') }}",
                    method: "POST",
                    data: {
                        _token: csrf,
                        rfilia: rfilia,
                        pas_id: pas_id,
                        name_pas: name_pas,
                        tek_oy: tek_oy,
                        xis_oy: xis_oy,
                        fil_name: fil_name
                    },
                    success: function(data) {
                        $('#modalshowfilkun').html(data);
                    }
                })

            });


            $(document).on('click', '#mnomanom', function() {
                var rfilia = $(this).data('rfilia');
                var fil_name = $(this).data('fil_name');
                var pas_id = $(this).data('pas_id');
                var name_pas = $(this).data('name_pas');
                var tek_oy = $(this).data('tek_oy');
                var xis_oy = $(this).data('xis_oy');
                var kuni = $(this).data('kuni');

                $('#modalkunlarname').modal('show');
                $('#modal-title-filial-kunlar-name').html(pas_id + ' -> ' + tek_oy + ' -> ' + name_pas + ' -> ' +
                    fil_name +
                    ' -> ' + kuni);
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                $.ajax({
                    url: "{{ route('storename') }}",
                    method: "POST",
                    data: {
                        _token: csrf,
                        rfilia: rfilia,
                        pas_id: pas_id,
                        name_pas: name_pas,
                        tek_oy: tek_oy,
                        xis_oy: xis_oy,
                        fil_name: fil_name,
                        kuni: kuni
                    },
                    success: function(data) {
                        $('#modalshowfilkunmane').html(data);
                    }
                })

            });
        </script>
    @endsection
