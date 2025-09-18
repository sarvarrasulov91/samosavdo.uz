@extends('layouts.almas_site')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Брендларни рўйхатга олиш бўлими
                    </h5>
                </li>
            </ol>
        </div>

        <div class="container-fluid ">
            <div class="row">
                <div class="col-3">
                    <div class="card h-auto">
                        <div class="card-header">
                            <h4 class="heading mb-0 text-primary">Янги бренд қўшиш </h4>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <form action="{{ route('brend.store') }}" method="POST">
                                    @csrf
    
                                    <div class="p-2">
                                        <label>Товар брендини киритинг
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="tovarbrendi" class="form-control"
                                            placeholder="Товар бренди.." value="{{ old('tovarbrendi') }}">
                                        @error('tovarbrendi')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="submit" id="adduser"
                                            class="btn btn-primary btn-submit">Сақлаш</button>
                                        <a href="{{ route('model.index') }}" class="btn btn-danger"> Қайтиш </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card h-auto">
                        <div class="card-header">
                            <h4 class="heading mb-0 text-primary"> Брендлар рўйхати </h4>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <table class="table table-bordered table-responsive-sm text-center align-middle ">
                                    <thead>
                                        <tr class="text-bold text-primary">
                                            <th>ID</th>
                                            <th>Куни</th>
                                            <th>Бренд номи</th>
                                            <th>Таҳрирлаш</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tab1">
                                        @foreach ($brend as $brend)
                                            <tr>
                                                <td>{{ $brend->id }}</td>
                                                <td>{{ date('d.m.Y', strtotime($brend->created_at)) }}</td>
                                                <td>{{ $brend->brend_name }}</td>
                                                <td>
                                                    <a href="" class="btn btn-primary btn-sm me-2 turedit"
                                                        data-id="{{ $brend->id }}"
                                                        data-brendname="{{ $brend->brend_name }}"
                                                        data-bs-toggle="modal" data-bs-target="#brendedit">Тахрирлаш</a>
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



        <!-- Modal -->
        <div class="modal fade" id="brendedit">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Товар брендини тахрирлаш</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="brendupdate" action="" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="p-2">
                                <label>Товар брендини киритинг
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="tovarbrendi" id="tovarbrendi" class="form-control"
                                    placeholder="Товар тури.." required>
                            </div>



                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="saqlash">Сақлаш</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Қайтиш</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




        <script src="/vendor/jquery/jquery-3.7.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.turedit').on('click', function() {
                    var id = $(this).data('id');
    
                    var brend_name = $(this).data('brendname');
                    var url = $(this).data('url');


                    $('#tovarbrendi').val(brend_name);
                    $('#brendupdate').attr('action', `{{ route('brend.index') }}/${id}`);

                });

                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })
        </script>
        @if (session('message'))
            <script>
                alert("{{ session('message') }}")
            </script>
        @endif

        <script src="/vendor/global/global.min.js"></script>
    @endsection
