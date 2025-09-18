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
                                    <h5 class="bc-title text-primary">Рўйхатга олинган мижозлар рўйхати</h5>
                                </li>
                            </ol>
                            <div class="d-flex align-items-center">
                                <ul class="nav nav-pills mix-chart-tab user-m-tabe" id="pills-tab" role="tablist">
                                    <li class="nav-item px-2" role="presentation">
                                        <!--<a href="" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal"-->
                                        <!--    data-bs-target="#pasravshik_add">+ Янги мижоз қўшиш</a>-->
                                    </li>
                                     <li class="nav-item" role="presentation">
                                        <button id="btnExportexcel" class="btn btn-primary btn-sm ms-2"><i
                                                class="fa fa-file-excel"></i> Excel </button>
                                </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll" id="tabpros" style="overflow: auto;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal pasravshik_add -->
        <div class="modal fade" id="pasravshik_add">
            <div class="modal-dialog modal-lg" style="max-width: 70%; font-size: 15px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Янги мижоз киритиш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            onclick="return window.location.reload(1)">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="pas_add" method="POST" action="{{ route('newmijoz.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Mijoz familiyasi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="famil" id="famil" placeholder="Familiyani kiriting" 
                                        pattern="[A-Za-z'`\s]*" title="Faqat lotin harflarifa ruxsat berilgan" required />
                                    <span id="famil_error" class="text-danger error-text"></span>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ism">Mijoz ismi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="ism" id="ism" placeholder="Ismini kiriting" 
                                        pattern="[A-Za-z'`\s]*" title="Faqat lotin harflarifa ruxsat berilgan" required />
                                    <span id="ism_error" class="text-danger error-text"></span>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="sharif">Mijoz sharifi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="sharif" id="sharif" placeholder="Sharifini kiriting" 
                                        pattern="[A-Za-z'`\s]*" title="Faqat lotin harflarifa ruxsat berilgan" required />
                                    <span id="sharif_error" class="text-danger error-text"></span>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="tSana">Мижоз туғилган санаси
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="date" name="t_sana" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pSeriya">Паспорт серия рақам
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input class="form-control" style="width: 30%;" type="text" name="p_seriya" pattern="[A-Z\s]+" title="Faqat Katta Lotin harflar"
                                            onkeyup="lettersOnly(this)" id="pSeriya" minlength="2" maxlength="2"
                                            placeholder="AA" required />
                                        <input class="form-control" style="width: 70%;" type="text" name="p_nomer"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                            id="pNomer" minlength="7" maxlength="7" placeholder="1234567" required />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pSana">Паспорт берилган санаси
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="date" name="p_sana" id="pSana" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pIib">Паспорт ИИБ
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="p_iib" id="pIib" style="width: 100%"
                                        required>
                                        <option value=""></option>
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
                                    <input class="form-control" type="number"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        name="jshshir" id="jshshir" minlength="14" maxlength="14"
                                        max="99999999999999" min="10000000000000" placeholder="ЖШШИРни киритинг"
                                        required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="tuman">Мижоз яшаш туман
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="tuman" id="tuman" style="width: 100%"
                                        required>
                                        <option value=""></option>
                                        @foreach ($tuman as $tumanlar)
                                            <option value="{{ $tumanlar->id }}">
                                                {{ $tumanlar->viloyat->name_uz . ' - ' . $tumanlar->name_uz }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="mfy">Мижоз яшаш МФЙ
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="mfy" id="mfy" style="width: 100%"
                                        required>
                                        <option value=""></option>
                                    </select>

                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="manzil">Мижоз манзили
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="manzil"
                                        placeholder="Манзил киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ishTuman">Иш жой туман
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="ish_tuman" id="ishTuman" style="width: 100%"
                                        required>
                                        <option value=""></option>
                                        @foreach ($tuman as $tumanlar)
                                            <option value="{{ $tumanlar->id }}">
                                                {{ $tumanlar->viloyat->name_uz . ' - ' . $tumanlar->name_uz }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ishJoy">Иш жойи
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="ish_joy" id="ish_joy">
                                        <option value="Мактаб">Мактаб</option>
                                        <option value="МТМ">МТМ</option>
                                        <option value="Тиббиёт">Тиббиёт</option>
                                        <option value="Давлат ташкилоти">Давлат ташкилоти</option>
                                        <option value="Пенсионер">Пенсионер</option>
                                        <option value="Ижтимоий нафақа">Ижтимоий нафақа</option>
                                        <option value="Бола пули">Бола пули</option>
                                        <option value="ЯТТ">ЯТТ</option>
                                        <option value="МЧЖ">МЧЖ</option>
                                        <option value="Бошкалар">Бошкалар</option>
                                    </select>
                                    <!--<input class="form-control" type="text" name="ish_joy"-->
                                    <!--    placeholder="Иш жойини киритинг" required />-->
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ishTashkiloti">Ташкилот номи
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="ish_tashkiloti" id="ish_tashkiloti"
                                    placeholder="Ташкилот номини киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="kasb">Мижоз касби (Лавозими)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="kasb"
                                        placeholder="Касбини киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="oylik">Мижоз ойлиги
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="oylik"
                                        placeholder="Ойлигини киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="mobileNomer">Мобиле номер
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="number" name="mobile_nomer"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        minlength="9" maxlength="9" min="100000000" max="999999999" id="mobileNomer"
                                        placeholder="991234567" required />
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label for="qoshimchaNomer">Мобиле номер
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="qoshimcha_nomer"
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

        --
        <!-- Modal -->
        <div class="modal fade" id="edit">
            <div class="modal-dialog modal-lg" style="max-width: 70%; font-size: 15px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Мижозларни тахрирлаш ойнаси</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"</button>
                    </div>
                    <div class="modal-body" id="EditModalBody">
                        <form method="POST" id="Editmijoz">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <input class="form-control" type="text" name="editid" id="editid" required
                                    readonly hidden>
                                <div class="col-3">
                                    <label>Мижоз фамиляси
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="famil" id="last_name"
                                        placeholder="Фамилясини киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ism">Мижоз исми
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="ism" id="first_name"
                                        placeholder="Исмини киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="sharif">Мижоз шарифи
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="sharif" id="middle_name"
                                        placeholder="Шарифини киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="tSana">Мижоз туғилган санаси
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="date" name="t_sana" id="t_sana" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pSeriya">Паспорт серия рақам
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input class="form-control" style="width: 30%;" type="text" name="p_seriya"
                                            onkeyup="lettersOnly(this)" id="pSeriya" minlength="2" maxlength="2"
                                            placeholder="AA" required />
                                        <input class="form-control" style="width: 70%;" type="text" name="p_nomer"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                            id="pNomer" minlength="7" maxlength="7" placeholder="1234567" required />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pSana">Паспорт берилган санаси
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="date" name="p_sana" id="passport_date"
                                        required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pIib">Паспорт ИИБ
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="p_iib" id="passport_iib" style="width: 100%"
                                        required>
                                        <option value=""></option>
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
                                    <input class="form-control" type="number" id="pinfl"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        name="jshshir" id="jshshir" minlength="14" maxlength="14"
                                        max="99999999999999" min="10000000000000" placeholder="ЖШШИРни киритинг"
                                        required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="tuman">Мижоз яшаш туман
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="tuman" id="tuman_id" style="width: 100%"
                                        required>
                                        <option value=""></option>
                                        @foreach ($tuman as $tumanlar)
                                            <option value="{{ $tumanlar->id }}">
                                                {{ $tumanlar->viloyat->name_uz . ' - ' . $tumanlar->name_uz }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="mfy">Мижоз яшаш МФЙ
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="mfy" id="mfy_id" style="width: 100%"
                                        required>
                                        @foreach ($mfy as $mfyname)
                                            <option value="{{ $mfyname->id }}">{{ $mfyname->name_uz }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="manzil">Мижоз манзили
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="manzil" id="manzil"
                                        placeholder="Манзил киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ishTuman">Иш жой туман
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="ish_tuman" id="ish_tumanid" style="width: 100%"
                                        required>
                                        <option value=""></option>
                                        @foreach ($tuman as $tumanlar)
                                            <option value="{{ $tumanlar->id }}">
                                                {{ $tumanlar->viloyat->name_uz . ' - ' . $tumanlar->name_uz }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ishJoy">Иш жойи (Ташкилоти)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="ish_joy" id="ish_joy">
                                        <option value="Мактаб">Мактаб</option>
                                        <option value="МТМ">МТМ</option>
                                        <option value="Тиббиёт">Тиббиёт</option>
                                        <option value="Давлат ташкилоти">Давлат ташкилоти</option>
                                        <option value="Пенсионер">Пенсионер</option>
                                        <option value="Ижтимоий нафақа">Ижтимоий нафақа</option>
                                        <option value="Бола пули">Бола пули</option>
                                        <option value="ЯТТ">ЯТТ</option>
                                        <option value="МЧЖ">МЧЖ</option>
                                        <option value="Бошкалар">Бошкалар</option>
                                    </select>
                                    <!--<input class="form-control" type="text" name="ish_joy" id="ish_joy"-->
                                    <!--    placeholder="Иш жойини киритинг" required />-->
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ishTashkiloti">Ташкилот номи
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="ish_tashkiloti" id="ish_tashkiloti"
                                    placeholder="Ташкилот номини киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="kasb">Мижоз касби (Лавозими)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="kasb" id="kasb"
                                        placeholder="Касбини киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="oylik">Мижоз ойлиги
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="oylik" id="maosh"
                                        placeholder="Ойлигини киритинг" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="mobileNomer">Мобиле номер
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="number" name="mobile_nomer" id="phone"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                        minlength="9" maxlength="9" min="100000000" max="999999999" id="mobileNomer"
                                        placeholder="991234567" required />
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label>Мобиле номер
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control" type="text" name="qoshimcha_nomer" id="extra_phone"
                                        placeholder="Қўшимча номерлар киритинг" required />
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

        <script src="/vendor/global/global.min.js"></script>
        <script>
            function tabyuklash() {
                $.ajax({
                    url: "{{ route('newmijoz.create') }}",
                    type: 'GET',
                    data: "",
                    success: function(data) {
                        $('#tabpros').html(data);
                    }
                });
            }
            
            $(function() {
                    $("#btnExportexcel").click(function() {
                        $("#tabpros").table2excel({
                            filename: "Mijozlar"
                        });
                    })
                });

            $(document).ready(function() {
                tabyuklash();

                $('#pIib').select2({
                    dropdownParent: $('#pasravshik_add'),
                    templateSelection: function(data) {
                        if (data.id === '') {
                            return 'ИИБни танланг';
                        }
                        return data.text;
                    },
                });


                $('#tuman').select2({
                    dropdownParent: $('#pasravshik_add'),
                    templateSelection: function(data) {
                        if (data.id === '') {
                            return 'Туманини танланг';
                        }
                        return data.text;
                    },
                })


                $('#mfy').select2({
                    dropdownParent: $('#pasravshik_add'),
                    templateSelection: function(data) {
                        if (data.id === '') {
                            return 'МФЙни танланг';
                        }
                        return data.text;
                    },
                })

                $('#ishTuman').select2({
                    dropdownParent: $('#pasravshik_add'),
                    templateSelection: function(data) {
                        if (data.id === '') {
                            return 'Туманини танланг';
                        }
                        return data.text;
                    },
                })



                $('#tuman').change(function() {
                    let id = $(this).val();
                    $.ajax({
                        url: "{{ route('newmijoz.index') }}/" + id,
                        method: "get",
                        data: {
                            id: id,
                        },
                        success: function(res) {
                            $('#mfy').html(res)
                        }
                    })
                })


                function lettersOnly(input) {
                    var regex = /[^A-Z]/gi;
                    input.value = input.value.replace(regex, "");
                }


                $("#qidirish").keyup(function() {
                    var value = $(this).val().toLowerCase();
                    $("#tab1 tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    })
                })
            })


            $(document).ready(function() {
                $('#passport_iib').select2({
                    dropdownParent: $('#edit'),
                    templateSelection: function(data) {
                        if (data.id === '') {
                            return 'ИИБни танланг';
                        }
                        return data.text;
                    },
                });
                $('#tuman_id').select2({
                    dropdownParent: $('#edit'),
                    templateSelection: function(data) {
                        if (data.id === '') {
                            return 'Tumanni танланг';
                        }
                        return data.text;
                    },
                })
                $('#mfy_id').select2({
                    dropdownParent: $('#edit'),
                    templateSelection: function(data) {
                        if (data.id === '') {
                            return 'МФЙни танланг';
                        }
                        return data.text;
                    },
                })
                $('#ish_tumanid').select2({
                    dropdownParent: $('#edit'),
                    templateSelection: function(data) {
                        if (data.id === '') {
                            return 'Tumanni танланг';
                        }
                        return data.text;
                    },
                })


                $('#Editmijoz').on('submit', function(e) {
                    e.preventDefault();
                    var id = $('#editid').val();
                    var formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('newmijoz.index') }}/" + id,
                        type: 'PUT',
                        data: formData,
                        success: function(response) {
                            toastr.success(response.message);
                            tabyuklash();
                        }
                    });
                });
            })


            $(document).on('click', '#fondedit', function() {
                var id = $(this).data('id');
                var last_name = $(this).data('last_name');
                var first_name = $(this).data('first_name');
                var middle_name = $(this).data('middle_name');
                var t_sana = $(this).data('t_sana');
                var passport_sn = $(this).data('passport_sn');
                var passport_iib = $(this).data('passport_iib');
                var passport_date = $(this).data('passport_date');
                var pinfl = $(this).data('pinfl');
                var tuman_id = $(this).data('tuman_id');
                var mfy_id = $(this).data('mfy_id');
                var manzil = $(this).data('manzil');
                var phone = $(this).data('phone');
                var extra_phone = $(this).data('extra_phone');
                var ish_tumanid = $(this).data('ish_tumanid');
                var ish_joy = $(this).data('ish_joy');
                var ish_tashkiloti = $(this).data('ish_tashkiloti');
                var kasb = $(this).data('kasb');
                var maosh = $(this).data('maosh');
                $('#editid').val(id);
                $('#last_name').val(last_name);
                $('#first_name').val(first_name);
                $('#middle_name').val(middle_name);
                $('#t_sana').val(t_sana);
                $('#passport_sn').val(passport_sn);
                $('#passport_iib').val(passport_iib).trigger("change");
                $('#passport_date').val(passport_date);
                $('#pinfl').val(pinfl);
                $('#tuman_id').val(tuman_id).trigger("change");
                $('#mfy_id').val(mfy_id).trigger("change");
                $('#manzil').val(manzil);
                $('#phone').val(phone);
                $('#extra_phone').val(extra_phone);
                $('#ish_tumanid').val(ish_tumanid).trigger("change");
                $('#ish_joy').val(ish_joy);
                $('#ish_tashkiloti').val(ish_tashkiloti);
                $('#kasb').val(kasb);
                $('#maosh').val(maosh);
            });
        </script>
    @endsection
