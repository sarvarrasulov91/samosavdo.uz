@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Шартномалар тахлилини кўриб олиш бўлими
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
                            </li>
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">
                                        Шартномалар хисоботи {{ $du2 }} холатига
                                    </h5>
                                </li>
                            </ol>
                            <li class="nav-item" role="presentation">
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


        <div id="modaloylar" class="modal fade bd-example-modal-lg" data-bs-backdrop="static" data-bs-keyboard="true"
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
                                        <th rowspan="2">Ойлар</th>
                                        <th colspan="2">Ой бошига</th>
                                        <th colspan="2">Тузилди</th>
                                        <th colspan="2">Ёпилди</th>
                                        <th colspan="2">Қўшилди</th>
                                        <th colspan="2">Камайди</th>
                                        <th colspan="2">Ой охирига</th>
                                    </tr>
                                    <tr class="text-bold text-primary align-middle">
                                        <th>Сони</th>
                                        <th>Суммаси</th>
                                        <th>Сони</th>
                                        <th>Суммаси</th>
                                        <th>Сони</th>
                                        <th>Суммаси</th>
                                        <th>Сони</th>
                                        <th>Суммаси</th>
                                        <th>Сони</th>
                                        <th>Суммаси</th>
                                        <th>Сони</th>
                                        <th>Суммаси</th>
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

        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash() {
                $.ajax({
                    url: "{{ route('SHartTahlil.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
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


                $(document).on('click', '#modalfil', function() {
                    var id = $(this).data('filial_id');
                    var filial_name = $(this).data('filial_name');
                    $('#modaloylar').modal('show');
                    $('.modal-title').html(id + ' -> ' + filial_name);
                    $.ajax({
                        url: "{{ route('SHartTahlil.index') }}/" + id,
                        method: "GET",
                        data: {
                            id: id,
                        },
                        success: function(data) {
                            $('#modalshow').html(data);

                        }
                    })
                })
            })
        </script>
    @endsection
