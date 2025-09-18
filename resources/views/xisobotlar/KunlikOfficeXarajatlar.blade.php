@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Офис кунлик харажатлар хисоботларини олиш бўлими
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
                                <div class="col-xl-4 select_div">
                                    <input type="date" name="boshkun" class="form-control form-control-sm text-center" id="boshkun"
                                        placeholder=" ">
                                </div>
                                <div class="col-xl-4 select_div">
                                    <input type="date" name="yakunkun" class="form-control form-control-sm text-center"
                                        id="yakunkun" placeholder=" ">
                                </div>
                                <style>
                                    #select_div {
                                        width: 150px !important;
                                    }
                                </style>
                                <div class="col-2">
                                    <button id="saqlash" class="btn btn-primary btn-xs"> Тасдиқлаш </button>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <div id="tabpros">
                                    <table class="table table-bordered table-responsive-sm text-center align-middle">
                                        <thead>
                                            <tr class="text-bold text-primary align-middle">
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">Харажатлар</th>
                                                <th colspan="2">Нақд</th>
                                                <th colspan="2">Пластик</th>
                                                <th colspan="2">Х-р</th>
                                                <th colspan="2">Сlick</th>
                                                <th colspan="2">Жами</th>
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
        </div>

        <script src="/vendor/global/global.min.js"></script>
        <script>

            $(document).ready(function() {
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })

                $("#yakunkun").val(new Date().toISOString().substring(0, 10));
                $("#boshkun").val(new Date().toISOString().substring(0, 8) + '01');

                $('#saqlash').on('click', function() {
                    var boshkun = $('#boshkun').val();
                    var yakunkun = $('#yakunkun').val();
                    var filial = $('#filial').val();
                    if (boshkun > yakunkun && filial>0) {
                        alert("Кунни киритишла хатолик ёки филиални танланг !!!");
                    } else {
                        $.ajax({
                            url: "{{ route('KunlikOfficeXarajatlar.store') }}",
                            method: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                boshkun: boshkun,
                                yakunkun: yakunkun,
                                filial: filial,
                            },
                            success: function(data) {
                                $("#tab1").html(data);
                            }
                        })
                    }
                })

            })
        </script>
    @endsection
