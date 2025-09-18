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
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">Мижозлар маълумотларини ўзгартириш</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item px-2" role="presentation">
                                        <a href="{{route('clients.index')}}" class="btn btn-warning btn-sm ms-2"> Ортга қайтиш</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" style="overflow: auto;" id="tabpros">
                                <form id="pas_add" method="POST" action="{{ route('clients.update', ['client'=>$client->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-3">
                                            <label>Мижоз фамиляси
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="text" name="last_name" value="{{$client->last_name}}"
                                                placeholder="Фамилясини киритинг" pattern="[A-Za-z'`\s]*" title="Faqat lotin harflar" required />
                                            <span id="famil_error" class="text-danger error-text"></span>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ism">Мижоз исми
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="text" name="first_name" placeholder="Исмини киритинг" value="{{$client->first_name}}"
                                                pattern="[A-Za-z'`\s]*" title="Faqat lotin harflar" required />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="sharif">Мижоз шарифи
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="text" name="middle_name" value="{{$client->middle_name}}"
                                                placeholder="Шарифини киритинг" pattern="[A-Za-z'`\s]*" title="Faqat lotin harflar" required />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="tSana">Мижоз туғилган санаси
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="date" name="t_sana" value="{{$client->t_sana}}" required /> 
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="pSeriya">Паспорт серия рақам
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input class="form-control" style="width: 30%;" type="text" name="p_seriya" value="{{substr($client->passport_sn, 0,2)}}"
                                                    onkeyup="lettersOnly(this)" id="pSeriya" minlength="2" maxlength="2"
                                                    placeholder="AA" required />
                                                <input class="form-control" style="width: 70%;" type="text" name="p_nomer" value="{{substr($client->passport_sn, 2)}}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                    id="pNomer" minlength="7" maxlength="7" placeholder="1234567" required />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="passport_date">Паспорт берилган санаси
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="date" name="passport_date" id="passport_date" value="{{$client->passport_date}}" required /> 
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="passport_date">Паспорт ИИБ
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control" name="passport_iib" id="passport_iib" style="width: 100%"
                                                required>
                                                <option value="{{$client->viloyat->name_uz.' - '.$client->tuman->name_uz . ' ИИБ'}}">{{$client->viloyat->name_uz.' - '.$client->tuman->name_uz . ' ИИБ'}}</option>
                                                @foreach ($tuman as $tumanlar)
                                                    <option
                                                        value="{{ $tumanlar->viloyat->name_uz . ' - ' . $tumanlar->name_uz . ' ИИБ' }}">
                                                        {{ $tumanlar->viloyat->name_uz . ' - ' . $tumanlar->name_uz . ' ИИБ' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="jshshir">Паспорт ЖШШИР
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="number" value="{{$client->pinfl}}"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                name="pinfl" id="pinfl" minlength="14" maxlength="14"
                                                max="99999999999999" min="10000000000000" placeholder="ЖШШИРни киритинг" 
                                                required />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="tuman">Вилоят
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control" name="viloyat" id="viloyat" style="width: 100%"
                                                required>
                                                <option value="{{$client->viloyat_id}}">{{$client->viloyat->name_uz}}</option>
                                                @foreach ($viloyat as $viloyatlar)
                                                    <option value="{{ $viloyatlar->id }}">
                                                        {{ $viloyatlar->name_uz }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="tuman">Мижоз яшаш туман
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control" name="tuman" id="tuman" style="width: 100%"
                                                required>
                                                <option value="{{$client->tuman_id}}">{{$client->tuman->name_uz}}</option>
                                               
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="mfy">Мижоз яшаш МФЙ
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control" name="mfy" id="mfy" style="width: 100%"
                                                required>
                                                <option value="{{$client->mfy_id}}"> {{ $client->mfy->name_uz }}</option>
                                            </select>
        
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="manzil">Мижоз манзили
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="text" name="manzil" value="{{$client->manzil}}"
                                                placeholder="Манзил киритинг" required />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ishTuman">Иш жой туман
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control" name="ish_tuman" id="ish_tuman" style="width: 100%"
                                                required>
                                                <option value="{{$client->ish_tumanid}}" > {{$client->tuman->name_uz}} </option>
                                                
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ishJoy">Иш жойи
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control" name="ish_joy" id="ish_joy" > {{$client->ish_joy}}
                                                <option value="{{$client->ish_joy}}">{{$client->ish_joy}}</option>
                                                @foreach ($ishJoy as $ishJoylar)
                                                    <option value="{{ $ishJoylar->ish_joy_name }}">
                                                        {{ $ishJoylar->ish_joy_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ishTashkiloti">Ташкилот номи
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="text" name="ish_tashkiloti" id="ish_tashkiloti" value="{{$client->ish_tashkiloti}}"
                                            placeholder="Ташкилот номини киритинг" required />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="kasb">Мижоз касби (Лавозими)
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="text" name="kasb" value="{{$client->kasb}}"
                                                placeholder="Касбини киритинг" required />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="oylik">Мижоз ойлиги
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="text" name="oylik" value="{{$client->maosh}}"
                                                placeholder="Ойлигини киритинг" required />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="mobileNomer">Мобиле номер
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="number" name="mobile_nomer" value="{{$client->phone}}"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                minlength="9" maxlength="9" min="100000000" max="999999999" id="mobileNomer"
                                                placeholder="991234567" required /> 
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="qoshimchaNomer">Мобиле номер
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="text" name="qoshimcha_nomer" value="{{$client->extra_phone}}"
                                                id="qoshimchaNomer" placeholder="Қўшимча номерлар киритинг" required />
                                        </div>
                                        <div class="col-12 text-center">
                                            <button class="btn btn-success" type="submit" name="mijoz_insert">Тасдиқлаш</button>
                                        </div>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       

        <script src="/vendor/global/global.min.js"></script>
        <script>

            $('#tuman').change(function() {
                let id = $(this).val();
                $.ajax({
                    url: "{{ route('clients.index') }}/" + id,
                    method: "get",
                    data: {
                        id: id,
                    },
                    success: function(res) {
                        $('#mfy').html(res)
                    }
                })
            })

            $('#viloyat').change(function() {
                let id2 = $(this).val();
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                $.ajax({
                    url: "{{ route('clients.index') }}/" + id2,
                    method: "DELETE",
                    data: {
                        id2: id2,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        $('#tuman').html(res)
                        $('#ish_tuman').html(res)
                        $('#tuman').change();
                    }
                })
            })

            function lettersOnly(input) {
                var regex = /[^A-Z]/gi;
                input.value = input.value.replace(regex, "");
            }

            $(document).ready(function () {
            //change selectboxes to selectize mode to be searchable
            $("select").select2();
            });     

        </script>
    @endsection
