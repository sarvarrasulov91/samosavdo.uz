@extends('layouts.almas_site')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="content-body">
    <div class="page-titles" style="justify-content:center !important">
        <ol class="breadcrumb">
            <li>
                <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">Турларни рўйхатга олиш бўлими
                </h5>
            </li>
        </ol>
    </div>

    <div class="container-fluid ">
        <div class="row">
            <div class="col-3">
                <div class="card h-auto">
                    <div class="card-header">
                        <h4 class="heading mb-0 text-primary">Янги тур қўшиш </h4>
                    </div>
                    <div class="card-body">
                        <div class="people-list dz-scroll">
                            <form action="{{ route('tur.store') }}" method="POST">
                                @csrf
                                <div class="p-2">
                                    <label>Транспорт харажати танланг
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="trans_harajatid" class="form-control" aria-label="select example">
                                        <option value="">Харажати...</option>
                                        @foreach ($transport as $transpor)
                                        <option {{ old('trans_harajatid')==$transpor->id?"selected":"" }} value="{{ $transpor->id }}">{{ $transpor->tr_har_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('trans_harajatid')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="p-2">
                                    <label>Наценкани танланг
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="natsenka" class="form-control" aria-label="select example">
                                        <option value="">Наценк...</option>
                                        @foreach ($natsenka1 as $natsenka)
                                            <option {{ old('natsenka') == $natsenka->id ? 'selected' : '' }}
                                                value="{{ $natsenka->id }}">{{ $natsenka->natsen_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('natsenka')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="p-2">
                                    <label>Товар турини киритинг
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="tovarturi" class="form-control" placeholder="Товар тури.." value="{{ old('tovarturi') }}">
                                    @error('tovarturi')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" id="adduser" class="btn btn-primary btn-submit">Сақлаш</button>
                                    <a href="{{ route('model.index')}}" class="btn btn-danger"> Қайтиш </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card h-auto">
                    <div class="card-header">
                        <h4 class="heading mb-0 text-primary"> Турлар рўйхати </h4>
                    </div>
                    <div class="card-body">
                        <div class="people-list dz-scroll">
                            <table class="table table-bordered table-responsive-sm text-center align-middle ">
                                <thead>
                                    <tr class="text-bold text-primary">
                                        <th>ID</th>
                                        <th>Куни</th>
                                        <th>Тур номи</th>
                                        <th>Транспорт харажати</th>
                                        <th>Натсенка</th>
                                        <th>Таҳрирлаш</th>
                                    </tr>
                                </thead>
                                <tbody id="tab1">
                                    @foreach ($tur as $tur)
                                    <tr>
                                        <td>{{ $tur->id }}</td>
                                        <td>{{ date('d.m.Y', strtotime($tur->created_at)) }}</td>
                                        <td>{{ $tur->tur_name }}</td>
                                        <td>{{ $tur->transport->tars_har }}%</td>
                                        <td>{{ $tur->natsenka_id }}%</td>
                                        <td>
                                            <a href="" class="btn btn-primary btn-sm me-2 turedit" 
                                            data-id="{{ $tur->id }}" 
                                            data-turname="{{ $tur->tur_name }}" 
                                            data-trharajat="{{ $tur->trans_harajatid }}" 
                                            data-natsenk="{{ $tur->natsenka->natsenkaid }}"
                                            data-bs-toggle="modal" data-bs-target="#turedit">Тахрирлаш</a>
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
    <div class="modal fade" id="turedit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Товар турини тахрирлаш</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="turupdate" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="p-2">
                            <label>Транспорт харажати танланг
                                <span class="text-danger">*</span>
                            </label>
                            <select name="edittrans_harajatid" id="trans_harajatid" class="form-control" aria-label="select example" required>
                                <option value="">Харажати...</option>
                                @foreach ($transport as $transpor)
                                <option {{ old('edittrans_harajatid')==$transpor->id ? "selected":"" }} value="{{ $transpor->id }}">{{ $transpor->tr_har_name }}
                                </option>
                                @endforeach
                            </select>
                            @error('edittrans_harajatid')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="p-2">
                            <label>Натсенкани танланг
                                <span class="text-danger">*</span>
                            </label>
                             <select name="editnatsenka" id="editnatsenka" class="form-control" aria-label="select example">
                                <option value="">Наценк...</option>
                                @foreach ($natsenka1 as $natsenk)
                                    <option value="{{ $natsenk->id }}">{{ $natsenk->natsen_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('editnatsenka')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="p-2">
                            <label>Товар турини киритинг
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="edittovarturi" id="tovarturi" class="form-control" placeholder="Товар тури.." value="{{ old('edittovarturi') }}" required>
                            @error('edittovarturi')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
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
                var trans_harajatid = $(this).data('trharajat');
                var natsenk = $(this).data('natsenk');
                var tur_name = $(this).data('turname');
                var url = $(this).data('url');
                
                $('#trans_harajatid').val(trans_harajatid);
                $('#editnatsenka').val(natsenk);
                $('#tovarturi').val(tur_name);
                $('#turupdate').attr('action', `{{ route('tur.index') }}/${id}`);

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
