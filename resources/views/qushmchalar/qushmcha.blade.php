@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">дастурни бошқариш бўлими
                    </h5>
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
                                    <h5 class="bc-title text-primary">
                                        Рўйхатлар
                                    </h5>
                                </li>
                            </ol>
                            <li class="nav-item" role="presentation">
                            </li>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <div id="tabprosd">
                                    <h5 class=" text-center text-uppercase" style="color: RoyalBlue;">Валюта курсини
                                        бошқариш</h5>
                                    <table class="table table-bordered text-center align-middle ">
                                        <thead>
                                            <tr class="text-bold text-primary align-middle">
                                                <th>ID</th>
                                                <th>Номи</th>
                                                <th>Валюта Курси</th>
                                                <th>Товар курси</th>
                                                <th>Ф.И.О</th>
                                                <th>Куни</th>
                                                <th>Ўзгартириш</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tab1">
                                            @foreach ($valyuta as $valyut)
                                                <tr class="align-middle">
                                                    <td>{{ $valyut->id }}</td>
                                                    <td>{{ $valyut->valyuta__nomi }}</td>
                                                    <td>{{ number_format($valyut->valyuta_narhi, 0, ',', ' ') }}</td>
                                                    <td>{{ number_format($valyut->tovar_kurs, 0, ',', ' ') }}</td>
                                                    <td>{{ $valyut->name }}</td>
                                                    <td>{{ date('d.m.Y h:i:s', strtotime($valyut->updated_at)) }}</td>
                                                    <td>
                                                        <a onclick="kursuzgar(' {{ $valyut->id }} ',' {{ $valyut->valyuta__nomi }}')"
                                                            class="btn btn-outline-danger btn-xxs"><i
                                                                class="fa fa-pencil"></i></a>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="tabprosoy">
                                    <h5 class=" text-center text-uppercase" style="color: RoyalBlue;">Янги ойга ўтиш</h5>
                                    <table class="table table-bordered text-center align-middle ">
                                        <thead>
                                            <tr class="text-bold text-primary align-middle">
                                                <th>ИД</th>
                                                <th>Хисобот<br>ойи</th>
                                                <th>Тариф</th>
                                                <th>Ўтган<br>вақти</th>
                                                <th>Холати</th>
                                                <th>Ф.И.О</th>
                                                <th>Ўзгартириш</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tab1">
                                            @foreach ($xis_oy as $xis_o)
                                                <tr class="align-middle">
                                                    <td>{{ $xis_o->id }}</td>
                                                    <td>{{ date('d.m.Y', strtotime($xis_o->xis_oy)) }}</td>
                                                    <td>{{ $xis_o->foiz }}</td>
                                                    <td>{{ date('d.m.Y h:i:s', strtotime($xis_o->updated_at)) }}</td>
                                                    <td> Актив </td>
                                                    <td>{{ $xis_o->name }}</td>
                                                    <td>
                                                        @if ($loop->first)
                                                        <a onclick="perexod('{{ $xis_o->xis_oy }}','{{ $xis_o->id }}')"
                                                            class="btn btn-outline-danger btn-xxs"><i
                                                                class="fa fa-pencil"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
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
            })

            function kursuzgar(id, m2) {
                var uzid = prompt("Dollar kursini kiriting:");
                if (uzid) {
                    var kursid = prompt("Tovar tan narhi uchun Dollar kursini kiriting:");
                    $.ajax({
                        url: "{{ route('bashqaruv.store') }}",
                        method: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            uzid: uzid,
                            kursid: kursid
                        },
                        success: function(data) {
                            alert(data);
                            window.location.reload(1);
                        }
                    })
                }
            }

            function perexod(oy, id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var tarif = parseInt(prompt("Yangi oy uchun Foizni kiriting:"));
                if (tarif) {
                    $.ajax({
                        url: "{{ route('bashqaruv.index') }}/" + id,
                        method: "PUT",
                        data: {
                            id: id,
                            oy: oy,
                            tarif: tarif,
                        },
                        success: function(data) {
                            alert(data);
                            window.location.reload(1);
                        }
                    })
                }
            }
        </script>
    @endsection
