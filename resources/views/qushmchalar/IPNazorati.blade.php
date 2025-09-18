@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Дастурга кирган ёки уриниб курган IP ларни куриш бўлими</h5>
                </li>
            </ol>
        </div>

        <div class="container-fluid ">
            <div class="row">
                <div class="col-6 md-6">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Бугунги санада</h5>
                                </li>
                            </ol>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" style="overflow: auto;">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center text-bold text-primary align-middle">
                                            <th>ID</th>
                                            <th>IP</th>
                                            <th>Қурилма</th>
                                            <th>Куни</th>
                                            <th>Логин</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($ipnazoratikun as $ipnazorati)
                                            <tr class="text-center align-middle">
                                                <td>{{ $ipnazorati->id }}</td>
                                                <td>{{ $ipnazorati->ip_manzili }}</td>
                                                @if (strpos($ipnazorati->qanday_qurilma, 'Windows') !== false)
                                                    <td>Windows</td>
                                                @elseif (strpos($ipnazorati->qanday_qurilma, 'Macintosh') !== false)
                                                    <td>Macintosh</td>
                                                @elseif (strpos($ipnazorati->qanday_qurilma, 'Android') !== false || strpos($ipnazorati->qanday_qurilma, 'iPhone') !== false || strpos($ipnazorati->qanday_qurilma, 'iPad') !== false)
                                                    <td>Телефон ёки Планшет</td>
                                                @else
                                                    <td>Бошқа қурилма</td>
                                                @endif
                                                <td>{{ date('d.m.Y H:i:s', strtotime($ipnazorati->created_at)) }}</td>
                                                <td>{{ $ipnazorati->login_name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 md-6">
                    <div class="card h-auto">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Ой бошидан</h5>
                                </li>
                            </ol>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabpros" style="overflow: auto;">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center text-bold text-primary align-middle">
                                            <th>ID</th>
                                            <th>IP</th>
                                            <th>Қурилма</th>
                                            <th>Куни</th>
                                            <th>Логин</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($ipnazoratioy as $ipnazorati)
                                            <tr class="text-center align-middle">
                                                <td>{{ $ipnazorati->id }}</td>
                                                <td>{{ $ipnazorati->ip_manzili }}</td>
                                                @if (strpos($ipnazorati->qanday_qurilma, 'Windows') !== false)
                                                    <td>Windows</td>
                                                @elseif (strpos($ipnazorati->qanday_qurilma, 'Macintosh') !== false)
                                                    <td>Macintosh</td>
                                                @elseif (strpos($ipnazorati->qanday_qurilma, 'Android') !== false || strpos($ipnazorati->qanday_qurilma, 'iPhone') !== false || strpos($ipnazorati->qanday_qurilma, 'iPad') !== false)
                                                    <td>Телефон ёки Планшет</td>
                                                @else
                                                    <td>Бошқа қурилма</td>
                                                @endif
                                                <td>{{ date('d.m.Y H:i:s', strtotime($ipnazorati->created_at)) }}</td>
                                                <td>{{ $ipnazorati->login_name }}</td>
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



        <script src="/vendor/jquery/jquery-3.7.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })
        </script>
        <script src="/vendor/global/global.min.js"></script>
    @endsection
