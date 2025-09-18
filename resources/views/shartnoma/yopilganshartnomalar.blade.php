@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Ёпилган ва ўчирилган
                        шартномаларни кўриб олиш бўлими
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
                                        Ёпилган шартномалар рўйхати
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

        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash() {
                var id = $('#filial').val();
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                if (id > 0) {
                    $.ajax({
                        url: "{{ route('yopilganshartnomalar.index') }}/" + id,
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
            })
        </script>
    @endsection
