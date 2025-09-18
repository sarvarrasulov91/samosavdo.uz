@extends('layouts.almas_site')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<div class="content-body">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li>
                <h5 class="bc-title">Янги фойдаланувчи қўшиш ойнаси</h5>
            </li>
        </ol>
    </div>
    <div class="container-fluid">
        <div class="row justify-center">
            <div class="col-xl-4">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div>
                        <label>Филиал
                            <span class="text-danger">*</span>
                        </label>
                        <select name="filial" class="form-control" aria-label="select example" >
                            <option value="">Филиал...</option>
                            @foreach ($filial as $filialname)
                            <option {{ old('filial')==$filialname->id?"selected":"" }} value="{{ $filialname->id }}"> {{ $filialname->fil_name }}</option>
                            @endforeach
                        </select>
                        @error('filial')
                            <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label>Лавозим
                            <span class="text-danger">*</span>
                        </label>
                        <select name="lavozim" class="form-control" aria-label="select example" >
                            <option value="">Лавозим...</option>
                            @foreach ($lavozimlar as $lavozim)
                            <option {{ old('lavozim')==$lavozim->id?"selected":"" }} value="{{ $lavozim->id }}">{{ $lavozim->lavozim }} </option>
                            @endforeach
                        </select>
                        @error('lavozim')
                            <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label>ФИО
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" id="validationCustom01" placeholder="ФИО.." value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label>email
                            <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" class="form-control" placeholder="email.." value="{{ old('email') }}">
                        @error('email')
                            <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label>Парол
                            <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password" class="form-control" placeholder="Парол..." value="{{ old('password') }}">
                        @error('password')
                            <span class="text-danger error-text">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" id="adduser" class="btn btn-primary btn-submit">Сақлаш</button>
                        <a href="{{ route('user.index')}}" class="btn btn-danger"> Қайтиш </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/vendor/global/global.min.js"></script>
@endsection
