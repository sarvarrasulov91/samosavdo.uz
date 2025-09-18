@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Кунлик хисоботларини олиш бўлими
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
                                <div class="col-xl-3">
                                    <input type="date" name="boshkun" class="form-control form-control-sm" id="boshkun"
                                        placeholder=" ">
                                </div>
                                <div class="col-xl-3">
                                    <input type="date" name="yakunkun" class="form-control form-control-sm"
                                        id="yakunkun" placeholder=" ">
                                </div>
                                <div id="select_div" class="col-xl-4">
                                    <select id="filial" name="filial" class="multi-select form-control">
                                        <option value="">Филиал...</option>
                                        @foreach ($filial as $filia)
                                            <option value="{{ $filia->id }}">{{ $filia->fil_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <style>
                                        #select_div {
                                            width: 150px !important;
                                        }
                                    </style>

                                </div>
                                <div class="col-2">
                                    <button id="saqlash" class="btn btn-primary btn-xs"> Тасдиқлаш </button>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <div id="tabpros">
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

                $("#filial").select2({
                    placeholder: "Филиал...",
                });


                $("#yakunkun").val(new Date().toISOString().substring(0, 10));
                $("#boshkun").val(new Date().toISOString().substring(0, 8) + '01');





                $('#saqlash').on('click', function() {
                    var boshkun = $('#boshkun').val();
                    var yakunkun = $('#yakunkun').val();
                    var filial = $('#filial').val();
                    if (boshkun > yakunkun) {
                        alert("Кунни киритишла хатолик.!!!");
                    } else {
                        $.ajax({
                            url: "{{ route('kunlik.store') }}",
                            method: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                boshkun: boshkun,
                                yakunkun: yakunkun,
                                filial: filial,
                            },
                            success: function(data) {
                                $("#tabpros").html(data);
                            }
                        })
                    }
                })

            })
        </script>
    @endsection
