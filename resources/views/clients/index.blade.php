@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @if (session('message'))
        <script>
            alert("{{ session('message') }}")
        </script>
    @endif
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Янги мижозларни рўйхатга олиш
                        бўлими</h5>
                </li>
            </ol>
        </div>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="card h-auto">
                        <div class="page-titles">
                            
                            <div>
                                <ol class="breadcrumb">
                                    <li>
                                        <h5 class="bc-title text-primary">Мижозлар рўйхати</h5>
                                    </li>
                                </ol>
                            </div>
                            <div style="width: 40%;">
                                <form action="{{route('clients.index')}}" method="get">

                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" name="search" placeholder="Familiyasi / Ismi / Passport / PINFL / Telefon" value="{{request()->query('search')}}">
                                        <button type="submit" class="input-group-text btn btn-primary btn-sm" >Search</button>
                                    </div>
                                </form>
                            </div>
                            <div>
                                <li class="nav-item px-2" role="presentation">
                                    <a href="{{route('clients.create')}}" class="btn btn-primary btn-sm ms-2">+ Янги мижоз қўшиш</a>
                                </li>
                            </div>
                            <div>
                                <li class="nav-item" role="presentation">
                                    <button id="btnExportexcel" class="btn btn-primary btn-sm ms-2"><i
                                            class="fa fa-file-excel"></i> Excel </button>
                            </li>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" style="overflow: auto;" id="tabpros">
                                <table class="table table-hover table-bordered table-responsive-sm text-center align-middle">
                                    <thead>
                                        <tr  class="text-bold text-primary align-middle">
                                            <th>№</th>
                                            <th>Сана</th>
                                            <th>ФИО</th>
                                            <th>Туғ-сана</th>
                                            <th>Паспорт</th>
                                            <th>Телефон</th>
                                            <th>ПИНФЛ</th>
                                            <th>Иш жойи</th>
                                            <th>Иш ташкилоти</th>
                                            <th>Филиал</th>
                                            <th>Kўриш</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @forelse ($clients as $client)
                                        <tr class="{{$trrang = ($client->m_type == 2) ? "text-danger" : ""}}">
                                            <td>{{$client->id}}</td>
                                            <td>{{date('d.m.Y', strtotime($client->created_at))}}</td>
                                            <td  style="white-space: wrap; width: 25%;">{{$client->last_name .' '.$client->first_name .' '.$client->middle_name}}</td>
                                            <td>{{date('d.m.Y', strtotime($client->t_sana))}}</td>
                                            <td>{{$client->passport_sn}}</td>
                                            <td>{{$client->phone}}</td>
                                            <td>{{$client->pinfl}}</td>
                                            <td>{{$client->ish_joy}}</td>
                                            <td style="white-space: wrap; width: 25%;">{{$client->ish_tashkiloti}}</td>
                                            <td>{{$client->filial->fil_name}}</td>
                                            <td>
                                                <a href="{{ route('showClient', ['id' => $client->id]) }}">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15.7161 16.2234H8.49609" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M15.7161 12.0369H8.49609" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M11.2511 7.86011H8.49609" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9085 2.74982C15.9085 2.74982 8.23149 2.75382 8.21949 2.75382C5.45949 2.77082 3.75049 4.58682 3.75049 7.35682V16.5528C3.75049 19.3368 5.47249 21.1598 8.25649 21.1598C8.25649 21.1598 15.9325 21.1568 15.9455 21.1568C18.7055 21.1398 20.4155 19.3228 20.4155 16.5528V7.35682C20.4155 4.57282 18.6925 2.74982 15.9085 2.74982Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="12">
                                                Mijoz topilmadi!
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{$clients->appends(['search' => request()->query('search')])->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       

        <script src="/vendor/global/global.min.js"></script>
        <script>

            $("#qidirish").keyup(function() {
                var value = $(this).val().toLowerCase();
                $("#tab1 tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                })
            })
            
            $(function() {
                    $("#btnExportexcel").click(function() {
                        $("#tabpros").table2excel({
                            filename: "Mijozlar"
                        });
                    })
                });

        </script>
    @endsection
