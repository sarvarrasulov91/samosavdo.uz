<style>
    #certificate * {
        font-family: 'Times New Roman';
        font-size: 16px;
    }

    .raqamlash > p,
    .undiruvchi > p,
    .qarzdor > p {
        text-align: left;
        padding: 0;
        margin: 0;
        line-height: normal;
    }

    h1, h2 {
        text-align: center;
        margin: 10px;
        padding: 10px;
    }

    .palata > p {
        text-align: center;
        margin: 10px;
        padding: 10px;
        line-height: normal;
    }

    .mazmuni > p,
    .mazmuni > li {
        text-align: justify;
        text-indent: 30px;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }
</style>

<div id="certificate" class="container-fluid bg-white p-4">

    <h1 style="color: green; font-size: 32px; font-weight: 700; text-align: center;">"SAMO SAVDO MARKAZI"</h1>
<br>
    <div style="border: 1px solid black; margin-bottom: 15px;"></div>

    <div class="ps-2 raqamlash mb-3">
        <p> ____ - ___________ - {{ date('Y') }} йил</p>
        <p>№:_________________ - сонли</p>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <p style="text-align: right"><b>Кимдан:</b></p>
        </div>
        <div class="col-6 undiruvchi">
            <p style="font-weight: bold;">{{ $mchjName }}</p>
            <p><b>Манзил:</b> {{$filial->manzil}}</p>
            <p><b>Стир:</b> {{$filial->inn}}</p>
            <p><b>Банк:</b> {{$filial->bankname}}</p>
            <p><b>Х-р:</b> {{$filial->xr}}</p>
            <p><b>МФО:</b> {{$filial->mfo}}</p>
            <p style="font-weight: bold;">Тел: (+998) {{$filial->telefon}}</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6"><p style="text-align: right"><b> Кимга: </b></p></div>
        <div class="col-6 qarzdor">
            <p style="font-weight: bold;"><b>{{$clientName}}</b></p>
            <p>
                {{$shartnom->mijozlar->viloyat->name_uz}} {{$shartnom->mijozlar->tuman->name_uz}}
                {{$shartnom->mijozlar->mfy->name_uz}} {{$shartnom->mijozlar->manzil}}
            </p>
            <p>
                <b>{{date('d.m.Y', strtotime($shartnom->mijozlar->t_sana))}}</b> йилда тугилган.
            </p>
            <p>
                Тел: <b>(+998) {{$shartnom->mijozlar->phone}}</b>
            </p>
        </div>

    </div>


    <h2 style="margin-bottom: 0; font-weight: bold"> Т А Л А Б Н О М А </h2>

    <div class="row mazmuni">
        <p>
            Хурматли <b>{{$clientName}}</b> (Ҳужжат: {{$shartnom->mijozlar->passport_sn}} {{$shartnom->mijozlar->passport_iib}} томонидан
            {{date("d.m.Y", strtotime($shartnom->mijozlar->passport_date))}} йилда берилган), Сиз ва {{$filial->manzil}}да фаолият кўрсатувчи <b>{{$mchjName}}</b>
            ўртасида <b>{{ date('d.m.Y', strtotime($shartnom->kun)) }}</b> куни <b>{{$shartnom->muddat}}</b> ой муддатга
            жами суммаси <b>{{ number_format($shartnomaSumma, 0, ",", " ") }} ({{numToStr($shartnomaSumma)}})</b> сўмлик
            № <b>{{$shartnom->id}}</b>-сонли "Муддатли тулов шартнома"си тузилган.
        </p>
        <p>
            Шартноманинг 1.1 ва 3.2-бандларига кўра, сиз  маҳсулотни  қабул қилиб олгач хар ойнинг графикда курсатилган
            {{date('d', strtotime($shartnom->t_kun))}}-санасидан
            кечиктирмасдан маҳсулотни нархини <b> {{number_format($grafikTulov, 0, ',', ' ')}} ({{numToStr($grafikTulov)}}) </b> сўмдан тўлаб  бориш мажбуриятини олгансиз.
        </p>
        <p>
            Аммо,  Сиз  шартномадаги мажбуриятингизни  бажармай  тўловларни ўз вактида амалга оширмагансиз,
            натижада хозирги кунда маҳсулот нархидан муддати ўтган қарзингиз <b>{{ number_format($qarzdorlik, 0, ",", " ") }} ({{numToStr($qarzdorlik)}})</b> сўмни ташкил қилади.
        </p>
        <p>
            Шунга кўра сиздан муддати ўтган қарзингиз
            <b>{{ number_format($qarzdorlik, 0, ",", " ") }} ({{numToStr($qarzdorlik)}})</b>
            сўмни 5 кун муддат ичида тўлашингизни талаб қиламиз.
        </p>
        <p>
            Акс холда,  сиздан муддати ўтган график карздорлик ва муддатли график карзингиз билан биргаликда ундириб
            олиш буйича тегишли суд органларига мурожаат килишга мажбур бўламиз.
        </p>
        <p>
            Бунда эса сиз томонингиздан қўшимча харажатлар (давлат божи, почта харажати, МИБ тўловлари) қилишингизни маълум қиламиз.
        </p>
    </div>

    <br>

    <div class="row mt-3">
        <div class="col-6 d-flex align-items-center">
            <p class="fw-bold"> <b>{{$mchjName}} рахбари: </b> </p>
        </div>
        <div class="col-3 d-flex align-self-end align-items-center">
            <p> <sub style="font-size: 12px"> (Мухр, имзо) </sub> </p>
        </div>
        <div class="col-3 d-flex justify-content-end align-items-center">
            <p class="fw-bold"> {{$filial->firma_raxbari}} </p>
        </div>
    </div>
</div>
